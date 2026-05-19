@extends('admin.layouts.app')

@section('page-title', 'Add AI Knowledge Entry')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">AI Training</p>
                <h2>Add Knowledge Entry</h2>
                <p>Add a new knowledge entry to train the AI assistant. This content will be used when answering visitor questions.</p>
            </div>
        </section>

        @if ($errors->any())
            <div style="background:#ffebee;border:1px solid #ef9a9a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#c62828;">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-knowledge.store') }}">
            @csrf

            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Knowledge Entry Details</h3>
                        <p>Enter the title, content, and metadata for this knowledge entry.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label class="admin-form-grid__full">
                            <span>Title</span>
                            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g., Why do people migrate to Australia?">
                            <small style="display:block;margin-top:6px;color:#667788;">A clear, descriptive title that summarizes this knowledge entry.</small>
                        </label>

                        <label class="admin-form-grid__full">
                            <span>Content</span>
                            <textarea name="content" rows="8" required placeholder="Enter the knowledge content here. This will be used by the AI to answer visitor questions.">{{ old('content') }}</textarea>
                            <small style="display:block;margin-top:6px;color:#667788;">Practical, factual information that the AI can use when answering questions. Max 5000 characters.</small>
                        </label>

                        <label class="admin-form-grid__full">
                            <span>Search Keywords</span>
                            <input type="text" name="search_keywords" value="{{ old('search_keywords') }}" placeholder="e.g., migrate, australia, reasons, income, quality life">
                            <small style="display:block;margin-top:6px;color:#667788;">Comma-separated keywords that help the AI match this entry to visitor questions.</small>
                        </label>

                        <label>
                            <span>Category</span>
                            <select name="category" class="pro-select" required>
                                <option value="general" @selected(old('category') === 'general')>General</option>
                                <option value="migration" @selected(old('category') === 'migration')>Migration</option>
                                <option value="housing" @selected(old('category') === 'housing')>Housing</option>
                                <option value="banking" @selected(old('category') === 'banking')>Banking</option>
                                <option value="healthcare" @selected(old('category') === 'healthcare')>Healthcare</option>
                                <option value="work" @selected(old('category') === 'work')>Work & Jobs</option>
                                <option value="culture" @selected(old('category') === 'culture')>Culture & Lifestyle</option>
                                <option value="challenges" @selected(old('category') === 'challenges')>Challenges</option>
                                <option value="tips" @selected(old('category') === 'tips')>Tips & Advice</option>
                            </select>
                        </label>

                        <label>
                            <span>Priority (0-100)</span>
                            <input type="number" name="priority" value="{{ old('priority', 0) }}" min="0" max="100">
                            <small style="display:block;margin-top:6px;color:#667788;">Higher priority entries are matched first. Default is 0.</small>
                        </label>

                        <label>
                            <span>Active</span>
                            <select name="is_active" class="pro-select">
                                <option value="1" @selected(old('is_active', true))>Yes — AI will use this entry</option>
                                <option value="0" @selected(old('is_active') === '0')>No — Save as draft</option>
                            </select>
                        </label>
                    </div>
                </div>
            </section>

            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button class="button button--large" type="submit">Add Knowledge Entry</button>
                <a href="{{ route('admin.ai-knowledge.index') }}" class="button button--large" style="background:#667788;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
