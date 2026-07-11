@extends('admin.layouts.app')

@section('page-title', 'PDF Viewer: ' . $ebook->title)

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System &raquo; PDF Viewer</p>
            <h2>{{ $ebook->title }}</h2>
            <p>Builtin secure PDF document reader</p>
        </div>
        <div style="display:flex;gap:0.5rem;align-items:center;">
            <a href="{{ route('admin.ebooks.show', $ebook) }}" class="button button--small button--ghost">&larr; Back to Details</a>
            <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="button button--small" style="background:#dbeafe;color:#0c4a6e;text-decoration:none;">Edit Ebook</a>
        </div>
    </section>

    <div class="admin-panel-card" style="padding: 1.25rem;">
        <div style="width: 100%; height: calc(100vh - 210px); background: #f8fafc; border-radius: 12px; border: 1px solid rgba(16, 88, 98, 0.1); overflow: hidden; position: relative;">
            <iframe src="{{ route('admin.ebooks.preview', $ebook) }}#toolbar=1" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
    </div>
</div>
@endsection
