import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, Menu, X } from 'lucide-react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import { useState, type ReactNode } from 'react';
import { useLocale, useNavigation, type SharedPageProps } from '@/core';
import { Container, LanguageSwitch, Logo } from '@/ui';
import { PageBanner } from './PageBanner';

export interface KorinBaraLayoutProps {
    children: ReactNode;
}

export function Layout({ children }: KorinBaraLayoutProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const { items, contactUrl, holdingUrl } = useNavigation();
    const navItems = items.filter((item) => item.key !== 'contact');
    const prefersReducedMotion = Boolean(useReducedMotion());
    const [mobileDrawerOpen, setMobileDrawerOpen] = useState(false);

    const company = props.company;
    const isHomePage = props.page?.key === 'home' || !props.page?.key;

    const homeHref = `/${locale}`;
    const pageTitle =
        props.product?.name ||
        props.page?.title ||
        (props.title ? String(props.title).split('·')[0].trim() : '');

    const currentPath =
        typeof window !== 'undefined'
            ? window.location.pathname
            : props.page?.slug
              ? `/${locale}/${props.page.slug}`
              : `/${locale}`;

    // Header CTA data
    const headerCtaLabel =
        (props.company as { header_cta_label?: string })?.header_cta_label ||
        t('contact_us');
    const headerCtaHref =
        (props.company as { header_cta_href?: string })?.header_cta_href ||
        contactUrl ||
        `/${locale}/kontak`;

    return (
        <div
            data-theme="korin-bara"
            className="min-h-screen flex flex-col bg-bg text-ink font-body selection:bg-accent selection:text-white"
        >
            <a
                href="#main-content"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-6 focus:z-50 focus:bg-surface-dark focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-white focus:shadow-lg focus:rounded-full"
            >
                {t('skip_to_content')}
            </a>

            {/* Horizontal White Sticky Header (height ~80px) */}
            <header className="sticky top-0 z-40 h-20 w-full border-b border-line bg-white/95 backdrop-blur-md transition-shadow duration-200">
                <Container className="flex h-full items-center justify-between gap-4">
                    {/* Left: Brand Logo */}
                    <div className="flex items-center">
                        <Link
                            href={homeHref}
                            aria-label={
                                locale === 'en'
                                    ? `${company?.name ?? 'PT Korin Bara Energy'}, Home`
                                    : `${company?.name ?? 'PT Korin Bara Energy'}, Beranda`
                            }
                            className="inline-flex min-h-[44px] items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:rounded-lg"
                        >
                            <Logo
                                company={company}
                                on="light"
                                compact={false}
                                size={44}
                                priority={true}
                                className="h-11 w-auto max-w-[200px] shrink-0"
                            />
                        </Link>
                    </div>

                    {/* Center: Desktop Horizontal Navigation */}
                    <nav
                        aria-label={t('nav.main')}
                        className="hidden lg:flex lg:items-center lg:gap-8"
                    >
                        {navItems.map((item) => {
                            // Exact active state check: only one item active at a time
                            const isActive =
                                item.active ??
                                (item.href === homeHref
                                    ? currentPath === homeHref || currentPath === `${homeHref}/`
                                    : currentPath.startsWith(item.href));

                            return (
                                <Link
                                    key={item.key}
                                    href={item.href}
                                    aria-current={isActive ? 'page' : undefined}
                                    className={`group relative inline-flex min-h-[44px] items-center text-sm transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:rounded-md ${
                                        isActive
                                            ? 'font-bold text-ink'
                                            : 'font-medium text-slate-600 hover:text-ink'
                                    }`}
                                >
                                    <span>{item.label}</span>
                                    {isActive ? (
                                        <span
                                            aria-hidden="true"
                                            className="absolute bottom-2 left-0 right-0 h-[2px] rounded-full bg-accent"
                                        />
                                    ) : (
                                        <span
                                            aria-hidden="true"
                                            className="absolute bottom-2 left-0 right-0 h-[2px] scale-x-0 rounded-full bg-accent/50 transition-transform duration-150 group-hover:scale-x-100"
                                        />
                                    )}
                                </Link>
                            );
                        })}
                    </nav>

                    {/* Right: Holding Link, CTA Pill Button & Language Switch */}
                    <div className="hidden lg:flex lg:items-center lg:gap-4">
                        <Link
                            href={holdingUrl}
                            aria-label={t('back_to_holding')}
                            className="inline-flex min-h-[44px] items-center gap-2 rounded-full border border-slate-200 bg-slate-50/80 px-4 py-2 text-xs font-semibold text-[#101F38] transition-all hover:border-[#8A6212] hover:bg-amber-50/40 hover:text-[#8A6212] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                        >
                            <ArrowLeft className="h-3.5 w-3.5 text-[#8A6212]" aria-hidden="true" />
                            <span>{t('holding_short')}</span>
                        </Link>

                        <Link
                            href={headerCtaHref}
                            className="kb-btn-solid min-h-[44px]"
                        >
                            <span>{headerCtaLabel}</span>
                            <ArrowRight className="h-4 w-4" aria-hidden="true" />
                        </Link>

                        <LanguageSwitch tone="light" />
                    </div>

                    {/* Mobile Hamburger Button */}
                    <div className="flex items-center gap-3 lg:hidden">
                        <LanguageSwitch tone="light" />
                        <button
                            type="button"
                            onClick={() => setMobileDrawerOpen(true)}
                            aria-expanded={mobileDrawerOpen}
                            aria-label={t('nav.toggle_menu')}
                            className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg border border-line bg-surface p-2.5 text-ink transition-colors hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                        >
                            <Menu className="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                </Container>
            </header>

            {/* Mobile Drawer */}
            <AnimatePresence>
                {mobileDrawerOpen ? (
                    <>
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: prefersReducedMotion ? 0 : 0.15 }}
                            onClick={() => setMobileDrawerOpen(false)}
                            aria-hidden="true"
                            className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs lg:hidden"
                        />
                        <motion.div
                            role="dialog"
                            aria-modal="true"
                            aria-label={t('nav.main')}
                            initial={{ x: '100%' }}
                            animate={{ x: 0 }}
                            exit={{ x: '100%' }}
                            transition={{ duration: prefersReducedMotion ? 0 : 0.25, ease: 'easeOut' }}
                            className="fixed inset-y-0 right-0 z-50 flex w-full max-w-xs flex-col bg-white p-6 shadow-2xl lg:hidden"
                        >
                            {/* Drawer Header */}
                            <div className="flex items-center justify-between border-b border-line pb-4">
                                <Logo
                                    company={company}
                                    on="light"
                                    compact={false}
                                    size={36}
                                    className="h-9 w-auto max-w-[160px]"
                                />
                                <button
                                    type="button"
                                    onClick={() => setMobileDrawerOpen(false)}
                                    aria-label={t('close')}
                                    className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                >
                                    <X className="h-6 w-6" aria-hidden="true" />
                                </button>
                            </div>

                            {/* Drawer Nav Items */}
                            <nav className="flex flex-1 flex-col gap-2 py-6">
                                {navItems.map((item) => {
                                    const isActive =
                                        item.active ??
                                        (item.href === homeHref
                                            ? currentPath === homeHref
                                            : currentPath.startsWith(item.href));

                                    return (
                                        <Link
                                            key={item.key}
                                            href={item.href}
                                            onClick={() => setMobileDrawerOpen(false)}
                                            aria-current={isActive ? 'page' : undefined}
                                            className={`flex min-h-[44px] items-center justify-between rounded-lg px-4 py-3 text-base transition-colors ${
                                                isActive
                                                    ? 'bg-amber-50 font-bold text-accent'
                                                    : 'font-medium text-ink hover:bg-slate-50'
                                            }`}
                                        >
                                            <span>{item.label}</span>
                                            {isActive ? (
                                                <span className="h-2 w-2 rounded-full bg-accent" aria-hidden="true" />
                                            ) : null}
                                        </Link>
                                    );
                                })}
                            </nav>

                            {/* Drawer Bottom CTA */}
                            <div className="border-t border-line pt-4 space-y-3">
                                <Link
                                    href={headerCtaHref}
                                    onClick={() => setMobileDrawerOpen(false)}
                                    className="kb-btn-solid w-full min-h-[44px]"
                                >
                                    <span>{headerCtaLabel}</span>
                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                </Link>

                                <Link
                                    href={holdingUrl}
                                    onClick={() => setMobileDrawerOpen(false)}
                                    aria-label={t('back_to_holding')}
                                    className="inline-flex min-h-[44px] w-full items-center justify-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-semibold text-[#101F38] transition-colors hover:border-[#8A6212] hover:bg-amber-50/40 hover:text-[#8A6212]"
                                >
                                    <ArrowLeft className="h-3.5 w-3.5 text-[#8A6212]" aria-hidden="true" />
                                    <span>{t('back_to_holding')}</span>
                                </Link>
                            </div>
                        </motion.div>
                    </>
                ) : null}
            </AnimatePresence>

            {/* Inside Pages Banner (Tentang Kami, Operasi, Produk, Kontak, Product Detail) */}
            {!isHomePage && pageTitle ? (
                <PageBanner title={pageTitle} />
            ) : null}

            {/* Main Content */}
            <main id="main-content" className="flex-1">
                {children}
            </main>

            {/* Footer (Navy, Mountain Silhouette, Diagonal Gold Wedge) */}
            <footer className="relative overflow-hidden bg-[#101F38] text-white">
                {/* Subtle Mountain Silhouette Background (SVG) */}
                <div
                    aria-hidden="true"
                    className="pointer-events-none absolute inset-x-0 bottom-0 h-48 opacity-[0.07]"
                >
                    <svg
                        viewBox="0 0 1200 240"
                        preserveAspectRatio="none"
                        className="h-full w-full fill-current text-white"
                    >
                        <path d="M0,240 L0,180 L140,80 L280,190 L450,40 L620,170 L790,60 L960,180 L1100,100 L1200,160 L1200,240 Z" />
                    </svg>
                </div>

                {/* Diagonal Gold Wedge in Bottom-Right Corner */}
                <div aria-hidden="true" className="kb-footer-wedge" />

                {/* Content Container */}
                <Container className="relative z-10 py-12 lg:py-16">
                    {/* Top Row: Logo, Navigation, Socials */}
                    <div className="flex flex-col items-center justify-between gap-8 border-b border-white/10 pb-10 lg:flex-row">
                        {/* Logo on="dark" height 44px without link (DESIGN.md 8.1) */}
                        <div className="flex shrink-0 items-center">
                            <Logo
                                company={company}
                                on="dark"
                                compact={false}
                                size={44}
                                className="h-11 w-auto max-w-[200px]"
                            />
                        </div>

                        {/* Horizontal Menu in Middle (Mobile: 2 columns) */}
                        <nav
                            aria-label={t('nav.footer')}
                            className="grid grid-cols-2 gap-x-8 gap-y-3 text-center sm:flex sm:flex-wrap sm:justify-center sm:gap-6 lg:gap-8"
                        >
                            {items.map((item) => (
                                <Link
                                    key={item.key}
                                    href={item.href}
                                    className="min-h-[44px] inline-flex items-center text-sm font-medium text-slate-300 transition-colors hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:rounded-md"
                                >
                                    {item.label}
                                </Link>
                            ))}
                        </nav>

                        {/* Circular White Social Icons */}
                        <div className="flex items-center gap-3">
                            <a
                                href="https://linkedin.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="LinkedIn PT Korin Bara Energy"
                                className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                            >
                                <svg className="h-4 w-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.2V10.9H6.46M7.83 6.45a1.6 1.6 0 0 0-1.6 1.6 1.6 1.6 0 0 0 1.6 1.6 1.6 1.6 0 0 0 1.6-1.6 1.6 1.6 0 0 0-1.6-1.6Z" />
                                </svg>
                            </a>
                            <a
                                href="https://youtube.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="YouTube PT Korin Bara Energy"
                                className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                            >
                                <svg className="h-4 w-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M10 15l5.19-3L10 9v6m11.56-7.83c.13.47.22 1.1.28 1.9.07.8.1 1.49.1 2.09L22 12c0 2.19-.16 3.8-.44 4.83-.25.9-.83 1.48-1.73 1.73-.47.13-1.33.22-2.65.28-1.3.07-2.49.1-3.59.1L12 19c-4.19 0-6.8-.16-7.83-.44-.9-.25-1.48-.83-1.73-1.73-.13-.47-.22-1.1-.28-1.9-.07-.8-.1-1.49-.1-2.09L2 12c0-2.19.16-3.8.44-4.83.25-.9.83-1.48 1.73-1.73.47-.13 1.33-.22 2.65-.28 1.3-.07 2.49-.1 3.59-.1L12 5c4.19 0 6.8.16 7.83.44.9.25 1.48.83 1.73 1.73Z" />
                                </svg>
                            </a>
                            <a
                                href="https://instagram.com"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Instagram PT Korin Bara Energy"
                                className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                            >
                                <svg className="h-4 w-4 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069ZM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0Zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324ZM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881Z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {/* Bottom Row: Dynamic Copyright Text */}
                    <div className="pt-8 text-center text-xs text-slate-400">
                        <p>
                            &copy; {new Date().getFullYear()} {company?.name ?? 'PT Korin Bara Energy'}. {t('footer.all_rights_reserved')}
                        </p>
                    </div>
                </Container>
            </footer>
        </div>
    );
}
