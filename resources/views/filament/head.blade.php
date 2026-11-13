{{-- Responsive link to Filament admin theme stylesheet (supports subfolders like XAMPP and custom domains) --}}
@php
    $themeCssUrl = \App\Support\FilamentThemeResolver::resolveAssetUrl('css/filament/admin/theme.css');
@endphp
<link rel="stylesheet" href="{{ $themeCssUrl }}?v={{ @filemtime(public_path('css/filament/admin/theme.css')) ?: 1 }}" />

{{-- Critical styles guaranteeing desktop grid layout, dark navy corporate sidebar, and defensive layout constraints --}}
<style>
    /* Alpine Cloak - Scoped properly so desktop sidebar is NEVER hidden */
    [x-cloak],
    [x-cloak=''],
    [x-cloak='x-cloak'],
    [x-cloak='1'] {
        display: none !important;
    }

    @media (max-width: 1023px) {
        [x-cloak='-lg'],
        [x-cloak='-md'],
        [x-cloak='-sm'] {
            display: none !important;
        }
    }

    :root {
        --cms-sidebar-bg: #121E31;
        --cms-sidebar-border: rgba(255, 255, 255, 0.08);
        --cms-sidebar-item-hover: rgba(255, 255, 255, 0.06);
        --cms-sidebar-item-active-bg: #1E2F48;
        --cms-sidebar-item-active-border: rgba(237, 184, 86, 0.4);
        --cms-sidebar-text: #cbd5e1;
        --cms-sidebar-text-active: #EDB856;
        --cms-content-bg: #EFF1F7;
        --cms-accent-gold: #EDB856;
        --cms-navy-dark: #122A44;
    }

    body {
        font-family: 'Plus Jakarta Sans', Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        background-color: var(--cms-content-bg) !important;
    }

    /* Screen reader & Skip to content defensive constraints */
    .fi-skip-link,
    .fi-skip-to-content,
    .fi-sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    /* Defensive constraints against unstyled giant icons and avatars */
    .fi-tenant-avatar,
    .fi-avatar,
    .fi-avatar img,
    .fi-tenant-menu-trigger img {
        max-width: 2.25rem !important;
        max-height: 2.25rem !important;
        width: 2.25rem !important;
        height: 2.25rem !important;
        object-fit: contain !important;
    }

    .fi-tenant-menu-trigger svg,
    .fi-icon-btn svg,
    .fi-topbar svg,
    .fi-sidebar svg {
        max-width: 1.5rem !important;
        max-height: 1.5rem !important;
    }

    /* Persistent Luxury Corporate Sidebar */
    .fi-sidebar,
    #fi-main-sidebar,
    .fi-sidebar-header-ctn,
    .fi-sidebar-header,
    .fi-sidebar-nav,
    .fi-sidebar-nav-groups {
        background-color: #121E31 !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    #fi-main-sidebar {
        min-height: 100vh !important;
        width: 17.5rem !important;
    }

    .fi-sidebar-header {
        background-color: #121E31 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        padding: 1.25rem 1rem !important;
    }

    /* Tenant switcher & Avatar constraints */
    .fi-tenant-menu {
        width: 100% !important;
        margin-top: 0.5rem !important;
    }

    .fi-tenant-menu-trigger {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.625rem 0.875rem !important;
        background-color: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 0.75rem !important;
        transition: all 0.15s ease-in-out !important;
        cursor: pointer !important;
        box-sizing: border-box !important;
    }

    .fi-tenant-menu-trigger:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(237, 184, 86, 0.3) !important;
    }

    .fi-tenant-menu-trigger img,
    .fi-tenant-avatar,
    .fi-avatar {
        width: 2.25rem !important;
        height: 2.25rem !important;
        max-width: 2.25rem !important;
        max-height: 2.25rem !important;
        border-radius: 0.5rem !important;
        object-fit: contain !important;
        background-color: rgba(255, 255, 255, 0.06) !important;
        padding: 0.25rem !important;
        flex-shrink: 0 !important;
        box-sizing: border-box !important;
    }

    .fi-tenant-menu-trigger-text {
        display: flex !important;
        flex-direction: column !important;
        min-width: 0 !important;
        text-align: left !important;
        flex: 1 1 auto !important;
        overflow: hidden !important;
    }

    .fi-tenant-menu-trigger-tenant-name {
        display: flex !important;
        align-items: center !important;
        gap: 0.375rem !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        color: #ffffff !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .fi-tenant-menu-trigger svg,
    .fi-sidebar-item-btn svg,
    .fi-icon-btn svg,
    .fi-topbar svg,
    .fi-icon {
        width: 1.25rem !important;
        height: 1.25rem !important;
        max-width: 1.25rem !important;
        max-height: 1.25rem !important;
        flex-shrink: 0 !important;
    }

    .fi-tenant-menu-trigger svg {
        color: #94a3b8 !important;
        margin-left: auto !important;
    }

    /* Tenant dropdown list styling */
    .fi-dropdown-panel {
        background-color: #121E31 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.3) !important;
        padding: 0.375rem !important;
    }

    .fi-dropdown-list-item {
        border-radius: 0.5rem !important;
        padding: 0.5rem 0.75rem !important;
        color: #cbd5e1 !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-dropdown-list-item:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #EDB856 !important;
    }

    .fi-dropdown-list-item-image {
        width: 1.5rem !important;
        height: 1.5rem !important;
        max-width: 1.5rem !important;
        max-height: 1.5rem !important;
        background-size: contain !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        border-radius: 0.375rem !important;
        flex-shrink: 0 !important;
    }

    /* Clean Corporate Static Navigation Groups */
    .fi-sidebar-group {
        margin-top: 0.75rem !important;
        margin-bottom: 0.25rem !important;
    }

    .fi-sidebar-group-label {
        color: #64748b !important;
        font-size: 0.6875rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
        padding: 0.375rem 0.75rem !important;
        display: block !important;
    }

    .fi-sidebar-group-items {
        display: flex !important;
        flex-direction: column !important;
        gap: 0.125rem !important;
        height: auto !important;
        overflow: visible !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .fi-sidebar-group-btn:hover svg,
    .fi-sidebar-group-btn:hover .fi-sidebar-group-collapse-button {
        color: #EDB856 !important;
    }

    /* Sidebar Navigation Items - High Contrast & Crisp */
    .fi-sidebar-item {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .fi-sidebar-item-btn {
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-sidebar-item-label,
    .fi-sidebar-item-btn .fi-sidebar-item-label,
    .fi-sidebar-item-btn > span {
        color: #cbd5e1 !important;
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .fi-sidebar-item-icon,
    .fi-sidebar-item-btn .fi-sidebar-item-icon,
    .fi-sidebar-item-btn > svg,
    .fi-sidebar-item-btn .fi-icon {
        color: #94a3b8 !important;
        width: 1.25rem !important;
        height: 1.25rem !important;
        flex-shrink: 0 !important;
        display: inline-block !important;
        visibility: visible !important;
    }

    .fi-sidebar-item-btn:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
    }

    .fi-sidebar-item-btn:hover .fi-sidebar-item-label,
    .fi-sidebar-item-btn:hover > span {
        color: #ffffff !important;
    }

    .fi-sidebar-item-btn:hover .fi-sidebar-item-icon,
    .fi-sidebar-item-btn:hover > svg,
    .fi-sidebar-item-btn:hover .fi-icon {
        color: #EDB856 !important;
    }

    /* Active Navigation Item */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background-color: #1E2F48 !important;
        border: 1px solid rgba(237, 184, 86, 0.5) !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label,
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > span {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon,
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > svg,
    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-icon {
        color: #EDB856 !important;
    }

    /* Clean Crisp Topbar */
    .fi-topbar {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02) !important;
    }

    .fi-main {
        background-color: var(--cms-content-bg) !important;
        padding: 1.5rem 2rem 3rem 2rem !important;
    }

    /* Robust Rock-Solid 12-Column Dashboard Grid Layout (Filament v5 Schema Components) */
    .fi-grid {
        display: grid !important;
        grid-template-columns: var(--cols-default, repeat(1, minmax(0, 1fr))) !important;
        gap: 1.5rem !important;
        width: 100% !important;
    }

    .fi-grid-col {
        grid-column: var(--col-span-default, span 1 / span 1) !important;
        min-width: 0 !important;
        width: 100% !important;
    }

    .fi-sc-component {
        width: 100% !important;
        min-width: 0 !important;
    }

    @media (min-width: 640px) {
        .sm\:fi-grid-cols {
            grid-template-columns: var(--cols-sm, var(--cols-default)) !important;
        }
        .sm\:fi-grid-col-span {
            grid-column: var(--col-span-sm, var(--col-span-default)) !important;
        }
    }

    @media (min-width: 768px) {
        .md\:fi-grid-cols {
            grid-template-columns: var(--cols-md, var(--cols-sm, var(--cols-default))) !important;
        }
        .md\:fi-grid-col-span {
            grid-column: var(--col-span-md, var(--col-span-sm, var(--col-span-default))) !important;
        }
    }

    @media (min-width: 1024px) {
        .fi-grid.lg\:fi-grid-cols,
        .lg\:fi-grid-cols {
            grid-template-columns: var(--cols-lg, repeat(12, minmax(0, 1fr))) !important;
        }

        .fi-grid-col.lg\:fi-grid-col-span,
        .lg\:fi-grid-col-span {
            grid-column: var(--col-span-lg, var(--col-span-default)) !important;
        }
    }

    @media (min-width: 1280px) {
        .xl\:fi-grid-cols {
            grid-template-columns: var(--cols-xl, var(--cols-lg, var(--cols-default))) !important;
        }
        .xl\:fi-grid-col-span {
            grid-column: var(--col-span-xl, var(--col-span-lg, var(--col-span-default))) !important;
        }
    }
</style>
