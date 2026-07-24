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
            
            <label for="{{ $uniqueId }}" class="math-verification-label">
                <span class="label-title-wrapper">Human Verification <span class="math-required">*</span></span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="math-verification-lock"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </label>
            
            <div class="math-verification-prompt">
                <span>What is: <strong class="math-question-text" data-math-question>{{ $question }}</strong> = ?</span>
                <button type="button" class="math-refresh-btn" data-math-refresh aria-label="Get new math question" title="Get a new question">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block;"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/></svg>
                </button>
            </div>
            
            <div class="math-input-container">
                <input 
                    type="text" 
                    id="{{ $uniqueId }}" 
                    name="math_answer" 
                    required 
                    class="math-answer-input"
                    placeholder="Enter answer"
                    autocomplete="off"
                    aria-required="true"
                >
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
            margin-bottom: 1.25rem;
            text-align: left;
            font-family: inherit;
        }
        .math-verification-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            margin-bottom: 0.35rem;
            font-size: 0.95rem;
            color: inherit;
        }
        .math-verification-lock {
            color: #64748b;
        }
        .math-required {
            color: #e53e3e;
            margin-left: 0.15rem;
        }
        .math-verification-prompt {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            color: inherit;
        }
        .math-refresh-btn {
            background: none;
            border: none;
            padding: 2px;
            cursor: pointer;
            color: #0b7a75;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            transition: transform 0.2s;
        }
        .math-refresh-btn:hover {
            transform: rotate(30deg);
        }
        .math-answer-input {
            width: 100%;
            max-width: 140px;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 0.95rem;
            color: #333;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out;
        }
        .math-answer-input:focus {
            border-color: #0b7a75;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(11, 122, 117, 0.25);
        }
        .math-error-msg {
            color: #e53e3e;
            display: block;
            margin-top: 0.35rem;
            font-size: 0.825rem;
            font-weight: 500;
        }
    </style>
    @endonce
@endif
