@extends('admin.layouts.app')

@php
    $meta = is_array($lead->metadata) ? $lead->metadata : [];
    $displayName = $lead->full_name ?: $lead->first_name ?: 'Unknown';
    $isDrawer = request()->header('X-Requested-With') === 'XMLHttpRequest' || request()->query('drawer');
    $phone = $lead->phone ?: data_get($meta, 'phone');
    $subject = data_get($meta, 'subject');
    $message = data_get($meta, 'message');
    
    // Find next follow up task if any
    $nextFollowUp = $lead->tasks->where('status', 'pending')->sortBy('due_at')->first();
    $lastActivity = $lead->activities->sortByDesc('created_at')->first();
@endphp

@section('content')
<style>
/* Design System CSS variables aligned with the mockup color palettes */
:root {
    --crm-primary: #14a394;
    --crm-primary-hover: #0f8b7e;
    --crm-primary-light: rgba(20, 163, 148, 0.06);
    --crm-bg: #f8fafc;
    --crm-card-bg: #ffffff;
    --crm-border: #e2e8f0;
    --crm-text: #0f172a;
    --crm-text-muted: #64748b;
    --crm-radius-sm: 6px;
    --crm-radius: 12px;
    --crm-radius-lg: 16px;
    --crm-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --crm-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    
    /* Status dots */
    --status-new: #3b82f6;
    --status-contacted: #f59e0b;
    --status-qualified: #10b981;
    --status-lost: #ef4444;
}

.crm-workspace {
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--crm-text);
    padding-bottom: 2rem;
}

/* Breadcrumbs exactly matching the mockup */
.crm-breadcrumbs {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: var(--crm-text-muted);
    margin-bottom: 1.25rem;
}
.crm-breadcrumbs a {
    color: var(--crm-text-muted);
    text-decoration: none;
}
.crm-breadcrumbs a:hover {
    color: var(--crm-primary);
}
.crm-breadcrumbs span {
    color: var(--crm-text);
    font-weight: 500;
}

