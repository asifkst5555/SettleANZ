<aside class="ai-settings-sidebar">
    <nav class="ai-sidebar-nav">
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
