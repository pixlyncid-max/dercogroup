import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowUpRight, Mail, Menu, Phone, X } from 'lucide-react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import { useRef, type ReactNode } from 'react';
import { useLocale, useNavigation, type SharedPageProps } from '@/core';
import { resolvePreset } from '@/motion/presets';
import { Container, LanguageSwitch, Logo } from '@/ui';
import { useKorinBerkatSmoothScroll } from './useKorinBerkatMotion';

export interface KorinBerkatLayoutProps {
    children: ReactNode;
}

function formatEditorialIndex(index: number): string {
    return String(index + 1).padStart(2, '0');
}

/**
 * PT Korin Berkat Energy Layout.
 * Executive Dark Navy header with gold accents, Lenis smooth scroll + GSAP scroll progress bar,
 * Motion micro-interactions, and a structured dark navy corporate footer.
 */
export function Layout({ children }: KorinBerkatLayoutProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const { items, holdingUrl, contactUrl, isMobileMenuOpen, closeMobileMenu, toggleMobileMenu } =
        useNavigation();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const progressBarRef = useRef<HTMLDivElement | null>(null);

    useKorinBerkatSmoothScroll(progressBarRef);

    const company = props.company;
    const contacts = props.contacts ?? company?.contacts ?? [];
    const primaryContact = contacts[0];
    const isPreview = Boolean(props.isPreview);

    const homeHref = items[0]?.href ?? `/${locale}`;

    return (
        <div
            data-theme="korin-berkat"
            className="min-h-screen flex flex-col bg-bg text-ink font-body selection:bg-accent selection:text-on-accent"
        >
            <a
                href="#main-content"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-[var(--radius-button)] focus:bg-accent focus:px-4 focus:py-2.5 focus:text-sm focus:font-bold focus:text-on-accent focus:shadow-lg"
            >
                {t('skip_to_content')}
            </a>

            {isPreview ? (
                <div
                    role="status"
                    className="bg-accent px-4 py-2 text-center text-xs font-bold uppercase tracking-[0.12em] text-on-accent"
                >
                    {t('preview_banner')}
                </div>
            ) : null}

            <header
                id="section-header"
                data-section="header"
                className="sticky top-0 z-40 bg-surface-dark/95 text-ink-on-dark backdrop-blur-md shadow-[0_6px_24px_-8px_rgba(0,0,0,0.45)]"
            >
                {/* GSAP Scroll Progress Bar */}
                <div
                    ref={progressBarRef}
                    aria-hidden="true"
                    className="h-[2.5px] w-full origin-left scale-x-0 bg-gradient-to-r from-[#d49b22] via-[#f0b941] to-[#eab338]"
                />

                {/* Top Subtle Utility Bar: Parent Holding Back-Link & Phone */}
                <div className="border-b border-white/10 bg-[#07101f]">
                    <Container className="flex min-h-9 items-center justify-between gap-4 py-0.5">
                        <Link
                            href={holdingUrl}
                            className="inline-flex min-h-[44px] items-center gap-2 text-[11px] font-semibold tracking-[0.04em] text-slate-300 transition-colors hover:text-accent-on-dark"
                        >
                            <ArrowLeft
                                className="h-3.5 w-3.5 text-accent-on-dark"
                                aria-hidden="true"
                            />
                            <span>{t('holding_short')}</span>
                        </Link>

                        {primaryContact?.phone ? (
                            <a
                                href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                className="hidden sm:inline-flex min-h-[36px] items-center gap-1.5 text-[11px] font-medium text-slate-300 transition-colors hover:text-white"
                            >
                                <Phone className="h-3 w-3 text-accent-on-dark" aria-hidden="true" />
                                <span>{primaryContact.phone}</span>
                            </a>
                        ) : null}
                    </Container>
                </div>

                {/* Main Executive Dark Navy Navigation Bar */}
                <div className="border-b border-white/10">
                    <Container className="flex h-[74px] items-center justify-between gap-4 lg:gap-6">
                        <Link
                            href={homeHref}
                            aria-label={
                                locale === 'en'
                                    ? `${company?.name ?? 'PT Korin Berkat Energy'}, Home`
                                    : `${company?.name ?? 'PT Korin Berkat Energy'}, Beranda`
                            }
                            className="group inline-flex shrink-0 items-center gap-3 py-1.5 text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                        >
                            <Logo company={company} on="dark" size={40} priority={true} />
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
                                        className={`group relative inline-flex min-h-11 items-center gap-1.5 py-2 text-[13px] transition-colors ${
                                            item.active
                                                ? 'font-bold text-accent-on-dark'
                                                : 'font-medium text-slate-200 hover:text-white'
                                        }`}
                                    >
                                        <span
                                            className={`font-heading text-[10px] font-bold tracking-[0.08em] ${
                                                item.active
                                                    ? 'text-accent-on-dark'
                                                    : 'text-slate-400 group-hover:text-accent-on-dark'
                                            }`}
                                        >
                                            {indexCode}
                                        </span>
                                        <span>{item.label}</span>
                                        {item.active ? (
                                            <motion.span
                                                layoutId="kbe-nav-active-underline"
                                                aria-hidden="true"
                                                transition={{
                                                    type: 'spring',
                                                    stiffness: 380,
                                                    damping: 32,
                                                }}
                                                className="absolute inset-x-0 bottom-1 h-[2px] rounded-full bg-accent"
                                            />
                                        ) : (
                                            <span
                                                aria-hidden="true"
                                                className="absolute inset-x-0 bottom-1 h-[2px] scale-x-0 rounded-full bg-accent/60 transition-transform duration-200 group-hover:scale-x-100"
                                            />
                                        )}
                                    </Link>
                                );
                            })}
                        </nav>

                        {/* Right: Language Switch + Gold CTA Button */}
                        <div className="hidden xl:flex xl:items-center xl:gap-4">
                            <div className="h-5 w-px bg-white/15" aria-hidden="true" />
                            <LanguageSwitch tone="dark" />
                            <Link
                                href={contactUrl}
                                className="kbe-btn-gold min-h-[40px] px-4 py-2 text-xs"
                            >
                                <span>{t('contact_us')}</span>
                                <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        </div>

                        {/* Mobile Controls */}
                        <div className="flex items-center gap-2.5 xl:hidden">
                            <LanguageSwitch tone="dark" />
                            <button
                                type="button"
                                onClick={toggleMobileMenu}
                                aria-expanded={isMobileMenuOpen}
                                aria-controls="korin-berkat-mobile-nav"
                                aria-label={
                                    isMobileMenuOpen ? t('nav.close_menu') : t('nav.open_menu')
                                }
                                className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-[var(--radius-button)] border border-white/15 bg-white/5 text-white transition-colors hover:border-accent hover:bg-white/10"
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

                {/* Mobile Drawer */}
                <AnimatePresence>
                    {isMobileMenuOpen ? (
                        <motion.div
                            id="korin-berkat-mobile-nav"
                            initial="hidden"
                            animate="visible"
                            exit="hidden"
                            variants={resolvePreset('fadeIn', prefersReducedMotion)}
                            className="border-b border-white/10 bg-surface-dark xl:hidden"
                        >
                            <Container className="py-5 space-y-4">
                                <nav aria-label={t('nav.main')} className="divide-y divide-white/10">
                                    {items.map((item, idx) => (
                                        <Link
                                            key={item.key}
                                            href={item.href}
                                            onClick={closeMobileMenu}
                                            aria-current={item.active ? 'page' : undefined}
                                            className={`flex min-h-11 items-center justify-between py-3 text-sm transition-colors ${
                                                item.active
                                                    ? 'font-bold text-accent-on-dark'
                                                    : 'font-medium text-slate-200 hover:text-white'
                                            }`}
                                        >
                                            <span className="inline-flex items-center gap-3">
                                                <span className="font-heading text-xs font-bold text-accent-on-dark">
                                                    {formatEditorialIndex(idx)}
                                                </span>
                                                <span>{item.label}</span>
                                            </span>
                                            {item.active ? (
                                                <span
                                                    aria-hidden="true"
                                                    className="h-2 w-2 rounded-full bg-accent"
                                                />
                                            ) : null}
                                        </Link>
                                    ))}
                                </nav>

                                <div className="pt-2">
                                    <Link
                                        href={contactUrl}
                                        onClick={closeMobileMenu}
                                        className="kbe-btn-gold w-full justify-center"
                                    >
                                        <span>{t('contact_us')}</span>
                                        <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                                    </Link>
                                </div>
                            </Container>
                        </motion.div>
                    ) : null}
                </AnimatePresence>
            </header>

            <main id="main-content" className="flex-1">
                {children}
            </main>

            {/* Executive Dark Navy Footer */}
            <footer
                id="section-footer"
                data-section="footer"
                className="relative overflow-hidden border-t border-white/10 bg-surface-dark text-ink-on-dark"
            >
                {/* Decorative Subtle Diagonal Gold Wedge */}
                <div
                    aria-hidden="true"
                    className="kbe-diagonal-wedge-right pointer-events-none absolute right-0 bottom-0 h-20 w-24 opacity-90"
                />

                <Container className="relative z-10 py-14 md:py-16">
                    <div className="grid grid-cols-1 gap-10 lg:grid-cols-12">
                        {/* Column 1: Entity Identity & Parent Holding Back-link */}
                        <div className="lg:col-span-5 space-y-5 lg:border-r lg:border-white/10 lg:pr-8">
                            <Logo company={company} on="dark" size={44} />
                            <p className="max-w-[42ch] text-sm leading-6 text-muted-on-dark">
                                {company?.about_short || company?.tagline}
                            </p>
                            <div className="pt-1">
                                <p className="text-[11px] font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                    {t('footer.parent_group_label')}
                                </p>
                                <Link
                                    href={holdingUrl}
                                    className="mt-1.5 inline-flex min-h-10 items-center gap-2 text-sm font-semibold text-white transition-colors hover:text-accent-on-dark"
                                >
                                    <ArrowLeft
                                        className="h-4 w-4 text-accent-on-dark"
                                        aria-hidden="true"
                                    />
                                    <span>{t('back_to_holding')}</span>
                                </Link>
                            </div>
                        </div>

                        {/* Column 2: Numbered Page Index 01..05 */}
                        <div className="lg:col-span-3 space-y-4">
                            <h2 className="text-xs font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                {t('nav.index_heading')}
                            </h2>
                            <ul className="space-y-1.5 text-sm">
                                {items.map((item, idx) => (
                                    <li key={item.key}>
                                        <Link
                                            href={item.href}
                                            className="inline-flex min-h-[40px] items-center gap-2.5 text-slate-300 transition-colors hover:text-accent-on-dark"
                                        >
                                            <span className="font-heading text-[11px] font-bold text-accent-on-dark">
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
                            <h2 className="text-xs font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                {t('footer.address_heading')}
                            </h2>
                            {primaryContact ? (
                                <div className="space-y-3 text-sm leading-6 text-slate-300">
                                    <p>{primaryContact.address}</p>
                                    {primaryContact.phone ? (
                                        <p className="flex items-center gap-2">
                                            <Phone
                                                className="h-4 w-4 shrink-0 text-accent-on-dark"
                                                aria-hidden="true"
                                            />
                                            <a
                                                href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                                className="font-semibold text-white transition-colors hover:text-accent-on-dark"
                                            >
                                                {primaryContact.phone}
                                            </a>
                                        </p>
                                    ) : null}
                                    {primaryContact.email ? (
                                        <p className="flex items-center gap-2">
                                            <Mail
                                                className="h-4 w-4 shrink-0 text-accent-on-dark"
                                                aria-hidden="true"
                                            />
                                            <a
                                                href={`mailto:${primaryContact.email}`}
                                                className="transition-colors hover:text-accent-on-dark"
                                            >
                                                {primaryContact.email}
                                            </a>
                                        </p>
                                    ) : null}
                                    {primaryContact.operating_hours ? (
                                        <p className="border-t border-white/10 pt-2.5 text-xs text-slate-400">
                                            {primaryContact.operating_hours}
                                        </p>
                                    ) : null}
                                </div>
                            ) : null}
                            <div className="pt-2">
                                <Link
                                    href={contactUrl}
                                    className="kbe-btn-gold min-h-[40px] px-4 py-2 text-xs"
                                >
                                    <span>{t('contact_us')}</span>
                                    <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div className="mt-12 flex flex-col gap-4 border-t border-white/10 pt-7 text-xs text-slate-400 sm:flex-row sm:items-center sm:justify-between">
                        <p>
                            {`© ${new Date().getFullYear()} ${company?.name ?? 'PT Korin Berkat Energy'}. ${t('footer.rights_reserved')}`}
                        </p>
                        <LanguageSwitch tone="dark" />
                    </div>
                </Container>
            </footer>
        </div>
    );
}
