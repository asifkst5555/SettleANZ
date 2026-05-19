<aside class="ai-settings-sidebar" style="background:#fff;border:1px solid rgba(16,88,98,0.12);border-radius:12px;padding:0.5rem;height:fit-content;position:sticky;top:100px;">
    <nav style="display:flex;flex-direction:column;gap:0.25rem;">
        <a href="{{ route('admin.ai-settings.api-connection') }}" class="ai-sidebar-link {{ $active === 'api-connection' ? 'is-active' : '' }}">
            <span class="ai-sidebar-icon">🔌</span>
            <span>API Connection</span>
        </a>
        <a href="{{ route('admin.ai-settings.chat-appearance') }}" class="ai-sidebar-link {{ $active === 'chat-appearance' ? 'is-active' : '' }}">
            <span class="ai-sidebar-icon">💬</span>
            <span>Chat Appearance</span>
        </a>
        <a href="{{ route('admin.ai-settings.response-behavior') }}" class="ai-sidebar-link {{ $active === 'response-behavior' ? 'is-active' : '' }}">
            <span class="ai-sidebar-icon">⚙️</span>
            <span>Response Behavior</span>
        </a>
        <a href="{{ route('admin.ai-settings.content-rules') }}" class="ai-sidebar-link {{ $active === 'content-rules' ? 'is-active' : '' }}">
            <span class="ai-sidebar-icon">📋</span>
            <span>Content Rules</span>
        </a>
        <a href="{{ route('admin.ai-settings.custom-prompts') }}" class="ai-sidebar-link {{ $active === 'custom-prompts' ? 'is-active' : '' }}">
            <span class="ai-sidebar-icon">✏️</span>
            <span>Custom Prompts</span>
        </a>
        <a href="{{ route('admin.ai-settings.knowledge-base') }}" class="ai-sidebar-link {{ $active === 'knowledge-base' ? 'is-active' : '' }}">
            <span class="ai-sidebar-icon">📚</span>
            <span>Knowledge Base</span>
        </a>
    </nav>
</aside>

<style>
    .ai-sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        color: #2c3a47;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .ai-sidebar-link:hover {
        background: #f0f7f6;
        color: #0b7a75;
    }
    .ai-sidebar-link.is-active {
        background: #0b7a75;
        color: #fff;
        font-weight: 600;
    }
    .ai-sidebar-icon {
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
    }
</style>
