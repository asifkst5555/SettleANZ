@extends('admin.layouts.app')

@section('page-title', 'Edit Ebook')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>Edit: {{ $ebook->title }}</h2>
            <p>Update ebook details, file, or status</p>
        </div>
        <a href="{{ route('admin.ebooks.index') }}" class="button button--small button--ghost">
            &larr; Back to Library
        </a>
    </section>

    <form action="{{ route('admin.ebooks.update', $ebook) }}" method="POST" enctype="multipart/form-data" class="admin-edit-form">
        @csrf
        @method('PUT')

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Basic Information</h3>
                </div>
                <div class="admin-form-grid">
                    <label class="admin-form-grid__full">
                        <span>Title *</span>
                        <input type="text" name="title" value="{{ old('title', $ebook->title) }}" required class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label class="admin-form-grid__full">
                        <span>Description</span>
                        <textarea name="description" rows="4" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $ebook->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Category</span>
                        <select name="category_id" class="pro-select w-full">
                            <option value="">None</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $ebook->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        <span>Author</span>
                        <input type="text" name="author" value="{{ old('author', $ebook->author) }}" class="w-full border rounded px-3 py-2">
                    </label>
                    <label>
                        <span>Language</span>
                        <select name="language" class="pro-select w-full">
                            <option value="en" @selected(old('language', $ebook->language) === 'en')>English</option>
                            <option value="es" @selected(old('language', $ebook->language) === 'es')>Spanish</option>
                            <option value="fr" @selected(old('language', $ebook->language) === 'fr')>French</option>
                            <option value="zh" @selected(old('language', $ebook->language) === 'zh')>Chinese</option>
                            <option value="ar" @selected(old('language', $ebook->language) === 'ar')>Arabic</option>
                        </select>
                    </label>
                    <label>
                        <span>Page Count</span>
                        <input type="number" name="page_count" value="{{ old('page_count', $ebook->page_count) }}" class="w-full border rounded px-3 py-2">
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>File & Status</h3>
                </div>
                <div class="admin-form-grid">
                    <label class="admin-form-grid__full">
                        <span>Replace File</span>
                        <input type="file" name="file" accept=".pdf,.zip,.docx,.epub,.mobi" class="w-full border rounded px-3 py-2 @error('file') border-red-500 @enderror">
                        <small style="display:block;margin-top:6px;color:#667788;">Current: {{ $ebook->file_name }} ({{ number_format($ebook->file_size / 1024, 1) }} KB). Leave empty to keep.</small>
                        @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </label>
                    <label>
                        <span>Thumbnail Image</span>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full border rounded px-3 py-2">
                        <small style="display:block;margin-top:6px;color:#667788;">Leave empty to keep current.</small>
                    </label>
                    <label>
                        <span>ISBN</span>
                        <input type="text" name="isbn" value="{{ old('isbn', $ebook->isbn) }}" class="w-full border rounded px-3 py-2">
                    </label>
                    <label>
                        <span>Status</span>
                        <select name="status" class="pro-select w-full">
                            <option value="draft" @selected(old('status', $ebook->status) === 'draft')>Draft</option>
                            <option value="published" @selected(old('status', $ebook->status) === 'published')>Published</option>
                            <option value="archived" @selected(old('status', $ebook->status) === 'archived')>Archived</option>
                        </select>
                    </label>
                </div>
            </div>
        </section>

        <section class="admin-panel-card">
            <div class="admin-settings-section">
                <div class="admin-settings-section__head">
                    <h3>Tags</h3>
                </div>
                <div style="padding:0 1.25rem 1.25rem;">
                    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                        @foreach($tags as $tag)
                        <label style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.5rem 0.875rem;border:1px solid #d7e1ea;border-radius:999px;cursor:pointer;font-size:0.875rem;">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', $ebook->tags->pluck('id')->toArray()))) style="accent-color:#0f8b8d;">
                            {{ $tag->name }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <div style="display:flex;gap:1rem;padding-top:1rem;">
            <button type="submit" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.75rem 2rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;font-size:0.9375rem;">Update Ebook</button>
            <a href="{{ route('admin.ebooks.index') }}" style="padding:0.75rem 2rem;border:1px solid #d7e1ea;border-radius:0.375rem;text-decoration:none;color:#476072;font-weight:500;">Cancel</a>
        </div>
    </form>
</div>
@endsection