/* Sticky top header actions bar matching mockup exactly */
.crm-header-sticky {
    position: sticky;
    top: 0;
    background: #ffffff;
    border-bottom: 1px solid var(--crm-border);
    padding: 1rem 0;
    margin-top: -0.75rem;
    z-index: 100;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.crm-header-left {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.crm-header-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #b2c5df;
    color: #ffffff;
    font-weight: 700;
    font-size: 0.95rem;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.crm-header-title-section {
    display: flex;
    flex-direction: column;
}

.crm-header-title-row {
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.crm-header-title-row h1 {
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0;
    color: #0f172a;
}

.crm-header-id {
    font-size: 0.65rem;
    color: var(--crm-text-muted);
    font-weight: 600;
    margin-left: 2px;
}

.crm-header-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    font-size: 0.725rem;
    color: var(--crm-text-muted);
    margin-top: 0.25rem;
    align-items: center;
}

.crm-header-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.crm-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

/* Nav groups prev/next page */
.crm-nav-group {
    display: flex;
    gap: 0.25rem;
    margin-left: 0.5rem;
}

.crm-nav-btn {
    background: #ffffff;
    border: 1px solid var(--crm-border);
    width: 32px;
    height: 32px;
    border-radius: 6px;
    color: var(--crm-text);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.crm-nav-btn:hover:not(.disabled) {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.crm-nav-btn.disabled {
    opacity: 0.35;
    cursor: not-allowed;
}

/* More Actions Dropdown */
.crm-more-actions-container {
    position: relative;
}

.crm-dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 0.5rem;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
    width: 170px;
    display: none;
    z-index: 100;
    padding: 0.35rem;
}

.crm-dropdown-item {
    padding: 0.55rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    outline: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-radius: 8px;
    transition: all 0.15s ease;
}
.crm-dropdown-item:hover {
    background: var(--crm-primary-light);
    color: var(--crm-primary);
}

/* Action Toolbar buttons */
.crm-action-toolbar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.crm-toolbar-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.crm-toolbar-group form {
    display: inline-block;
    margin: 0;
}

.crm-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 0.85rem;
    padding-right: 2rem !important;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0.45rem 0.75rem;
    font-size: 0.775rem;
    font-weight: 500;
    color: #334155;
    outline: none;
    cursor: pointer;
    transition: all 0.15s ease;
}

.crm-select:hover {
    border-color: #94a3b8;
}

.crm-select:focus {
    border-color: var(--crm-primary);
    box-shadow: 0 0 0 3px rgba(20, 163, 148, 0.1);
}

/* Custom Select Dropdown UI Styling */
.crm-custom-select {
    position: relative;
    width: 100%;
}

.crm-custom-select-trigger {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0.45rem 0.75rem;
    font-size: 0.775rem;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
}

.crm-custom-select-trigger:hover {
    border-color: #94a3b8;
}

.crm-custom-select.active .crm-custom-select-trigger {
    border-color: var(--crm-primary);
    box-shadow: 0 0 0 3px rgba(20, 163, 148, 0.1);
}

.crm-custom-select-options {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 0.35rem;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
    z-index: 150;
    display: none;
    padding: 0.25rem;
    max-height: 220px;
    overflow-y: auto;
}

.crm-custom-select.active .crm-custom-select-options {
    display: block;
}

.crm-custom-select-option {
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.15s ease;
    user-select: none;
    text-align: left;
}

.crm-custom-select-option:hover {
    background: var(--crm-primary-light);
    color: var(--crm-primary);
}

.crm-custom-select-option.selected {
    background: var(--crm-primary-light);
    color: var(--crm-primary);
    font-weight: 600;
}

.crm-input {
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 0.45rem 0.75rem;
    font-size: 0.775rem;
    font-weight: 500;
    color: #334155;
    outline: none;
    transition: all 0.15s ease;
}

.crm-input:hover {
    border-color: #94a3b8;
}

.crm-input:focus {
    border-color: var(--crm-primary);
    box-shadow: 0 0 0 3px rgba(20, 163, 148, 0.1);
}

.crm-form-row {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.crm-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.85rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    border: 1px solid transparent;
    text-decoration: none;
}

.crm-btn--secondary {
    background: #ffffff;
    border-color: var(--crm-border);
    color: var(--crm-text);
}
.crm-btn--secondary:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.crm-btn--danger {
    background: #de3e44;
    color: #ffffff;
}
.crm-btn--danger:hover {
    background: #c53035;
}

/* 7 KPI Cards strip formatting matching mockup exactly */
.crm-kpi-strip {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.75rem;
    margin-top: 1.25rem;
}

@media (max-width: 1200px) {
    .crm-kpi-strip {
        grid-template-columns: repeat(4, 1fr);
    }
}
@media (max-width: 768px) {
    .crm-kpi-strip {
        grid-template-columns: repeat(2, 1fr);
    }
}

.crm-kpi-card {
    background: #ffffff;
    border: 1px solid var(--crm-border);
    border-radius: 8px;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    box-shadow: var(--crm-shadow-sm);
}

.crm-kpi-icon {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.crm-kpi-body {
    display: flex;
    flex-direction: column;
    min-width: 0;
    line-height: 1.3;
}

.crm-kpi-label {
    font-size: 0.65rem;
    color: var(--crm-text-muted);
    font-weight: 500;
}

.crm-kpi-value {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--crm-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 1px;
}

.crm-kpi-desc {
    font-size: 0.65rem;
    color: var(--crm-text-muted);
    margin-top: 1px;
}

/* 3-Column Layout grids */
.crm-grid {
    display: grid;
    grid-template-columns: 290px 1fr 320px;
    gap: 1.25rem;
    margin-top: 1.25rem;
    align-items: start;
}

@media (max-width: 1300px) {
    .crm-grid {
        grid-template-columns: 1fr 320px;
    }
    .crm-col-left {
        grid-row: 2;
        grid-column: 1 / span 2;
    }
    .crm-col-center {
        grid-column: 1;
    }
}
@media (max-width: 900px) {
    .crm-grid {
        grid-template-columns: 1fr;
    }
    .crm-col-left, .crm-col-center, .crm-col-right {
        grid-column: 1 !important;
        grid-row: auto !important;
    }
}

/* Card aesthetics */
.crm-card {
    background: var(--crm-card-bg);
    border: 1px solid var(--crm-border);
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1.25rem;
    box-shadow: var(--crm-shadow-sm);
}

.crm-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 0.35rem;
}

.crm-card-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    font-weight: 700;
    color: var(--crm-text-muted);
    letter-spacing: 0.05em;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

/* Cover Profile card formatting */
.crm-profile-cover {
    height: 75px;
    background: linear-gradient(135deg, #5c62d6, #8f5fe6);
    position: relative;
}

.crm-profile-bookmark {
    position: absolute;
    top: 10px;
    right: 12px;
    color: rgba(255, 255, 255, 0.85);
    cursor: pointer;
}

.crm-profile-avatar-overlap {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: -36px;
    text-align: center;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--crm-border);
}

.crm-profile-avatar-circle {
    position: relative;
    z-index: 2;
    width: 68px;
    height: 68px;
    border-radius: 50%;
    background: #809ad2;
    color: #ffffff;
    font-weight: 700;
    font-size: 1.35rem;
    display: grid;
    place-items: center;
    border: 4px solid #ffffff;
    box-shadow: var(--crm-shadow-sm);
}

.crm-profile-name {
    font-size: 1.05rem;
    font-weight: 700;
    margin: 0.5rem 0 0.15rem;
    color: #0f172a;
}

.crm-profile-email {
    font-size: 0.75rem;
    color: var(--crm-text-muted);
}

/* Key-value attributes */
.crm-kv {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}

.crm-kv-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
}

.crm-kv-label {
    color: var(--crm-text-muted);
}

.crm-kv-value {
    font-weight: 500;
    color: #0f172a;
}

/* Badges */
.crm-badge {
    display: inline-flex;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-size: 0.675rem;
    font-weight: 600;
}
.crm-badge--primary {
    background: #e2f2f0;
    color: var(--crm-primary);
}

/* Tabs layout */
.crm-tabs {
    display: flex;
    border-bottom: 1px solid var(--crm-border);
    margin-bottom: 1rem;
    gap: 1.25rem;
}

.crm-tab-link {
    background: none;
    border: none;
    padding: 0.5rem 0.15rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--crm-text-muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.crm-tab-link:hover, .crm-tab-link.active {
    color: var(--crm-primary);
}

.crm-tab-link.active {
    border-bottom-color: var(--crm-primary);
}

.crm-tab-content {
    display: none !important;
}

.crm-tab-content.active {
    display: block !important;
}

/* Timeline Feed updates */
.crm-timeline-filter {
    display: flex;
    gap: 0.25rem;
    margin-bottom: 1.25rem;
}

.crm-timeline-filter-btn {
    background: #f1f5f9;
    border: none;
    padding: 0.3rem 0.65rem;
    border-radius: 6px;
    font-size: 0.725rem;
    font-weight: 600;
    color: var(--crm-text-muted);
    cursor: pointer;
    transition: all 0.15s ease;
}
.crm-timeline-filter-btn.active {
    background: #3b82f6;
    color: #ffffff;
}

.crm-timeline {
    position: relative;
    padding-left: 1.75rem;
}
.crm-timeline::before {
    content: '';
    position: absolute;
    left: 11px;
    top: 10px;
    bottom: 10px;
    width: 1px;
    background: #e2e8f0;
}

.crm-timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.crm-timeline-dot {
    position: absolute;
    left: -1.75rem;
    top: 3px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #f1f5f9;
    display: grid;
    place-items: center;
    color: #ffffff;
    z-index: 10;
}
.crm-timeline-dot svg {
    width: 10px !important;
    height: 10px !important;
}

.crm-timeline-body-wrap {
    display: flex;
    justify-content: space-between;
    font-size: 0.775rem;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 0.75rem;
}

.crm-timeline-details {
    display: flex;
    flex-direction: column;
}

.crm-timeline-title {
    font-weight: 700;
    color: #0f172a;
}
.crm-timeline-subtitle {
    color: var(--crm-text-muted);
    margin-top: 2px;
}
.crm-timeline-actor {
    font-size: 0.68rem;
    color: var(--crm-text-muted);
    margin-top: 4px;
}

.crm-timeline-time-col {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    color: var(--crm-text-muted);
    font-size: 0.68rem;
}
.crm-timeline-elapsed {
    font-weight: 600;
    color: #64748b;
}
.crm-timeline-date {
    margin-top: 4px;
}

/* Submission details */
.crm-submission-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

/* Right sidebar components */
.crm-health-card {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1rem;
}

.crm-score-ring {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    display: grid;
    place-items: center;
    position: relative;
    font-weight: 800;
    font-size: 1.15rem;
    color: #0f172a;
}

.crm-score-ring-inner {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: #ffffff;
    display: grid;
    place-items: center;
}

.crm-health-status-row {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex: 1;
}

/* Staff assignment card row layout */
.crm-sidebar-row {
    display: flex;
    gap: 0.35rem;
    align-items: center;
    margin-top: 0.35rem;
}

/* File dropzone */
.crm-dropzone {
    border: 2px dashed var(--crm-border);
    border-radius: 8px;
    padding: 1.25rem 0.5rem;
    text-align: center;
    background: #fafafa;
    cursor: pointer;
    transition: all 0.2s ease;
}
.crm-dropzone:hover {
    border-color: var(--crm-primary);
    background: var(--crm-primary-light);
}

.crm-dropzone p {
    margin: 0.25rem 0 0;
    font-size: 0.7rem;
    color: var(--crm-text-muted);
}

.crm-dropzone input[type="file"] {
    display: none;
}

/* Note Composer Styles */
.note-composer-container {
    position: relative;
    margin-bottom: 1.25rem;
}

.note-composer {
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: var(--crm-shadow-sm);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.note-format-bar {
    display: flex;
    gap: 0.35rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.5rem;
    align-items: center;
}

.note-format-btn {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    font-size: 0.725rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
}

.note-format-btn:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: var(--crm-primary);
}

#note-content {
    border: none;
    outline: none;
    width: 100%;
    min-height: 110px;
    padding: 0.75rem;
    font-size: 0.8rem;
    line-height: 1.5;
    color: #334155;
    resize: vertical;
}

.note-composer-footer {
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    padding: 0.65rem 0.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.note-composer-actions {
    display: flex;
    gap: 0.75rem;
}

.note-composer-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.725rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
}

.note-composer-checkbox input[type="checkbox"] {
    accent-color: var(--crm-primary);
    cursor: pointer;
}

/* Mentions suggest box popover */
.note-mentions-popover {
    position: absolute;
    top: 100%;
    left: 0;
    margin-top: 0.25rem;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    width: 200px;
    max-height: 150px;
    overflow-y: auto;
    z-index: 100;
    display: none;
    padding: 0.25rem;
}

.note-mentions-item {
    padding: 0.45rem 0.65rem;
    font-size: 0.75rem;
    color: #334155;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.note-mentions-item:hover {
    background: var(--crm-primary-light);
    color: var(--crm-primary);
}

/* Notes List Styles */
.sz-note {
    background: #ffffff;
    border: 1px solid var(--crm-border);
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: var(--crm-shadow-sm);
    transition: all 0.2s ease;
    text-align: left;
}

.sz-note.is-pinned {
    border-left: 3px solid #f59e0b;
}

.sz-note-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.75rem;
    color: var(--crm-text-muted);
    margin-bottom: 0.5rem;
}

.sz-note-author {
    font-weight: 700;
    color: #334155;
}

.sz-note-date {
    font-size: 0.7rem;
}

.sz-note-content {
    font-size: 0.8rem;
    color: #475569;
    line-height: 1.5;
}

/* Tasks completion progress styles */
.tasks-progress-container {
    background: #f8fafc;
    border: 1px solid var(--crm-border);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
}

.tasks-progress-header {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.45rem;
}

.tasks-progress-bar-bg {
    height: 6px;
    background: #e2e8f0;
    border-radius: 99px;
    overflow: hidden;
}

.tasks-progress-bar-fill {
    height: 100%;
    background: var(--crm-primary);
    width: 0%;
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Communication Channel Logs Styling */
.comms-tabs {
    display: flex;
    gap: 0.35rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 0.5rem;
}

.comms-tab-btn {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 0.35rem 0.75rem;
    font-size: 0.725rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
}

.comms-tab-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: var(--crm-primary);
}

.comms-tab-btn.active {
    background: var(--crm-primary);
    border-color: var(--crm-primary);
    color: #ffffff;
}

