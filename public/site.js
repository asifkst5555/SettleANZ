(() => {
    const menuToggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-menu]');
    const backToTop = document.querySelector('[data-back-to-top]');
    const siteHeader = document.querySelector('[data-site-header]');
    const leadModal = document.querySelector('[data-lead-modal]');
    const openLeadModalTriggers = document.querySelectorAll('[data-open-lead-modal]');
    const closeLeadModalTriggers = document.querySelectorAll('[data-close-lead-modal]');
    const bookingModal = document.querySelector('[data-booking-modal]');
    const openBookingModalTriggers = document.querySelectorAll('[data-open-booking-modal]');
    const closeBookingModalTriggers = document.querySelectorAll('[data-close-booking-modal]');
    const bookingAgentName = document.querySelector('[data-booking-agent-name]');
    const bookingAgentField = document.querySelector('[data-booking-agent-field]');
    const bookingAgentId = document.querySelector('[data-booking-agent-id]');
    const packageModal = document.querySelector('[data-package-modal]');
    const openPackageModalTriggers = document.querySelectorAll('[data-open-package-modal]');
    const closePackageModalTriggers = document.querySelectorAll('[data-close-package-modal]');
    const packageFormModalOverlay = document.getElementById('packageFormModalOverlay');
    const packageFormModalLoading = document.getElementById('packageFormModalLoading');
    const packageFormModalSuccess = document.getElementById('packageFormModalSuccess');
    const packageFormModalError = document.getElementById('packageFormModalError');
    const packageFormModalErrorText = document.getElementById('packageFormModalErrorText');
    const packageFormModalSuccessMessage = document.getElementById('packageFormModalSuccessMessage');
    const closePackageFormModalTriggers = document.querySelectorAll('[data-close-package-form-modal]');
    const roadmapFormModalOverlay = document.getElementById('roadmapFormModalOverlay');
    const roadmapFormModalLoading = document.getElementById('roadmapFormModalLoading');
    const roadmapFormModalSuccess = document.getElementById('roadmapFormModalSuccess');
    const roadmapFormModalError = document.getElementById('roadmapFormModalError');
    const roadmapFormModalErrorText = document.getElementById('roadmapFormModalErrorText');
    const roadmapFormModalSuccessMessage = document.getElementById('roadmapFormModalSuccessMessage');
    const closeRoadmapFormModalTriggers = document.querySelectorAll('[data-close-roadmap-form-modal]');
    const chatSection = document.querySelector('.site-chat');
    const chatPanel = document.querySelector('[data-chat-panel]');
    const chatToggleButtons = document.querySelectorAll('[data-chat-toggle]');
    const chatCloseButton = document.querySelector('[data-chat-close]');
    const chatResetButton = document.querySelector('[data-chat-reset]');
    const chatLog = chatPanel ? chatPanel.querySelector('[data-chat-log]') : null;
    const chatForm = chatPanel ? chatPanel.querySelector('[data-chat-form]') : null;
    const chatInput = chatPanel ? chatPanel.querySelector('[data-chat-input]') : null;
    const chatSendButton = chatForm ? chatForm.querySelector('button[type="submit"]') : null;
    const asyncForms = document.querySelectorAll('[data-async-form]');
    const body = document.body;
    const blogFilterButtons = document.querySelectorAll('[data-blog-filter]');
    const blogPosts = document.querySelectorAll('[data-blog-post]');
    const loadMoreButton = document.querySelector('[data-blog-load-more]');
    const blogSearch = document.querySelector('[data-blog-search]');
    const directoryFilterButtons = document.querySelectorAll('[data-directory-filter]');
    const directoryListings = document.querySelectorAll('[data-directory-listing]');
    const directorySearch = document.querySelector('[data-directory-search]');
    const directoryCity = document.querySelector('[data-directory-city]');
    const directoryReset = document.querySelector('[data-directory-reset]');

    const popupSeenKey = 'settleanzLeadPopupSeen';
    const popupDismissedUntilKey = 'settleanzLeadPopupDismissedUntil';
    const popupSubmittedKey = 'settleanzLeadPopupSubmitted';
    const chatConversationKey = 'settleanzChatConversationId';
    const chatVisitorKey = 'settleanzChatVisitorId';

    let chatConversationId = window.localStorage.getItem(chatConversationKey) || '';
    let chatHasLoaded = false;
    let chatIsSubmitting = false;
    let chatThinkingEl = null;

    const getVisibleBlogPosts = () => Array.from(blogPosts).filter((post) => !post.classList.contains('is-hidden-filter'));

    const getChatVisitorId = () => {
        let visitorId = window.localStorage.getItem(chatVisitorKey);
        if (!visitorId) {
            visitorId = window.crypto?.randomUUID ? window.crypto.randomUUID() : `visitor-${Date.now()}-${Math.random().toString(16).slice(2)}`;
            window.localStorage.setItem(chatVisitorKey, visitorId);
        }

        return visitorId;
    };

    const scrollChatToBottom = () => {
        if (chatLog) {
            chatLog.scrollTop = chatLog.scrollHeight;
        }
    };

    const removeThinkingMessage = () => {
        if (chatThinkingEl) {
            chatThinkingEl.remove();
            chatThinkingEl = null;
        }
    };

    const appendChatMessage = (role, content) => {
        if (!chatLog || !content) return;

        const message = document.createElement('div');
        message.className = `site-chat-msg ${role === 'user' ? 'user' : role === 'assistant' ? 'bot' : 'system'}`;

        const formattedContent = content.replace(/(https?:\/\/[^\s<]+)/g, '<a href="$1" target="_blank" rel="noopener" class="chat-link">$1</a>')
            .replace(/(?:^|\s)(\/[a-zA-Z0-9\-\/]+)/g, (match, path) => {
                const cleanPath = path.replace(/[,.)]$/, '');
                const trailing = path !== cleanPath ? path.slice(-1) : '';
                return ` <a href="${cleanPath}" class="chat-link">${cleanPath}</a>${trailing}`;
            });

        message.innerHTML = formattedContent;
        chatLog.appendChild(message);
        scrollChatToBottom();
    };

    const appendThinkingMessage = () => {
        if (!chatLog) return;

        removeThinkingMessage();

        const message = document.createElement('div');
        message.className = 'site-chat-msg bot thinking';
        message.innerHTML = `
            <div class="site-chat-thinking-title">AI is thinking</div>
            <div class="site-chat-thinking-copy">Planning the best answer for your question.</div>
            <div class="site-chat-thinking-dots" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;

        chatLog.appendChild(message);
        chatThinkingEl = message;
        scrollChatToBottom();
    };

    const renderChatGreeting = () => {
        if (!chatLog || chatLog.childElementCount > 0) return;
        appendChatMessage('assistant', chatPanel?.dataset.chatGreeting || 'Hi, I am the SettleANZ AI Assistant. How can I help you today?');
    };

    const setChatBusy = (isBusy) => {
        chatIsSubmitting = isBusy;
        if (chatInput) chatInput.disabled = isBusy;
        if (chatSendButton) {
            chatSendButton.disabled = isBusy;
            chatSendButton.textContent = isBusy ? 'Thinking...' : 'Send';
        }
        if (!isBusy && chatInput) {
            chatInput.focus();
        }
    };

    const requestJson = async (url, options = {}) => {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(options.headers || {}),
            },
        });

        const text = await response.text();
        let payload = {};
        if (text) {
            try {
                payload = JSON.parse(text);
            } catch (error) {
                payload = { message: 'Unexpected response from server.' };
            }
        }

        if (!response.ok) {
            const errorMessage = payload.message || (payload.errors ? Object.values(payload.errors).flat()[0] : 'Something went wrong. Please try again.');
            throw new Error(errorMessage || 'Something went wrong. Please try again.');
        }

        return payload;
    };

    const createChatSession = async (isReset = false) => {
        const payload = {
            channel: 'website_widget',
            visitor_id: getChatVisitorId(),
            language: 'en',
        };

        if (isReset && chatConversationId) {
            payload.conversation_id = chatConversationId;
        }

        const endpoint = isReset ? '/api/chat/reset' : '/api/chat/session';
        const response = await requestJson(endpoint, {
            method: 'POST',
            body: JSON.stringify(payload),
        });

        chatConversationId = response.conversation_id || '';
        if (chatConversationId) {
            window.localStorage.setItem(chatConversationKey, chatConversationId);
        }

        return chatConversationId;
    };

    const showChatVerificationPrompt = async () => {
        if (!chatLog) return;
        
        chatLog.innerHTML = '';
        
        const welcomeMsg = document.createElement('div');
        welcomeMsg.className = 'site-chat-msg bot';
        welcomeMsg.textContent = 'Welcome to SettleANZ AI. Please verify you are a human to start chatting.';
        chatLog.appendChild(welcomeMsg);
        
        try {
            const response = await fetch('/verification/refresh', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.enabled) {
                    if (data.driver === 'math') {
                        const verifyBox = document.createElement('div');
                        verifyBox.className = 'site-chat-msg bot chat-verification-box';
                        verifyBox.style.padding = '12px';
                        verifyBox.style.margin = '8px 0';
                        verifyBox.style.borderRadius = '8px';
                        verifyBox.style.background = 'rgba(0, 0, 0, 0.04)';
                        verifyBox.innerHTML = `
                            <p style="margin: 0 0 8px; font-weight: 600;">Verification Challenge</p>
                            <p style="margin: 0 0 8px;">What is <strong data-chat-math-question>${data.question}</strong>? = ?</p>
                            <input type="hidden" id="chat-math-token" value="${data.token}">
                            <div style="display: flex; gap: 8px;">
                                <input type="text" id="chat-math-answer" placeholder="Answer" style="width: 90px; padding: 6px; border: 1px solid #ccc; border-radius: 4px; color: #333;">
                                <button type="button" id="chat-math-submit" class="button button--small" style="padding: 6px 12px; background: #0b7a75; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Verify</button>
                            </div>
                            <p id="chat-verify-error" style="color: #dc2626; margin: 6px 0 0; font-size: 0.85rem; display: none;"></p>
                        `;
                        chatLog.appendChild(verifyBox);
                        
                        if (chatInput) chatInput.disabled = true;
                        if (chatSendButton) chatSendButton.disabled = true;
                        
                        const submitBtn = verifyBox.querySelector('#chat-math-submit');
                        const answerInput = verifyBox.querySelector('#chat-math-answer');
                        const errorMsg = verifyBox.querySelector('#chat-verify-error');
                        
                        const performVerify = async () => {
                            const val = answerInput.value.trim();
                            const tok = verifyBox.querySelector('#chat-math-token').value;
                            if (!val) return;
                            
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Verifying...';
                            errorMsg.style.display = 'none';
                            
                            try {
                                const sessionRes = await fetch('/api/chat/session', {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({
                                        channel: 'website_widget',
                                        visitor_id: getChatVisitorId(),
                                        language: 'en',
                                        math_answer: val,
                                        verification_token: tok
                                    })
                                });
                                
                                const payload = await sessionRes.json();
                                if (!sessionRes.ok) {
                                    throw new Error(payload.message || 'Verification failed. Please try again.');
                                }
                                
                                chatConversationId = payload.conversation_id || '';
                                if (chatConversationId) {
                                    window.localStorage.setItem(chatConversationKey, chatConversationId);
                                }
                                
                                verifyBox.remove();
                                renderChatGreeting();
                                if (chatInput) chatInput.disabled = false;
                                if (chatSendButton) chatSendButton.disabled = false;
                                if (chatInput) chatInput.focus();
                            } catch (err) {
                                errorMsg.textContent = err.message || 'Incorrect. Try again.';
                                errorMsg.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.textContent = 'Verify';
                                
                                const refreshRes = await fetch('/verification/refresh', {
                                    headers: {
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                });
                                if (refreshRes.ok) {
                                    const refreshData = await refreshRes.json();
                                    verifyBox.querySelector('[data-chat-math-question]').textContent = refreshData.question;
                                    verifyBox.querySelector('#chat-math-token').value = refreshData.token;
                                    answerInput.value = '';
                                }
                            }
                        };
                        
                        submitBtn.addEventListener('click', performVerify);
                        answerInput.addEventListener('keydown', (e) => {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                performVerify();
                            }
                        });
                    } else {
                        renderChatGreeting();
                        if (chatInput) chatInput.disabled = false;
                        if (chatSendButton) chatSendButton.disabled = false;
                    }
                } else {
                    renderChatGreeting();
                    if (chatInput) chatInput.disabled = false;
                    if (chatSendButton) chatSendButton.disabled = false;
                }
            }
        } catch (e) {
            console.error('Failed to initialize chatbot verification:', e);
            renderChatGreeting();
        }
        scrollChatToBottom();
    };

    const loadChatHistory = async () => {
        if (!chatLog) return;

        if (!chatConversationId) {
            await showChatVerificationPrompt();
            chatHasLoaded = true;
            return;
        }

        try {
            const payload = await requestJson(`/api/chat/history/${chatConversationId}`);
            chatLog.innerHTML = '';
            removeThinkingMessage();

            (payload.messages || []).forEach((message) => {
                const role = message.role === 'assistant' ? 'assistant' : message.role === 'user' ? 'user' : 'system';
                appendChatMessage(role, message.content || '');
            });

            renderChatGreeting();
            chatHasLoaded = true;
        } catch (error) {
            window.localStorage.removeItem(chatConversationKey);
            chatConversationId = '';
            chatLog.innerHTML = '';
            removeThinkingMessage();
            await showChatVerificationPrompt();
            chatHasLoaded = true;
        }
    };

    const ensureChatReady = async () => {
        if (chatHasLoaded) return;
        await loadChatHistory();
    };

    const handleChatReset = async () => {
        if (!chatLog) return;

        chatLog.innerHTML = '';
        removeThinkingMessage();

        window.localStorage.removeItem(chatConversationKey);
        chatConversationId = '';

        chatHasLoaded = true;
        await showChatVerificationPrompt();
    };

    const submitChatMessage = async () => {
        if (!chatInput || chatIsSubmitting) return;

        const content = chatInput.value.trim();
        if (!content) return;

        if (!chatConversationId) {
            appendChatMessage('system', 'Please verify your humanity first.');
            return;
        }

        appendChatMessage('user', content);
        chatInput.value = '';
        appendThinkingMessage();
        setChatBusy(true);

        try {
            const payload = await requestJson(`/api/chat/message/${chatConversationId}`, {
                method: 'POST',
                body: JSON.stringify({ content }),
            });

            removeThinkingMessage();
            const assistantContent = payload.assistant?.content || 'I am here, but I could not generate a reply just now.';
            appendChatMessage('assistant', assistantContent);
        } catch (error) {
            removeThinkingMessage();
            appendChatMessage('system', error.message || 'Sorry, the AI assistant is unavailable right now.');
        } finally {
            chatHasLoaded = true;
            setChatBusy(false);
        }
    };

    const refreshLoadMore = () => {
        if (!loadMoreButton || !blogPosts.length) return;
        const hiddenCount = getVisibleBlogPosts().filter((post) => post.classList.contains('is-hidden')).length;
        loadMoreButton.hidden = hiddenCount === 0;
    };

    const applyBlogFilters = () => {
        if (!blogPosts.length) return;
        const activeCategoryButton = Array.from(blogFilterButtons).find((button) => button.classList.contains('is-active'));
        const category = activeCategoryButton ? (activeCategoryButton.dataset.blogFilter || 'all') : 'all';
        const term = blogSearch ? blogSearch.value.trim().toLowerCase() : '';
        let visibleCounter = 0;

        blogPosts.forEach((post) => {
            const categoryMatch = category === 'all' || post.dataset.category === category;
            const searchHaystack = post.dataset.search || '';
            const searchMatch = !term || searchHaystack.includes(term);
            const matches = categoryMatch && searchMatch;

            post.classList.toggle('is-hidden-filter', !matches);
            if (!matches) {
                post.classList.add('is-hidden');
                return;
            }

            visibleCounter += 1;
            post.classList.toggle('is-hidden', visibleCounter > 6);
        });

        refreshLoadMore();
    };

    const applyDirectoryFilters = () => {
        if (!directoryListings.length) return;
        const activeCategoryButton = Array.from(directoryFilterButtons).find((button) => button.classList.contains('is-active'));
        const category = activeCategoryButton ? activeCategoryButton.dataset.directoryFilter : 'all';
        const city = directoryCity ? directoryCity.value : 'all cities';
        const term = directorySearch ? directorySearch.value.trim().toLowerCase() : '';
        let visibleCount = 0;

        directoryListings.forEach((listing) => {
            const categoryMatch = category === 'all' || listing.dataset.category === category;
            const cityMatch = city === 'all cities' || listing.dataset.city === city;
            const termMatch = !term || listing.dataset.name.includes(term) || listing.dataset.category.includes(term) || listing.dataset.city.includes(term);
            const isMatch = categoryMatch && cityMatch && termMatch;

            listing.classList.toggle('is-hidden', !isMatch);

            if (isMatch) {
                visibleCount += 1;
            }
        });

        if (directoryReset) {
            directoryReset.disabled = category === 'all' && city === 'all cities' && !term;
        }
    };

    const openLeadModal = () => {
        if (!leadModal) return;
        leadModal.hidden = false;
        body.classList.add('has-modal-open');
        sessionStorage.setItem(popupSeenKey, 'true');
    };

    const closeLeadModal = (persistDismiss = false) => {
        if (!leadModal) return;
        leadModal.hidden = true;
        body.classList.remove('has-modal-open');
        if (persistDismiss) {
            const sevenDays = 7 * 24 * 60 * 60 * 1000;
            localStorage.setItem(popupDismissedUntilKey, String(Date.now() + sevenDays));
        }
    };

    const openBookingModal = (trigger) => {
        if (!bookingModal) return;
        const agentName = trigger?.dataset.agentName || 'Migration Agent';
        const agentId = trigger?.dataset.agentId || '';
        bookingModal.hidden = false;
        body.classList.add('has-modal-open');
        if (bookingAgentName) bookingAgentName.textContent = agentName;
        if (bookingAgentField) bookingAgentField.value = agentName;
        if (bookingAgentId) bookingAgentId.value = agentId;
    };

    const closeBookingModal = () => {
        if (!bookingModal) return;
        bookingModal.hidden = true;
        body.classList.remove('has-modal-open');
    };

    const openPackageModal = (trigger) => {
        if (!packageModal) return;
        packageModal.hidden = false;
        body.classList.add('has-modal-open');
        const packageNumber = trigger?.dataset.packageNumber || '';
        const packageStage = trigger?.dataset.packageStage || '';
        const packageHeadline = trigger?.dataset.packageHeadline || '';
        const packagePrice = trigger?.dataset.packagePrice || '';
        const subjectField = packageModal.querySelector('#package-subject');
        const subtitle = packageModal.querySelector('#package-modal-subtitle');
        if (subjectField) {
            subjectField.value = `Package ${packageNumber} - ${packageStage}: ${packageHeadline} (${packagePrice})`;
        }
        if (subtitle) {
            subtitle.textContent = `You're booking: ${packageStage} - ${packageHeadline}`;
        }
    };

    const closePackageModal = () => {
        if (!packageModal) return;
        packageModal.hidden = true;
        body.classList.remove('has-modal-open');
    };

    const showPackageFormModal = (type, message = '') => {
        if (!packageFormModalOverlay) return;

        if (packageFormModalLoading) packageFormModalLoading.hidden = true;
        if (packageFormModalSuccess) packageFormModalSuccess.hidden = true;
        if (packageFormModalError) packageFormModalError.hidden = true;

        if (type === 'loading' && packageFormModalLoading) {
            packageFormModalLoading.hidden = false;
        } else if (type === 'success' && packageFormModalSuccess) {
            packageFormModalSuccess.hidden = false;
        } else if (type === 'error' && packageFormModalError) {
            packageFormModalError.hidden = false;
            if (packageFormModalErrorText && message) {
                packageFormModalErrorText.textContent = message;
            }
        }

        packageFormModalOverlay.hidden = false;
        window.setTimeout(() => {
            packageFormModalOverlay.classList.add('is-visible');
        }, 10);
    };

    const closePackageFormModal = () => {
        if (!packageFormModalOverlay) return;

        packageFormModalOverlay.classList.remove('is-visible');
        window.setTimeout(() => {
            packageFormModalOverlay.hidden = true;
        }, 300);
    };

    const showRoadmapFormModal = (type, message = '') => {
        if (!roadmapFormModalOverlay) return;

        if (roadmapFormModalLoading) roadmapFormModalLoading.hidden = true;
        if (roadmapFormModalSuccess) roadmapFormModalSuccess.hidden = true;
        if (roadmapFormModalError) roadmapFormModalError.hidden = true;

        if (type === 'loading' && roadmapFormModalLoading) {
            roadmapFormModalLoading.hidden = false;
        } else if (type === 'success' && roadmapFormModalSuccess) {
            roadmapFormModalSuccess.hidden = false;
        } else if (type === 'error' && roadmapFormModalError) {
            roadmapFormModalError.hidden = false;
            if (roadmapFormModalErrorText && message) {
                roadmapFormModalErrorText.textContent = message;
            }
        }

        roadmapFormModalOverlay.hidden = false;
        window.setTimeout(() => {
            roadmapFormModalOverlay.classList.add('is-visible');
        }, 10);
    };

    const closeRoadmapFormModal = () => {
        if (!roadmapFormModalOverlay) return;

        roadmapFormModalOverlay.classList.remove('is-visible');
        window.setTimeout(() => {
            roadmapFormModalOverlay.hidden = true;
        }, 300);
    };

    const openChatPanel = async () => {
        if (!chatPanel) return;
        chatPanel.hidden = false;
        chatPanel.classList.add('is-open');
        if (chatSection) chatSection.classList.add('is-open');
        chatToggleButtons.forEach((button) => button.setAttribute('aria-expanded', 'true'));
        await ensureChatReady();
        if (chatInput) chatInput.focus();
    };

    const closeChatPanel = () => {
        if (!chatPanel) return;
        chatPanel.hidden = true;
        chatPanel.classList.remove('is-open');
        if (chatSection) chatSection.classList.remove('is-open');
        chatToggleButtons.forEach((button) => button.setAttribute('aria-expanded', 'false'));
    };

    const shouldAutoOpenPopup = () => {
        if (!leadModal || body.dataset.leadSubmitted === 'true') {
            localStorage.setItem(popupSubmittedKey, 'true');
        }
        if (!leadModal || localStorage.getItem(popupSubmittedKey) === 'true') {
            return false;
        }
        if (sessionStorage.getItem(popupSeenKey) === 'true') {
            return false;
        }
        const dismissedUntil = Number(localStorage.getItem(popupDismissedUntilKey) || '0');
        return dismissedUntil < Date.now();
    };

    const syncScrolledHeader = () => {
        if (siteHeader) siteHeader.classList.toggle('is-scrolled', window.scrollY > 12);
        if (backToTop) backToTop.classList.toggle('is-visible', window.scrollY > 600);
    };

    const initScrollRevealAnimations = () => {
        const selectors = [];

        if (document.body.classList.contains('is-homepage')) {
            selectors.push(
                '.hero-reference__content',
                '.empathy-section__heading',
                '.empathy-card',
                '#guides .section-heading',
                '#guides [data-reveal-stagger-item]',
                '.owner-photo-wrap',
                '.owner-content',
                '.value-stack__heading',
                '.value-stack__card',
                '.value-stack__cta',
                '.testimonial-band__heading',
                '.testimonial-band__card',
                '.lead-strip__copy',
                '.lead-strip__form',
                '.country-acknowledgement__inner',
            );
        }

        if (document.querySelector('.settlement-page')) {
            selectors.push(
                '.settlement-hero__content',
                '.settlement-hero__visual',
                '.settlement-overview__card',
                '.settlement-packages__intro',
                '.settlement-package',
                '.settlement-eligibility__panel',
                '.settlement-faqs__intro',
                '.settlement-faq',
            );
        }

        if (!selectors.length) return;

        const revealTargets = Array.from(document.querySelectorAll(selectors.join(', ')));

        if (!revealTargets.length) return;

        revealTargets.forEach((el, index) => {
            el.classList.add('reveal-on-scroll');
            const staggerGroup = el.closest('[data-reveal-stagger]');
            const staggerItems = Array.from(staggerGroup?.querySelectorAll('[data-reveal-stagger-item]') || []);
            const staggerIndex = Number.parseInt(el.dataset.revealStaggerIndex || `${staggerItems.indexOf(el)}`, 10);
            const hasStagger = Number.isFinite(staggerIndex) && staggerIndex >= 0;
            const delay = hasStagger ? 220 + (staggerIndex * 360) : Math.min((index % 6) * 80, 400);
            el.style.setProperty('--reveal-delay', `${delay}ms`);
        });

        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reducedMotion || !('IntersectionObserver' in window)) {
            revealTargets.forEach((el) => el.classList.add('is-revealed'));
            return;
        }

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-revealed');
                const delay = Number.parseInt(entry.target.style.getPropertyValue('--reveal-delay') || '0', 10);
                window.setTimeout(() => {
                    entry.target.style.setProperty('--reveal-delay', '0ms');
                }, (Number.isFinite(delay) ? delay : 0) + 1200);
                obs.unobserve(entry.target);
            });
        }, {
            threshold: 0.08,
            rootMargin: '0px 0px -18% 0px',
        });

        revealTargets.forEach((el) => observer.observe(el));
    };

    if (menuToggle && menu) {
        const syncMenuState = (isOpen) => {
            menu.classList.toggle('is-open', isOpen);
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            body.classList.toggle('has-mobile-menu-open', isOpen);

            if (window.innerWidth <= 767) {
                menu.style.display = isOpen ? 'flex' : 'none';
                menu.style.visibility = isOpen ? 'visible' : 'hidden';
                menu.style.opacity = isOpen ? '1' : '0';
                menu.style.pointerEvents = isOpen ? 'auto' : 'none';
            } else {
                menu.style.removeProperty('display');
                menu.style.removeProperty('visibility');
                menu.style.removeProperty('opacity');
                menu.style.removeProperty('pointer-events');
            }
        };

        syncMenuState(false);

        menuToggle.addEventListener('click', () => {
            syncMenuState(!menu.classList.contains('is-open'));
        });

        menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => syncMenuState(false));
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 767) {
                syncMenuState(false);
            }
        });
    }

    openLeadModalTriggers.forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            if (trigger.tagName === 'A') event.preventDefault();
            openLeadModal();
        });
    });

    openBookingModalTriggers.forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            openBookingModal(trigger);
        });
    });

    closeLeadModalTriggers.forEach((trigger) => trigger.addEventListener('click', () => closeLeadModal(true)));
    closeBookingModalTriggers.forEach((trigger) => trigger.addEventListener('click', closeBookingModal));
    closePackageModalTriggers.forEach((trigger) => trigger.addEventListener('click', closePackageModal));
    closeRoadmapFormModalTriggers.forEach((trigger) => trigger.addEventListener('click', closeRoadmapFormModal));
    openPackageModalTriggers.forEach((trigger) => {
        console.log('Binding click to:', trigger);
        trigger.addEventListener('click', () => {
            console.log('Package button clicked!');
            openPackageModal(trigger);
        });
    });
    chatToggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (!chatPanel) return;
            if (chatPanel.hidden) {
                void openChatPanel();
            } else {
                closeChatPanel();
            }
        });
    });

    if (chatCloseButton) {
        chatCloseButton.addEventListener('click', closeChatPanel);
    }

    if (chatResetButton) {
        chatResetButton.addEventListener('click', () => {
            void handleChatReset();
        });
    }

    if (chatForm) {
        chatForm.addEventListener('submit', (event) => {
            event.preventDefault();
            void submitChatMessage();
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            if (packageFormModalOverlay && !packageFormModalOverlay.hidden) {
                closePackageFormModal();
                return;
            }
            closeLeadModal(true);
            closeBookingModal();
            closePackageModal();
            closeChatPanel();
        }
    });

    // Auto popup disabled by user request
    // if (shouldAutoOpenPopup()) {
    //     window.setTimeout(() => {
    //         if (shouldAutoOpenPopup()) openLeadModal();
    //     }, 15000);
    //     document.addEventListener('mouseout', (event) => {
    //         if (event.clientY <= 0 && shouldAutoOpenPopup()) openLeadModal();
    //     }, { once: true });
    // }

    if (blogFilterButtons.length) {
        blogFilterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                blogFilterButtons.forEach((item) => item.classList.remove('is-active'));
                button.classList.add('is-active');
                applyBlogFilters();
            });
        });
        applyBlogFilters();
    }

    if (loadMoreButton) {
        loadMoreButton.addEventListener('click', () => {
            const hiddenVisiblePosts = getVisibleBlogPosts().filter((post) => post.classList.contains('is-hidden')).slice(0, 6);
            hiddenVisiblePosts.forEach((post) => post.classList.remove('is-hidden'));
            refreshLoadMore();
        });
        refreshLoadMore();
    }

    if (blogSearch) {
        blogSearch.addEventListener('input', applyBlogFilters);
    }

    if (directoryFilterButtons.length) {
        directoryFilterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                directoryFilterButtons.forEach((item) => item.classList.remove('is-active'));
                button.classList.add('is-active');
                applyDirectoryFilters();
            });
        });
    }

    if (directorySearch) directorySearch.addEventListener('input', applyDirectoryFilters);
    if (directoryCity) directoryCity.addEventListener('change', applyDirectoryFilters);
    if (directoryReset) {
        directoryReset.addEventListener('click', () => {
            if (directorySearch) directorySearch.value = '';
            if (directoryCity) directoryCity.value = 'all cities';

            const allDirectoryButton = Array.from(directoryFilterButtons).find((button) => button.dataset.directoryFilter === 'all');
            directoryFilterButtons.forEach((item) => item.classList.remove('is-active'));
            if (allDirectoryButton) {
                allDirectoryButton.classList.add('is-active');
            }

            applyDirectoryFilters();
        });
    }
    if (directoryListings.length) applyDirectoryFilters();

    const directoryBookmarkButtons = document.querySelectorAll('[data-directory-bookmark]');
    const directoryBookmarksKey = 'settleanzDirectoryBookmarks';

    const readDirectoryBookmarks = () => {
        try {
            const raw = window.localStorage.getItem(directoryBookmarksKey);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch {
            return [];
        }
    };

    let directoryBookmarks = readDirectoryBookmarks();

    const persistDirectoryBookmarks = () => {
        window.localStorage.setItem(directoryBookmarksKey, JSON.stringify(directoryBookmarks));
    };

    directoryBookmarkButtons.forEach((button) => {
        const slug = button.dataset.directorySlug;
        if (slug && directoryBookmarks.includes(slug)) {
            button.classList.add('is-saved');
            button.setAttribute('aria-pressed', 'true');
        }

        button.addEventListener('click', () => {
            if (!slug) return;
            if (directoryBookmarks.includes(slug)) {
                directoryBookmarks = directoryBookmarks.filter((item) => item !== slug);
                button.classList.remove('is-saved');
                button.setAttribute('aria-pressed', 'false');
            } else {
                directoryBookmarks = [...directoryBookmarks, slug];
                button.classList.add('is-saved');
                button.setAttribute('aria-pressed', 'true');
            }
            persistDirectoryBookmarks();
        });
    });

    const refreshMathVerification = async () => {
        try {
            const response = await fetch('/verification/refresh', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.enabled) {
                    if (data.driver === 'math') {
                        document.querySelectorAll('[data-math-question]').forEach(el => {
                            el.textContent = data.question;
                        });
                        document.querySelectorAll('.math-answer-input').forEach(el => {
                            el.value = '';
                        });
                        document.querySelectorAll('.math-verification-token').forEach(el => {
                            el.value = data.token || '';
                        });
                    }
                }
            }
        } catch (e) {
            console.error('Failed to refresh math verification:', e);
        }
    };

    document.addEventListener('click', (e) => {
        if (e.target && (e.target.closest('[data-math-refresh]') || e.target.matches('[data-math-refresh]'))) {
            e.preventDefault();
            refreshMathVerification();
        }
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            refreshMathVerification();
        }
    });

    asyncForms.forEach((form) => {
        const statusId = form.dataset.successTarget;
        const statusEl = statusId ? document.getElementById(statusId) : null;
        const isPackageForm = Boolean(form.closest('[data-package-modal]'));
        const isRoadmapForm = form.action.includes('/get-roadmap');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (statusEl) {
                statusEl.hidden = true;
                statusEl.classList.remove('is-error');
                statusEl.textContent = '';
            }
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) submitButton.disabled = true;

            if (isPackageForm) {
                showPackageFormModal('loading');
            } else if (isRoadmapForm) {
                showRoadmapFormModal('loading');
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });

                let payload = {};
                try {
                    payload = await response.json();
                } catch {
                    throw new Error('Something went wrong. Please try again.');
                }
                if (!response.ok) {
                    const firstError = payload.errors ? Object.values(payload.errors).flat()[0] : (payload.message || 'Something went wrong. Please try again.');
                    throw new Error(firstError || 'Something went wrong. Please try again.');
                }

                form.reset();
                if (isPackageForm) {
                    closePackageModal();
                    const defaultBookingThanks = 'Thank you. We have received your package request and will contact you within 24 hours.';
                    if (packageFormModalSuccessMessage) {
                        packageFormModalSuccessMessage.textContent = payload.message || defaultBookingThanks;
                    }
                    showPackageFormModal('success');
                } else if (isRoadmapForm) {
                    closeLeadModal();
                    if (roadmapFormModalSuccessMessage) {
                        roadmapFormModalSuccessMessage.textContent = payload.message || 'Check your email — we\'ve sent the download link for your free roadmap!';
                    }

                    // Dynamically set download and view links if present in response
                    const downloadBtn = document.getElementById('roadmapDownloadBtn');
                    const viewBtn = document.getElementById('roadmapViewBtn');
                    const actionsDiv = document.getElementById('roadmapFormModalActions');

                    if (payload.download_url && downloadBtn) {
                        downloadBtn.href = payload.download_url;
                        downloadBtn.style.display = 'inline-block';
                    } else if (downloadBtn) {
                        downloadBtn.style.display = 'none';
                    }

                    if (payload.view_url && viewBtn) {
                        viewBtn.href = payload.view_url;
                        viewBtn.style.display = 'inline-block';
                    } else if (viewBtn) {
                        viewBtn.style.display = 'none';
                    }

                    if (actionsDiv) {
                        actionsDiv.style.display = (payload.download_url || payload.view_url) ? 'flex' : 'none';
                    }

                    showRoadmapFormModal('success');
                } else if (statusEl) {
                    statusEl.textContent = payload.message || 'Thanks - we will be in touch within 24 hours.';
                    statusEl.hidden = false;
                }
                if (form.closest('[data-booking-modal]')) {
                    window.setTimeout(() => closeBookingModal(), 1200);
                }
                refreshMathVerification();
            } catch (error) {
                if (isPackageForm) {
                    closePackageModal();
                    showPackageFormModal('error', error.message || 'Something went wrong. Please try again.');
                } else if (isRoadmapForm) {
                    closeLeadModal();
                    showRoadmapFormModal('error', error.message || 'Something went wrong. Please try again.');
                } else if (statusEl) {
                    statusEl.textContent = error.message || 'Something went wrong. Please try again.';
                    statusEl.hidden = false;
                    statusEl.classList.add('is-error');
                }
                refreshMathVerification();
            } finally {
                if (submitButton) submitButton.disabled = false;
            }
        });
    });

    closePackageFormModalTriggers.forEach((trigger) => {
        trigger.addEventListener('click', closePackageFormModal);
    });

    if (backToTop) {
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    syncScrolledHeader();
    window.addEventListener('scroll', syncScrolledHeader, { passive: true });
    initScrollRevealAnimations();

    // Custom Pro Dropdown
    const initProDropdowns = () => {
        document.querySelectorAll('select.pro-select').forEach(select => {
            if (select.dataset.proSelectInitialized) return;
            select.dataset.proSelectInitialized = 'true';

            const wrapper = document.createElement('div');
            wrapper.className = 'pro-select-wrapper';
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);
            select.classList.add('pro-select-native');

            const display = document.createElement('div');
            display.className = 'pro-select-display';
            const selectedOption = select.options[select.selectedIndex];
            display.textContent = selectedOption ? selectedOption.text : 'Select option';
            if (selectedOption && !selectedOption.value) {
                display.style.color = '#999';
            } else {
                display.style.color = '#2c3a47';
            }
            wrapper.appendChild(display);

            const dropdown = document.createElement('div');
            dropdown.className = 'pro-select-dropdown';
            wrapper.appendChild(dropdown);

            Array.from(select.options).forEach(option => {
                const opt = document.createElement('div');
                opt.className = 'pro-select-option';
                opt.textContent = option.text;
                opt.dataset.value = option.value;
                if (option.selected) opt.classList.add('is-selected');
                dropdown.appendChild(opt);

                opt.addEventListener('mousedown', event => event.preventDefault());

                opt.addEventListener('click', function(event) {
                    event.stopPropagation();
                    select.value = this.dataset.value;
                    const selectedOpt = select.options[select.selectedIndex];
                    display.textContent = selectedOpt ? selectedOpt.text : this.textContent;
                    display.style.color = '#2c3a47';
                    dropdown.querySelectorAll('.pro-select-option').forEach(o => o.classList.remove('is-selected'));
                    this.classList.add('is-selected');
                    wrapper.classList.remove('is-open');
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            dropdown.addEventListener('click', event => event.stopPropagation());

            display.addEventListener('click', function(e) {
                e.stopPropagation();
                document.querySelectorAll('.pro-select-wrapper.is-open').forEach(w => w.classList.remove('is-open'));
                wrapper.classList.toggle('is-open');
            });
        });
    };

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.pro-select-wrapper')) {
            document.querySelectorAll('.pro-select-wrapper.is-open').forEach(w => w.classList.remove('is-open'));
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProDropdowns);
    } else {
        initProDropdowns();
    }
})();
