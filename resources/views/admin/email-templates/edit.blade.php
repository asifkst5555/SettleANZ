@extends('admin.layouts.app')

@section('page-title', 'Edit Email Template')

@section('content')
<style>
    /* Override parent padding so email builder can fit the screen perfectly */
    .admin-main {
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
    }
    .admin-topbar-saas {
        position: static !important;
        width: 100%;
        box-sizing: border-box;
    }
    /* Premium visual email builder styles */
    .builder-container {
        display: flex;
        flex: 1;
        margin: 0;
        background: #f8fafc;
        font-family: 'Inter', sans-serif;
        overflow: hidden;
    }
    .builder-sidebar {
        width: 440px;
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        z-index: 10;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.02);
    }
    .builder-sidebar-tabs {
        display: flex;
        border-bottom: 1px solid #edf2f7;
        background: #f8fafc;
    }
    .builder-tab-btn {
        flex: 1;
        padding: 0.75rem 0.5rem;
        border: none;
        background: none;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        text-align: center;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    .builder-tab-btn.active {
        color: #0b7a75;
        border-bottom-color: #0b7a75;
        background: #ffffff;
    }
    .builder-sidebar-content {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem;
    }
    .builder-tab-pane {
        display: none;
    }
    .builder-tab-pane.active {
        display: block;
    }
    
    /* Grid layout for component block selection */
    .component-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    .component-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }
    .component-card:hover {
        border-color: #0b7a75;
        background: #f0fdfa;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .component-card svg {
        color: #0b7a75;
    }
    .component-card span {
        font-size: 0.8125rem;
        font-weight: 500;
        color: #334155;
    }
    
    /* Interactive preview workspace */
    .builder-workspace {
        flex: 1;
        background: #eef2f6;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }
    .builder-workspace-header {
        height: 52px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
    }
    .preview-toggle-btn {
        padding: 0.4rem 0.8rem;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .preview-toggle-btn.active {
        background: #0b7a75;
        color: #ffffff;
        border-color: #0b7a75;
    }
    
    .builder-preview-container {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1.5rem;
        overflow: auto;
    }
    .preview-frame-wrapper {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        max-height: 800px;
    }
    .preview-iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: #ffffff;
    }
    
    /* Meta Form Settings */
    .form-group-builder {
        margin-bottom: 1rem;
    }
    .form-group-builder label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.35rem;
    }
    .form-group-builder input, .form-group-builder select, .form-group-builder textarea {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .form-group-builder input:focus, .form-group-builder select:focus, .form-group-builder textarea:focus {
        border-color: #0b7a75;
        outline: none;
        box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.15);
    }
    .dropdown-var-wrapper {
        position: relative;
    }
    .dropdown-var-btn {
        margin-top: 0.25rem;
        font-size: 0.75rem;
        background: #e2e8f0;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        cursor: pointer;
        color: #475569;
        font-weight: 500;
    }
    
    /* Sorting blocks stack list */
    .blocks-stack-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.65rem 0.85rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8125rem;
        font-weight: 500;
        color: #334155;
        transition: all 0.2s;
    }
    .blocks-stack-item:hover, .blocks-stack-item.selected {
        border-color: #0b7a75;
        background: #f0fdfa;
    }
    
    /* Revision lists styling */
    .rev-list-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 0.75rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .rev-list-item:last-child { border-bottom: none; }
    
    /* Modal windows */
    .builder-modal {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }
    .builder-modal-content {
        background: #ffffff;
        border-radius: 12px;
        padding: 1.5rem;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }
    .builder-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        border-bottom: 1px solid #edf2f7;
        padding-bottom: 0.5rem;
    }
    .builder-modal-header h3 { margin: 0; font-size: 1.125rem; color: #1e293b; }
</style>

<div class="builder-container">
    <div class="builder-sidebar">
        <!-- Tab controls -->
        <div class="builder-sidebar-tabs">
            <button class="builder-tab-btn active" onclick="switchTab('meta')">Details</button>
            <button class="builder-tab-btn" onclick="switchTab('blocks')">Blocks</button>
            <button class="builder-tab-btn" onclick="switchTab('properties')">Properties</button>
            <button class="builder-tab-btn" onclick="switchTab('revisions')">Revisions</button>
        </div>

        <form id="templateSaveForm" action="{{ route('admin.email-templates.update', $template) }}" method="POST" style="display:flex; flex-direction:column; height:calc(100% - 40px); margin:0;">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="builder_json" id="builder_json_input">
            <input type="hidden" name="body_html" id="body_html_input">
            
            <div class="builder-sidebar-content">
                <!-- Meta Pane -->
                <div id="pane-meta" class="builder-tab-pane active">
                    <h3 style="font-size:0.9375rem; margin-top:0; color:#1e293b;">Template Settings</h3>
                    <div class="form-group-builder">
                        <label>Template Name *</label>
                        <input type="text" name="name" id="template_name" value="{{ old('name', $template->name) }}" required>
                    </div>
                    <div class="form-group-builder">
                        <label>Type *</label>
                        <select name="type" id="template_type" required>
                            @foreach($types as $t)
                            <option value="{{ $t }}" @selected(old('type', $template->type) === $t)>{{ $typeLabels[$t] ?? ucfirst(str_replace('_', ' ', $t)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-builder">
                        <label>Email Subject *</label>
                        <div class="dropdown-var-wrapper">
                            <input type="text" name="subject" id="template_subject" value="{{ old('subject', $template->subject) }}" required>
                            <button type="button" class="dropdown-var-btn" onclick="toggleVarDropdown('template_subject')">+ Insert Variable</button>
                            <div class="var-dropdown" id="var-template_subject" style="display:none; position:absolute; background:#fff; border:1px solid #ccc; border-radius:4px; box-shadow:0 2px 5px rgba(0,0,0,0.1); z-index:100; max-height:150px; overflow-y:auto; width:200px;">
                                @foreach($availableVariables as $var)
                                <div style="padding:0.35rem 0.5rem; font-size:0.75rem; cursor:pointer;" onclick="insertVarAtCursor('template_subject', '{{ $var }}')"><code>@{{ {{ $var }} }}</code></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group-builder">
                        <label>Preheader Text</label>
                        <input type="text" name="preheader" id="template_preheader" placeholder="Brief text that displays in email inbox preview..." oninput="updatePreheader(this.value)">
                    </div>
                    <div class="form-group-builder" style="display:flex; align-items:center; gap:0.5rem;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="template_is_active" @checked(old('is_active', $template->is_active)) style="width:1.25rem; height:1.25rem; accent-color:#0b7a75;">
                        <label for="template_is_active" style="margin:0;">Active (Ready for sending)</label>
                    </div>
                </div>

                <!-- Blocks Pane -->
                <div id="pane-blocks" class="builder-tab-pane">
                    <h3 style="font-size:0.9375rem; margin-top:0; color:#1e293b;">Click to Add Block</h3>
                    <div class="component-grid">
                        <div class="component-card" onclick="addBlock('logo')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span>Logo (PNG)</span>
                        </div>
                        <div class="component-card" onclick="addBlock('heading')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12h16M4 18V6m16 12V6"/></svg>
                            <span>Heading</span>
                        </div>
                        <div class="component-card" onclick="addBlock('text')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7V4h16v3M9 20h6M12 4v16"/></svg>
                            <span>Text</span>
                        </div>
                        <div class="component-card" onclick="addBlock('image')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="9" cy="9" r="2" /><path d="M21 15l-3.086-3.086a2 2 0 00-2.828 0L6 21" /></svg>
                            <span>Image</span>
                        </div>
                        <div class="component-card" onclick="addBlock('button')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="M7 12h10" /></svg>
                            <span>Button</span>
                        </div>
                        <div class="component-card" onclick="addBlock('divider')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12" /></svg>
                            <span>Divider</span>
                        </div>
                        <div class="component-card" onclick="addBlock('spacer')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 14V4h16v10M12 4v10" /></svg>
                            <span>Spacer</span>
                        </div>
                        <div class="component-card" onclick="addBlock('social_icons')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            <span>Social Link</span>
                        </div>
                        <div class="component-card" onclick="addBlock('signature')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            <span>Signature</span>
                        </div>
                        <div class="component-card" onclick="addBlock('banner')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                            <span>Banner</span>
                        </div>
                        <div class="component-card" onclick="addBlock('quote')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21c3 0 7-9 7-12H3V3h9v9c0 5-3 9-9 9zm11 0c3 0 7-9 7-12h-7V3h9v9c0 5-3 9-9 9z"/></svg>
                            <span>Quote</span>
                        </div>
                        <div class="component-card" onclick="addBlock('columns_2')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><rect x="14" y="3" width="7" height="18" rx="1"/></svg>
                            <span>2 Columns</span>
                        </div>
                        <div class="component-card" onclick="addBlock('columns_3')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="5" height="18" rx="1"/><rect x="9.5" y="3" width="5" height="18" rx="1"/><rect x="17" y="3" width="5" height="18" rx="1"/></svg>
                            <span>3 Columns</span>
                        </div>
                        <div class="component-card" onclick="addBlock('footer')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="16" width="18" height="5" rx="1"/><path d="M3 16V4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v12"/></svg>
                            <span>Footer Block</span>
                        </div>
                    </div>
                    
                    <h3 style="font-size:0.9375rem; margin-top:1.5rem; color:#1e293b; border-top:1px solid #edf2f7; padding-top:1rem;">Manage Blocks Stack</h3>
                    <div id="blocks-list-stack">
                        <!-- Javascript populated -->
                    </div>
                </div>

                <!-- Block Properties Settings Pane -->
                <div id="pane-properties" class="builder-tab-pane">
                    <div id="theme-global-settings">
                        <h3 style="font-size:0.9375rem; margin-top:0; color:#1e293b;">Global Theme Settings</h3>
                        <p style="font-size:0.75rem; color:#64748b; margin-top:-0.5rem; margin-bottom:1rem;">Apply template-level overrides to standard configurations.</p>
                        <div class="form-group-builder">
                            <label>Primary Accent Color</label>
                            <input type="color" id="theme_primaryColor" onchange="updateThemeProp('primaryColor', this.value)">
                        </div>
                        <div class="form-group-builder">
                            <label>Secondary Accent Color</label>
                            <input type="color" id="theme_secondaryColor" onchange="updateThemeProp('secondaryColor', this.value)">
                        </div>
                        <div class="form-group-builder">
                            <label>Outer Background Color</label>
                            <input type="color" id="theme_backgroundColor" onchange="updateThemeProp('backgroundColor', this.value)">
                        </div>
                        <div class="form-group-builder">
                            <label>Default Text Color</label>
                            <input type="color" id="theme_textColor" onchange="updateThemeProp('textColor', this.value)">
                        </div>
                        <div class="form-group-builder">
                            <label>Button Border Radius (px)</label>
                            <input type="text" id="theme_buttonRadius" oninput="updateThemeProp('buttonRadius', this.value)">
                        </div>
                    </div>
                    <div id="block-properties-editor" style="display:none;">
                        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #edf2f7; padding-bottom:0.5rem; margin-bottom:1rem;">
                            <h3 style="font-size:0.9375rem; margin:0; color:#1e293b;" id="editing-block-title">Edit Block</h3>
                            <button type="button" class="button button--small button--ghost" onclick="deselectBlock()" style="padding:0.25rem 0.5rem; font-size:0.75rem;">Done</button>
                        </div>
                        <div id="properties-fields-container">
                            <!-- Dynamic settings widgets -->
                        </div>
                        <div style="display:flex; gap:0.5rem; margin-top:1.5rem; border-top:1px solid #edf2f7; padding-top:1rem;">
                            <button type="button" style="background:#fee2e2; color:#b91c1c; flex:1; border:none; padding:0.5rem; border-radius:6px; font-weight:600; cursor:pointer;" onclick="deleteActiveBlock()">Delete Block</button>
                        </div>
                    </div>
                </div>

                <!-- Revisions Pane -->
                <div id="pane-revisions" class="builder-tab-pane">
                    <h3 style="font-size:0.9375rem; margin-top:0; color:#1e293b;">Version History</h3>
                    <p style="font-size:0.75rem; color:#64748b; margin-top:-0.5rem; margin-bottom:1rem;">Restoring an older draft will automatically save a backup of the current state.</p>
                    
                    @forelse($revisions as $rev)
                    <div class="rev-list-item">
                        <div>
                            <strong style="font-size:0.8125rem; display:block; color:#334155;">{{ $rev->name }}</strong>
                            <span style="font-size:0.75rem; color:#64748b;">{{ $rev->created_at->format('M d, H:i') }} &middot; by {{ $rev->creator?->name ?? 'System' }}</span>
                        </div>
                        <button type="button" class="button button--small button--ghost" onclick="confirmRestore('{{ route('admin.email-templates.restore-revision', [$template, $rev]) }}')" style="padding:0.25rem 0.5rem; font-size:0.75rem;">Restore</button>
                    </div>
                    @empty
                    <p style="font-size:0.8125rem; color:#94a3b8; text-align:center; padding:1.5rem 0;">No revisions saved yet. Revisions are created every time you click update.</p>
                    @endforelse
                </div>
            </div>
            
            <div style="padding:1.25rem; border-top:1px solid #e2e8f0; display:flex; gap:0.75rem; background:#ffffff;">
                <button type="button" class="button" style="background:#0b7a75; color:white; flex:1; font-weight:600;" onclick="submitTemplateForm()">Update Template</button>
                <a href="{{ route('admin.email-templates.index') }}" class="button button--ghost" style="padding:0.5rem 1rem;">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Live Preview Workspace -->
    <div class="builder-workspace">
        <div class="builder-workspace-header">
            <div style="display:flex; gap:0.5rem;">
                <button class="preview-toggle-btn active" id="btn-vp-desktop" onclick="setViewport('desktop')">Desktop</button>
                <button class="preview-toggle-btn" id="btn-vp-mobile" onclick="setViewport('mobile')">Mobile</button>
            </div>
            
            <div style="display:flex; gap:0.5rem;">
                <button type="button" class="preview-toggle-btn" onclick="openTestModal()">Send Test</button>
                <button type="button" class="preview-toggle-btn" onclick="openHtmlModal()">View HTML</button>
                <button type="button" class="preview-toggle-btn" onclick="downloadHTML()">Download</button>
            </div>
        </div>

        <div class="builder-preview-container">
            <div class="preview-frame-wrapper" id="frame-wrapper" style="width: 600px;">
                <iframe id="preview-iframe" class="preview-iframe"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialogs -->
<div class="builder-modal" id="test-email-modal">
    <div class="builder-modal-content">
        <div class="builder-modal-header">
            <h3>Send Test Email</h3>
            <button type="button" onclick="closeTestModal()" style="background:none; border:none; font-size:1.25rem; cursor:pointer;">&times;</button>
        </div>
        <div class="form-group-builder">
            <label>Recipient Email Address</label>
            <input type="email" id="test_recipient_email" placeholder="example@business.com" required>
        </div>
        <div style="display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1.5rem;">
            <button type="button" class="button button--ghost" onclick="closeTestModal()">Cancel</button>
            <button type="button" class="button" style="background:#0b7a75; color:white; font-weight:600;" onclick="dispatchTestEmail()">Send Test</button>
        </div>
    </div>
</div>

<div class="builder-modal" id="view-html-modal">
    <div class="builder-modal-content" style="max-width:800px; width:90%;">
        <div class="builder-modal-header">
            <h3>Compiled Responsive HTML</h3>
            <button type="button" onclick="closeHtmlModal()" style="background:none; border:none; font-size:1.25rem; cursor:pointer;">&times;</button>
        </div>
        <textarea id="compiled-html-area" rows="20" style="font-family:monospace; font-size:0.75rem; width:100%; box-sizing:border-box; border:1px solid #cbd5e1; border-radius:6px; padding:0.5rem;" readonly></textarea>
        <div style="display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1.25rem;">
            <button type="button" class="button" style="background:#0b7a75; color:white; font-weight:600;" onclick="copyCompiledHTML()">Copy Code</button>
            <button type="button" class="button button--ghost" onclick="closeHtmlModal()">Close</button>
        </div>
    </div>
</div>

<script>
    // Preview variables for rendering template placeholders on the fly
    const previewVariables = {
        'company_logo': "{{ asset('media/logo/email_logo.png') }}",
        'company_name': "SettleANZ",
        'lead_name': "John Doe",
        'ebook_title': "New Arrival Checklist",
        'download_url': "#",
        'expires_at': "July 25, 2026",
        'expires_in_hours': "48",
        'support_email': "support@settleanz.com",
        'current_year': new Date().getFullYear(),
        'unsubscribe': "#",
        'name': "John Doe",
        'ebook_name': "New Arrival Checklist",
        'website': "{{ url('/') }}",
        'view_url': "#",
        'form_type': "contact-page",
        'enquiry_type': "Contact enquiry",
        'response_time': "24 hours",
        'ebook_description': "A practical guide for your first steps after arrival.",
        'days_since_download': "3",
        'download_count': "1",
        'email': "john@example.com"
    };

    // Initial configuration state
    const defaultTheme = {
        primaryColor: '#065e5b',
        secondaryColor: '#e8773a',
        backgroundColor: '#f5f0e8',
        textColor: '#2c3a47',
        buttonRadius: '8px',
        defaultFont: "Arial, 'Helvetica Neue', Helvetica, sans-serif"
    };

    // Load state
    let templateState = @json($builderJson);
    if (!templateState || !templateState.blocks) {
        templateState = {
            settings: {
                preheader: '',
                theme: { ...defaultTheme }
            },
            blocks: []
        };
    }
    // Make sure theme holds required properties
    templateState.settings = templateState.settings || {};
    templateState.settings.theme = { ...defaultTheme, ...(templateState.settings.theme || {}) };
    templateState.blocks = templateState.blocks || [];
    templateState.blocks.forEach(b => {
        if (!b.id) {
            b.id = 'block_' + Math.random().toString(36).substr(2, 9);
        }
    });

    let activeBlockId = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize values inside DOM inputs
        document.getElementById('template_preheader').value = templateState.settings.preheader || '';
        
        // Initialize Theme Pickers
        document.getElementById('theme_primaryColor').value = templateState.settings.theme.primaryColor;
        document.getElementById('theme_secondaryColor').value = templateState.settings.theme.secondaryColor;
        document.getElementById('theme_backgroundColor').value = templateState.settings.theme.backgroundColor;
        document.getElementById('theme_textColor').value = templateState.settings.theme.textColor;
        document.getElementById('theme_buttonRadius').value = templateState.settings.theme.buttonRadius;

        // Render preview iframe & manage stack list
        renderPreview();
        renderBlocksStackList();
        
        // Close variables dropdown clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-var-wrapper')) {
                document.querySelectorAll('.var-dropdown').forEach(d => d.style.display = 'none');
            }
        });
    });

    function switchTab(tabId) {
        document.querySelectorAll('.builder-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.builder-tab-pane').forEach(pane => pane.classList.remove('active'));
        
        // Target buttons and tabs
        event.target.classList.add('active');
        document.getElementById('pane-' + tabId).classList.add('active');
    }

    function setViewport(size) {
        document.querySelectorAll('.preview-toggle-btn').forEach(btn => btn.classList.remove('active'));
        const wrapper = document.getElementById('frame-wrapper');
        
        if (size === 'mobile') {
            document.getElementById('btn-vp-mobile').classList.add('active');
            wrapper.style.width = '375px';
        } else {
            document.getElementById('btn-vp-desktop').classList.add('active');
            wrapper.style.width = '600px';
        }
    }

    // Dynamic Variables Insertion
    function toggleVarDropdown(inputId) {
        const drop = document.getElementById('var-' + inputId);
        drop.style.display = drop.style.display === 'none' ? 'block' : 'none';
    }

    function insertVarAtCursor(inputId, variable) {
        const input = document.getElementById(inputId);
        const textToInsert = '{{ ' + variable + ' }}';
        
        if (input.selectionStart || input.selectionStart === 0) {
            const start = input.selectionStart;
            const end = input.selectionEnd;
            input.value = input.value.substring(0, start) + textToInsert + input.value.substring(end, input.value.length);
            input.selectionStart = start + textToInsert.length;
            input.selectionEnd = start + textToInsert.length;
        } else {
            input.value += textToInsert;
        }
        input.focus();
        document.getElementById('var-' + inputId).style.display = 'none';
        
        // Trigger live preview reload if modifying preview properties
        if (inputId.startsWith('prop-')) {
            const propName = inputId.replace('prop-', '');
            updateBlockProperty(propName, input.value);
        }
    }

    // Add block functionality
    function addBlock(type) {
        const id = 'block_' + Math.random().toString(36).substr(2, 9);
        let properties = {};

        switch (type) {
            case 'logo':
                properties = { alignment: 'center', width: '150', paddingTop: '20', paddingBottom: '15' };
                break;
            case 'heading':
                properties = { text: 'New Heading', fontSize: '24px', color: templateState.settings.theme.primaryColor, fontWeight: 'bold', alignment: 'center', paddingTop: '15', paddingBottom: '15', margin: '0' };
                break;
            case 'text':
                properties = { text: 'Paragraph content goes here. Highlight details, mention variables like @{{name}} or links.', fontSize: '16px', color: templateState.settings.theme.textColor, fontWeight: 'normal', alignment: 'left', lineHeight: '1.6', paddingTop: '10', paddingBottom: '10' };
                break;
            case 'image':
                properties = { src: '', width: '560', alt: 'Relocation assistance image', alignment: 'center', borderRadius: '8', link: '', padding: '10' };
                break;
            case 'button':
                properties = { text: 'Take Action Now', url: '#', background: templateState.settings.theme.secondaryColor, radius: templateState.settings.theme.buttonRadius, fontColor: '#ffffff', alignment: 'center', fontSize: '16px', padding: '15' };
                break;
            case 'divider':
                properties = { height: '1', color: '#e6f4f3', margin: '20' };
                break;
            case 'spacer':
                properties = { height: '20' };
                break;
            case 'columns_2':
                properties = { background: '#ffffff', padding: '15', gap: '20', col1_blocks: [], col2_blocks: [] };
                break;
            case 'columns_3':
                properties = { background: '#ffffff', padding: '15', gap: '10', col1_blocks: [], col2_blocks: [], col3_blocks: [] };
                break;
            case 'social_icons':
                properties = { alignment: 'center', size: '32', padding: '15', facebook_enabled: true, instagram_enabled: true, linkedin_enabled: true, pinterest_enabled: true, youtube_enabled: true };
                break;
            case 'footer':
                properties = { background: '#f5f0e8', color: '#607080', padding: '25', alignment: 'center' };
                break;
            case 'signature':
                properties = { name: 'SettleANZ Support', title: 'Relocation Assistant', company: '', image: '', alignment: 'left', padding: '15' };
                break;
            case 'banner':
                properties = { backgroundImage: '', title: 'Visual Banner Title', subtitle: 'Detailed subtitle descriptions', buttonText: '', buttonUrl: '#', height: '220', overlayOpacity: '0.4', color: '#ffffff', alignment: 'center' };
                break;
            case 'quote':
                properties = { text: 'Outstanding assistance with local bank accounts and visa guidelines!', author: 'Happy Settler', borderColor: templateState.settings.theme.primaryColor, background: '#f0fbfb', color: templateState.settings.theme.textColor, padding: '20' };
                break;
        }

        templateState.blocks.push({ id, type, properties });
        renderBlocksStackList();
        renderPreview();
        
        // Select the newly added block
        selectBlock(id);
    }

    // Render preview document inside iframe
    function renderPreview() {
        const iframe = document.getElementById('preview-iframe');
        let docHtml = compileTemplateHtml();
        
        // Replace variables with preview values for rendering
        Object.keys(previewVariables).forEach(key => {
            const regex = new RegExp('\\{\\{\\s*' + key + '\\s*\\}\\}', 'g');
            docHtml = docHtml.replace(regex, previewVariables[key]);
        });
        
        iframe.srcdoc = docHtml;

        // Attach click triggers once iframe loads
        iframe.onload = function() {
            const innerDoc = iframe.contentDocument || iframe.contentWindow.document;
            
            // Add click events on all rendering tables representing blocks
            templateState.blocks.forEach(b => {
                const el = innerDoc.querySelector(`[data-preview-block-id="${b.id}"]`);
                if (el) {
                    el.style.cursor = 'pointer';
                    el.style.position = 'relative';
                    el.addEventListener('click', function(e) {
                        e.stopPropagation();
                        selectBlock(b.id);
                    });
                    
                    // Draw highlight overlay on hover
                    el.addEventListener('mouseenter', function() {
                        if (activeBlockId !== b.id) {
                            el.style.outline = '2px dashed #0b7a75';
                            el.style.outlineOffset = '-2px';
                        }
                    });
                    el.addEventListener('mouseleave', function() {
                        if (activeBlockId !== b.id) {
                            el.style.outline = 'none';
                        }
                    });
                }
            });
        };
    }

    function selectBlock(id) {
        activeBlockId = id;
        
        // Highlight active block item in sidebar list
        document.querySelectorAll('.blocks-stack-item').forEach(item => {
            item.classList.remove('selected');
        });
        const activeStackItem = document.getElementById('stack-item-' + id);
        if (activeStackItem) {
            activeStackItem.classList.add('selected');
        }

        // Open properties tab panel
        document.getElementById('theme-global-settings').style.display = 'none';
        const editor = document.getElementById('block-properties-editor');
        editor.style.display = 'block';
        
        switchTab('properties');

        const block = templateState.blocks.find(b => b.id === id);
        document.getElementById('editing-block-title').textContent = 'Edit: ' + capitalize(block.type);

        // Render input controls dynamically based on properties
        renderPropertyFields(block);

        // Highlight selection border inside iframe
        const iframe = document.getElementById('preview-iframe');
        const innerDoc = iframe.contentDocument || iframe.contentWindow.document;
        templateState.blocks.forEach(b => {
            const el = innerDoc.querySelector(`[data-preview-block-id="${b.id}"]`);
            if (el) {
                if (b.id === id) {
                    el.style.outline = '2px solid #0b7a75';
                    el.style.outlineOffset = '-2px';
                } else {
                    el.style.outline = 'none';
                }
            }
        });
    }

    function deselectBlock() {
        activeBlockId = null;
        document.getElementById('block-properties-editor').style.display = 'none';
        document.getElementById('theme-global-settings').style.display = 'block';
        
        // Clean highlights in iframe
        const iframe = document.getElementById('preview-iframe');
        const innerDoc = iframe.contentDocument || iframe.contentWindow.document;
        if (innerDoc) {
            templateState.blocks.forEach(b => {
                const el = innerDoc.querySelector(`[data-preview-block-id="${b.id}"]`);
                if (el) el.style.outline = 'none';
            });
        }
        
        document.querySelectorAll('.blocks-stack-item').forEach(item => item.classList.remove('selected'));
        switchTab('blocks');
    }

    function renderPropertyFields(block) {
        const container = document.getElementById('properties-fields-container');
        container.innerHTML = ''; // reset

        for (const [key, value] of Object.entries(block.properties)) {
            // Do not show sub-blocks arrays inside regular properties editor fields
            if (key === 'col1_blocks' || key === 'col2_blocks' || key === 'col3_blocks') {
                continue;
            }

            const fieldDiv = document.createElement('div');
            fieldDiv.className = 'form-group-builder';

            const label = document.createElement('label');
            label.textContent = formatPropertyLabel(key);
            fieldDiv.appendChild(label);

            if (typeof value === 'boolean') {
                // Checkbox input
                const wrap = document.createElement('div');
                wrap.style.display = 'flex';
                wrap.style.alignItems = 'center';
                wrap.style.gap = '0.5rem';
                
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.checked = value;
                input.id = 'prop-' + key;
                input.style.width = '1.2rem';
                input.style.height = '1.2rem';
                input.onchange = function() {
                    updateBlockProperty(key, this.checked);
                };
                
                wrap.appendChild(input);
                const span = document.createElement('span');
                span.textContent = 'Enabled';
                span.style.fontSize = '0.8rem';
                wrap.appendChild(span);
                fieldDiv.appendChild(wrap);
            } else if (key.toLowerCase().includes('color') || key === 'background') {
                // Color Picker
                const wrap = document.createElement('div');
                wrap.style.display = 'flex';
                wrap.style.gap = '0.5rem';
                
                const colorInput = document.createElement('input');
                colorInput.type = 'color';
                colorInput.value = value.startsWith('#') ? value : '#ffffff';
                colorInput.style.width = '40px';
                colorInput.style.height = '40px';
                colorInput.style.padding = '0';
                colorInput.style.border = 'none';
                colorInput.style.cursor = 'pointer';
                colorInput.onchange = function() {
                    this.nextElementSibling.value = this.value;
                    updateBlockProperty(key, this.value);
                };
                
                const textInput = document.createElement('input');
                textInput.type = 'text';
                textInput.value = value;
                textInput.style.flex = '1';
                textInput.oninput = function() {
                    colorInput.value = this.value;
                    updateBlockProperty(key, this.value);
                };

                wrap.appendChild(colorInput);
                wrap.appendChild(textInput);
                fieldDiv.appendChild(wrap);
            } else if (key === 'alignment') {
                // Dropdown select
                const select = document.createElement('select');
                ['left', 'center', 'right'].forEach(opt => {
                    const o = document.createElement('option');
                    o.value = opt;
                    o.textContent = capitalize(opt);
                    o.selected = value === opt;
                    select.appendChild(o);
                });
                select.onchange = function() {
                    updateBlockProperty(key, this.value);
                };
                fieldDiv.appendChild(select);
            } else if (key === 'fontWeight') {
                const select = document.createElement('select');
                ['normal', 'medium', 'bold'].forEach(opt => {
                    const o = document.createElement('option');
                    o.value = opt;
                    o.textContent = capitalize(opt);
                    o.selected = value === opt;
                    select.appendChild(o);
                });
                select.onchange = function() {
                    updateBlockProperty(key, this.value);
                };
                fieldDiv.appendChild(select);
            } else if (key === 'text') {
                // Rich Text area with variable insertions
                const wrap = document.createElement('div');
                wrap.className = 'dropdown-var-wrapper';

                const textarea = document.createElement('textarea');
                textarea.id = 'prop-text';
                textarea.rows = 5;
                textarea.value = value;
                textarea.oninput = function() {
                    updateBlockProperty(key, this.value);
                };
                wrap.appendChild(textarea);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'dropdown-var-btn';
                btn.textContent = '+ Insert Variable';
                btn.onclick = function() { toggleVarDropdown('prop-text'); };
                wrap.appendChild(btn);

                const listDiv = document.createElement('div');
                listDiv.className = 'var-dropdown';
                listDiv.id = 'var-prop-text';
                listDiv.style.display = 'none';
                listDiv.style.position = 'absolute';
                listDiv.style.background = '#fff';
                listDiv.style.border = '1px solid #ccc';
                listDiv.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
                listDiv.style.zIndex = '100';
                listDiv.style.width = '200px';
                listDiv.style.maxHeight = '150px';
                listDiv.style.overflowY = 'auto';

                const vars = @json($availableVariables);
                vars.forEach(v => {
                    const item = document.createElement('div');
                    item.style.padding = '0.35rem 0.5rem';
                    item.style.fontSize = '0.75rem';
                    item.style.cursor = 'pointer';
                    item.innerHTML = `<code>\${ ${v} }</code>`;
                    item.onclick = function() {
                        insertVarAtCursor('prop-text', v);
                    };
                    listDiv.appendChild(item);
                });

                wrap.appendChild(listDiv);
                fieldDiv.appendChild(wrap);
            } else {
                // Generic text/number inputs
                const input = document.createElement('input');
                input.type = 'text';
                input.value = value;
                input.oninput = function() {
                    updateBlockProperty(key, this.value);
                };
                fieldDiv.appendChild(input);
            }

            container.appendChild(fieldDiv);
        }
    }

    function updateBlockProperty(key, val) {
        if (!activeBlockId) return;
        const block = templateState.blocks.find(b => b.id === activeBlockId);
        if (block) {
            block.properties[key] = val;
            renderPreview();
        }
    }

    function updateThemeProp(key, val) {
        templateState.settings.theme[key] = val;
        renderPreview();
    }

    function updatePreheader(val) {
        templateState.settings.preheader = val;
        renderPreview();
    }

    function deleteActiveBlock() {
        if (!activeBlockId) return;
        templateState.blocks = templateState.blocks.filter(b => b.id !== activeBlockId);
        activeBlockId = null;
        renderBlocksStackList();
        renderPreview();
        deselectBlock();
    }

    // Re-render blocks stack control
    function renderBlocksStackList() {
        const stack = document.getElementById('blocks-list-stack');
        stack.innerHTML = ''; // reset

        templateState.blocks.forEach((b, idx) => {
            const div = document.createElement('div');
            div.className = 'blocks-stack-item' + (activeBlockId === b.id ? ' selected' : '');
            div.id = 'stack-item-' + b.id;
            div.onclick = function() {
                selectBlock(b.id);
            };

            const info = document.createElement('div');
            info.innerHTML = `<span style="font-weight:600; color:#0f172a;">${capitalize(b.type)}</span> <span style="font-size:0.7rem; color:#64748b; margin-left:0.25rem;">#${idx+1}</span>`;
            div.appendChild(info);

            // Reorder controls
            const controls = document.createElement('div');
            controls.style.display = 'flex';
            controls.style.gap = '0.35rem';

            if (idx > 0) {
                const up = document.createElement('button');
                up.type = 'button';
                up.style.background = 'none';
                up.style.border = 'none';
                up.style.cursor = 'pointer';
                up.innerHTML = '▲';
                up.onclick = function(e) {
                    e.stopPropagation();
                    moveBlock(idx, -1);
                };
                controls.appendChild(up);
            }

            if (idx < templateState.blocks.length - 1) {
                const down = document.createElement('button');
                down.type = 'button';
                down.style.background = 'none';
                down.style.border = 'none';
                down.style.cursor = 'pointer';
                down.innerHTML = '▼';
                down.onclick = function(e) {
                    e.stopPropagation();
                    moveBlock(idx, 1);
                };
                controls.appendChild(down);
            }

            div.appendChild(controls);
            stack.appendChild(div);
        });
    }

    function moveBlock(index, direction) {
        const block = templateState.blocks[index];
        templateState.blocks.splice(index, 1);
        templateState.blocks.splice(index + direction, 0, block);
        renderBlocksStackList();
        renderPreview();
        
        if (activeBlockId === block.id) {
            selectBlock(block.id);
        }
    }

    // Client-side visual email rendering function
    function compileTemplateHtml() {
        const theme = templateState.settings.theme;
        let html = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body { margin: 0; padding: 0 !important; background-color: ${theme.backgroundColor}; font-family: ${theme.defaultFont}; }
        a { text-decoration: none; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: ${theme.backgroundColor}; font-family: ${theme.defaultFont};">`;

        if (templateState.settings.preheader) {
            html += `<div style="display:none; max-height:0px; max-width:0px; opacity:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px;">
                ${templateState.settings.preheader}
            </div>`;
        }

        html += `<center style="width: 100%; background-color: ${theme.backgroundColor};">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: ${theme.backgroundColor};">
            <tr>
                <td align="center" valign="top" style="padding: 40px 10px;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden;">
                        <tr>
                            <td style="background-color: #ffffff;">`;

        templateState.blocks.forEach(b => {
            html += `<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" data-preview-block-id="${b.id}">
                <tr>
                    <td>`;
            html += renderBlockHtml(b, theme);
            html += `</td>
                </tr>
            </table>`;
        });

        html += `</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>`;
        return html;
    }

    function renderBlockHtml(block, theme) {
        const props = block.properties;
        switch (block.type) {
            case 'logo':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'center'}" style="padding: ${props.paddingTop || 20}px 0 ${props.paddingBottom || 15}px 0;">
                            <img src="@{{ company_logo }}" width="${props.width || 150}" style="width: ${props.width || 150}px; max-width: 100%; height: auto; display: block; border: 0;" alt="Logo">
                        </td>
                    </tr>
                </table>`;

            case 'heading':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'left'}" style="padding: ${props.paddingTop || 15}px 20px ${props.paddingBottom || 15}px 20px;">
                            <h1 style="color: ${props.color || theme.primaryColor}; font-family: ${theme.defaultFont}; font-size: ${props.fontSize || '24px'}; font-weight: ${props.fontWeight || 'bold'}; line-height: 1.3; margin: 0; text-align: ${props.alignment || 'left'};">${props.text || 'Heading'}</h1>
                        </td>
                    </tr>
                </table>`;

            case 'text':
                let fmtText = (props.text || 'Text block').replace(/\n/g, '<br>');
                fmtText = fmtText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'left'}" style="padding: ${props.paddingTop || 10}px 20px ${props.paddingBottom || 10}px 20px; font-family: ${theme.defaultFont}; font-size: ${props.fontSize || '16px'}; color: ${props.color || theme.textColor}; font-weight: ${props.fontWeight || 'normal'}; line-height: ${props.lineHeight || '1.6'}; text-align: ${props.alignment || 'left'};">
                            <div style="margin: 0;">${fmtText}</div>
                        </td>
                    </tr>
                </table>`;

            case 'image':
                const imgUrl = props.src || '@{{ company_logo }}';
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'center'}" style="padding: ${props.padding || 10}px 20px;">
                            <img src="${imgUrl}" width="${props.width || 560}" alt="${props.alt || 'Image'}" style="width: ${props.width || 560}px; max-width: 100%; height: auto; display: block; border: 0; border-radius: ${props.borderRadius || 0}px;">
                        </td>
                    </tr>
                </table>`;

            case 'button':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'center'}" style="padding: ${props.padding || 15}px 20px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="${props.alignment || 'center'}">
                                <tr>
                                    <td align="center" bgcolor="${props.background || theme.secondaryColor}" style="border-radius: ${props.radius || 8}px;">
                                        <a href="${props.url || '#'}" style="background-color: ${props.background || theme.secondaryColor}; border: 1px solid ${props.background || theme.secondaryColor}; border-radius: ${props.radius || 8}px; color: ${props.fontColor || '#ffffff'}; display: inline-block; font-family: ${theme.defaultFont}; font-size: ${props.fontSize || '16px'}; font-weight: bold; line-height: 1.5; padding: 12px 30px; text-decoration: none; text-align: center;">
                                            ${props.text || 'Action Button'}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            case 'divider':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding: ${props.margin || 20}px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="border-top: ${props.height || 1}px solid ${props.color || '#e6f4f3'}; font-size: 1px; line-height: 1px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            case 'spacer':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td height="${props.height || 20}" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                    </tr>
                </table>`;

            case 'notice':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding: 10px 20px 14px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: ${props.background || '#E6F4F3'}; border-radius: ${props.radius || 7}px;">
                                <tr>
                                    <td style="padding: ${props.padding || 16}px 20px; font-family: ${theme.defaultFont}; font-size: 13px; line-height: 1.55; color: ${props.textColor || theme.textColor};">
                                        <p style="margin: 0 0 4px 0; font-weight: 700; color: ${props.titleColor || theme.primaryColor};">${props.title || ''}</p>
                                        <p style="margin: 0; color: ${props.textColor || theme.textColor};">${props.text || ''}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            case 'social_icons':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'center'}" style="padding: ${props.padding || 15}px 20px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="${props.alignment || 'center'}">
                                <tr>
                                    ${props.facebook_enabled ? `<td style="padding: 0 8px;"><img src="https://img.icons8.com/color/48/facebook.png" width="${props.size || 32}" height="${props.size || 32}"></td>` : ''}
                                    ${props.instagram_enabled ? `<td style="padding: 0 8px;"><img src="https://img.icons8.com/color/48/instagram-new.png" width="${props.size || 32}" height="${props.size || 32}"></td>` : ''}
                                    ${props.linkedin_enabled ? `<td style="padding: 0 8px;"><img src="https://img.icons8.com/color/48/linkedin.png" width="${props.size || 32}" height="${props.size || 32}"></td>` : ''}
                                    ${props.pinterest_enabled ? `<td style="padding: 0 8px;"><img src="https://img.icons8.com/color/48/pinterest.png" width="${props.size || 32}" height="${props.size || 32}"></td>` : ''}
                                    ${props.youtube_enabled ? `<td style="padding: 0 8px;"><img src="https://img.icons8.com/color/48/youtube-play.png" width="${props.size || 32}" height="${props.size || 32}"></td>` : ''}
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            case 'footer':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: ${props.background || '#f5f0e8'};">
                    <tr>
                        <td align="${props.alignment || 'center'}" style="padding: ${props.padding || 25}px 20px; font-family: ${theme.defaultFont}; font-size: 12px; color: ${props.color || '#607080'}; line-height: 1.6; text-align: ${props.alignment || 'center'};">
                            <p style="margin: 0 0 10px 0;">&copy; @{{ current_year }} @{{ company_name }}. All rights reserved.</p>
                            <p style="margin: 0 0 10px 0;">Company Address Address</p>
                            <p style="margin: 0;"><a href="@{{ unsubscribe }}" style="color: ${theme.primaryColor}; text-decoration: underline;">Unsubscribe</a> &middot; <a href="mailto:@{{ support_email }}" style="color: ${theme.primaryColor}; text-decoration: underline;">Support</a></p>
                        </td>
                    </tr>
                </table>`;

            case 'signature':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'left'}" style="padding: ${props.padding || 15}px 20px; font-family: ${theme.defaultFont}; font-size: 16px; color: ${theme.textColor};">
                            <p style="margin:0 0 10px 0; font-style:italic;">Sincerely,</p>
                            ${props.image ? `<img src="${props.image}" max-height="50" style="max-height:50px; display:block; margin-bottom:10px;">` : ''}
                            <strong style="display:block;">${props.name || 'SettleANZ Team'}</strong>
                            <span style="font-size:14px; color:#607080;">${props.title || 'Support Representative'}</span>
                        </td>
                    </tr>
                </table>`;

            case 'banner':
                const bg = props.backgroundImage ? `url('${props.backgroundImage}')` : `linear-gradient(135deg, ${theme.primaryColor} 0%, ${theme.secondaryColor} 100%)`;
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td align="${props.alignment || 'center'}" style="padding: 10px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background: ${bg} no-repeat center center / cover; border-radius: 8px; overflow: hidden;">
                                <tr>
                                    <td height="${props.height || 250}" valign="middle" style="padding: 30px 20px; background-color: rgba(0,0,0,${props.overlayOpacity || '0.4'}); text-align: ${props.alignment || 'center'};">
                                        <h2 style="color: ${props.color || '#ffffff'}; font-family: ${theme.defaultFont}; font-size: 28px; font-weight: bold; margin: 0 0 10px 0; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">${props.title || 'Visual Banner'}</h2>
                                        <p style="color: ${props.color || '#ffffff'}; font-family: ${theme.defaultFont}; font-size: 16px; margin: 0; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">${props.subtitle || 'Promo Description'}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            case 'quote':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding: 15px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: ${props.background || '#f0fbfb'}; border-left: 4px solid ${props.borderColor || theme.primaryColor};">
                                <tr>
                                    <td style="padding: ${props.padding || 20}px;">
                                        <p style="margin: 0; font-family: ${theme.defaultFont}; font-size: 16px; font-style: italic; color: ${props.color || theme.textColor}; line-height: 1.6;">&ldquo;${props.text || 'Quote'}&rdquo;</p>
                                        ${props.author ? `<p style="margin: 10px 0 0 0; font-size: 14px; font-weight: bold; color: #607080; text-align: right;">&mdash; ${props.author}</p>` : ''}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            case 'columns_2':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: ${props.background || '#ffffff'};">
                    <tr>
                        <td style="padding: ${props.padding || 15}px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="48%" valign="top" style="border:1px dashed #cbd5e1; padding:10px;">
                                        <p style="text-align:center; font-size:0.75rem; color:#94a3b8; margin:0;">Column 1</p>
                                    </td>
                                    <td width="4%"></td>
                                    <td width="48%" valign="top" style="border:1px dashed #cbd5e1; padding:10px;">
                                        <p style="text-align:center; font-size:0.75rem; color:#94a3b8; margin:0;">Column 2</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            case 'columns_3':
                return `
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: ${props.background || '#ffffff'};">
                    <tr>
                        <td style="padding: ${props.padding || 15}px 20px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td width="30%" valign="top" style="border:1px dashed #cbd5e1; padding:10px;">
                                        <p style="text-align:center; font-size:0.75rem; color:#94a3b8; margin:0;">Column 1</p>
                                    </td>
                                    <td width="5%"></td>
                                    <td width="30%" valign="top" style="border:1px dashed #cbd5e1; padding:10px;">
                                        <p style="text-align:center; font-size:0.75rem; color:#94a3b8; margin:0;">Column 2</p>
                                    </td>
                                    <td width="5%"></td>
                                    <td width="30%" valign="top" style="border:1px dashed #cbd5e1; padding:10px;">
                                        <p style="text-align:center; font-size:0.75rem; color:#94a3b8; margin:0;">Column 3</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>`;

            default:
                return '';
        }
    }

    // Modal windows toggle controls
    function openTestModal() {
        document.getElementById('test-email-modal').style.display = 'flex';
    }
    function closeTestModal() {
        document.getElementById('test-email-modal').style.display = 'none';
    }
    function openHtmlModal() {
        const area = document.getElementById('compiled-html-area');
        area.value = compileTemplateHtml();
        document.getElementById('view-html-modal').style.display = 'flex';
    }
    function closeHtmlModal() {
        document.getElementById('view-html-modal').style.display = 'none';
    }

    function copyCompiledHTML() {
        const text = document.getElementById('compiled-html-area');
        text.select();
        document.execCommand('copy');
        notificationSystem.success('Copied!', 'Compiled HTML code copied to clipboard!');
    }

    function downloadHTML() {
        const html = compileTemplateHtml();
        const blob = new Blob([html], { type: 'text/html' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = templateState.settings.name || 'email_template.html';
        a.click();
    }

    function dispatchTestEmail() {
        const email = document.getElementById('test_recipient_email').value;
        if (!email) {
            notificationSystem.warning('Validation', 'Please enter a valid email address.');
            return;
        }

        const bodyHtml = compileTemplateHtml();
        const subject = document.getElementById('template_subject').value;

        fetch('{{ route("admin.email-templates.send-test", $template) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email, subject, body_html: bodyHtml })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                notificationSystem.success('Sent!', 'Test email successfully sent to ' + email + '!');
                closeTestModal();
            } else {
                notificationSystem.error('Failed', data.message);
            }
        })
        .catch(err => {
            notificationSystem.error('Error', 'Error sending test email. Check your SMTP configurations.');
            console.error(err);
        });
    }

    function confirmRestore(url) {
        adminModal.confirm({
            title: 'Restore revision?',
            message: 'Are you sure you want to restore this revision? Your current draft will be backed up.',
            confirmText: 'Restore',
            isDangerous: false
        }).then(function(confirmed) {
            if (!confirmed) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `@csrf`;
            document.body.appendChild(form);
            form.submit();
        });
    }

    // Submit complete visual template fields
    function submitTemplateForm() {
        const jsonInput = document.getElementById('builder_json_input');
        const htmlInput = document.getElementById('body_html_input');
        
        // Ensure name is bound
        templateState.settings.name = document.getElementById('template_name').value;
        
        jsonInput.value = JSON.stringify(templateState);
        htmlInput.value = compileTemplateHtml();

        document.getElementById('templateSaveForm').submit();
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).replace('_', ' ');
    }

    function formatPropertyLabel(str) {
        return str.replace(/([A-Z])/g, ' $1')
                  .replace(/^./, function(str){ return str.toUpperCase(); });
    }
</script>
@endsection
