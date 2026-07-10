<section class="admin-copilot-chat site-chat" aria-label="AI Admin Copilot" style="position:fixed;right:1rem;bottom:1rem;z-index:9997;">
    <div class="floating-actions" aria-label="Quick actions" style="position:fixed;right:1rem;bottom:1rem;z-index:9997;display:flex;flex-direction:column;align-items:center;gap:0.75rem;">
        <button class="floating-action floating-action--assistant admin-copilot-toggle" type="button" data-admin-copilot-toggle aria-expanded="false" aria-controls="admin-copilot-panel" aria-label="Open AI Admin Copilot" style="appearance:none;-webkit-appearance:none;background:transparent;border:0;box-shadow:none;padding:0;margin:0;">
            <img src="{{ asset('media/icons/ai_assistance.webp') }}" alt="" class="floating-action__icon" width="56" height="56" loading="lazy">
        </button>
    </div>
    <div
        id="admin-copilot-panel"
        class="site-chat-panel"
        data-admin-copilot-panel
        data-admin-copilot-greeting="👋 Hi! Welcome to SettleANZ Admin.

I can help you manage every part of the admin panel.

📝 Blog  🌐 SEO  📚 eBooks  📧 Email  ⚙️ Settings  📊 Reports

Ask me anything about the admin panel."
        hidden
        style="position:fixed;right:1rem;bottom:6.5rem;z-index:9998;width:min(420px, calc(100vw - 1.5rem));"
    >
        <div class="site-chat-head">
            <div class="site-chat-head-main">
                <h2 class="site-chat-title">Admin Copilot</h2>
                <p class="site-chat-sub">Your AI guide for the admin panel</p>
            </div>
            <div class="site-chat-head-actions">
                <button class="site-chat-send site-chat-send--secondary" type="button" data-admin-copilot-reset>Clear</button>
                <button class="site-chat-close" type="button" data-admin-copilot-close aria-label="Close copilot">&times;</button>
            </div>
        </div>
        <div class="site-chat-log" data-admin-copilot-log></div>
        <form class="site-chat-form" data-admin-copilot-form>
            <input class="site-chat-input" type="text" name="message" data-admin-copilot-input maxlength="1800" placeholder="Ask about the admin panel..." required>
            <button class="site-chat-send" type="submit">Send</button>
        </form>
    </div>
</section>

