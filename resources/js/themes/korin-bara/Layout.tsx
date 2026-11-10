import { Link, usePage } from '@inertiajs/react';
import {
    ArrowLeft,
    ArrowRight,
    Building2,
    ChevronLeft,
    ChevronRight,
    Compass,
    Home,
    Layers,
    Mail,
    MoreHorizontal,
    Phone,
    ShieldCheck,
    X,
    type LucideProps,
} from 'lucide-react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import { useState, type ComponentType, type ReactNode } from 'react';
import { useLocale, useNavigation, type SharedPageProps } from '@/core';
import { resolvePreset } from '@/motion/presets';
import { Button, Container, LanguageSwitch } from '@/ui';

export interface KorinBaraLayoutProps {
    children: ReactNode;
}

const navIconByKey: Record<string, ComponentType<LucideProps>> = {
    home: Home,
    about: Building2,
    products: Layers,
    operations: Compass,
    compliance: ShieldCheck,
    contact: Mail,
};

/**
 * PT Korin Bara Energy Layout ("Field Rail", DESIGN.md Section 6.3).
 * Desktop: Fixed 88px vertical left rail (expandable on hover/toggle) with icons + labels and bottom language switcher.
 * Mobile: Fixed bottom bar with 5 icons + "More / Lainnya" sheet.
 * Footer: Deep navy with a bold Brass Gold CTA block.
 */
