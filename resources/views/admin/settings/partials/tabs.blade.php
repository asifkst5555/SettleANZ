<section class="admin-topbar" style="margin-bottom: 1.5rem; flex-direction: column; align-items: stretch; gap: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <p class="eyebrow">System Settings</p>
            <h2>System Control Panel</h2>
        </div>
        <a class="button button--small" href="/" target="_blank" rel="noreferrer">Open Site</a>
    </div>
    
    <div class="admin-quick-filters" style="margin-top: 0.5rem; border-bottom: 1px solid rgba(11, 122, 117, 0.08); padding-bottom: 0.75rem; width: 100%; display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a class="admin-quick-filter {{ request()->routeIs('admin.settings.edit') ? 'is-active' : '' }}" href="{{ route('admin.settings.edit') }}">
            🏢 General Info
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.ai-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.ai-settings.api-connection') }}">
            🤖 AI Configuration
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.email-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.email-settings.index') }}">
            ✉️ SMTP & Mail Themes
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.ebook-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.ebook-settings.index') }}">
            📚 Ebook Defaults
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.social-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.social-settings.index') }}">
            🌐 Social Media
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.seo.*') ? 'is-active' : '' }}" href="{{ route('admin.seo.index') }}">
            🔍 SEO Manager
        </a>
    </div>
</section>
