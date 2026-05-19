@extends('admin.layouts.app')

@section('page-title', 'AI Knowledge Base')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">AI Training</p>
                <h2>AI Knowledge Base</h2>
                <p>Add, edit, and manage custom knowledge entries that train the AI assistant. These entries are used alongside built-in website content.</p>
            </div>
            <div style="display:flex;gap:0.75rem;align-items:center;">
                <a href="{{ route('admin.ai-knowledge.generate-form') }}" class="ai-btn" style="background:#fff0ec;color:#d17453;padding:0.85rem 1.4rem;border-radius:10px;font-weight:700;font-size:0.9rem;text-decoration:none;display:inline-flex;align-items:center;gap:0.7rem;position:relative;overflow:hidden;transition:all 0.3s ease;flex-shrink:0;">
                    <span class="ai-btn__label">Bulk Generate with AI</span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
                <a href="{{ route('admin.ai-knowledge.create') }}" class="button button--large">+ Add Knowledge Entry</a>
            </div>
        </section>

        @if (session('status'))
            <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Stats Cards --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
            <div style="background:#f0f7f6;border:1px solid #b9cfcb;border-radius:12px;padding:1.25rem;text-align:center;">
                <p style="font-size:2rem;font-weight:700;color:#0b7a75;margin:0;">{{ $totalActive }}</p>
                <p style="font-size:0.85rem;color:#667788;margin:0.25rem 0 0;">Active Entries</p>
            </div>
            <div style="background:#fff3e0;border:1px solid #ffcc80;border-radius:12px;padding:1.25rem;text-align:center;">
                <p style="font-size:2rem;font-weight:700;color:#e65100;margin:0;">{{ $totalInactive }}</p>
                <p style="font-size:0.85rem;color:#667788;margin:0.25rem 0 0;">Inactive Entries</p>
            </div>
            <div style="background:#f5f3ff;border:1px solid #c4b5fd;border-radius:12px;padding:1.25rem;text-align:center;">
                <p style="font-size:2rem;font-weight:700;color:#6d28d9;margin:0;">{{ $categories->sum('count') }}</p>
                <p style="font-size:0.85rem;color:#667788;margin:0.25rem 0 0;">Total Entries</p>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.ai-knowledge.index') }}" style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;align-items:flex-end;">
            <label style="flex:1;min-width:200px;">
                <span style="font-size:0.85rem;color:#667788;">Search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, content, or keywords..." style="width:100%;padding:0.7rem 1rem;border:1px solid rgba(16,88,98,0.16);border-radius:6px;font-size:0.9rem;">
            </label>
            <label style="min-width:180px;">
                <span style="font-size:0.85rem;color:#667788;">Category</span>
                <select name="category" class="pro-select" style="width:100%;padding:0.7rem 1rem;border:1px solid rgba(16,88,98,0.16);border-radius:6px;font-size:0.9rem;background:#fff;">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->category }}" @selected(request('category') === $cat->category)>{{ ucfirst($cat->category) }} ({{ $cat->count }})</option>
                    @endforeach
                </select>
            </label>
            <button type="submit" class="button">Filter</button>
            @if (request('search') || request('category'))
                <a href="{{ route('admin.ai-knowledge.index') }}" class="button" style="background:#667788;">Clear</a>
            @endif
        </form>

        {{-- Entries Table --}}
        <div style="background:#fff;border:1px solid rgba(16,88,98,0.12);border-radius:12px;overflow:hidden;">
            @if ($entries->isEmpty())
                <div style="padding:3rem;text-align:center;color:#667788;">
                    <p style="font-size:1.1rem;font-weight:600;margin-bottom:0.5rem;">No knowledge entries found</p>
                    <p style="font-size:0.9rem;">Add your first knowledge entry to train the AI assistant.</p>
                    <a href="{{ route('admin.ai-knowledge.create') }}" class="button" style="margin-top:1rem;">+ Add Knowledge Entry</a>
                </div>
            @else
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8f9fa;border-bottom:2px solid rgba(16,88,98,0.12);">
                            <th style="padding:1rem;text-align:left;font-size:0.85rem;color:#667788;font-weight:600;">Title</th>
                            <th style="padding:1rem;text-align:left;font-size:0.85rem;color:#667788;font-weight:600;">Category</th>
                            <th style="padding:1rem;text-align:left;font-size:0.85rem;color:#667788;font-weight:600;">Priority</th>
                            <th style="padding:1rem;text-align:left;font-size:0.85rem;color:#667788;font-weight:600;">Status</th>
                            <th style="padding:1rem;text-align:left;font-size:0.85rem;color:#667788;font-weight:600;">Updated</th>
                            <th style="padding:1rem;text-align:right;font-size:0.85rem;color:#667788;font-weight:600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($entries as $entry)
                            <tr style="border-bottom:1px solid rgba(16,88,98,0.08);">
                                <td style="padding:1rem;">
                                    <p style="font-weight:600;color:#2c3a47;margin:0;font-size:0.95rem;">{{ Str::limit($entry->title, 60) }}</p>
                                    <p style="font-size:0.8rem;color:#667788;margin:0.25rem 0 0;">{{ Str::limit($entry->content, 80) }}</p>
                                </td>
                                <td style="padding:1rem;">
                                    <span style="display:inline-block;padding:0.25rem 0.6rem;background:#f0f7f6;border:1px solid #b9cfcb;border-radius:20px;font-size:0.8rem;color:#0b7a75;">
                                        {{ ucfirst($entry->category) }}
                                    </span>
                                </td>
                                <td style="padding:1rem;font-size:0.9rem;color:#2c3a47;">{{ $entry->priority }}</td>
                                <td style="padding:1rem;">
                                    @if ($entry->is_active)
                                        <span style="display:inline-block;padding:0.25rem 0.6rem;background:#e8f5e9;border:1px solid #66bb6a;border-radius:20px;font-size:0.8rem;color:#2e7d32;">Active</span>
                                    @else
                                        <span style="display:inline-block;padding:0.25rem 0.6rem;background:#fff3e0;border:1px solid #ffcc80;border-radius:20px;font-size:0.8rem;color:#e65100;">Inactive</span>
                                    @endif
                                </td>
                                <td style="padding:1rem;font-size:0.85rem;color:#667788;">{{ $entry->updated_at->diffForHumans() }}</td>
                                <td style="padding:1rem;text-align:right;">
                                    <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                        <a href="{{ route('admin.ai-knowledge.edit', $entry) }}" class="button" style="padding:0.4rem 0.8rem;font-size:0.85rem;">Edit</a>
                                        <form method="POST" action="{{ route('admin.ai-knowledge.toggle-active', $entry) }}" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="button" style="padding:0.4rem 0.8rem;font-size:0.85rem;background:{{ $entry->is_active ? '#667788' : '#14a394' }};">
                                                {{ $entry->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.ai-knowledge.destroy', $entry) }}" style="display:inline;" onsubmit="return confirm('Delete this knowledge entry?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="button" style="padding:0.4rem 0.8rem;font-size:0.85rem;background:#d32f2f;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($entries->hasPages())
                    <div style="padding:1rem;border-top:1px solid rgba(16,88,98,0.08);">
                        {{ $entries->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <style>
        .ai-btn {
            background: #fff0ec;
            padding: 0.85rem 1.4rem;
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            transform: translate(0%, 0%);
            overflow: hidden;
            color: #d17453;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-align: center;
            text-transform: none;
            text-decoration: none;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .ai-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ff8c42;
            opacity: 0;
            transition: 0.2s opacity ease-in-out;
        }
        .ai-btn:hover::before {
            opacity: 0.1;
        }
        .ai-btn:hover {
            transform: translateY(-3px);
            color: #ff6b35;
        }
        .ai-btn span:not(.ai-btn__label) {
            position: absolute;
            pointer-events: none;
        }
        .ai-btn span:nth-child(2) {
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to left, rgba(255, 140, 66, 0), #ff8c42);
            animation: ai-kb-animate-top 2s linear infinite;
        }
        .ai-btn span:nth-child(3) {
            top: 0;
            right: 0;
            height: 100%;
            width: 2px;
            background: linear-gradient(to top, rgba(255, 140, 66, 0), #ff8c42);
            animation: ai-kb-animate-right 2s linear -1s infinite;
        }
        .ai-btn span:nth-child(4) {
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, rgba(255, 140, 66, 0), #ff8c42);
            animation: ai-kb-animate-bottom 2s linear infinite;
        }
        .ai-btn span:nth-child(5) {
            top: 0;
            left: 0;
            height: 100%;
            width: 2px;
            background: linear-gradient(to bottom, rgba(255, 140, 66, 0), #ff8c42);
            animation: ai-kb-animate-left 2s linear -1s infinite;
        }
        @keyframes ai-kb-animate-top {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        @keyframes ai-kb-animate-right {
            0% { transform: translateY(100%); }
            100% { transform: translateY(-100%); }
        }
        @keyframes ai-kb-animate-bottom {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        @keyframes ai-kb-animate-left {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100%); }
        }
        .ai-btn__label {
            position: relative;
            z-index: 1;
        }
    </style>
@endsection
