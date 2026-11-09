import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowUpRight, Menu, X } from 'lucide-react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import type { ReactNode } from 'react';
import { useLocale, useNavigation, type SharedPageProps } from '@/core';
import { resolvePreset } from '@/motion/presets';
import { Container, LanguageSwitch } from '@/ui';

export interface KorinBerkatLayoutProps {
    children: ReactNode;
}

function formatEditorialIndex(index: number): string {
    return String(index + 1).padStart(2, '0');
}

/**
 * PT Korin Berkat Energy Layout ("Editorial Index", DESIGN.md Section 5.3).
 * Features a two-row header (thin navy utility bar + white numbered editorial menu) and a navy index footer.
 */
export function Layout({ children }: KorinBerkatLayoutProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const { items, holdingUrl, contactUrl, isMobileMenuOpen, closeMobileMenu, toggleMobileMenu } =
        useNavigation();
    const prefersReducedMotion = Boolean(useReducedMotion());

    const company = props.company;
    const contacts = props.contacts ?? company?.contacts ?? [];
    const primaryContact = contacts[0];
    const isPreview = Boolean(props.isPreview);

    const homeHref = items[0]?.href ?? `/${locale}`;

    return (
        <div
            data-theme="korin-berkat"
            className="min-h-screen flex flex-col bg-bg text-ink font-body selection:bg-surface-dark selection:text-ink-on-dark"
        >
            <a
                href="#main-content"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-[var(--radius-button)] focus:bg-surface-dark focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-ink-on-dark focus:shadow-lg"
            >
                {t('skip_to_content')}
            </a>

            {isPreview ? (
                <div
                    role="status"
                    className="bg-accent-strong px-4 py-2 text-center text-xs font-semibold uppercase tracking-[0.1em] text-on-accent"
                >
                    {t('preview_banner')}
                </div>
            ) : null}

            <header className="sticky top-0 z-40 bg-bg">
                {/* Row 1: Thin Navy Bar with Holding Back-Link & Language Switch */}
                <div className="bg-surface-dark text-ink-on-dark border-b border-ink-on-dark/10">
                    <Container className="flex min-h-11 items-center justify-between gap-4 py-1">
                        <Link
                            href={holdingUrl}
                            className="inline-flex min-h-9 items-center gap-2 text-xs font-medium tracking-[0.04em] text-muted-on-dark transition-colors hover:text-accent-on-dark"
                        >
                            <ArrowLeft
                                className="h-3.5 w-3.5 text-accent-on-dark"
                                aria-hidden="true"
                            />
                            <span>{t('holding_short')}</span>
                        </Link>

                        <div className="flex items-center gap-4">
                            {primaryContact?.phone ? (
                                <a
                                    href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                    className="hidden sm:inline-block text-xs text-muted-on-dark transition-colors hover:text-ink-on-dark"
                                >
                                    {primaryContact.phone}
                                </a>
                            ) : null}
                            <LanguageSwitch />
                        </div>
                    </Container>
                </div>

                {/* Row 2: White Editorial Bar with Identity & Numbered 01..06 Menu */}
                <div className="border-b border-line bg-bg">
                    <Container className="flex h-20 items-center justify-between gap-6">
                        <Link
                            href={homeHref}
                            className="group inline-flex items-center gap-3.5 py-2 text-ink"
                        >
                            <span
                                aria-hidden="true"
                                className="flex h-10 w-10 items-center justify-center rounded-[var(--radius-button)] bg-surface-dark font-heading text-base font-semibold text-accent-on-dark"
                            >
                                KB
                            </span>
                            <div className="flex flex-col">
                                <span className="font-heading text-base font-semibold tracking-tight text-ink sm:text-lg">
                                    {company?.name ?? 'PT Korin Berkat Energy'}
                                </span>
                                <span className="text-[11px] font-medium uppercase tracking-[0.14em] text-muted">
                                    {locale === 'en'
                                        ? 'Coal Trading & Supply'
                                        : 'Perdagangan & Pasokan Batu Bara'}
                                </span>
                            </div>
                        </Link>

                        <nav
                            aria-label={t('nav.main')}
                            className="hidden xl:flex xl:items-center xl:gap-6"
                        >
                            {items.map((item, idx) => {
                                const indexCode = formatEditorialIndex(idx);

                                return (
                                    <Link
                                        key={item.key}
                                        href={item.href}
                                        aria-current={item.active ? 'page' : undefined}
                                        className={`group relative inline-flex min-h-11 items-center gap-1.5 py-2 text-xs font-medium tracking-[0.04em] transition-colors ${
                                            item.active
                                                ? 'text-ink font-semibold'
                                                : 'text-muted hover:text-ink'
                                        }`}
                                    >
                                        <span
                                            className={`font-heading text-xs font-semibold ${
                                                item.active
                                                    ? 'text-accent-strong'
                                                    : 'text-muted group-hover:text-accent-strong'
                                            }`}
                                        >
                                            {indexCode}
                                        </span>
                                        <span>{item.label}</span>
                                        {item.active ? (
                                            <span
                                                aria-hidden="true"
                                                className="absolute inset-x-0 bottom-1 h-0.5 bg-accent-strong"
                                            />
                                        ) : null}
                                    </Link>
                                );
                            })}
                        </nav>

                        <div className="flex items-center gap-3 xl:hidden">
                            <button
                                type="button"
                                onClick={toggleMobileMenu}
                                aria-expanded={isMobileMenuOpen}
                                aria-controls="korin-berkat-mobile-nav"
                                aria-label={
                                    isMobileMenuOpen ? t('nav.close_menu') : t('nav.open_menu')
                                }
                                className="inline-flex min-h-11 min-w-11 items-center justify-center rounded-[var(--radius-button)] border border-line text-ink transition-colors hover:bg-surface"
                            >
                                {isMobileMenuOpen ? (
                                    <X className="h-5 w-5" aria-hidden="true" />
                                ) : (
                                    <Menu className="h-5 w-5" aria-hidden="true" />
                                )}
                            </button>
                        </div>
                    </Container>
                </div>

                {/* Mobile Editorial Drawer */}
                <AnimatePresence>
                    {isMobileMenuOpen ? (
                        <motion.div
                            id="korin-berkat-mobile-nav"
                            initial="hidden"
                            animate="visible"
                            exit="hidden"
                            variants={resolvePreset('fadeIn', prefersReducedMotion)}
                            className="border-b border-line bg-bg xl:hidden"
                        >
                            <Container className="py-5">
                                <nav aria-label={t('nav.main')} className="divide-y divide-line">
                                    {items.map((item, idx) => (
                                        <Link
                                            key={item.key}
                                            href={item.href}
                                            onClick={closeMobileMenu}
                                            aria-current={item.active ? 'page' : undefined}
                                            className={`flex min-h-11 items-center justify-between py-3 text-sm transition-colors ${
                                                item.active
                                                    ? 'font-semibold text-ink'
                                                    : 'font-medium text-muted hover:text-ink'
                                            }`}
                                        >
                                            <span className="inline-flex items-center gap-3">
                                                <span className="font-heading text-xs font-semibold text-accent-strong">
                                                    {formatEditorialIndex(idx)}
                                                </span>
                                                <span>{item.label}</span>
                                            </span>
                                            {item.active ? (
                                                <span
                                                    aria-hidden="true"
                                                    className="h-1.5 w-1.5 rounded-full bg-accent-strong"
                                                />
                                            ) : null}
                                        </Link>
                                    ))}
                                </nav>
                            </Container>
                        </motion.div>
                    ) : null}
                </AnimatePresence>
            </header>

            <main id="main-content" className="flex-1">
                {children}
            </main>

            {/* Navy Editorial Footer */}
            <footer className="bg-surface-dark text-ink-on-dark border-t-2 border-accent">
                <Container className="py-16 md:py-20">
                    <div className="grid grid-cols-1 gap-12 lg:grid-cols-12">
                        {/* Column 1: Entity Identity & Parent Holding Back-link */}
                        <div className="lg:col-span-5 space-y-5">
                            <div className="flex items-center gap-3">
                                <span
                                    aria-hidden="true"
                                    className="flex h-9 w-9 items-center justify-center rounded-[var(--radius-button)] border border-accent-on-dark/50 font-heading text-sm font-semibold text-accent-on-dark"
                                >
                                    KB
                                </span>
                                <p className="font-heading text-xl font-semibold text-ink-on-dark">
                                    {company?.name ?? 'PT Korin Berkat Energy'}
                                </p>
                            </div>
                            <p className="max-w-[44ch] text-sm leading-6 text-muted-on-dark">
                                {company?.about_short || company?.tagline}
                            </p>
                            <div className="pt-2">
                                <p className="text-[11px] font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                    {t('footer.parent_group_label')}
                                </p>
                                <Link
                                    href={holdingUrl}
                                    className="mt-1.5 inline-flex min-h-10 items-center gap-2 text-sm font-medium text-ink-on-dark transition-colors hover:text-accent-on-dark"
                                >
                                    <ArrowLeft
                                        className="h-4 w-4 text-accent-on-dark"
                                        aria-hidden="true"
                                    />
                                    <span>{t('back_to_holding')}</span>
                                </Link>
                            </div>
                        </div>

                        {/* Column 2: Numbered Page Index 01..06 */}
                        <div className="lg:col-span-3 space-y-4">
                            <h2 className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                {t('nav.index_heading')}
                            </h2>
                            <ul className="space-y-2 text-sm">
                                {items.map((item, idx) => (
                                    <li key={item.key}>
                                        <Link
                                            href={item.href}
                                            className="inline-flex min-h-9 items-center gap-2.5 text-muted-on-dark transition-colors hover:text-ink-on-dark"
                                        >
                                            <span className="font-heading text-xs font-semibold text-accent-on-dark">
                                                {formatEditorialIndex(idx)}
                                            </span>
                                            <span>{item.label}</span>
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        {/* Column 3: Head Office Contacts & Hours */}
                        <div className="lg:col-span-4 space-y-4">
                            <h2 className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                {t('footer.address_heading')}
                            </h2>
                            {primaryContact ? (
                                <div className="space-y-3 text-sm leading-6 text-muted-on-dark">
                                    <p>{primaryContact.address}</p>
                                    {primaryContact.phone ? (
                                        <p>
                                            <a
                                                href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                                className="font-medium text-ink-on-dark transition-colors hover:text-accent-on-dark"
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
                                        <p className="pt-2 text-xs text-muted-on-dark border-t border-ink-on-dark/10">
                                            {primaryContact.operating_hours}
                                        </p>
                                    ) : null}
                                </div>
                            ) : null}
                            <div className="pt-2">
                                <Link
                                    href={contactUrl}
                                    className="inline-flex min-h-10 items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-accent-on-dark transition-colors hover:text-ink-on-dark"
                                >
                                    <span>{t('contact_us')}</span>
                                    <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div className="mt-12 flex flex-col gap-4 border-t border-ink-on-dark/10 pt-8 text-xs text-muted-on-dark sm:flex-row sm:items-center sm:justify-between">
                        <p>
                            {`© ${new Date().getFullYear()} ${company?.name ?? 'PT Korin Berkat Energy'}. ${t('footer.rights_reserved')}`}
                        </p>
                        <LanguageSwitch />
                    </div>
                </Container>
            </footer>
        </div>
    );
}
