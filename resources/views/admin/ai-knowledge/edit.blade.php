@extends('admin.layouts.app')

@section('page-title', 'Edit AI Knowledge Entry')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">AI Training</p>
                <h2>Edit Knowledge Entry</h2>
                <p>Update the knowledge entry details.</p>
            </div>
        </section>

        @if (session('status'))
            <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="background:#ffebee;border:1px solid #ef9a9a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#c62828;">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-knowledge.update', $entry) }}">
            @csrf
            @method('PUT')

            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Knowledge Entry Details</h3>
                        <p>Update the title, content, and metadata for this knowledge entry.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label class="admin-form-grid__full">
                            <span>Title</span>
                            <input type="text" name="title" value="{{ old('title', $entry->title) }}" required>
                            <small style="display:block;margin-top:6px;color:#667788;">A clear, descriptive title that summarizes this knowledge entry.</small>
                        </label>

                        <label class="admin-form-grid__full">
                            <span>Content</span>
                            <textarea name="content" rows="8" required>{{ old('content', $entry->content) }}</textarea>
                            <small style="display:block;margin-top:6px;color:#667788;">Practical, factual information that the AI can use when answering questions. Max 5000 characters.</small>
                        </label>

                        <label class="admin-form-grid__full">
                            <span>Search Keywords</span>
                            <input type="text" name="search_keywords" value="{{ old('search_keywords', $entry->search_keywords) }}">
                            <small style="display:block;margin-top:6px;color:#667788;">Comma-separated keywords that help the AI match this entry to visitor questions.</small>
                        </label>

                        <label>
                            <span>Category</span>
                            <select name="category" class="pro-select" required>
                                <option value="general" @selected(old('category', $entry->category) === 'general')>General</option>
                                <option value="migration" @selected(old('category', $entry->category) === 'migration')>Migration</option>
                                <option value="housing" @selected(old('category', $entry->category) === 'housing')>Housing</option>
                                <option value="banking" @selected(old('category', $entry->category) === 'banking')>Banking</option>
                                <option value="healthcare" @selected(old('category', $entry->category) === 'healthcare')>Healthcare</option>
                                <option value="work" @selected(old('category', $entry->category) === 'work')>Work & Jobs</option>
                                <option value="culture" @selected(old('category', $entry->category) === 'culture')>Culture & Lifestyle</option>
                                <option value="challenges" @selected(old('category', $entry->category) === 'challenges')>Challenges</option>
                                <option value="tips" @selected(old('category', $entry->category) === 'tips')>Tips & Advice</option>
                            </select>
                        </label>

                        <label>
                            <span>Priority (0-100)</span>
                            <input type="number" name="priority" value="{{ old('priority', $entry->priority) }}" min="0" max="100">
                            <small style="display:block;margin-top:6px;color:#667788;">Higher priority entries are matched first. Default is 0.</small>
                        </label>

                        <label>
                            <span>Active</span>
                            <select name="is_active" class="pro-select">
                                <option value="1" @selected(old('is_active', $entry->is_active))>Yes — AI will use this entry</option>
                                <option value="0" @selected(old('is_active') === '0' && !$entry->is_active)>No — Save as draft</option>
                            </select>
                        </label>
                    </div>
                </div>
            </section>

            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button class="button button--large" type="submit">Update Knowledge Entry</button>
                <a href="{{ route('admin.ai-knowledge.index') }}" class="button button--large" style="background:#667788;">Cancel</a>
            </div>
        </form>
    </div>
@endsection
