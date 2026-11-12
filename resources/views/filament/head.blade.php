{{-- Responsive link to Filament admin theme stylesheet (supports subfolders like XAMPP and custom domains) --}}
<link rel="stylesheet" href="{{ asset('css/filament/admin/theme.css') }}?v={{ @filemtime(public_path('css/filament/admin/theme.css')) ?: 1 }}" />

{{-- Auto-reset stale Alpine collapsed groups so menus are always active, non-collapsed, and never throw null errors --}}
<script>
    try {
        localStorage.setItem('filament:sidebar:collapsed_groups', '[]');
    } catch (e) {}

    document.addEventListener('alpine:init', function () {
        try {
            localStorage.setItem('filament:sidebar:collapsed_groups', '[]');
            if (window.Alpine && window.Alpine.store) {
                // Hook into sidebar store if already registered or when registered
                const origStore = window.Alpine.store;
                window.Alpine.store = function (name, value) {
                    if (name === 'sidebar' && value && typeof value === 'object') {
                        value.collapsedGroups = [];
                        value.groupIsCollapsed = function () { return false; };
                    }
                    return origStore.apply(this, arguments);
                };

                const existingSidebar = window.Alpine.store('sidebar');
                if (existingSidebar) {
                    existingSidebar.collapsedGroups = [];
                    existingSidebar.groupIsCollapsed = function () { return false; };
                }
            }
        } catch (e) {}
    });

    document.addEventListener('DOMContentLoaded', function () {
        try {
            if (window.Alpine && window.Alpine.store && window.Alpine.store('sidebar')) {
                const store = window.Alpine.store('sidebar');
                store.collapsedGroups = [];
                store.groupIsCollapsed = function () { return false; };
            }
        } catch (e) {}
    });
</script>

{{-- Critical styles guaranteeing desktop grid layout and dark navy corporate sidebar --}}
<style>
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
