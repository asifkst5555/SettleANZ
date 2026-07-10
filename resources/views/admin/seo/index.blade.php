@extends('admin.layouts.app')

@section('content')
<div class="admin-main__inner">
    @include('admin.settings.partials.tabs')

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif

    <section class="admin-panel-card" style="padding: 0;">
        <div class="seo-table-wrap">
            <table class="seo-table admin-table-mobile-cards">
                <thead>
                    <tr>
                        <th style="width: 46%;">Page</th>
                        <th style="width: 28%;">Status</th>
                        <th style="width: 26%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td data-label="Page">
                                <div class="page-label">{{ $row['label'] }}</div>
                                <div class="page-url">{{ $row['url'] }}</div>
                            </td>
                            <td data-label="Status" style="min-width:130px">
                                <span class="seo-badge {{ $row['has_custom'] ? 'seo-badge--custom' : 'seo-badge--default' }}">
                                    {{ $row['has_custom'] ? '✓ Custom' : '◌ Default' }}
                                </span>
                                @if ($row['no_index'])
                                    <span class="seo-badge seo-badge--noindex" style="margin-top:4px;display:block">⛔ No-Index</span>
                                @endif
                                @if ($row['og_image'])
                                    <span class="seo-badge seo-badge--ogimage" style="margin-top:4px;display:block">🖼 OG Image</span>
                                @endif
                            </td>
                            <td data-label="Action" class="seo-actions">
                                <a href="{{ route('admin.seo.edit', $row['key']) }}" class="seo-edit-btn">
                                    ✏️ Edit SEO
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem 1.25rem; border-top: 1px solid #e5e7eb;"></div>
    </section>
</div>
@endsection
