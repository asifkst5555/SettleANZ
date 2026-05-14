@extends('admin.layouts.app')

@section('content')
<div class="admin-main__inner">
    <section class="admin-topbar">
        <div>
            <p class="eyebrow">Admin</p>
            <h2>SEO Manager</h2>
            <p>Manage meta titles, descriptions, Open Graph tags, and structured data for every page.</p>
        </div>
    </section>

    @if (session('status'))
        <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
    @endif

    <style>
        .seo-table-wrap {
            border: 1px solid #edf2f7;
            border-radius: 0.75rem;
            background: #fff;
            width: 100%;
            overflow-x: visible;
        }
        .seo-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 0.9rem;
        }
        .seo-table thead th {
            background: #f3f4f6;
            padding: 1rem 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            white-space: nowrap;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        @media (max-width: 1100px) {
            .seo-table-wrap {
                overflow-x: auto;
            }
            .seo-table {
                min-width: 560px;
            }
        }
        .seo-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.15s;
        }
        .seo-table tbody tr:hover { background: #f9fafb; }
        .seo-table td {
            padding: 1rem 0.75rem;
            vertical-align: top;
            color: #22313d;
        }
        .seo-table .page-label {
            font-weight: 600;
            color: #12384f;
        }
        .seo-table .page-url {
            font-size: 0.78rem;
            color: #667788;
            margin-top: 2px;
        }
        .seo-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .seo-badge--custom { background: #e8f5f4; color: #0b7a75; }
        .seo-badge--default { background: #eef2f5; color: #667788; }
        .seo-badge--noindex { background: #fff0ec; color: #b94c2e; }
        .seo-badge--ogimage { background: #e8f5f4; color: #12384f; }
        .seo-actions { white-space: nowrap; }
        .seo-actions form { display: inline; }
        .seo-edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.4rem 0.9rem;
            background: #0b7a75;
            color: #fff;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .seo-edit-btn:hover { background: #085f5b; }
    </style>

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
