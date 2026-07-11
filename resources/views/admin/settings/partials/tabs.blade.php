<section class="admin-topbar" style="margin-bottom: 1.5rem; flex-direction: column; align-items: stretch; gap: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div>
            <p class="eyebrow">System Settings</p>
            <h2>System Control Panel</h2>
        </div>
        <a class="button button--small" href="/" target="_blank" rel="noreferrer">Open Site</a>
    </div>
    
    <div class="admin-quick-filters" style="margin-top: 0.5rem; border-bottom: 1px solid rgba(11, 122, 117, 0.08); padding-bottom: 0.75rem; width: 100%; display: flex; gap: 0.5rem; flex-wrap: wrap;">
        <a class="admin-quick-filter {{ request()->routeIs('admin.settings.edit') ? 'is-active' : '' }}" href="{{ route('admin.settings.edit') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;" aria-label="General Info" title="General Info">
            @include('admin.partials.icon', ['name' => 'sliders-horizontal', 'size' => 15])
            <span>General Info</span>
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.ai-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.ai-settings.api-connection') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;" aria-label="AI Configuration" title="AI Configuration">
            @include('admin.partials.icon', ['name' => 'brain', 'size' => 15])
            <span>AI Configuration</span>
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.email-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.email-settings.index') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;" aria-label="SMTP & Mail Themes" title="SMTP & Mail Themes">
            @include('admin.partials.icon', ['name' => 'mail', 'size' => 15])
            <span>SMTP & Mail Themes</span>
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.ebook-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.ebook-settings.index') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;" aria-label="Ebook Defaults" title="Ebook Defaults">
            @include('admin.partials.icon', ['name' => 'book', 'size' => 15])
            <span>Ebook Defaults</span>
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.social-settings.*') ? 'is-active' : '' }}" href="{{ route('admin.social-settings.index') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;" aria-label="Social Media" title="Social Media">
            @include('admin.partials.icon', ['name' => 'globe', 'size' => 15])
            <span>Social Media</span>
        </a>
        <a class="admin-quick-filter {{ request()->routeIs('admin.seo.*') ? 'is-active' : '' }}" href="{{ route('admin.seo.index') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; text-decoration: none;" aria-label="SEO Manager" title="SEO Manager">
            @include('admin.partials.icon', ['name' => 'search', 'size' => 15])
            <span>SEO Manager</span>
        </a>
    </div>
</section>
