@php
    $manager = app(\App\Services\Verification\VerificationManager::class);
    $isEnabled = $manager->isEnabled();
    $driver = $manager->getDriverName();
    
    $challenge = $isEnabled ? $manager->generate() : null;
    $uniqueId = 'verification_' . uniqid();
@endphp

@if($isEnabled)
    @if($driver === 'math')
        @php
            $question = $challenge['question'] ?? '';
            $token = $challenge['token'] ?? '';
        @endphp
        <div class="math-verification-wrapper">
            <!-- Hidden token input for multi-tab challenge validation -->
            <input type="hidden" name="verification_token" class="math-verification-token" value="{{ $token }}">
            
            <div class="math-verification-row">
                <label for="{{ $uniqueId }}" class="math-verification-prompt">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="math-verification-lock" aria-hidden="true"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span class="math-label-title">Verify:</span>
                    <strong class="math-question-text" data-math-question>{{ $question }}</strong>
                    <span class="math-equals">=</span>
                </label>
                
                <div class="math-input-group">
                    <input 
                        type="text" 
                        id="{{ $uniqueId }}" 
                        name="math_answer" 
                        required 
                        class="math-answer-input"
                        placeholder="Answer"
                        autocomplete="off"
                        aria-required="true"
                        aria-label="Math verification answer"
                    >
                    <button type="button" class="math-refresh-btn" data-math-refresh aria-label="Get new math question" title="Get a new question">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                    </button>
                </div>
            </div>
            
            @error('math_answer')
                <span class="math-error-msg">{{ $message }}</span>
            @enderror
        </div>
    @elseif($driver === 'recaptcha')
        <div class="recaptcha-verification-wrapper" style="margin-bottom: 1.25rem;">
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <div class="g-recaptcha" data-sitekey="{{ $challenge['site_key'] }}"></div>
            @error('g-recaptcha-response')
                <span class="math-error-msg">{{ $message }}</span>
            @enderror
            @error('math_answer')
                <span class="math-error-msg">{{ $message }}</span>
            @enderror
        </div>
    @elseif($driver === 'turnstile')
        <div class="turnstile-verification-wrapper" style="margin-bottom: 1.25rem;">
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            <div class="cf-turnstile" data-sitekey="{{ $challenge['site_key'] }}"></div>
            @error('cf-turnstile-response')
                <span class="math-error-msg">{{ $message }}</span>
            @enderror
            @error('math_answer')
                <span class="math-error-msg">{{ $message }}</span>
            @enderror
        </div>
    @elseif($driver === 'hcaptcha')
        <div class="hcaptcha-verification-wrapper" style="margin-bottom: 1.25rem;">
            <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
            <div class="h-captcha" data-sitekey="{{ $challenge['site_key'] }}"></div>
            @error('h-captcha-response')
                <span class="math-error-msg">{{ $message }}</span>
            @enderror
            @error('math_answer')
                <span class="math-error-msg">{{ $message }}</span>
            @enderror
        </div>
    @endif

    @once
    <style>
        .math-verification-wrapper {
            width: 100%;
            font-family: inherit;
            box-sizing: border-box;
            margin-bottom: 1.25rem;
        }
        .math-verification-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            box-sizing: border-box;
        }
        .math-verification-prompt {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.95rem;
            font-weight: 600;
            color: inherit;
            white-space: nowrap;
            margin: 0;
            cursor: pointer;
            user-select: none;
            flex-shrink: 0;
        }
        .math-verification-lock {
            color: currentColor;
            opacity: 0.85;
            flex-shrink: 0;
        }
        .math-label-title {
            font-weight: 600;
        }
        .math-question-text {
            font-weight: 700;
            letter-spacing: -0.2px;
        }
        .math-equals {
            opacity: 0.9;
        }
        .math-input-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            max-width: 220px;
            width: 100%;
            box-sizing: border-box;
        }
        .math-answer-input {
            flex: 1;
            width: 100%;
            height: 48px;
            min-height: 48px;
            padding: 0 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 0.95rem;
            color: #1e293b;
            background-color: #ffffff;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
        }
        .math-answer-input:focus {
            border-color: #0b7a75;
            box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.18);
        }
        .math-refresh-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            min-width: 48px;
            min-height: 48px;
            border-radius: 10px;
            background: rgba(11, 122, 117, 0.08);
            border: 1px solid rgba(11, 122, 117, 0.15);
            color: #0b7a75;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.2s ease, color 0.2s ease;
            flex-shrink: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .math-refresh-btn svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        .math-refresh-btn:hover {
            transform: rotate(180deg);
            background: rgba(11, 122, 117, 0.18);
            color: #085854;
        }
        .math-error-msg {
            color: #e53e3e;
            display: block;
            margin-top: 0.35rem;
            font-size: 0.825rem;
            font-weight: 500;
            text-align: left;
        }

        @media (max-width: 767px) {
            .math-verification-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                width: 100%;
            }
            .math-verification-prompt {
                font-size: 0.9rem;
            }
            .math-input-group {
                max-width: 100%;
                width: 100%;
                display: flex;
                gap: 8px;
            }
            .math-answer-input {
                height: 52px;
                min-height: 52px;
                font-size: 16px; /* Prevents auto-zoom on mobile safari */
            }
            .math-refresh-btn {
                width: 52px;
                height: 52px;
                min-width: 52px;
                min-height: 52px;
            }
        }
    </style>
    @endonce
@endif