export function Layout({ children }: KorinBaraLayoutProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const { items, holdingUrl, contactUrl, isMobileMenuOpen, closeMobileMenu, toggleMobileMenu } =
        useNavigation();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const [railExpanded, setRailExpanded] = useState(false);

    const company = props.company;
    const contacts = props.contacts ?? company?.contacts ?? [];
    const primaryContact = contacts[0];
    const isPreview = Boolean(props.isPreview);

    const homeHref = items[0]?.href ?? `/${locale}`;
    const mobilePrimaryItems = items.slice(0, 5);
    const mobileOverflowItems = items.slice(5);

    return (
        <div
            data-theme="korin-bara"
            className="min-h-screen flex flex-col bg-bg text-ink font-body selection:bg-accent selection:text-ink"
        >
            <a
                href="#main-content"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-24 focus:z-50 focus:bg-surface-dark focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-ink-on-dark focus:shadow-lg"
            >
                {t('skip_to_content')}
            </a>

            {/* Desktop Fixed Vertical Left Rail (88px collapsed, 256px expanded on hover/toggle) */}
            <aside
                aria-label={t('nav.main')}
                onMouseEnter={() => setRailExpanded(true)}
                onMouseLeave={() => setRailExpanded(false)}
                className={`fixed inset-y-0 left-0 z-40 hidden lg:flex lg:flex-col lg:justify-between bg-surface-dark text-ink-on-dark border-r-4 border-accent transition-[width] duration-200 ease-out ${
                    railExpanded ? 'lg:w-64 shadow-md' : 'lg:w-[88px]'
                }`}
            >
                {/* Top: Monogram + Holding Back Link */}
                <div className="border-b border-ink-on-dark/15 p-3 space-y-3">
                    <div className="flex items-center justify-between gap-2">
                        <Link
                            href={homeHref}
                            className="inline-flex items-center gap-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                        >
                            <span
                                aria-hidden="true"
                                className="flex h-12 w-12 shrink-0 items-center justify-center bg-accent font-heading text-xl font-bold tracking-wider text-ink"
                            >
                                KB
                            </span>
                            <span
                                className={`font-heading text-base font-bold uppercase tracking-wider text-ink-on-dark whitespace-nowrap transition-opacity duration-150 ${
                                    railExpanded ? 'opacity-100' : 'sr-only'
                                }`}
                            >
                                {company?.name ?? 'PT Korin Bara Energy'}
                            </span>
                        </Link>

                        <button
                            type="button"
                            onClick={() => setRailExpanded((prev) => !prev)}
                            aria-expanded={railExpanded}
                            aria-label={
                                railExpanded ? t('nav.collapse_rail') : t('nav.expand_rail')
                            }
                            className="inline-flex h-8 w-8 shrink-0 items-center justify-center text-muted-on-dark hover:text-accent-on-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                        >
                            {railExpanded ? (
                                <ChevronLeft className="h-4 w-4" aria-hidden="true" />
                            ) : (
                                <ChevronRight className="h-4 w-4" aria-hidden="true" />
                            )}
                        </button>
                    </div>

                    <Link
                        href={holdingUrl}
                        title={t('back_to_holding')}
                        className="flex min-h-10 items-center justify-center gap-2 border border-ink-on-dark/15 bg-surface-deeper px-2.5 py-1.5 text-xs font-semibold uppercase tracking-[0.08em] text-muted-on-dark transition-colors hover:border-accent hover:text-accent-on-dark"
                    >
                        <ArrowLeft
                            className="h-3.5 w-3.5 shrink-0 text-accent-on-dark"
                            aria-hidden="true"
                        />
                        <span className={railExpanded ? 'truncate' : 'sr-only'}>
                            {t('holding_short')}
                        </span>
                    </Link>
                </div>

                {/* Middle: Vertical Icon Navigation */}
                <nav
                    aria-label={t('nav.main')}
                    className="flex-1 overflow-y-auto py-4 px-2 space-y-1.5"
                >
                    {items.map((item) => {
                        const Icon = navIconByKey[item.key] ?? Layers;

                        return (
                            <Link
                                key={item.key}
                                href={item.href}
                                aria-current={item.active ? 'page' : undefined}
                                className={`group flex min-h-12 items-center gap-3.5 px-3.5 py-2.5 text-xs font-bold uppercase tracking-[0.1em] transition-colors ${
                                    item.active
                                        ? 'border-l-4 border-accent bg-ink-on-dark/10 text-accent-on-dark'
                                        : 'border-l-4 border-transparent text-muted-on-dark hover:bg-ink-on-dark/5 hover:text-ink-on-dark'
                                }`}
                            >
                                <Icon
                                    className={`h-5 w-5 shrink-0 ${
                                        item.active
                                            ? 'text-accent-on-dark'
                                            : 'text-muted-on-dark group-hover:text-accent-on-dark'
                                    }`}
                                    strokeWidth={1.85}
                                    aria-hidden="true"
                                />
                                <span
                                    className={`whitespace-nowrap transition-opacity duration-150 ${
                                        railExpanded ? 'opacity-100' : 'sr-only'
                                    }`}
                                >
                                    {item.label}
                                </span>
                            </Link>
                        );
                    })}
                </nav>

                {/* Bottom: Language Switcher */}
                <div className="border-t border-ink-on-dark/15 p-3 flex justify-center">
                    <LanguageSwitch className="flex-col gap-0.5" />
                </div>
            </aside>

            {/* Content Wrapper Offset by 88px Left Rail on Desktop */}
            <div className="flex min-h-screen flex-1 flex-col lg:pl-[88px] pb-16 lg:pb-0">
                {isPreview ? (
                    <div
                        role="status"
                        data-testid="preview-banner"
                        className="bg-accent px-4 py-2.5 text-center font-heading text-sm font-bold uppercase tracking-[0.1em] text-ink border-b-2 border-ink"
                    >
                        {t('preview_banner')}
                    </div>
                ) : null}

                {/* Mobile Top Identity Bar (< lg) */}
                <header className="sticky top-0 z-30 flex items-center justify-between border-b-2 border-accent bg-surface-dark px-4 py-3 text-ink-on-dark lg:hidden">
                    <Link href={homeHref} className="inline-flex items-center gap-3">
                        <span
                            aria-hidden="true"
                            className="flex h-9 w-9 items-center justify-center bg-accent font-heading text-base font-bold text-ink"
                        >
                            KB
                        </span>
                        <span className="font-heading text-base font-bold uppercase tracking-wider text-ink-on-dark">
                            {company?.name ?? 'PT Korin Bara Energy'}
                        </span>
                    </Link>

                    <Link
                        href={holdingUrl}
                        className="inline-flex min-h-10 items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.08em] text-accent-on-dark"
                    >
                        <ArrowLeft className="h-3.5 w-3.5" aria-hidden="true" />
                        <span>Derco</span>
                    </Link>
                </header>

                <main id="main-content" className="flex-1">
                    {children}
                </main>

                {/* Navy Footer with Bold Brass Gold CTA Block (DESIGN.md 6.3) */}
                <footer className="bg-surface-dark text-ink-on-dark border-t-4 border-accent">
                    <Container className="py-14 md:py-20 space-y-14">
                        {/* Brass Gold CTA Block */}
                        <div className="grid grid-cols-1 gap-6 bg-accent p-7 text-ink sm:p-10 lg:grid-cols-12 lg:items-center">
                            <div className="lg:col-span-8 space-y-2">
                                <p className="font-heading text-xs font-bold uppercase tracking-[0.16em] text-ink">
                                    {locale === 'en'
                                        ? 'FIELD OPERATIONS & COMMERCIAL DESK'
                                        : 'OPERASI LAPANGAN & MEJA KOMERSIAL'}
                                </p>
                                <h2 className="font-heading text-2xl font-bold uppercase tracking-wide text-ink sm:text-3xl">
                                    {company?.name ?? 'PT Korin Bara Energy'}
                                </h2>
                                <p className="max-w-[58ch] text-sm font-medium leading-6 text-ink">
                                    {company?.tagline}
                                </p>
                            </div>
                            <div className="lg:col-span-4 flex flex-wrap items-center gap-4 lg:justify-end">
                                {primaryContact?.phone ? (
                                    <a
                                        href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                        className="inline-flex min-h-11 items-center gap-2 font-heading text-lg font-bold text-ink hover:underline"
                                    >
                                        <Phone className="h-4 w-4" aria-hidden="true" />
                                        <span>{primaryContact.phone}</span>
                                    </a>
                                ) : null}
                                <Button
                                    href={contactUrl}
                                    variant="solid"
                                    size="md"
                                    className="bg-surface-dark text-ink-on-dark hover:bg-surface-deeper font-heading text-sm font-bold uppercase tracking-[0.12em]"
                                >
                                    <span>{t('contact_us')}</span>
                                    <ArrowRight
                                        className="h-4 w-4 text-accent-on-dark"
                                        aria-hidden="true"
                                    />
                                </Button>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 gap-10 lg:grid-cols-12">
                            <div className="lg:col-span-5 space-y-4">
                                <p className="font-heading text-xl font-bold uppercase tracking-wide text-accent-on-dark">
                                    {company?.name ?? 'PT Korin Bara Energy'}
                                </p>
                                <p className="max-w-[46ch] text-sm leading-6 text-muted-on-dark">
                                    {company?.about_short || company?.tagline}
                                </p>
                                <div className="pt-2">
                                    <Link
                                        href={holdingUrl}
                                        className="inline-flex min-h-10 items-center gap-2 text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark hover:underline"
                                    >
                                        <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                                        <span>{t('back_to_holding')}</span>
                                    </Link>
                                </div>
                            </div>

                            <div className="lg:col-span-3 space-y-3">
                                <h3 className="font-heading text-sm font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                    {t('nav.index_heading')}
                                </h3>
                                <ul className="space-y-2 text-sm">
                                    {items.map((item) => (
                                        <li key={item.key}>
                                            <Link
                                                href={item.href}
                                                className="inline-flex min-h-9 items-center text-muted-on-dark hover:text-accent-on-dark"
                                            >
                                                {item.label}
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            </div>

                            <div className="lg:col-span-4 space-y-3">
                                <h3 className="font-heading text-sm font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                    {t('footer.address_heading')}
                                </h3>
                                {primaryContact ? (
                                    <div className="space-y-2 text-sm leading-6 text-muted-on-dark">
                                        <p>{primaryContact.address}</p>
                                        {primaryContact.email ? (
                                            <p>{primaryContact.email}</p>
                                        ) : null}
                                        {primaryContact.operating_hours ? (
                                            <p className="pt-2 text-xs border-t border-ink-on-dark/15">
                                                {primaryContact.operating_hours}
                                            </p>
                                        ) : null}
                                    </div>
                                ) : null}
                            </div>
                        </div>

                        <div className="border-t border-ink-on-dark/15 pt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between text-xs text-muted-on-dark">
                            <p>
                                {`© ${new Date().getFullYear()} ${company?.name ?? 'PT Korin Bara Energy'}. ${t('footer.rights_reserved')}`}
                            </p>
                            <LanguageSwitch />
                        </div>
                    </Container>
                </footer>
            </div>

            {/* Mobile Bottom Navigation Bar: 5 Icons + "Lainnya / More" (DESIGN.md 6.3) */}
            <nav
                aria-label={t('nav.main')}
                className="fixed inset-x-0 bottom-0 z-40 grid grid-cols-6 border-t-2 border-accent bg-surface-dark text-ink-on-dark lg:hidden"
            >
                {mobilePrimaryItems.map((item) => {
                    const Icon = navIconByKey[item.key] ?? Layers;

                    return (
                        <Link
                            key={item.key}
                            href={item.href}
                            aria-current={item.active ? 'page' : undefined}
                            className={`flex min-h-14 flex-col items-center justify-center gap-1 px-1 py-1.5 text-[10px] font-bold uppercase tracking-wider ${
                                item.active
                                    ? 'bg-ink-on-dark/10 text-accent-on-dark'
                                    : 'text-muted-on-dark hover:text-ink-on-dark'
                            }`}
                        >
                            <Icon className="h-4 w-4 shrink-0" aria-hidden="true" />
                            <span className="truncate max-w-full">{item.label}</span>
                        </Link>
                    );
                })}

                <button
                    type="button"
                    onClick={toggleMobileMenu}
                    aria-expanded={isMobileMenuOpen}
                    aria-controls="korin-bara-mobile-more"
                    className="flex min-h-14 flex-col items-center justify-center gap-1 px-1 py-1.5 text-[10px] font-bold uppercase tracking-wider text-muted-on-dark hover:text-accent-on-dark"
                >
                    {isMobileMenuOpen ? (
                        <X className="h-4 w-4 shrink-0" aria-hidden="true" />
                    ) : (
                        <MoreHorizontal className="h-4 w-4 shrink-0" aria-hidden="true" />
                    )}
                    <span className="truncate max-w-full">{t('nav.more')}</span>
                </button>
            </nav>

            {/* Mobile "Lainnya / More" Sheet */}
            <AnimatePresence>
                {isMobileMenuOpen ? (
                    <motion.div
                        id="korin-bara-mobile-more"
                        initial="hidden"
                        animate="visible"
                        exit="hidden"
                        variants={resolvePreset('fadeIn', prefersReducedMotion)}
                        className="fixed inset-x-0 bottom-14 z-40 border-t-2 border-accent bg-surface-dark p-5 text-ink-on-dark shadow-lg lg:hidden"
                    >
                        <div className="space-y-4">
                            {mobileOverflowItems.map((item) => {
                                const Icon = navIconByKey[item.key] ?? Mail;

                                return (
                                    <Link
                                        key={item.key}
                                        href={item.href}
                                        onClick={closeMobileMenu}
                                        className="flex min-h-11 items-center gap-3 border-b border-ink-on-dark/15 pb-3 font-heading text-base font-bold uppercase tracking-wider text-ink-on-dark"
                                    >
                                        <Icon
                                            className="h-4 w-4 text-accent-on-dark"
                                            aria-hidden="true"
                                        />
                                        <span>{item.label}</span>
                                    </Link>
                                );
                            })}

                            <div className="flex items-center justify-between pt-1">
                                <Link
                                    href={holdingUrl}
                                    onClick={closeMobileMenu}
                                    className="inline-flex min-h-11 items-center gap-2 text-xs font-bold uppercase tracking-wider text-accent-on-dark"
                                >
                                    <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                                    <span>{t('back_to_holding')}</span>
                                </Link>
                                <LanguageSwitch />
                            </div>
                        </div>
                    </motion.div>
                ) : null}
            </AnimatePresence>
        </div>
    );
}
