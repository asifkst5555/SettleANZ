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

    const popupSeenKey = 'settleanzLeadPopupSeen';
    const popupDismissedUntilKey = 'settleanzLeadPopupDismissedUntil';
    const popupSubmittedKey = 'settleanzLeadPopupSubmitted';

    const getVisibleBlogPosts = () => Array.from(blogPosts).filter((post) => !post.classList.contains('is-hidden-filter'));

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
        directoryListings.forEach((listing) => {
            const categoryMatch = category === 'all' || listing.dataset.category === category;
            const cityMatch = city === 'all cities' || listing.dataset.city === city;
            const termMatch = !term || listing.dataset.name.includes(term) || listing.dataset.category.includes(term) || listing.dataset.city.includes(term);
            listing.classList.toggle('is-hidden', !(categoryMatch && cityMatch && termMatch));
        });
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

    const initChatWidget = () => {
        const chatToggle = document.querySelector('[data-chat-toggle]');
        const chatPanel = document.querySelector('[data-chat-panel]');
        const chatClose = document.querySelector('[data-chat-close]');
        const chatReset = document.querySelector('[data-chat-reset]');
        const chatLog = document.querySelector('[data-chat-log]');
        const chatForm = document.querySelector('[data-chat-form]');
        const chatInput = document.querySelector('[data-chat-input]');

        if (!chatToggle || !chatPanel || !chatLog || !chatForm || !chatInput) return;

        const storageKey = 'settleanz_chat_conversation_id';
        const visitorKey = 'settleanz_chat_visitor_id';
        let conversationId = null;
        let loading = false;

        const getVisitorId = () => {
            const stored = localStorage.getItem(visitorKey);
            if (stored) return stored;
            const generated = 'visitor-' + Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 8);
            localStorage.setItem(visitorKey, generated);
            return generated;
        };

        const appendMessage = (type, text) => {
            const message = document.createElement('div');
            message.className = 'site-chat-msg ' + type;
            message.textContent = text;
            chatLog.appendChild(message);
            chatLog.scrollTop = chatLog.scrollHeight;
            return message;
        };

        const appendThinking = () => {
            const message = document.createElement('div');
            message.className = 'site-chat-msg bot thinking';
            message.innerHTML = '<span class="site-chat-thinking-label">SettleANZ AI is thinking</span><span class="site-chat-thinking-dots" aria-hidden="true"><span></span><span></span><span></span></span>';
            chatLog.appendChild(message);
            chatLog.scrollTop = chatLog.scrollHeight;
            return message;
        };

        const setLoading = (state) => {
            loading = state;
            chatInput.disabled = state;
            const submit = chatForm.querySelector('button[type="submit"]');
            if (submit) submit.disabled = state;
        };

        const appendGreeting = () => {
            if (chatLog.childElementCount) return;
            const greeting = chatPanel.dataset.chatGreeting || 'Hi, I am the SettleANZ AI Assistant. Ask me about migration, housing, banking, healthcare, newcomer checklists, or which guide to read next.';
            appendMessage('bot', greeting);
        };

        const createSession = async () => {
            const response = await fetch('/api/chat/session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    channel: 'website_widget',
                    visitor_id: getVisitorId(),
                    language: 'en',
                }),
            });

            if (!response.ok) throw new Error('Could not start chat session.');
            const data = await response.json();
            conversationId = String(data.conversation_id || '');
            localStorage.setItem(storageKey, conversationId);
        };

        const ensureSession = async () => {
            if (conversationId) return;

            const storedId = localStorage.getItem(storageKey);
            if (!storedId) {
                await createSession();
                return;
            }

            const historyResponse = await fetch('/api/chat/history/' + storedId, {
                headers: { 'Accept': 'application/json' },
            });

            if (!historyResponse.ok) {
                localStorage.removeItem(storageKey);
                await createSession();
                return;
            }

            conversationId = storedId;
            const history = await historyResponse.json();
            const messages = Array.isArray(history.messages) ? history.messages : [];
            if (!messages.length) return;

            chatLog.innerHTML = '';
            messages.forEach((item) => {
                appendMessage(item.role === 'user' ? 'user' : 'bot', item.content || '');
            });
        };

        const resetSession = async () => {
            const response = await fetch('/api/chat/reset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    channel: 'website_widget',
                    visitor_id: getVisitorId(),
                    language: 'en',
                    conversation_id: conversationId || null,
                }),
            });

            if (!response.ok) {
                localStorage.removeItem(storageKey);
                conversationId = null;
                await createSession();
            } else {
                const data = await response.json();
                conversationId = String(data.conversation_id || '');
                localStorage.setItem(storageKey, conversationId);
            }

            chatLog.innerHTML = '';
            appendGreeting();
        };

        const sendMessage = async (text) => {
            setLoading(true);
            const thinking = appendThinking();

            try {
                await ensureSession();
                const response = await fetch('/api/chat/message/' + conversationId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ content: text }),
                });

                if (!response.ok) {
                    appendMessage('system', 'Assistant is temporarily unavailable. Please try again.');
                    return;
                }

                const data = await response.json();
                appendMessage('bot', data.assistant?.content || 'Thanks. Our team will get back to you soon.');
            } catch (error) {
                appendMessage('system', 'Could not connect to the assistant right now.');
            } finally {
                if (thinking && thinking.parentNode) {
                    thinking.remove();
                }
                setLoading(false);
            }
        };

        chatToggle.addEventListener('click', async () => {
            const isOpen = chatPanel.classList.toggle('is-open');
            chatToggle.setAttribute('aria-expanded', String(isOpen));
            if (!isOpen) return;

            appendGreeting();
            try {
                await ensureSession();
            } catch (error) {
                appendMessage('system', 'Could not connect to the assistant right now.');
            }
            chatInput.focus();
        });

        if (chatClose) {
            chatClose.addEventListener('click', () => {
                chatPanel.classList.remove('is-open');
                chatToggle.setAttribute('aria-expanded', 'false');
            });
        }

        if (chatReset) {
            chatReset.addEventListener('click', () => {
                resetSession().catch(() => {
                    appendMessage('system', 'Could not reset the chat right now.');
                });
            });
        }

        chatForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (loading) return;

            const text = chatInput.value.trim();
            if (!text) return;

            appendMessage('user', text);
            chatInput.value = '';
            await sendMessage(text);
        });
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

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeLeadModal(true);
            closeBookingModal();
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
                    statusEl.textContent = payload.message || 'Thanks — we\'ll be in touch within 24 hours.';
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

    initChatWidget();

    if (backToTop) {
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    syncScrolledHeader();
    window.addEventListener('scroll', syncScrolledHeader, { passive: true });
})();


