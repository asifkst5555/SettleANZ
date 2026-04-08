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
        message.textContent = content;
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

    const loadChatHistory = async () => {
        if (!chatLog) return;

        if (!chatConversationId) {
            renderChatGreeting();
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
            renderChatGreeting();
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

        try {
            await createChatSession(true);
        } catch (error) {
            window.localStorage.removeItem(chatConversationKey);
            chatConversationId = '';
        }

        chatHasLoaded = true;
        renderChatGreeting();
    };

    const submitChatMessage = async () => {
        if (!chatInput || chatIsSubmitting) return;

        const content = chatInput.value.trim();
        if (!content) return;

        appendChatMessage('user', content);
        chatInput.value = '';
        appendThinkingMessage();
        setChatBusy(true);

        try {
            if (!chatConversationId) {
                await createChatSession();
            }

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

    if (menuToggle && menu) {
        menuToggle.addEventListener('click', () => {
            const isOpen = menu.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                menu.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            });
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
            closeLeadModal(true);
            closeBookingModal();
            closeChatPanel();
        }
    });

    if (shouldAutoOpenPopup()) {
        window.setTimeout(() => {
            if (shouldAutoOpenPopup()) openLeadModal();
        }, 15000);
        document.addEventListener('mouseout', (event) => {
            if (event.clientY <= 0 && shouldAutoOpenPopup()) openLeadModal();
        }, { once: true });
    }

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

    asyncForms.forEach((form) => {
        const statusId = form.dataset.successTarget;
        const statusEl = statusId ? document.getElementById(statusId) : null;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (statusEl) {
                statusEl.hidden = true;
                statusEl.classList.remove('is-error');
                statusEl.textContent = '';
            }
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) submitButton.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });

                const payload = await response.json();
                if (!response.ok) {
                    const firstError = payload.errors ? Object.values(payload.errors).flat()[0] : 'Something went wrong. Please try again.';
                    throw new Error(firstError || 'Something went wrong. Please try again.');
                }

                form.reset();
                if (statusEl) {
                    statusEl.textContent = payload.message || 'Thanks - we will be in touch within 24 hours.';
                    statusEl.hidden = false;
                }
                if (form.closest('[data-booking-modal]')) {
                    window.setTimeout(() => closeBookingModal(), 1200);
                }
            } catch (error) {
                if (statusEl) {
                    statusEl.textContent = error.message || 'Something went wrong. Please try again.';
                    statusEl.hidden = false;
                    statusEl.classList.add('is-error');
                }
            } finally {
                if (submitButton) submitButton.disabled = false;
            }
        });
    });

    if (backToTop) {
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    syncScrolledHeader();
    window.addEventListener('scroll', syncScrolledHeader, { passive: true });
})();