<script>
(function() {
    var KEY = 'settleanzAdminCopilotConversation';

    var panel = document.querySelector('[data-admin-copilot-panel]');
    var toggleButtons = document.querySelectorAll('[data-admin-copilot-toggle]');
    var closeButton = document.querySelector('[data-admin-copilot-close]');
    var resetButton = document.querySelector('[data-admin-copilot-reset]');
    var log = panel ? panel.querySelector('[data-admin-copilot-log]') : null;
    var form = panel ? panel.querySelector('[data-admin-copilot-form]') : null;
    var input = panel ? panel.querySelector('[data-admin-copilot-input]') : null;
    var sendBtn = form ? form.querySelector('button[type="submit"]') : null;

    var conversationId = window.localStorage.getItem(KEY) || '';
    var hasLoaded = false;
    var isSubmitting = false;
    var thinkingEl = null;
    var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    var scrollToBottom = function() {
        if (log) log.scrollTop = log.scrollHeight;
    };

    var removeThinking = function() {
        if (thinkingEl) { thinkingEl.remove(); thinkingEl = null; }
    };

    var stripThinkBlocks = function(text) {
        return text.replace(/<think>[\s\S]*?<\/think>/g, '').replace(/<\/?think>/g, '').trim();
    };

    var appendMessage = function(role, content) {
        if (!log || !content) return;
        content = stripThinkBlocks(content);
        if (!content) return;
        var msg = document.createElement('div');
        msg.className = 'site-chat-msg ' + (role === 'user' ? 'user' : role === 'assistant' ? 'bot' : 'system');
        var formatted = content
            .replace(/(https?:\/\/[^\s<]+)/g, '<a href="$1" target="_blank" rel="noopener" class="chat-link">$1</a>')
            .replace(/(?:^|\s)(\/[a-zA-Z0-9\-\/]+)/g, function(match, path) {
                var clean = path.replace(/[,.)]$/, '');
                var trail = path !== clean ? path.slice(-1) : '';
                return ' <a href="' + clean + '" class="chat-link">' + clean + '</a>' + trail;
            });
        msg.innerHTML = formatted;
        log.appendChild(msg);
        scrollToBottom();
    };

    var appendThinking = function() {
        if (!log) return;
        removeThinking();
        var msg = document.createElement('div');
        msg.className = 'site-chat-msg bot thinking';
        msg.innerHTML =
            '<div class="site-chat-thinking-title">AI is thinking</div>' +
            '<div class="site-chat-thinking-copy">Planning the best answer for your question.</div>' +
            '<div class="site-chat-thinking-dots" aria-hidden="true">' +
                '<span></span><span></span><span></span>' +
            '</div>';
        log.appendChild(msg);
        thinkingEl = msg;
        scrollToBottom();
    };

    var renderGreeting = function() {
        if (!log || log.childElementCount > 0) return;
        appendMessage('assistant', panel ? panel.dataset.adminCopilotGreeting : '👋 Hi! Welcome to SettleANZ Admin. How can I help you today?');
    };

    var getPageTitle = function() {
        var el = document.querySelector('.admin-topbar-saas__page-title');
        return el ? (el.textContent || '').trim() : '';
    };

    var getPageRoute = function() {
        return document.body.getAttribute('data-route') || '';
    };

    var requestJson = async function(url, options) {
        var resp = await fetch(url, options);
        var text = await resp.text();
        var data;
        try { data = JSON.parse(text); } catch (e) { throw new Error('Invalid server response'); }
        if (!resp.ok) throw new Error(data.message || 'Request failed');
        return data;
    };

    var createSession = async function() {
        var data = await requestJson('{{ route("admin.ai-assistant.copilot.session") }}', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
        });
        conversationId = data.conversation_id;
        window.localStorage.setItem(KEY, conversationId);
    };

    var loadHistory = async function() {
        if (!log || !conversationId) return;
        try {
            var data = await requestJson('{{ url("admin/ai-assistant/copilot") }}/' + conversationId + '/history', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (data.messages) {
                log.innerHTML = '';
                data.messages.forEach(function(msg) {
                    appendMessage(msg.role, msg.content);
                });
            }
        } catch (e) {
            // silent — will show greeting
        }
    };

    var handleSubmit = async function() {
        if (!input || isSubmitting) return;
        var content = input.value.trim();
        if (!content) return;

        appendMessage('user', content);
        input.value = '';
        appendThinking();
        isSubmitting = true;
        if (input) input.disabled = true;
        if (sendBtn) { sendBtn.disabled = true; sendBtn.textContent = 'Thinking...'; }

        try {
            if (!conversationId) await createSession();

            var result = await requestJson('{{ url("admin/ai-assistant/copilot") }}/' + conversationId + '/message', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    content: content,
                    page_title: getPageTitle(),
                    page_route: getPageRoute(),
                }),
            });

            removeThinking();
            appendMessage('assistant', result.assistant.content || 'I could not generate a reply. Please try again.');
        } catch (err) {
            removeThinking();
            appendMessage('system', err.message || 'Sorry, the copilot is unavailable right now.');
        } finally {
            isSubmitting = false;
            if (input) input.disabled = false;
            if (sendBtn) { sendBtn.disabled = false; sendBtn.textContent = 'Send'; }
            if (input) input.focus();
        }
    };

    var handleReset = async function() {
        if (!log) return;
        log.innerHTML = '';
        removeThinking();
        window.localStorage.removeItem(KEY);
        conversationId = '';

        try {
            if (conversationId) {
                await requestJson('{{ route("admin.ai-assistant.copilot.reset") }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ conversation_id: conversationId }),
                });
            }
        } catch (e) { /* ignore */ }

        conversationId = '';
        renderGreeting();
    };

    // Toggle open/close
    toggleButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (panel.hidden) {
                panel.hidden = false;
                panel.classList.add('is-open');
                btn.setAttribute('aria-expanded', 'true');
                if (!hasLoaded) {
                    hasLoaded = true;
                    if (conversationId) {
                        loadHistory().then(function() {
                            if (log && log.childElementCount === 0) renderGreeting();
                        });
                    } else {
                        renderGreeting();
                    }
                } else {
                    renderGreeting();
                }
                if (input) setTimeout(function() { input.focus(); }, 100);
            } else {
                panel.hidden = true;
                panel.classList.remove('is-open');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    });

    if (closeButton) {
        closeButton.addEventListener('click', function() {
            panel.hidden = true;
            panel.classList.remove('is-open');
            toggleButtons.forEach(function(btn) { btn.setAttribute('aria-expanded', 'false'); });
        });
    }

    if (resetButton) {
        resetButton.addEventListener('click', handleReset);
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleSubmit();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && panel && !panel.hidden) {
            panel.hidden = true;
            panel.classList.remove('is-open');
            toggleButtons.forEach(function(btn) { btn.setAttribute('aria-expanded', 'false'); });
        }
    });
})();
</script>
