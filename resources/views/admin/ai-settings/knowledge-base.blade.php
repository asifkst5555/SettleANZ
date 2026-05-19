@extends('admin.layouts.app')

@section('page-title', 'Knowledge Base')

@section('content')
    <div class="admin-main__inner" style="display:grid;grid-template-columns:240px 1fr;gap:2rem;">
        @include('admin.ai-settings.partials.sidebar', ['active' => 'knowledge-base'])

        <div>
            <section class="admin-topbar" style="margin-bottom:1.5rem;">
                <div>
                    <p class="eyebrow">AI Settings</p>
                    <h2>Knowledge Base</h2>
                    <p>Overview of content the AI is trained on.</p>
                </div>
                <div>
                    <a href="{{ route('admin.ai-knowledge.generate-form') }}" class="ai-btn" style="background:#fff0ec;color:#d17453;padding:0.85rem 1.4rem;border-radius:10px;font-weight:700;font-size:0.9rem;text-decoration:none;display:inline-flex;align-items:center;gap:0.7rem;position:relative;overflow:hidden;transition:all 0.3s ease;flex-shrink:0;">
                        <span class="ai-btn__label">Bulk Generate with AI</span>
                        <span></span><span></span><span></span><span></span>
                    </a>
                </div>
            </section>

            @if (session('status'))
                <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#2e7d32;font-weight:600;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="display:grid;gap:1rem;">
                <div style="background:#f0f7f6;border:1px solid #b9cfcb;border-radius:12px;padding:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
                        <h4 style="margin:0;color:#0b7a75;font-size:1rem;">Custom AI Knowledge Entries</h4>
                        <a href="{{ route('admin.ai-knowledge.index') }}" class="button" style="padding:0.4rem 0.8rem;font-size:0.85rem;">Manage Entries ({{ $customKnowledgeCount }} active)</a>
                    </div>
                    <p style="font-size:0.9rem;color:#2c3a47;margin:0;">You have <strong>{{ $customKnowledgeCount }}</strong> active custom knowledge entries. These are used alongside built-in website content to train the AI assistant.</p>
                </div>

                <div style="background:#f0f7f6;border:1px solid #b9cfcb;border-radius:12px;padding:1.25rem;">
                    <h4 style="margin:0 0 0.75rem;color:#0b7a75;font-size:1rem;">Pages the AI Knows About</h4>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.5rem;font-size:0.9rem;color:#2c3a47;">
                        <div><strong>/</strong> — Homepage</div>
                        <div><strong>/about</strong> — About SettleANZ</div>
                        <div><strong>/new-to-australia</strong> — Arrival Guide</div>
                        <div><strong>/settlement-services</strong> — Settlement Hub</div>
                        <div><strong>/housing</strong> — Housing Guide</div>
                        <div><strong>/banking</strong> — Banking Guide</div>
                        <div><strong>/migration-services</strong> — Migration Services</div>
                        <div><strong>/blog</strong> — Blog Index</div>
                        <div><strong>/directory</strong> — Directory</div>
                        <div><strong>/contact</strong> — Contact Page</div>
                        <div><strong>/privacy-policy</strong> — Privacy Policy</div>
                        <div><strong>/terms-of-service</strong> — Terms of Service</div>
                    </div>
                </div>

                <div style="background:#fff8f0;border:1px solid #f0d9b5;border-radius:12px;padding:1.25rem;">
                    <h4 style="margin:0 0 0.75rem;color:#d86424;font-size:1rem;">FAQ Topics the AI Can Answer</h4>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:0.5rem;font-size:0.9rem;color:#2c3a47;">
                        <div>What does SettleANZ help with?</div>
                        <div>How should visitors use the site?</div>
                        <div>When should visitors contact a human?</div>
                        <div>How do business listings work?</div>
                        <div>What is on the migration page?</div>
                        <div>What is in the directory?</div>
                        <div>What topics are covered in the blog?</div>
                        <div>What do the main guides cover?</div>
                        <div>How fast does SettleANZ respond?</div>
                        <div>How can someone book migration help?</div>
                        <div>What is the Settlement Services page?</div>
                        <div>What is SettleANZ?</div>
                        <div>Is the AI assistant accurate?</div>
                        <div>What should I do before arriving?</div>
                        <div>How do I find housing?</div>
                        <div>How do I open a bank account?</div>
                        <div>What visa options are available?</div>
                    </div>
                </div>

                <div style="background:#f5f3ff;border:1px solid #c4b5fd;border-radius:12px;padding:1.25rem;">
                    <h4 style="margin:0 0 0.75rem;color:#6d28d9;font-size:1rem;">Dynamic Content (Auto-loaded from Database)</h4>
                    <ul style="margin:0;padding-left:1.25rem;font-size:0.9rem;color:#2c3a47;line-height:1.7;">
                        <li><strong>Blog posts</strong> — Latest 20 published posts with titles, excerpts, and content</li>
                        <li><strong>Directory listings</strong> — Latest 30 published listings with names, categories, cities, and descriptions</li>
                        <li><strong>Blog categories</strong> — Auto-generated from published posts</li>
                        <li><strong>Directory categories & cities</strong> — Auto-generated from published listings</li>
                    </ul>
                </div>
            </div>
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
        .ai-btn:hover::before { opacity: 0.1; }
        .ai-btn:hover { transform: translateY(-3px); color: #ff6b35; }
        .ai-btn span:not(.ai-btn__label) { position: absolute; pointer-events: none; }
        .ai-btn span:nth-child(2) { top: 0; left: 0; width: 100%; height: 2px; background: linear-gradient(to left, rgba(255, 140, 66, 0), #ff8c42); animation: kb-ai-animate-top 2s linear infinite; }
        .ai-btn span:nth-child(3) { top: 0; right: 0; height: 100%; width: 2px; background: linear-gradient(to top, rgba(255, 140, 66, 0), #ff8c42); animation: kb-ai-animate-right 2s linear -1s infinite; }
        .ai-btn span:nth-child(4) { bottom: 0; left: 0; width: 100%; height: 2px; background: linear-gradient(to right, rgba(255, 140, 66, 0), #ff8c42); animation: kb-ai-animate-bottom 2s linear infinite; }
        .ai-btn span:nth-child(5) { top: 0; left: 0; height: 100%; width: 2px; background: linear-gradient(to bottom, rgba(255, 140, 66, 0), #ff8c42); animation: kb-ai-animate-left 2s linear -1s infinite; }
        @keyframes kb-ai-animate-top { 0% { transform: translateX(100%); } 100% { transform: translateX(-100%); } }
        @keyframes kb-ai-animate-right { 0% { transform: translateY(100%); } 100% { transform: translateY(-100%); } }
        @keyframes kb-ai-animate-bottom { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
        @keyframes kb-ai-animate-left { 0% { transform: translateY(-100%); } 100% { transform: translateY(100%); } }
        .ai-btn__label { position: relative; z-index: 1; }
    </style>
@endsection
