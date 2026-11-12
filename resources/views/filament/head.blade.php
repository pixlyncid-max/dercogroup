{{-- Root-relative link to Filament admin theme stylesheet (immune to mixed-content & APP_URL localhost issues) --}}
<link rel="stylesheet" href="/css/filament/admin/theme.css?v={{ @filemtime(public_path('css/filament/admin/theme.css')) ?: 1 }}" />

{{-- Critical styles guaranteeing desktop grid layout and dark navy corporate sidebar --}}
<style>
    :root {
        --cms-sidebar-bg: #121E31;
        --cms-sidebar-border: rgba(255, 255, 255, 0.08);
        --cms-sidebar-item-hover: rgba(255, 255, 255, 0.06);
        --cms-sidebar-item-active-bg: #1E2F48;
        --cms-sidebar-item-active-border: rgba(237, 184, 86, 0.4);
        --cms-sidebar-text: #94a3b8;
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
    aside.fi-sidebar {
        background-color: var(--cms-sidebar-bg) !important;
        border-right: 1px solid var(--cms-sidebar-border) !important;
        min-height: 100vh !important;
    }

    .fi-sidebar-header {
        background-color: var(--cms-sidebar-bg) !important;
        border-bottom: 1px solid var(--cms-sidebar-border) !important;
        padding: 1.25rem 1rem !important;
    }

    .fi-sidebar-group-label {
        color: #64748b !important;
        font-size: 0.6875rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.08em !important;
        text-transform: uppercase !important;
    }

    .fi-sidebar-item-btn {
        border-radius: 0.75rem !important;
        color: var(--cms-sidebar-text) !important;
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-sidebar-item-btn:hover {
        background-color: var(--cms-sidebar-item-hover) !important;
        color: #ffffff !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background-color: var(--cms-sidebar-item-active-bg) !important;
        border: 1px solid var(--cms-sidebar-item-active-border) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon {
        color: var(--cms-sidebar-text-active) !important;
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

    /* Robust Rock-Solid 12-Column Dashboard Grid Layout */
    .fi-wi {
        display: grid !important;
        grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
        gap: 1.5rem !important;
        width: 100% !important;
    }

    @media (max-width: 1023px) {
        .fi-wi {
            grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
        }
        .fi-wi > * {
            grid-column: span 1 / span 1 !important;
        }
    }

    @media (min-width: 1024px) {
        /* Greeting Section: Full Width (12 cols) */
        .fi-wi > div:has(.cms-greeting-section),
        .fi-wi > div:first-child {
            grid-column: span 12 / span 12 !important;
        }

        /* 4 Stat Cards: Full Width (12 cols) */
        .fi-wi > div:has(.cms-stat-cards),
        .fi-wi > div:nth-child(2) {
            grid-column: span 12 / span 12 !important;
        }

        /* Hero Banner: 8 Columns */
        .fi-wi > div:has(.cms-hero-banner),
        .fi-wi > div:nth-child(3) {
            grid-column: span 8 / span 8 !important;
        }

        /* Quick Actions: 4 Columns */
        .fi-wi > div:has(.cms-quick-actions),
        .fi-wi > div:nth-child(4) {
            grid-column: span 4 / span 4 !important;
        }

        /* Website Overview: 7 Columns */
        .fi-wi > div:has(.cms-website-overview),
        .fi-wi > div:nth-child(5) {
            grid-column: span 7 / span 7 !important;
        }

        /* Recent Activity: 5 Columns */
        .fi-wi > div:has(.cms-recent-activity),
        .fi-wi > div:nth-child(6) {
            grid-column: span 5 / span 5 !important;
        }
    }
</style>

