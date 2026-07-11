<aside class="ai-settings-sidebar">
    <nav class="ai-sidebar-nav">
        <a href="{{ route('admin.ai-settings.api-connection') }}" class="ai-sidebar-link {{ $active === 'api-connection' ? 'is-active' : '' }}" aria-label="API Connection" title="API Connection">
            <span class="ai-sidebar-icon">
                @include('admin.partials.icon', ['name' => 'plug', 'size' => 18])
            </span>
            <span>API Connection</span>
        </a>
        <a href="{{ route('admin.ai-settings.chat-appearance') }}" class="ai-sidebar-link {{ $active === 'chat-appearance' ? 'is-active' : '' }}" aria-label="Chat Appearance" title="Chat Appearance">
            <span class="ai-sidebar-icon">
                @include('admin.partials.icon', ['name' => 'bot', 'size' => 18])
            </span>
            <span>Chat Appearance</span>
        </a>
        <a href="{{ route('admin.ai-settings.response-behavior') }}" class="ai-sidebar-link {{ $active === 'response-behavior' ? 'is-active' : '' }}" aria-label="Response Behavior" title="Response Behavior">
            <span class="ai-sidebar-icon">
                @include('admin.partials.icon', ['name' => 'settings', 'size' => 18])
            </span>
            <span>Response Behavior</span>
        </a>
        <a href="{{ route('admin.ai-settings.content-rules') }}" class="ai-sidebar-link {{ $active === 'content-rules' ? 'is-active' : '' }}" aria-label="Content Rules" title="Content Rules">
            <span class="ai-sidebar-icon">
                @include('admin.partials.icon', ['name' => 'scroll-text', 'size' => 18])
            </span>
            <span>Content Rules</span>
        </a>
        <a href="{{ route('admin.ai-settings.custom-prompts') }}" class="ai-sidebar-link {{ $active === 'custom-prompts' ? 'is-active' : '' }}" aria-label="Custom Prompts" title="Custom Prompts">
            <span class="ai-sidebar-icon">
                @include('admin.partials.icon', ['name' => 'pen-tool', 'size' => 18])
            </span>
            <span>Custom Prompts</span>
        </a>
        <a href="{{ route('admin.ai-settings.knowledge-base') }}" class="ai-sidebar-link {{ $active === 'knowledge-base' ? 'is-active' : '' }}" aria-label="Knowledge Base" title="Knowledge Base">
            <span class="ai-sidebar-icon">
                @include('admin.partials.icon', ['name' => 'brain', 'size' => 18])
            </span>
            <span>Knowledge Base</span>
        </a>
    </nav>
</aside>
