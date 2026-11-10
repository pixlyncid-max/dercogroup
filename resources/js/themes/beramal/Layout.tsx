import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowUpRight, Menu, X } from 'lucide-react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import type { ReactNode } from 'react';
import { useLocale, useNavigation, type SharedPageProps } from '@/core';
import { resolvePreset } from '@/motion/presets';
import { Container, LanguageSwitch, Logo } from '@/ui';
import { WaveDivider } from './WaveDivider';

export interface BeramalLayoutProps {
    children: ReactNode;
}

/**
 * PT Beramal Sukses Sejahtera Layout ("Calm Flow", DESIGN.md Section 7.3).
 * Features a floating pill header suspended 16px from the top (shadow-md, translucent on scroll)
 * and a dark-water footer with thin wave pattern and a Cerulean pill CTA.
 */
export function Layout({ children }: BeramalLayoutProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const {
        items,
        holdingUrl,
        contactUrl,
        isSticky,
        isMobileMenuOpen,
        closeMobileMenu,
        toggleMobileMenu,
    } = useNavigation();
    const prefersReducedMotion = Boolean(useReducedMotion());

    const company = props.company;
    const contacts = props.contacts ?? company?.contacts ?? [];
    const primaryContact = contacts[0];
    const isPreview = Boolean(props.isPreview);

    const homeHref = items[0]?.href ?? `/${locale}`;
    const middleNavItems = items.filter((item) => item.key !== 'contact');
    const contactItem = items.find((item) => item.key === 'contact');
    const contactHref = contactItem?.href ?? contactUrl;
    const isContactActive = Boolean(contactItem?.active);

    return (
        <div
            data-theme="beramal"
            className="min-h-screen flex flex-col bg-bg text-ink font-body selection:bg-accent selection:text-on-accent"
        >
            <a
                href="#main-content"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-full focus:bg-surface-dark focus:px-5 focus:py-2.5 focus:text-sm focus:font-bold focus:text-ink-on-dark focus:shadow-md"
            >
                {t('skip_to_content')}
            </a>

            {isPreview ? (
                <div
                    role="status"
                    className="bg-accent px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.1em] text-on-accent"
                >
                    {t('preview_banner')}
                </div>
            ) : null}

            {/* Floating Pill Header suspended 16px (top-4) from the top */}
            <header className="sticky top-4 z-40 px-4 sm:px-6 lg:px-8 pt-4">
                <div className="mx-auto max-w-[var(--container-wide)]">
                    <div
                        className={[
                            'flex min-h-16 items-center justify-between gap-3 rounded-full border border-line px-4 py-2 shadow-md transition-colors duration-300 sm:px-6',
                            isSticky ? 'bg-bg/92 backdrop-blur-md' : 'bg-bg',
                        ].join(' ')}
                    >
                        {/* Brand Identity */}
                        <div className="flex items-center gap-3 min-w-0">
                            <Link
                                href={homeHref}
                                aria-label={
                                    locale === 'en'
                                        ? `${company?.name ?? 'PT Beramal Sukses Sejahtera'}, Home`
                                        : `${company?.name ?? 'PT Beramal Sukses Sejahtera'}, Beranda`
                                }
                                className="inline-flex items-center gap-3 py-1 text-ink focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 rounded-full"
                            >
                                <Logo
                                    company={company}
                                    on="light"
                                    size={36}
                                    priority={true}
                                    className="shrink-0"
                                />
                            </Link>
                        </div>

                        {/* Center Pill Navigation (Non-Contact Items) */}
                        <nav
                            aria-label={t('nav.main')}
                            className="hidden xl:flex xl:items-center xl:gap-1"
                        >
                            {middleNavItems.map((item) => (
                                <Link
                                    key={item.key}
                                    href={item.href}
                                    aria-current={item.active ? 'page' : undefined}
                                    className={[
                                        'inline-flex min-h-11 items-center rounded-full px-4 py-2 text-xs font-bold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2',
                                        item.active
                                            ? 'bg-surface-dark text-ink-on-dark'
                                            : 'text-muted hover:bg-surface hover:text-ink',
                                    ].join(' ')}
                                >
                                    {item.label}
                                </Link>
                            ))}
                        </nav>

                        {/* Right Controls: Holding link, LanguageSwitch, "Hubungi Kami" Text Link & Mobile Menu Toggle */}
                        <div className="flex items-center gap-2">
                            <Link
                                href={holdingUrl}
                                className="hidden md:inline-flex min-h-10 items-center gap-1.5 rounded-full border border-line bg-surface px-3 py-1.5 text-xs font-semibold text-ink transition-colors hover:bg-accent hover:text-on-accent"
                                aria-label={t('back_to_holding')}
                            >
                                <ArrowLeft className="h-3.5 w-3.5" aria-hidden="true" />
                                <span className="hidden lg:inline">{t('holding_short')}</span>
                            </Link>

                            <LanguageSwitch tone="light" className="hidden sm:inline-flex" />

                            {contactItem ? (
                                <Link
                                    href={contactHref}
                                    aria-current={isContactActive ? 'page' : undefined}
                                    className={[
                                        'relative hidden xl:inline-flex min-h-[44px] min-w-[44px] items-center justify-center px-4 py-2 text-xs font-bold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 rounded-full',
                                        isContactActive
                                            ? 'text-ink font-bold'
                                            : 'text-muted hover:text-ink',
                                    ].join(' ')}
                                >
                                    <span>{contactItem.label}</span>
                                    {isContactActive ? (
                                        <span
                                            aria-hidden="true"
                                            className="absolute inset-x-3 bottom-2 h-0.5 rounded-full bg-accent"
                                        />
                                    ) : null}
                                </Link>
                            ) : null}

                            <button
                                type="button"
                                onClick={toggleMobileMenu}
                                aria-expanded={isMobileMenuOpen}
                                aria-controls="beramal-mobile-nav"
                                aria-label={
                                    isMobileMenuOpen ? t('nav.close_menu') : t('nav.open_menu')
                                }
                                className="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full border border-line bg-surface text-ink transition-colors hover:bg-accent hover:text-on-accent xl:hidden"
                            >
                                {isMobileMenuOpen ? (
                                    <X className="h-5 w-5" aria-hidden="true" />
                                ) : (
                                    <Menu className="h-5 w-5" aria-hidden="true" />
                                )}
                            </button>
                        </div>
                    </div>

                    {/* Mobile Rounded Capsule Menu */}
                    <AnimatePresence>
                        {isMobileMenuOpen ? (
                            <motion.div
                                id="beramal-mobile-nav"
                                initial="hidden"
                                animate="visible"
                                exit="hidden"
                                variants={resolvePreset('fadeIn', prefersReducedMotion)}
                                className="mt-3 overflow-hidden rounded-[var(--radius-card)] border border-line bg-bg p-5 shadow-md xl:hidden"
                            >
                                <nav aria-label={t('nav.main')} className="space-y-1.5">
                                    {items.map((item) => (
                                        <Link
                                            key={item.key}
                                            href={item.href}
                                            onClick={closeMobileMenu}
                                            aria-current={item.active ? 'page' : undefined}
                                            className={[
                                                'flex min-h-11 items-center justify-between rounded-full px-4 py-2.5 text-sm font-bold transition-colors',
                                                item.active
                                                    ? 'bg-surface-dark text-ink-on-dark'
                                                    : 'text-ink hover:bg-surface',
                                            ].join(' ')}
                                        >
                                            <span>{item.label}</span>
                                            {item.active ? (
                                                <span
                                                    aria-hidden="true"
                                                    className="h-2 w-2 rounded-full bg-accent-on-dark"
                                                />
                                            ) : null}
                                        </Link>
                                    ))}
                                </nav>

                                <div className="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-4">
                                    <Link
                                        href={holdingUrl}
                                        onClick={closeMobileMenu}
                                        className="inline-flex min-h-11 items-center gap-2 rounded-full bg-surface px-4 py-2 text-xs font-bold text-ink"
                                    >
                                        <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                                        <span>{t('back_to_holding')}</span>
                                    </Link>
                                    <LanguageSwitch tone="light" />
                                </div>
                            </motion.div>
                        ) : null}
                    </AnimatePresence>
                </div>
            </header>

            <main id="main-content" className="flex-1">
                {children}
            </main>

            {/* Wave transition into Dark Water Footer */}
            <WaveDivider tone="dark" className="mt-8" />

            {/* Dark Water Footer with Thin Wave Pattern and Cerulean CTA */}
            <footer className="relative overflow-hidden bg-surface-dark text-ink-on-dark">
                {/* Subtle Thin Wave Background Pattern */}
                <svg
                    aria-hidden="true"
                    viewBox="0 0 1440 320"
                    fill="none"
                    preserveAspectRatio="none"
                    className="pointer-events-none absolute inset-0 h-full w-full text-accent-on-dark opacity-12"
                >
                    <path
                        d="M-60 90C240 20 520 160 840 90C1140 20 1320 130 1500 80"
                        stroke="currentColor"
                        strokeWidth="1.5"
                    />
                    <path
                        d="M-60 180C220 110 540 250 860 180C1160 110 1340 220 1500 170"
                        stroke="currentColor"
                        strokeWidth="1.5"
                    />
                    <path
                        d="M-60 265C260 195 560 325 880 255C1180 185 1360 285 1500 245"
                        stroke="currentColor"
                        strokeWidth="1.5"
                    />
                </svg>

                <Container className="relative z-10 py-14 md:py-20">
                    {/* Top Calm Capsule CTA Bar */}
                    <div className="mb-14 flex flex-col items-start justify-between gap-6 rounded-[var(--radius-card)] border border-ink-on-dark/15 bg-surface-deeper/80 p-7 sm:flex-row sm:items-center sm:p-9">
                        <div className="space-y-1.5">
                            <p className="text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                {company?.name ?? 'PT Beramal Sukses Sejahtera'}
                            </p>
                            <p className="font-heading text-xl font-bold text-ink-on-dark sm:text-2xl">
                                {locale === 'en'
                                    ? 'Ready to coordinate operational and facility services?'
                                    : 'Siap mengoordinasikan layanan operasional dan fasilitas?'}
                            </p>
                        </div>

                        <Link
                            href={contactUrl}
                            className="inline-flex min-h-12 shrink-0 items-center gap-2 rounded-full bg-accent px-7 py-3 text-sm font-bold text-on-accent transition-opacity hover:opacity-90"
                        >
                            <span>{t('contact_us')}</span>
                            <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 gap-12 lg:grid-cols-12">
                        {/* Column 1: Entity Profile & Holding Back-link */}
                        <div className="lg:col-span-5 space-y-5">
                            <Logo company={company} on="dark" size={44} />
                            <p className="max-w-[46ch] text-sm leading-6 text-muted-on-dark">
                                {company?.about_short || company?.tagline}
                            </p>
                            <div className="pt-2">
                                <p className="text-[11px] font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                    {t('footer.parent_group_label')}
                                </p>
                                <Link
                                    href={holdingUrl}
                                    className="mt-2 inline-flex min-h-11 items-center gap-2 rounded-full border border-ink-on-dark/20 px-4 py-2 text-xs font-bold text-ink-on-dark transition-colors hover:border-accent-on-dark hover:text-accent-on-dark"
                                >
                                    <ArrowLeft
                                        className="h-4 w-4 text-accent-on-dark"
                                        aria-hidden="true"
                                    />
                                    <span>{t('back_to_holding')}</span>
                                </Link>
                            </div>
                        </div>

                        {/* Column 2: Navigation Links */}
                        <div className="lg:col-span-3 space-y-4">
                            <h2 className="text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                {t('nav.index_heading')}
                            </h2>
                            <ul className="space-y-2 text-sm">
                                {items.map((item) => (
                                    <li key={item.key}>
                                        <Link
                                            href={item.href}
                                            className="inline-flex min-h-[44px] items-center gap-2 text-muted-on-dark transition-colors hover:text-ink-on-dark"
                                        >
                                            <span
                                                aria-hidden="true"
                                                className="h-1.5 w-1.5 rounded-full bg-accent-on-dark"
                                            />
                                            <span>{item.label}</span>
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        {/* Column 3: Contact Info */}
                        <div className="lg:col-span-4 space-y-4">
                            <h2 className="text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                {t('footer.address_heading')}
                            </h2>
                            {primaryContact ? (
                                <div className="space-y-3 text-sm leading-6 text-muted-on-dark">
                                    <p>{primaryContact.address}</p>
                                    {primaryContact.phone ? (
                                        <p>
                                            <a
                                                href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                                className="font-bold text-ink-on-dark transition-colors hover:text-accent-on-dark"
                                            >
                                                {primaryContact.phone}
                                            </a>
                                        </p>
                                    ) : null}
                                    {primaryContact.email ? (
                                        <p>
                                            <a
                                                href={`mailto:${primaryContact.email}`}
                                                className="transition-colors hover:text-ink-on-dark"
                                            >
                                                {primaryContact.email}
                                            </a>
                                        </p>
                                    ) : null}
                                    {primaryContact.operating_hours ? (
                                        <p className="border-t border-ink-on-dark/15 pt-3 text-xs text-muted-on-dark">
                                            {primaryContact.operating_hours}
                                        </p>
                                    ) : null}
                                </div>
                            ) : null}
                        </div>
                    </div>

                    <div className="mt-12 flex flex-col gap-4 border-t border-ink-on-dark/15 pt-8 text-xs text-muted-on-dark sm:flex-row sm:items-center sm:justify-between">
                        <p>
                            {`© ${new Date().getFullYear()} ${company?.name ?? 'PT Beramal Sukses Sejahtera'}. ${t('footer.rights_reserved')}`}
                        </p>
                        <LanguageSwitch tone="dark" />
                    </div>
                </Container>
            </footer>
        </div>
    );
}
