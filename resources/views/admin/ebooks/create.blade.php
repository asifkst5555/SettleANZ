@extends('admin.layouts.app')

@section('page-title', 'Create Ebook')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Create New Ebook</h2>
            <p>Upload and configure a new digital guide or lead magnet</p>
        </div>
        <a href="{{ route('admin.ebooks.index') }}" class="button button--small button--ghost">
            &larr; Back to Library
        </a>
    </section>

    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" class="admin-edit-form">
        @csrf

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Basic Information</h3>
                    <p>Title, description, and categorization</p>
                </div>
                <div class="admin-form-grid">
                    <label class="admin-form-grid__full">
                        <span>Title *</span>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label class="admin-form-grid__full">
                        <span>Description</span>
                        <textarea name="description" rows="4" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Category</span>
                        <select name="category_id" class="pro-select w-full">
                            <option value="">None</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        <span>Author</span>
                        <input type="text" name="author" value="{{ old('author') }}" class="w-full border rounded px-3 py-2">
                    </label>
                    <label>
                        <span>Language</span>
                        <select name="language" class="pro-select w-full">
                            <option value="en">English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <option value="zh">Chinese</option>
                            <option value="ar">Arabic</option>
                        </select>
                    </label>
                    <label>
                        <span>Page Count</span>
                        <input type="number" name="page_count" value="{{ old('page_count') }}" class="w-full border rounded px-3 py-2">
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>File & Status</h3>
                    <p>Upload your ebook file and set its visibility</p>
                </div>
                <div class="admin-form-grid">
                    <label class="admin-form-grid__full">
                        <span>Ebook File *</span>
                        <input type="file" name="file" required accept=".pdf,.zip,.docx,.epub,.mobi" class="w-full border rounded px-3 py-2 @error('file') border-red-500 @enderror">
                        <small style="display:block;margin-top:6px;color:#667788;">Allowed: PDF, ZIP, DOCX, EPUB, MOBI. Max 50MB.</small>
                        @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Thumbnail Image</span>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full border rounded px-3 py-2">
                        <small style="display:block;margin-top:6px;color:#667788;">Recommended: 300x400px. Max 5MB.</small>
                    </label>
                    <label>
                        <span>ISBN</span>
                        <input type="text" name="isbn" value="{{ old('isbn') }}" class="w-full border rounded px-3 py-2">
                    </label>
                    <label>
                        <span>Status</span>
                        <select name="status" class="pro-select w-full">
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                            <option value="published" @selected(old('status') === 'published')>Published</option>
                        </select>
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Tags</h3>
                    <p>Categorize your ebook with tags for filtering</p>
                </div>
                <div style="padding:0 1.25rem 1.25rem;">
                    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                        @foreach($tags as $tag)
                        <label style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.5rem 0.875rem;border:1px solid #d7e1ea;border-radius:999px;cursor:pointer;font-size:0.875rem;transition:border-color 0.2s,background 0.2s;">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', []))) style="accent-color:#0f8b8d;">
                            {{ $tag->name }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <div style="display:flex;gap:1rem;padding-top:1rem;">
            <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.75rem 2rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;font-size:0.9375rem;">Create Ebook</button>
            <a href="{{ route('admin.ebooks.index') }}" style="padding:0.75rem 2rem;border:1px solid #d7e1ea;border-radius:0.375rem;text-decoration:none;color:#476072;font-weight:500;">Cancel</a>
        </div>
    </form>
</div>
@endsection
