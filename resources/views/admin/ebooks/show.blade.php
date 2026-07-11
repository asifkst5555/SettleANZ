@extends('admin.layouts.app')

@section('page-title', $ebook->title)

@section('content')
<style>
    .ebook-detail-label { font-size:0.875rem; color:#6b7280; margin-bottom:0.25rem; }
    .ebook-detail-value { font-weight:600; color:#1f2937; }
</style>
<link rel="stylesheet" href="{{ asset('css/pdf-viewer.css') }}">
<script src="{{ asset('js/pdf-viewer.js') }}"></script>

<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Ebook System</p>
            <h2>{{ $ebook->title }}</h2>
            <p>Ebook details, leads, and download activity</p>
        </div>
        <div style="display:flex;gap:0.5rem;align-items:center;">
            <a href="{{ route('admin.ebooks.index') }}" class="button button--small button--ghost">&larr; Back</a>
            <button type="button" class="button button--small"
                style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;display:inline-flex;align-items:center;gap:0.25rem;cursor:pointer;"
                data-ebook="{{ json_encode([
                    'id' => $ebook->id,
                    'title' => $ebook->title,
                    'file_name' => $ebook->file_name ?? $ebook->title . '.pdf',
                    'file_size' => $ebook->file_size,
                    'page_count' => $ebook->page_count,
                    'language' => $ebook->language,
                    'download_count' => $ebook->download_count,
                    'author' => $ebook->author,
                    'category' => $ebook->category?->name,
                    'description' => $ebook->description,
                    'uploaded_by' => '—',
                    'created_at' => $ebook->created_at?->toIso8601String(),
                    'updated_at' => $ebook->updated_at?->toIso8601String(),
                    'preview_url' => route('admin.ebooks.preview', $ebook),
                ]) }}"
                onclick="openPdfViewer(this)">
                @include('admin.partials.icon', ['name' => 'eye', 'size' => 14])
                <span>View PDF</span>
            </button>
            <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="button button--small" style="background:#dbeafe;color:#0c4a6e;text-decoration:none;">Edit</a>
        </div>
    </section>

    <div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;">
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <section class="admin-panel-card">
                <div style="padding:1.5rem 1.75rem;">
                    <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:1rem;">Details</h3>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div><div class="ebook-detail-label">Status</div><div><span style="display:inline-block;padding:0.375rem 0.75rem;border-radius:0.25rem;font-size:0.85rem;font-weight:500;{{ $ebook->status === 'published' ? 'background:#d1fae5;color:#065f46;' : ($ebook->status === 'draft' ? 'background:#fef3c7;color:#92400e;' : 'background:#fee2e2;color:#7f1d1d;') }}">{{ ucfirst($ebook->status) }}</span></div></div>
                        <div><div class="ebook-detail-label">Category</div><div class="ebook-detail-value">{{ $ebook->category?->name ?? '—' }}</div></div>
                        <div><div class="ebook-detail-label">Author</div><div class="ebook-detail-value">{{ $ebook->author ?? '—' }}</div></div>
                        <div><div class="ebook-detail-label">File Type</div><div class="ebook-detail-value uppercase">{{ $ebook->file_type }}</div></div>
                        <div><div class="ebook-detail-label">File Size</div><div class="ebook-detail-value">{{ $ebook->file_size_for_humans }}</div></div>
                        <div><div class="ebook-detail-label">Pages</div><div class="ebook-detail-value">{{ $ebook->page_count ?? '—' }}</div></div>
                        <div><div class="ebook-detail-label">Language</div><div class="ebook-detail-value">{{ strtoupper($ebook->language) }}</div></div>
                        <div><div class="ebook-detail-label">Version</div><div class="ebook-detail-value">{{ $ebook->current_version }}</div></div>
                    </div>

                    @if($ebook->description)
                    <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #e5e7eb;">
                        <div class="ebook-detail-label" style="margin-bottom:0.5rem;">Description</div>
                        <p style="color:#4b5563;">{{ $ebook->description }}</p>
                    </div>
                    @endif

                    @if($ebook->tags->isNotEmpty())
                    <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #e5e7eb;">
                        <div class="ebook-detail-label" style="margin-bottom:0.5rem;">Tags</div>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            @foreach($ebook->tags as $tag)
                            <span style="display:inline-flex;padding:0.375rem 0.75rem;border-radius:999px;background:#e0e7ff;color:#3730a3;font-size:0.82rem;font-weight:600;">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </section>

            <section class="admin-panel-card">
                <div style="padding:1.5rem 1.75rem;">
                    <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:1rem;">Recent Leads</h3>
                    @if($ebook->leads->isNotEmpty())
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
                            <thead><tr style="text-align:left;color:#6b7280;border-bottom:2px solid #e5e7eb;"><th style="padding-bottom:0.5rem;">Name</th><th style="padding-bottom:0.5rem;">Email</th><th style="padding-bottom:0.5rem;">Date</th></tr></thead>
                            <tbody>
                                @foreach($ebook->leads as $lead)
                                <tr style="border-bottom:1px solid #e5e7eb;"><td style="padding:0.5rem 0;color:#1f2937;">{{ $lead->full_name }}</td><td style="padding:0.5rem 0;color:#6b7280;">{{ $lead->email }}</td><td style="padding:0.5rem 0;color:#6b7280;">{{ $lead->created_at->diffForHumans() }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p style="color:#6b7280;">No leads captured yet.</p>
                    @endif
                </div>
            </section>

            <section class="admin-panel-card">
                <div style="padding:1.5rem 1.75rem;">
                    <h3 style="font-size:1.125rem;font-weight:600;margin-bottom:1rem;">Recent Downloads</h3>
                    @if($downloadLogs->isNotEmpty())
                    <div style="display:flex;flex-direction:column;gap:0.75rem;font-size:0.875rem;">
                        @foreach($downloadLogs as $log)
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid #e5e7eb;">
                            <div>
                                <div style="font-weight:500;">{{ $log->lead?->full_name ?? 'Anonymous' }}</div>
                                <div style="color:#6b7280;font-size:0.8125rem;">{{ $log->ip_address }}</div>
                            </div>
                            <div style="color:#6b7280;font-size:0.8125rem;">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p style="color:#6b7280;">No downloads yet.</p>
                    @endif
                </div>
            </section>
        </div>

        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <section class="admin-panel-card" style="text-align:center;">
                <div style="padding:1.5rem;">
                    @if($ebook->thumbnail_path)
                    <img src="{{ Storage::url($ebook->thumbnail_path) }}" alt="{{ $ebook->title }}" style="width:100%;border-radius:0.75rem;margin-bottom:1rem;">
                    @else
                    <div style="width:100%;aspect-ratio:3/4;background:#f3f4f6;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <svg width="48" height="48" fill="none" stroke="#9ca3af" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    @endif
                    <div style="font-size:2.25rem;font-weight:700;color:#1f2937;">{{ $ebook->download_count }}</div>
                    <div style="font-size:0.875rem;color:#6b7280;">Total Downloads</div>
                    <div style="margin-top:0.5rem;font-size:1.875rem;font-weight:700;color:#1f2937;">{{ $ebook->lead_count }}</div>
                    <div style="font-size:0.875rem;color:#6b7280;">Leads Generated</div>
                </div>
            </section>

            @if($ebook->isDraft())
            <form action="{{ route('admin.ebooks.publish', $ebook) }}" method="POST">
                @csrf
                <button type="submit" style="width:100%;background:linear-gradient(135deg,#10b981 0%,#059669 100%);color:white;padding:0.75rem;border:none;border-radius:0.375rem;cursor:pointer;font-weight:600;font-size:0.9375rem;">Publish Ebook</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