.chat-container {
    background: #f8fafc;
    border: 1px solid var(--crm-border);
    border-radius: 10px;
    padding: 1rem;
    max-height: 380px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.chat-bubble {
    display: flex;
    flex-direction: column;
    max-width: 85%;
    padding: 0.75rem 1rem;
    font-size: 0.8rem;
    line-height: 1.45;
    border-radius: 10px;
    box-shadow: var(--crm-shadow-sm);
}

.chat-bubble.client {
    align-self: flex-start;
    background: #ffffff;
    border: 1px solid var(--crm-border);
    border-bottom-left-radius: 2px;
}

.chat-bubble.assistant {
    align-self: flex-end;
    background: var(--crm-primary-light);
    border: 1px solid rgba(20, 163, 148, 0.15);
    border-bottom-right-radius: 2px;
}

.chat-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.65rem;
    color: var(--crm-text-muted);
    margin-top: 0.35rem;
    border-top: 1px solid rgba(0,0,0,0.03);
    padding-top: 0.25rem;
}
</style>

<div class="admin-main__inner crm-workspace" data-drawer-content>
    @if(!$isDrawer)
    {{-- Breadcrumbs exactly as in mockup --}}
    <div class="crm-breadcrumbs">
        <a href="{{ route('admin.leads.index') }}">Lead Center</a>
        <svg style="width:10px; height:10px; color: var(--crm-text-muted); transform: rotate(-90deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        <a href="{{ route('admin.leads.index') }}">Leads</a>
        <svg style="width:10px; height:10px; color: var(--crm-text-muted); transform: rotate(-90deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        <span>Lead #{{ $lead->id }}</span>
    </div>

    {{-- Sticky Header layout matching mockup exactly --}}
    <div class="crm-header-sticky">
        <div class="crm-header-left">
            <div class="crm-header-avatar">{{ $lead->initials }}</div>
            <div class="crm-header-title-section">
                <div class="crm-header-title-row">
                    <h1>{{ $displayName }}</h1>
                    <span class="crm-header-id">ID: #{{ $lead->id }}</span>
                </div>
                <div class="crm-header-meta-row">
                    @php $sc = $statusColors[$lead->status] ?? '#94a3b8'; @endphp
                    <span class="crm-header-meta-item">
                        <span class="crm-status-dot" style="background:{{ $sc }};"></span>
                        <span>{{ ucfirst(str_replace('_',' ',$lead->status)) }}</span>
                    </span>
                    <span style="color:#cbd5e1;">·</span>
                    <span class="crm-header-meta-item">
                        <svg style="width:12px; height:12px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Assigned: {{ $lead->assignedStaff?->name ?? 'Unassigned' }}</span>
                    </span>
                    <span style="color:#cbd5e1;">·</span>
                    <span class="crm-header-meta-item">
                        <svg style="width:12px; height:12px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Created: {{ $lead->created_at?->format('j M Y, h:i A') }}</span>
                    </span>
                    @if($lastActivity)
                    <span style="color:#cbd5e1;">·</span>
                    <span class="crm-header-meta-item">
                        <svg style="width:12px; height:12px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Activity: {{ $lastActivity->created_at->diffForHumans() }}</span>
                    </span>
                    @endif
                </div>
            </div>
            
            {{-- Navigation Prev/Next group --}}
            <div class="crm-nav-group">
                <button type="button" id="prev-lead-btn" class="crm-nav-btn disabled" disabled title="Previous Lead (ArrowLeft)">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="next-lead-btn" class="crm-nav-btn disabled" disabled title="Next Lead (ArrowRight)">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
        
        <div class="crm-action-toolbar">
            <div class="crm-toolbar-group">
                {{-- More Actions Dropdown Menu --}}
                <div class="crm-more-actions-container">
                    <button type="button" class="crm-btn crm-btn--secondary" onclick="toggleMoreActionsDropdown(event)" style="display:inline-flex; align-items:center; gap:4px;">
                        <span>More Actions</span>
                        <svg style="width:12px; height:12px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="crm-dropdown-menu" id="more-actions-menu">
                        <form method="POST" action="{{ route('admin.leads.update', $lead) }}" id="archive-form" style="display:none;">
                            @csrf @method('PUT')
                            <input type="hidden" name="is_archived" value="{{ $lead->is_archived ? '0' : '1' }}">
                        </form>
                        <button type="button" class="crm-dropdown-item" onclick="document.getElementById('archive-form').submit()">
                            <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            <span>{{ $lead->is_archived ? 'Unarchive Lead' : 'Archive Lead' }}</span>
                        </button>
                        <button type="button" class="crm-dropdown-item" onclick="window.print()">
                            <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Export dossier</span>
                        </button>
                        <button type="button" class="crm-dropdown-item" onclick="recalcScore({{ $lead->id }})">
                            <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18"/></svg>
                            <span>Recalculate Score</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="crm-toolbar-group">
                @can('lead_center.edit')
                <a href="{{ route('admin.leads.edit', $lead) }}" class="crm-btn crm-btn--secondary" style="display:inline-flex; align-items:center; gap:4px;">
                    <svg style="width:14px; height:14px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Edit Lead</span>
                </a>
                @endcan
                @can('lead_center.delete')
                <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirmDelete(this, 'lead')">
                    @csrf @method('DELETE')
                    <button type="submit" class="crm-btn crm-btn--danger" style="display:inline-flex; align-items:center; gap:4px;">
                        <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Delete Lead</span>
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    {{-- Replicating the 7 KPI Cards strip exactly --}}
    <div class="crm-kpi-strip">
        <div class="crm-kpi-card">
            <div class="crm-kpi-icon" style="background:#e6f6f5; color:#14a394;">
                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"/></svg>
            </div>
            <div class="crm-kpi-body">
                <span class="crm-kpi-label">Lead Score</span>
                <span class="crm-kpi-value" style="color:#14a394;">{{ $lead->lead_score }}/100</span>
                <span class="crm-kpi-desc">{{ $lead->lead_score >= 70 ? 'High' : ($lead->lead_score >= 40 ? 'Good' : 'Poor') }}</span>
            </div>
        </div>
        <div class="crm-kpi-card">
            <div class="crm-kpi-icon" style="background:#fffbeb; color:#d97706;">
                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="crm-kpi-body">
                <span class="crm-kpi-label">Priority</span>
                <span class="crm-kpi-value">{{ ucfirst($lead->priority) }}</span>
                <span class="crm-kpi-desc">Normal</span>
            </div>
        </div>
        <div class="crm-kpi-card">
            <div class="crm-kpi-icon" style="background:#ecfdf5; color:#059669;">
                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>
            <div class="crm-kpi-body">
                <span class="crm-kpi-label">Source</span>
                <span class="crm-kpi-value" title="{{ $lead->lead_source_label }}">{{ $lead->lead_source_label }}</span>
                <span class="crm-kpi-desc">Website</span>
            </div>
        </div>
        <div class="crm-kpi-card">
            <div class="crm-kpi-icon" style="background:#fffbeb; color:#d97706;">
                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div class="crm-kpi-body">
                <span class="crm-kpi-label">Stage</span>
                <span class="crm-kpi-value">{{ ucfirst(str_replace('_',' ',$lead->status)) }}</span>
                <span class="crm-kpi-desc">Lead Stage</span>
            </div>
        </div>
        <div class="crm-kpi-card">
            <div class="crm-kpi-icon" style="background:#eff6ff; color:#2563eb;">
                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="crm-kpi-body">
                <span class="crm-kpi-label">Last Activity</span>
                <span class="crm-kpi-value">{{ $lastActivity ? $lastActivity->created_at->diffForHumans() : 'None' }}</span>
                <span class="crm-kpi-desc" style="font-size:0.6rem;">{{ $lastActivity ? $lastActivity->created_at->format('d M Y, h:i A') : '-' }}</span>
            </div>
        </div>
        <div class="crm-kpi-card">
            <div class="crm-kpi-icon" style="background:#e6f6f5; color:#14a394;">
                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="crm-kpi-body">
                <span class="crm-kpi-label">Next Follow-up</span>
                <span class="crm-kpi-value" title="{{ $nextFollowUp ? $nextFollowUp->title : 'Not scheduled' }}">{{ $nextFollowUp ? $nextFollowUp->due_at?->format('d M') : 'Not scheduled' }}</span>
                <span class="crm-kpi-desc">Set follow-up</span>
            </div>
        </div>
        <div class="crm-kpi-card">
            <div class="crm-kpi-icon" style="background:#e6f6f5; color:#14a394;">
                <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div class="crm-kpi-body">
                <span class="crm-kpi-label">Consent</span>
                <span class="crm-kpi-value" style="color:{{ $lead->consent ? '#10b981' : '#f59e0b' }};">{{ $lead->consent ? 'Verified' : 'Pending' }}</span>
                <span class="crm-kpi-desc" style="cursor:pointer;" onclick="recalcScore({{ $lead->id }})">Recalculate</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Grid columns --}}
    <div class="crm-grid">
        
        {{-- Column 1: Context & Attributes --}}
        <div class="crm-col-left">
            {{-- Avatar profile card with cover banner --}}
            <div class="crm-card" style="padding: 0; overflow:hidden;">
                <div class="crm-profile-cover">
                    {{-- Bookmark/ribbon icon --}}
                    <svg class="crm-profile-bookmark" style="width:18px; height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
                <div class="crm-profile-avatar-overlap">
                    <div class="crm-profile-avatar-circle">{{ $lead->initials }}</div>
                    <h2 class="crm-profile-name">{{ $displayName }}</h2>
                    <span class="crm-profile-email">{{ $lead->email }}</span>
                </div>
                
                {{-- Contact information block --}}
                <div style="padding:1.25rem;">
                    <div class="crm-card-header" style="border:none; padding:0; margin-bottom:0.75rem;">
                        <h3 class="crm-card-title" style="color:#64748b; font-size:0.725rem;">Contact Information</h3>
                    </div>
                    <div class="crm-kv">
                        <div class="crm-kv-row"><span class="crm-kv-label">Company</span><span class="crm-kv-value">{{ $lead->company ?: '-' }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">Country</span><span class="crm-kv-value">{{ $lead->country ?: '-' }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">Website / Link</span><span class="crm-kv-value">{{ data_get($meta, 'website') ?: '-' }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">IP Address</span><span class="crm-kv-value">{{ $lead->ip_address ?: '-' }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Interested In block --}}
            @if($lead->interested_service || $lead->visa_type)
            <div class="crm-card">
                <div class="crm-card-header" style="border:none; padding:0; margin-bottom:0.75rem;">
                    <h3 class="crm-card-title" style="color:#64748b; font-size:0.725rem;">Interested In</h3>
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:0.35rem; margin-top:0.25rem;">
                    @if($lead->interested_service)
                        <span class="crm-badge crm-badge--primary">{{ $lead->interested_service }}</span>
                    @endif
                    @if($lead->visa_type)
                        <span class="crm-badge crm-badge--primary">{{ $visaTypes[$lead->visa_type] ?? $lead->visa_type }}</span>
                    @endif
                </div>
            </div>
            @endif

            {{-- Ebook / AI Chat summary --}}
            <div class="crm-card">
                <div class="crm-card-header" style="border:none; padding:0; margin-bottom:0.75rem;">
                    <h3 class="crm-card-title" style="color:#64748b; font-size:0.725rem;">Ebook / AI Chat</h3>
                </div>
                <div style="font-size:0.75rem; color:var(--crm-text-muted); display:flex; flex-direction:column; gap:0.35rem;">
                    <div>{{ $lead->form_type === 'ebook_download' ? 'Downloaded Ebook: ' . ($lead->ebook?->title ?? $lead->ebook_title) : 'No ebook downloaded' }}</div>
                    <div>{{ $lead->form_type === 'ai_chat' && $lead->conversation_summary ? 'AI Assistant Summary logged' : 'No AI chat session' }}</div>
                </div>
            </div>
        </div>

        {{-- Column 2: Center Workspace --}}
        <div class="crm-col-center">
            {{-- Tabs --}}
            <div class="crm-tabs">
                <button type="button" class="crm-tab-link active" onclick="switchTab(event, 'tab-timeline')">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Timeline</span>
                </button>
                <button type="button" class="crm-tab-link" onclick="switchTab(event, 'tab-notes')">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span>Notes ({{ $lead->leadNotes->count() }})</span>
                </button>
                <button type="button" class="crm-tab-link" onclick="switchTab(event, 'tab-tasks')">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Tasks ({{ $lead->tasks->count() }})</span>
                </button>
                <button type="button" class="crm-tab-link" onclick="switchTab(event, 'tab-communication')">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Communication</span>
                </button>
                <button type="button" class="crm-tab-link" onclick="switchTab(event, 'tab-files')">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Files ({{ $lead->files->count() }})</span>
                </button>
            </div>

            {{-- Content: Timeline with filters --}}
            <div id="tab-timeline" class="crm-tab-content active">
                <div class="crm-timeline-filter">
                    <button type="button" class="crm-timeline-filter-btn active" onclick="filterTimeline('all', this)">All</button>
                    <button type="button" class="crm-timeline-filter-btn" onclick="filterTimeline('note_added', this)">Notes</button>
                    <button type="button" class="crm-timeline-filter-btn" onclick="filterTimeline('status_changed', this)">Status</button>
                    <button type="button" class="crm-timeline-filter-btn" onclick="filterTimeline('task_completed', this)">Tasks</button>
                    <button type="button" class="crm-timeline-filter-btn" onclick="filterTimeline('file_uploaded', this)">Files</button>
                    <button type="button" class="crm-timeline-filter-btn" onclick="filterTimeline('created', this)">System</button>
                </div>
                <div class="crm-timeline">
                    @forelse($lead->activities->sortByDesc('created_at')->take(30) as $activity)
                        @php 
                            $dotColor = match($activity->type) { 
                                'created','task_completed' => '#10b981', 
                                'status_changed','assigned' => '#3b82f6', 
                                default => '#d97706' 
                            }; 
                        @endphp
                        <div class="crm-timeline-item" data-activity-type="{{ $activity->type }}">
                            <div class="crm-timeline-dot" style="background:{{ $dotColor }};">
                                @if($activity->type === 'status_changed')
                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18"/></svg>
                                @elseif($activity->type === 'created')
                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                @elseif($activity->type === 'assigned')
                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                @else
                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @endif
                            </div>
                            <div class="crm-timeline-body-wrap">
                                <div class="crm-timeline-details">
                                    <span class="crm-timeline-title">
                                        {{ $activity->label ?: ucwords(str_replace('_',' ',$activity->type)) }}
                                        @if($activity->type === 'status_changed')
                                            <span class="crm-badge crm-badge--primary" style="margin-left:4px; padding:0 4px; font-size:0.6rem;">New</span>
                                        @endif
                                    </span>
                                    <span class="crm-timeline-subtitle">{{ $activity->description }}</span>
                                    <span class="crm-timeline-actor">By {{ $activity->user?->name ?? 'System' }}</span>
                                </div>
                                <div class="crm-timeline-time-col">
                                    <span class="crm-timeline-elapsed">{{ $activity->created_at->diffForHumans() }}</span>
                                    <span class="crm-timeline-date">{{ $activity->created_at->format('j M Y, h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 2rem 0; color: var(--crm-text-muted);">
                            <svg style="width:32px; height:32px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <p style="margin-top: 0.5rem; font-size: 0.8rem;">No activities logged yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Content: Notes --}}
            <div id="tab-notes" class="crm-tab-content">
                @can('lead_center.edit')
                <div class="note-composer-container">
                    <div class="note-composer">
                        <div class="note-format-bar">
                            <button type="button" class="note-format-btn" onclick="insertFormat('**')" title="Bold"><strong>B</strong></button>
                            <button type="button" class="note-format-btn" onclick="insertFormat('*')" title="Italic"><em>I</em></button>
                            <button type="button" class="note-format-btn" onclick="insertFormat('`')" title="Code">Code</button>
                            <button type="button" class="note-format-btn" onclick="insertFormat('@')" title="Mention user">@</button>
                        </div>
                        <textarea id="note-content" placeholder="Write a note... Type @ to trigger staff list" onkeyup="checkMentions(event)"></textarea>
                        <div class="note-composer-footer">
                            <div class="note-composer-actions">
                                <label class="note-composer-checkbox">
                                    <input type="checkbox" id="note-pinned">
                                    <span>Pin Note</span>
                                </label>
                                <label class="note-composer-checkbox">
                                    <input type="checkbox" id="note-private">
                                    <span>Internal Only</span>
                                </label>
                            </div>
                            <button type="button" class="crm-btn crm-btn--primary" onclick="addNote({{ $lead->id }})">Save Note</button>
                        </div>
                    </div>
                    
                    {{-- Mentions suggest box --}}
                    <div class="note-mentions-popover" id="mentions-popover">
                        @foreach($staff as $s)
                            <div class="note-mentions-item" onclick="insertMention('{{ $s->name }}')">{{ $s->name }}</div>
                        @endforeach
                    </div>
                </div>
                @endcan

                <div id="notes-list">
                    @forelse($lead->leadNotes->sortByDesc('is_pinned')->sortByDesc('created_at') as $note)
                        <div class="sz-note {{ $note->is_pinned ? 'is-pinned' : '' }}">
                            <div class="sz-note-header">
                                <span class="sz-note-author">
                                    {{ $note->user?->name ?? 'Unknown User' }}
                                    @if($note->is_private)
                                        <span style="font-size:0.65rem; background:#fee2e2; color:#ef4444; padding:2px 6px; border-radius:4px; margin-left:6px; font-weight:600;">Internal</span>
                                    @endif
                                </span>
                                <span class="sz-note-date">
                                    {{ $note->created_at->diffForHumans() }}
                                    @if($note->is_pinned) · <strong style="color:#f59e0b;">Pinned</strong> @endif
                                    @if($note->user_id === auth()->id() || auth()->user()?->hasPermission('super_admin'))
                                        <button onclick="deleteNote({{ $lead->id }}, {{ $note->id }})" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:1.1rem;line-height:0.5;margin-left:8px;vertical-align:middle;">&times;</button>
                                    @endif
                                </span>
                            </div>
                            <div class="sz-note-content">{!! nl2br(e($note->content)) !!}</div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 3rem 0; color: var(--crm-text-muted); border: 1px dashed var(--crm-border); border-radius: 12px;">
                            <svg style="width:32px; height:32px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            <p style="margin-top: 0.5rem; font-size: 0.8rem; margin-bottom: 0;">No notes have been logged for this lead.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Content: Tasks --}}
            <div id="tab-tasks" class="crm-tab-content">
                {{-- Task Completion Progress Bar --}}
                <div class="tasks-progress-container" id="tasks-progress-wrapper">
                    <div class="tasks-progress-header">
                        <span>Checklist Completion</span>
                        <span id="tasks-progress-text">0%</span>
                    </div>
                    <div class="tasks-progress-bar-bg">
                        <div class="tasks-progress-bar-fill" id="tasks-progress-fill"></div>
                    </div>
                </div>

                @can('lead_center.edit')
                <div class="crm-card">
                    <h3 class="crm-card-title" style="margin-bottom: 0.75rem;">Create Follow-up Task</h3>
                    <div class="crm-form-row">
                        <input type="text" id="task-title" class="crm-input" placeholder="Task heading..." style="flex: 2;">
                        <input type="date" id="task-due" class="crm-input" style="flex: 1;">
                    </div>
                    <div class="crm-form-row">
                        <select id="task-type" class="crm-select">
                            <option value="follow_up">Follow up</option>
                            <option value="call">Phone Call</option>
                            <option value="email">Send Email</option>
                            <option value="meeting">Schedule Meeting</option>
                        </select>
                        <select id="task-priority" class="crm-select">
                            <option value="medium">Medium Priority</option>
                            <option value="low">Low Priority</option>
                            <option value="high">High Priority</option>
                            <option value="urgent">Urgent Priority</option>
                        </select>
                        <button type="button" class="crm-btn crm-btn--primary" onclick="addTask({{ $lead->id }})">Create</button>
                    </div>
                </div>
                @endcan

                <div id="tasks-list">
                    @forelse($lead->tasks->sortByDesc('created_at') as $task)
                        @php
                            $isOverdue = $task->due_at && $task->due_at->isPast() && $task->status !== 'completed';
                        @endphp
                        <div class="crm-task-row {{ $task->status === 'completed' ? 'is-completed' : '' }} {{ $isOverdue ? 'is-overdue' : '' }}">
                            <div style="margin-top: 3px;">
                                <input type="checkbox" class="sz-checkbox task-checkbox" {{ $task->status === 'completed' ? 'checked' : '' }} onchange="toggleTask({{ $lead->id }}, {{ $task->id }}, this)">
                            </div>
                            <div class="crm-task-body">
                                <div class="crm-task-title" style="{{ $task->status === 'completed' ? 'text-decoration:line-through; color:var(--crm-text-muted);' : '' }}">
                                    {{ $task->title }}
                                    @if($isOverdue)
                                        <span class="crm-task-overdue-tag">Overdue</span>
                                    @endif
                                </div>
                                <div class="crm-task-meta">
                                    {{ ucfirst(str_replace('_',' ',$task->type)) }} · 
                                    <span style="font-weight:600; color: {{ $task->priority === 'urgent' ? '#ef4444' : ($task->priority === 'high' ? '#f59e0b' : '#64748b') }};">{{ ucfirst($task->priority) }}</span>
                                    @if($task->assignee) · Assigned: {{ $task->assignee->name }}@endif
                                    @if($task->due_at) · Due {{ $task->due_at->format('j M Y') }}@endif
                                </div>
                            </div>
                            <button type="button" onclick="deleteTask({{ $lead->id }}, {{ $task->id }})" style="background:none;border:none;color:var(--crm-text-muted);cursor:pointer;font-size:1.25rem;line-height:0.5;padding:0.25rem;">&times;</button>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 3rem 0; color: var(--crm-text-muted); border: 1px dashed var(--crm-border); border-radius: 12px;">
                            <svg style="width:32px; height:32px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p style="margin-top: 0.5rem; font-size: 0.8rem; margin-bottom: 0;">No tasks currently scheduled.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Content: Communication --}}
            <div id="tab-communication" class="crm-tab-content">
                <div class="crm-card">
                    <div class="crm-card-header" style="border: none; margin-bottom: 0.5rem;">
                        <h3 class="crm-card-title">AI Chat Summary & Communication Logs</h3>
                    </div>
                    
                    <div class="comms-tabs">
                        <button type="button" class="comms-tab-btn active" onclick="switchCommsChannel(event, 'comms-ai-chat')">AI Chat</button>
                        <button type="button" class="comms-tab-btn" onclick="switchCommsChannel(event, 'comms-emails')">Emails</button>
                        <button type="button" class="comms-tab-btn" onclick="switchCommsChannel(event, 'comms-sms')">SMS</button>
                        <button type="button" class="comms-tab-btn" onclick="switchCommsChannel(event, 'comms-calls')">Calls</button>
                    </div>
                    
                    <div class="chat-container">
                        <div id="comms-ai-chat" class="comms-channel active">
                            @if($lead->form_type === 'ai_chat' && $lead->conversation_summary)
                                <div class="chat-bubble client" style="margin-bottom:0.75rem;">
                                    <strong>{{ $displayName }}</strong>
                                    <p style="margin:4px 0 0;">Hi, I would like to inquire about visa pathway and pricing estimates.</p>
                                    <div class="chat-meta">
                                        <span>Client</span>
                                        <span>{{ $lead->created_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                                
                                <div class="chat-bubble assistant">
                                    <strong>SettleANZ AI Agent</strong>
                                    <p style="margin:4px 0 0;">{{ $lead->conversation_summary }}</p>
                                    <div class="chat-meta">
                                        <span>AI Assistant</span>
                                        <span>{{ $lead->created_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @else
                                <div style="text-align: center; padding: 2rem 0; color:var(--crm-text-muted);">
                                    <svg style="width:32px; height:32px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <p style="margin-top: 0.5rem; font-size: 0.8rem; margin-bottom:0;">No AI chat logs found.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div id="comms-emails" class="comms-channel" style="display:none;">
                            @if($lead->email)
                                <div class="chat-bubble assistant" style="margin-bottom:0.75rem; align-self:stretch; max-width:100%;">
                                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid rgba(20,163,148,0.15); padding-bottom:4px; margin-bottom:4px;">
                                        <strong>System Auto-Welcome</strong>
                                        <span style="font-size:0.65rem;">Sent automatically</span>
                                    </div>
                                    <p style="margin:4px 0 0; font-size:0.775rem;">Subject: Welcome to SettleANZ!<br><br>Hi {{ $displayName }}, thank you for submitting your inquiry. An adviser will review your case shortly.</p>
                                    <div class="chat-meta">
                                        <span>Email Server</span>
                                        <span>{{ $lead->created_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @else
                                <div style="text-align: center; padding: 2rem 0; color:var(--crm-text-muted);">
                                    <p style="font-size: 0.8rem; margin:0;">No email logs.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div id="comms-sms" class="comms-channel" style="display:none;">
                            <div style="text-align: center; padding: 2rem 0; color:var(--crm-text-muted);">
                                <svg style="width:24px; height:24px; color:var(--crm-text-muted); margin:0 auto 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <p style="font-size: 0.8rem; margin:0;">No SMS history.</p>
                            </div>
                        </div>
                        
                        <div id="comms-calls" class="comms-channel" style="display:none;">
                            <div style="text-align: center; padding: 2rem 0; color:var(--crm-text-muted);">
                                <svg style="width:24px; height:24px; color:var(--crm-text-muted); margin:0 auto 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <p style="font-size: 0.8rem; margin:0;">No call recordings found.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content: Files list --}}
            <div id="tab-files" class="crm-tab-content">
                <div class="crm-card">
                    <h3 class="crm-card-title" style="margin-bottom: 0.75rem;">Documents Attached</h3>
                    <div id="tab-files-list">
                        @forelse($lead->files as $file)
                            <div class="crm-file-card" id="tab-file-item-{{ $file->id }}" style="display:flex; align-items:center; justify-content:space-between; padding:0.75rem; border:1px solid var(--crm-border); border-radius:8px; margin-bottom:0.5rem;">
                                <div style="display:flex; align-items:center; gap:0.5rem;">
                                    <span>📄</span>
                                    <div>
                                        <div style="font-size:0.8rem; font-weight:600;">{{ $file->original_filename }}</div>
                                        <div style="font-size:0.65rem; color:var(--crm-text-muted);">{{ $file->size_for_humans ?? $file->size . ' B' }} · by {{ $file->user?->name ?? 'System' }}</div>
                                    </div>
                                </div>
                                <div style="display:flex; gap:0.35rem;">
                                    <a href="/storage/{{ $file->path }}" download class="crm-btn crm-btn--secondary" style="padding:0.25rem 0.5rem;">Download</a>
                                    @can('lead_center.edit')
                                    <button type="button" class="crm-btn crm-btn--danger" onclick="deleteFile({{ $lead->id }}, {{ $file->id }})" style="padding:0.25rem 0.5rem;">Delete</button>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <p style="font-size:0.75rem; color:var(--crm-text-muted);">No documents attached.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Card 2: Submission details --}}
            <div class="crm-card">
                <div class="crm-card-header">
                    <h3 class="crm-card-title">Submission Details</h3>
                </div>
                <div class="crm-submission-details-grid">
                    <div class="crm-kv">
                        <div class="crm-kv-row"><span class="crm-kv-label">Lead Source</span><span class="crm-kv-value">{{ $lead->lead_source_label }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">Form Name</span><span class="crm-kv-value">{{ $lead->form_name ?: '-' }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">Website Page</span><span class="crm-kv-value" style="font-size:0.75rem; max-width: 140px; overflow: hidden; text-overflow: ellipsis;" title="{{ $lead->source_page }}">{{ $lead->website_page_label }}</span></div>
                    </div>
                    <div class="crm-kv">
                        <div class="crm-kv-row"><span class="crm-kv-label">UTM Source</span><span class="crm-kv-value">{{ $lead->utm_source ?: '-' }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">UTM Medium</span><span class="crm-kv-value">{{ $lead->utm_medium ?: '-' }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">UTM Campaign</span><span class="crm-kv-value">{{ $lead->utm_campaign ?: '-' }}</span></div>
                        <div class="crm-kv-row"><span class="crm-kv-label">Consent No</span><span class="crm-kv-value">{{ $lead->consent ? 'Yes' : 'No' }}</span></div>
                    </div>
                </div>
            </div>
            
            {{-- Subject and Message if filled --}}
            @if(filled($subject) || filled($message))
            <div class="crm-card">
                @if(filled($subject))
                    <div class="crm-card-header" style="border:none; margin-bottom:0.5rem;"><h3 class="crm-card-title">Subject</h3></div>
                    <p style="font-size: 0.9rem; font-weight: 700; color: var(--crm-text); margin-top: 0; margin-bottom: 1rem;">{{ $subject }}</p>
                @endif
                @if(filled($message))
                    <div class="crm-card-header" style="border:none; margin-bottom:0.5rem;"><h3 class="crm-card-title">User Message</h3></div>
                    <div style="font-size: 0.825rem; color: #334155; line-height: 1.5; background: #fafafa; border: 1px solid var(--crm-border); padding: 1rem; border-radius: 8px; white-space: pre-wrap; margin:0;">{{ $message }}</div>
                @endif
            </div>
            @endif
        </div>

        {{-- Column 3: Right Sidebar (Sticky Attributes) --}}
        <div class="crm-col-right" style="position: sticky; top: 90px;">
            {{-- Health Card --}}
            <div class="crm-card">
                <div class="crm-card-header">
                    <h3 class="crm-card-title">Lead Health & Status</h3>
                </div>
                <div class="crm-health-card">
                    @php 
                        $scoreColor = $lead->lead_score >= 70 ? '#10b981' : ($lead->lead_score >= 40 ? '#f59e0b' : '#94a3b8'); 
                        $radialPercent = $lead->lead_score * 3.6;
                    @endphp
                    <div class="crm-score-ring" style="background: conic-gradient({{ $scoreColor }} {{ $radialPercent }}deg, #e2e8f0 {{ $radialPercent }}deg);">
                        <div class="crm-score-ring-inner">{{ $lead->lead_score }}</div>
                    </div>
                    <div class="crm-health-status-row">
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.75rem;">
                            <span style="color:var(--crm-text-muted);">Priority</span>
                            <span style="font-weight:700; display:flex; align-items:center; gap:4px;">
                                <span style="width:8px; height:8px; border-radius:50%; background:#f59e0b;"></span>
                                <span>{{ ucfirst($lead->priority) }}</span>
                            </span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.75rem;">
                            <span style="color:var(--crm-text-muted);">Status</span>
                            <div class="crm-custom-select" id="custom-select-status" style="width: 120px;">
                                <div class="crm-custom-select-trigger" onclick="toggleCustomSelect(event, 'custom-select-status')">
                                    <span class="crm-custom-select-label">{{ ucfirst(str_replace('_',' ',$lead->status)) }}</span>
                                    <svg style="width:10px; height:10px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <div class="crm-custom-select-options">
                                    @foreach(array_keys($statusColors) as $s)
                                        <div class="crm-custom-select-option {{ $lead->status === $s ? 'selected' : '' }}" onclick="selectStatusOption('{{ $s }}')">
                                            {{ ucfirst(str_replace('_',' ',$s)) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="crm-btn crm-btn--secondary" onclick="recalcScore({{ $lead->id }})" style="width:100%; justify-content:center;">Recalculate Score</button>
            </div>

            {{-- Staff Assignment --}}
            <div class="crm-card">
                <div class="crm-card-header">
                    <h3 class="crm-card-title">Staff Assignment</h3>
                </div>
                <div style="font-size:0.75rem; color:var(--crm-text-muted);">Owner</div>
                @can('lead_center.edit')
                <div class="crm-sidebar-row">
                    <div class="crm-custom-select" id="custom-select-staff" style="flex: 1;">
                        <div class="crm-custom-select-trigger" onclick="toggleCustomSelect(event, 'custom-select-staff')">
                            <span class="crm-custom-select-label">{{ $lead->assignedStaff?->name ?? 'Unassigned' }}</span>
                            <svg style="width:10px; height:10px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <div class="crm-custom-select-options">
                            <div class="crm-custom-select-option {{ !$lead->assigned_to ? 'selected' : '' }}" onclick="selectStaffOption('')">
                                Unassigned
                            </div>
                            @foreach($staff as $s)
                                <div class="crm-custom-select-option {{ $lead->assigned_to == $s->id ? 'selected' : '' }}" onclick="selectStaffOption('{{ $s->id }}')">
                                    {{ $s->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="crm-btn crm-btn--secondary" style="padding:0.45rem;" title="Search Staff">
                        <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </button>
                </div>
                @else
                <div style="font-weight:600; color:#0f172a; margin-top:2px;">{{ $lead->assignedStaff?->name ?? 'Unassigned' }}</div>
                @endcan
            </div>

            {{-- Tags --}}
            <div class="crm-card">
                <div class="crm-card-header">
                    <h3 class="crm-card-title">Tags</h3>
                </div>
                <div id="lead-tags" class="crm-tags" style="margin-bottom: 0.75rem; font-size:0.75rem;">
                    @forelse($lead->tags as $tag)
                        <span class="crm-tag" style="background:{{ $tag->color }}22; color:{{ $tag->color }}; padding: 0.15rem 0.45rem; border-radius:4px; margin-right:4px;">
                            {{ $tag->name }}
                            @can('lead_center.edit')
                            <span class="crm-tag-remove" onclick="detachTag({{ $lead->id }}, {{ $tag->id }})" style="cursor:pointer; margin-left:3px;">&times;</span>
                            @endcan
                        </span>
                    @empty
                        <span style="color:var(--crm-text-muted);">No tags attached</span>
                    @endforelse
                </div>
                @can('lead_center.edit')
                <div class="crm-sidebar-row">
                    <div class="crm-custom-select" id="custom-select-tag" style="flex: 1;">
                        <div class="crm-custom-select-trigger" onclick="toggleCustomSelect(event, 'custom-select-tag')">
                            <span class="crm-custom-select-label" id="custom-select-tag-placeholder">Add a tag...</span>
                            <svg style="width:10px; height:10px; color:var(--crm-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        <div class="crm-custom-select-options">
                            @foreach($tags as $tag)
                                @if(!$lead->tags->contains($tag->id))
                                    <div class="crm-custom-select-option" onclick="selectTagOption('{{ $tag->id }}', '{{ $tag->name }}')">
                                        {{ $tag->name }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" id="tag-select-value" value="">
                    <button type="button" class="crm-btn crm-btn--secondary" onclick="attachTag({{ $lead->id }})" style="padding:0.45rem;">+</button>
                </div>
                @endcan
            </div>

            {{-- Attachments Section --}}
            <div class="crm-card">
                <div class="crm-card-header">
                    <h3 class="crm-card-title">Attachments</h3>
                </div>
                <div id="files-list" style="margin-bottom: 0.75rem; font-size:0.75rem; color:var(--crm-text-muted);">
                    @forelse($lead->files->take(3) as $file)
                        <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                            <span style="font-weight:600; color:#0f172a; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:200px;">{{ $file->original_filename }}</span>
                            <button type="button" onclick="deleteFile({{ $lead->id }}, {{ $file->id }})" style="background:none; border:none; color:#ef4444; cursor:pointer;">&times;</button>
                        </div>
                    @empty
                        <span>No files uploaded</span>
                    @endforelse
                </div>

                @can('lead_center.edit')
                <div class="crm-dropzone" id="file-dropzone">
                    <svg style="width:20px; height:20px; color:var(--crm-text-muted); margin: 0 auto 0.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p style="font-weight:600; font-size:0.725rem; color:#0f172a;">Drag &amp; drop files here</p>
                    <p style="font-size:0.65rem;">or click to upload</p>
                    <p style="font-size:0.6rem; color:var(--crm-text-muted); margin-top:2px;">PDF, DOCX, XLS, CSV, PNG, JPG (Max 10MB)</p>
                    <input type="file" id="file-upload-input" onchange="handleFileUpload(this)">
                </div>
                <div class="crm-upload-progress-container" id="upload-progress-wrapper">
                    <div style="display:flex; justify-content:space-between; font-size:0.65rem; color:var(--crm-text-muted); margin-bottom:2px;">
                        <span>Uploading...</span>
                        <span id="upload-percent">0%</span>
                    </div>
                    <div class="crm-upload-progress-bar-bg">
                        <div class="crm-upload-progress-bar-fill" id="upload-percent-fill"></div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>

<script>
// CSRF Token Helper
const t = () => document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

// Generic API caller
async function api(path, options = {}) {
    const response = await fetch(path, {
        headers: {
            'X-CSRF-TOKEN': t(),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        ...options
    });
    return response.json();
}

// Toggle custom dropdown select panels
function toggleCustomSelect(event, id) {
    event.stopPropagation();
    
    // Close other open selects
    document.querySelectorAll('.crm-custom-select').forEach(el => {
        if (el.id !== id) {
            el.classList.remove('active');
        }
    });
    
    const dropdown = document.getElementById(id);
    if (dropdown) {
        dropdown.classList.toggle('active');
    }
}

// Options selection handlers
function selectStatusOption(val) {
    updateStatus({{ $lead->id }}, val);
}

function selectStaffOption(val) {
    assignStaff({{ $lead->id }}, val);
}

function selectTagOption(id, name) {
    document.getElementById('tag-select-value').value = id;
    document.getElementById('custom-select-tag-placeholder').innerText = name;
}

// Switch tabs inside workspace
function switchTab(event, tabId) {
    document.querySelectorAll('.crm-tab-link').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.crm-tab-content').forEach(content => content.classList.remove('active'));
    
    event.currentTarget.classList.add('active');
    document.getElementById(tabId).classList.add('active');
}

// Switch communication subchannels
function switchCommsChannel(event, channelId) {
    document.querySelectorAll('.comms-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.comms-channel').forEach(c => c.style.display = 'none');
    
    event.currentTarget.classList.add('active');
    document.getElementById(channelId).style.display = 'block';
}

// Timeline filtering
function filterTimeline(type, btn) {
    document.querySelectorAll('.crm-timeline-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    const items = document.querySelectorAll('.crm-timeline-item');
    items.forEach(item => {
        if (type === 'all') {
            item.style.display = 'block';
        } else {
            const itemType = item.getAttribute('data-activity-type');
            if (itemType === type) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });
}

// Toggle More Actions Dropdown
function toggleMoreActionsDropdown(event) {
    event.stopPropagation();
    const menu = document.getElementById('more-actions-menu');
    if (menu) {
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }
}

// Star / Favorite local storage tracker
function initFavoriteButton(leadId) {
    const btn = document.getElementById('favorite-btn');
    if (!btn) return;
    
    let favorites = [];
    try {
        favorites = JSON.parse(localStorage.getItem('settleanz_favorite_leads') || '[]');
    } catch(e) {}
    
    if (favorites.includes(leadId)) {
        btn.classList.add('is-active');
    }
}

function toggleFavorite(leadId) {
    const btn = document.getElementById('favorite-btn');
    if (!btn) return;
    
    let favorites = [];
    try {
        favorites = JSON.parse(localStorage.getItem('settleanz_favorite_leads') || '[]');
    } catch(e) {}
    
    if (favorites.includes(leadId)) {
        favorites = favorites.filter(id => id !== leadId);
        btn.classList.remove('is-active');
    } else {
        favorites.push(leadId);
        btn.classList.add('is-active');
    }
    localStorage.setItem('settleanz_favorite_leads', JSON.stringify(favorites));
}

// Rich note formatting text helpers
function insertFormat(tag) {
    const area = document.getElementById('note-content');
    if (!area) return;
    
    const start = area.selectionStart;
    const end = area.selectionEnd;
    const text = area.value;
    const selectedText = text.substring(start, end);
    
    let replacement = '';
    if (tag === '@') {
        replacement = '@';
        showMentionsPopover();
    } else {
        replacement = tag + selectedText + tag;
    }
    
    area.value = text.substring(0, start) + replacement + text.substring(end);
    area.focus();
    area.selectionStart = start + tag.length;
    area.selectionEnd = start + tag.length + selectedText.length;
}

function checkMentions(event) {
    const area = document.getElementById('note-content');
    if (!area) return;
    
    const text = area.value;
    const cursor = area.selectionStart;
    const beforeCursor = text.substring(0, cursor);
    
    if (beforeCursor.endsWith('@')) {
        showMentionsPopover();
    } else if (!beforeCursor.includes('@')) {
        hideMentionsPopover();
    }
}

function showMentionsPopover() {
    const popover = document.getElementById('mentions-popover');
    if (popover) popover.style.display = 'block';
}

function hideMentionsPopover() {
    const popover = document.getElementById('mentions-popover');
    if (popover) popover.style.display = 'none';
}

function insertMention(name) {
    const area = document.getElementById('note-content');
    if (!area) return;
    
    const text = area.value;
    const cursor = area.selectionStart;
    const beforeCursor = text.substring(0, cursor);
    
    // Find last @ and replace it
    const lastAtIdx = beforeCursor.lastIndexOf('@');
    if (lastAtIdx !== -1) {
        area.value = text.substring(0, lastAtIdx) + '@' + name + ' ' + text.substring(cursor);
    }
    
    hideMentionsPopover();
    area.focus();
}

// Tasks checklist progress calculations
function calculateTasksProgress() {
    const total = document.querySelectorAll('.task-checkbox').length;
    const completed = document.querySelectorAll('.task-checkbox:checked').length;
    
    const progressFill = document.getElementById('tasks-progress-fill');
    const progressText = document.getElementById('tasks-progress-text');
    
    if (progressFill && progressText) {
        const percent = total > 0 ? Math.round((completed / total) * 100) : 0;
        progressFill.style.width = percent + '%';
        progressText.innerText = percent + '%';
    }
}

// Drag & drop dropzone handlers
function initDragAndDrop() {
    const zone = document.getElementById('file-dropzone');
    const input = document.getElementById('file-upload-input');
    if (!zone || !input) return;
    
    zone.addEventListener('click', () => input.click());
    
    zone.addEventListener('dragover', (e) => {
        e.preventDefault();
        zone.style.borderColor = 'var(--crm-primary)';
        zone.style.backgroundColor = 'var(--crm-primary-light)';
    });
    
    zone.addEventListener('dragleave', () => {
        zone.style.borderColor = 'var(--crm-border)';
        zone.style.backgroundColor = '#fafafa';
    });
    
    zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.style.borderColor = 'var(--crm-border)';
        zone.style.backgroundColor = '#fafafa';
        
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            handleFileUpload(input);
        }
    });
}

// Initialize Client-side pagination and handlers on DOM load
document.addEventListener('DOMContentLoaded', () => {
    const leadId = {{ $lead->id }};
    initFavoriteButton(leadId);
    calculateTasksProgress();
    initDragAndDrop();
    
    // Close dropdown on click outside
    document.addEventListener('click', () => {
        const menu = document.getElementById('more-actions-menu');
        if (menu) menu.style.display = 'none';
        
        document.querySelectorAll('.crm-custom-select').forEach(el => {
            el.classList.remove('active');
        });
    });
    
    const storedIds = sessionStorage.getItem('settleanz_lead_ids');
    if (storedIds) {
        try {
            const ids = JSON.parse(storedIds).map(Number);
            const index = ids.indexOf(leadId);
            const prevBtn = document.getElementById('prev-lead-btn');
            const nextBtn = document.getElementById('next-lead-btn');
            
            if (prevBtn && nextBtn) {
                if (index > 0) {
                    prevBtn.onclick = () => window.location.href = `/admin/leads/${ids[index - 1]}`;
                    prevBtn.removeAttribute('disabled');
                    prevBtn.classList.remove('disabled');
                } else {
                    prevBtn.setAttribute('disabled', 'true');
                    prevBtn.classList.add('disabled');
                }
                
                if (index >= 0 && index < ids.length - 1) {
                    nextBtn.onclick = () => window.location.href = `/admin/leads/${ids[index + 1]}`;
                    nextBtn.removeAttribute('disabled');
                    nextBtn.classList.remove('disabled');
                } else {
                    nextBtn.setAttribute('disabled', 'true');
                    nextBtn.classList.add('disabled');
                }
                
                // Bind keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                    if (e.key === 'ArrowLeft' && index > 0) {
                        window.location.href = `/admin/leads/${ids[index - 1]}`;
                    } else if (e.key === 'ArrowRight' && index >= 0 && index < ids.length - 1) {
                        window.location.href = `/admin/leads/${ids[index + 1]}`;
                    }
                });
            }
        } catch (e) {
            console.error('Error initializing client-side lead pagination:', e);
        }
    }
});

// Operations AJAX handlers
async function addNote(id) {
    const noteArea = document.getElementById('note-content');
    const notePinned = document.getElementById('note-pinned');
    const notePrivate = document.getElementById('note-private');
    
    if (!noteArea.value.trim()) return;
    noteArea.disabled = true;
    
    const r = await api('/admin/leads/' + id + '/notes', {
        method: 'POST',
        body: JSON.stringify({
            content: noteArea.value,
            is_pinned: notePinned?.checked || false,
            is_private: notePrivate?.checked || false
        })
    });
    
    noteArea.disabled = false;
    if (r.success) {
        noteArea.value = '';
        location.reload();
    }
}

async function deleteNote(lid, nid) {
    const c = await adminModal.confirm({
        title: 'Delete note?',
        message: 'Are you sure you want to permanently delete this note?',
        confirmText: 'Delete',
        isDangerous: true
    });
    if (!c) return;
    const r = await api('/admin/leads/' + lid + '/notes/' + nid, { method: 'DELETE' });
    if (r.success) location.reload();
}

async function addTask(id) {
    const title = document.getElementById('task-title');
    const due = document.getElementById('task-due');
    const type = document.getElementById('task-type');
    const priority = document.getElementById('task-priority');
    
    if (!title.value.trim()) return;
    title.disabled = true;
    
    const r = await api('/admin/leads/' + id + '/tasks', {
        method: 'POST',
        body: JSON.stringify({
            title: title.value,
            due_at: due.value || null,
            type: type.value,
            priority: priority.value
        })
    });
    
    title.disabled = false;
    if (r.success) {
        title.value = '';
        location.reload();
    }
}

async function toggleTask(lid, tid, cb) {
    const row = cb.closest('.crm-task-row');
    if (cb.checked) {
        row.classList.add('is-completed');
        row.querySelector('.crm-task-title').style.textDecoration = 'line-through';
    } else {
        row.classList.remove('is-completed');
        row.querySelector('.crm-task-title').style.textDecoration = 'none';
    }
    
    calculateTasksProgress();
    
    await api('/admin/leads/' + lid + '/tasks/' + tid, {
        method: 'PATCH',
        body: JSON.stringify({
            status: cb.checked ? 'completed' : 'pending'
        })
    });
}

async function deleteTask(lid, tid) {
    const c = await adminModal.confirm({
        title: 'Delete task?',
        message: 'Are you sure you want to delete this follow-up task?',
        confirmText: 'Delete',
        isDangerous: true
    });
    if (!c) return;
    const r = await api('/admin/leads/' + lid + '/tasks/' + tid, { method: 'DELETE' });
    if (r.success) location.reload();
}

async function attachTag(id) {
    const s = document.getElementById('tag-select');
    if (!s.value) return;
    const r = await api('/admin/leads/' + id + '/tags/attach', {
        method: 'POST',
        body: JSON.stringify({ tag_id: s.value })
    });
    if (r.success) location.reload();
}

async function detachTag(lid, tid) {
    const r = await api('/admin/leads/' + lid + '/tags/' + tid, { method: 'DELETE' });
    if (r.success) location.reload();
}

async function assignStaff(id, val = null) {
    const s = val !== null ? val : document.getElementById('assign-staff').value;
    await api('/admin/leads/' + id, {
        method: 'PUT',
        body: JSON.stringify({ assigned_to: s || null })
    });
    location.reload();
}

async function updateStatus(id, val = null) {
    const s = val !== null ? val : document.getElementById('lead-status').value;
    await api('/admin/leads/' + id + '/status', {
        method: 'PATCH',
        body: JSON.stringify({ status: s })
    });
    location.reload();
}

async function recalcScore(id) {
    const r = await api('/admin/leads/' + id + '/recalculate-score', { method: 'POST' });
    if (r.success) location.reload();
}

async function deleteFile(lid, fid) {
    const c = await adminModal.confirm({
        title: 'Delete file?',
        message: 'Are you sure you want to permanently delete this file attachment?',
        confirmText: 'Delete',
        isDangerous: true
    });
    if (!c) return;
    
    const r = await api('/admin/leads/' + lid + '/files/' + fid, { method: 'DELETE' });
    if (r.success) {
        location.reload();
    }
}

// File AJAX Upload Handler with Simulated upload animation
async function handleFileUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('file', file);
    
    const dropzone = document.getElementById('file-dropzone');
    const wrapper = document.getElementById('upload-progress-wrapper');
    const fill = document.getElementById('upload-percent-fill');
    const percent = document.getElementById('upload-percent');
    
    dropzone.style.display = 'none';
    wrapper.style.display = 'block';
    
    // Simulate loading progress bar
    let pct = 0;
    const interval = setInterval(() => {
        if (pct < 90) {
            pct += Math.floor(Math.random() * 15) + 5;
            if (pct > 90) pct = 90;
            fill.style.width = pct + '%';
            percent.innerText = pct + '%';
        }
    }, 120);
    
    try {
        const response = await fetch('/admin/leads/{{ $lead->id }}/files', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': t(),
                'Accept': 'application/json'
            },
            body: formData
        });
        const data = await response.json();
        
        clearInterval(interval);
        fill.style.width = '100%';
        percent.innerText = '100%';
        
        if (data.success) {
            setTimeout(() => location.reload(), 300);
        } else {
            alert('Upload failed: ' + (data.message || 'Verification error'));
            dropzone.style.display = 'block';
            wrapper.style.display = 'none';
        }
    } catch (e) {
        clearInterval(interval);
        alert('Upload failed: ' + e.message);
        dropzone.style.display = 'block';
        wrapper.style.display = 'none';
    }
}
</script>
@endsection
