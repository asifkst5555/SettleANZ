@extends('admin.layouts.app')

@section('page-title', 'Bulk Generate AI Knowledge')

@section('content')
    <div class="admin-main__inner">
        <section class="admin-topbar">
            <div>
                <p class="eyebrow">AI Training</p>
                <h2>Bulk Generate Knowledge Entries</h2>
                <p>Enter a topic or prompt and the AI will research and create multiple Q&A entries automatically. Perfect for adding 10-20 related questions at once.</p>
            </div>
        </section>

        @if (session('error'))
            <div style="background:#ffebee;border:1px solid #ef9a9a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#c62828;">
                <strong>Error:</strong> {{ session('error') }}
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

        <form class="admin-edit-form" method="POST" action="{{ route('admin.ai-knowledge.generate') }}" id="generateForm">
            @csrf

            <section class="admin-panel-card">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Generate Settings</h3>
                        <p>Describe what knowledge entries you want the AI to create.</p>
                    </div>
                    <div class="admin-form-grid">
                        <label class="admin-form-grid__full">
                            <span>Topic / Prompt *</span>
                            <textarea name="prompt" rows="6" required placeholder="e.g., Create Q&A entries about challenges new immigrants face in Australia, including housing, jobs, culture shock, banking, healthcare, and social integration.">{{ old('prompt') }}</textarea>
                            <small style="display:block;margin-top:6px;color:#667788;">Be specific about the topic, target audience, and what aspects to cover. The AI will create detailed Q&A entries based on this.</small>
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
                            <span>Number of Entries (1-30)</span>
                            <input type="number" name="count" value="{{ old('count', 10) }}" min="1" max="30">
                            <small style="display:block;margin-top:6px;color:#667788;">How many Q&A entries to generate. 10-20 is recommended.</small>
                        </label>
                    </div>
                </div>
            </section>

            <section class="admin-panel-card" style="margin-top:1.5rem;">
                <div class="admin-settings-section">
                    <div class="admin-settings-section__head">
                        <h3>Example Prompts</h3>
                        <p>Click any example to use it as your prompt.</p>
                    </div>
                    <div style="display:grid;gap:0.75rem;margin-top:1rem;">
                        <button type="button" class="example-prompt" data-prompt="Create Q&A entries about challenges new immigrants face in Australia, including housing difficulties, job hunting without local experience, social isolation, culture shock, banking setup, healthcare navigation, and building a new social network.">
                            <strong>New Immigrant Challenges</strong>
                            <span>Covers housing, jobs, isolation, culture, banking, healthcare, networking</span>
                        </button>
                        <button type="button" class="example-prompt" data-prompt="Create Q&A entries about the Australian rental market for newcomers, including how to search for rentals, rental application process, bond and lease agreements, tenant rights, common mistakes to avoid, best suburbs for different budgets, and dealing with real estate agents.">
                            <strong>Rental Market Guide</strong>
                            <span>Covers rental search, applications, bonds, leases, tenant rights, suburbs, agents</span>
                        </button>
                        <button type="button" class="example-prompt" data-prompt="Create Q&A entries about Australian visa types and migration pathways, including skilled worker visas, student visas, partner visas, working holiday visas, permanent residency pathways, visa application costs, processing times, and when to use a registered migration agent.">
                            <strong>Visa Types & Migration</strong>
                            <span>Covers skilled, student, partner, working holiday visas, PR pathways, costs, agents</span>
                        </button>
                        <button type="button" class="example-prompt" data-prompt="Create Q&A entries about banking and finance for new arrivals in Australia, including opening bank accounts, choosing a bank, transferring money internationally, tax file numbers (TFN), superannuation, building credit history, common banking mistakes, and money transfer services like Wise and OFX.">
                            <strong>Banking & Finance Setup</strong>
                            <span>Covers accounts, bank selection, transfers, TFN, super, credit, money services</span>
                        </button>
                        <button type="button" class="example-prompt" data-prompt="Create Q&A entries about Australian healthcare system for newcomers, including Medicare eligibility, private health insurance, finding a GP, specialist referrals, prescription medications, mental health support, ambulance cover, and healthcare costs without Medicare.">
                            <strong>Healthcare System</strong>
                            <span>Covers Medicare, private insurance, GPs, specialists, medications, mental health</span>
                        </button>
                        <button type="button" class="example-prompt" data-prompt="Create Q&A entries about finding work and building a career in Australia, including job search strategies, resume and cover letter tips, interview expectations, networking, recognizing overseas qualifications, workplace culture, minimum wage, and career progression for migrants.">
                            <strong>Work & Career Building</strong>
                            <span>Covers job search, resumes, interviews, networking, qualifications, workplace culture</span>
                        </button>
                    </div>
                </div>
            </section>

            <div style="display:flex;gap:1rem;margin-top:1.5rem;">
                <button class="button button--large" type="submit" id="generateBtn">
                    <span id="btnText">Generate with AI</span>
                    <span id="btnLoading" hidden>Generating... Please wait (30-60 seconds)</span>
                </button>
                <a href="{{ route('admin.ai-knowledge.index') }}" class="button button--large" style="background:#667788;">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .example-prompt {
            display: block;
            width: 100%;
            text-align: left;
            padding: 1rem 1.25rem;
            border: 1px solid rgba(11, 122, 117, 0.16);
            border-radius: 12px;
            background: #f8fffe;
            color: #1b5d6d;
            cursor: pointer;
            transition: all 0.2s;
        }
        .example-prompt:hover {
            border-color: #0b7a75;
            background: #e8f8f6;
            box-shadow: 0 4px 12px rgba(11, 122, 117, 0.1);
        }
        .example-prompt strong {
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }
        .example-prompt span {
            display: block;
            font-size: 0.82rem;
            color: #667788;
            font-weight: 400;
        }
    </style>

    <script>
        document.querySelectorAll('.example-prompt').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelector('textarea[name="prompt"]').value = btn.dataset.prompt;
                document.querySelector('textarea[name="prompt"]').focus();
            });
        });

        document.getElementById('generateForm').addEventListener('submit', () => {
            const btn = document.getElementById('generateBtn');
            const text = document.getElementById('btnText');
            const loading = document.getElementById('btnLoading');
            btn.disabled = true;
            text.hidden = true;
            loading.hidden = false;
        });
    </script>
@endsection
