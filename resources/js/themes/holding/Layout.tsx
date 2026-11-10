import type { ReactNode } from 'react';
import { usePage } from '@inertiajs/react';
import { Menu, X } from 'lucide-react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import { useLocale, useNavigation, type SharedPageProps } from '@/core';
import { DURATION, EASE_OUT } from '@/motion/presets';
import { Container, LanguageSwitch, Logo } from '@/ui';

export interface HoldingLayoutProps {
    children: ReactNode;
}

export function Layout({ children }: HoldingLayoutProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const { items, holdingUrl, isSticky, isMobileMenuOpen, openMobileMenu, closeMobileMenu } =
        useNavigation();
    const prefersReducedMotion = Boolean(useReducedMotion());

    const company = props.company;
    const subsidiaries = props.subsidiaries ?? [];
    const primaryContact = (props.contacts ?? company?.contacts ?? [])[0];
    const currentYear = new Date().getFullYear();

    const middleNavItems = items.filter((item) => item.key !== 'contact');
    const contactNavItem = items.find((item) => item.key === 'contact');
    const orderedMobileItems = [...middleNavItems, ...(contactNavItem ? [contactNavItem] : [])];

    return (
        <div data-theme="holding" className="min-h-dvh flex flex-col bg-bg text-ink">
            <a
                href="#main-content"
                className="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-[var(--radius-button)] focus:bg-surface-dark focus:px-4 focus:py-2.5 focus:text-sm focus:font-bold focus:text-ink-on-dark focus:shadow-lg"
            >
                {t('skip_to_content')}
            </a>

            {props.isPreview ? (
                <div
                    role="status"
                    className="bg-accent-strong px-4 py-2 text-center text-xs font-bold tracking-wide text-on-accent"
                >
                    {t('preview_banner')}
                </div>
            ) : null}

            <header
                className={[
                    'sticky top-0 z-[var(--z-index-overlay)] bg-surface-dark text-ink-on-dark border-b border-ink-on-dark/10 transition-[min-height,box-shadow] duration-[var(--duration-base)]',
                    isSticky ? 'min-h-[60px] shadow-md' : 'min-h-[80px]',
                ].join(' ')}
            >
                <Container
                    size="wide"
                    className={[
                        'flex items-center justify-between gap-6 transition-[min-height] duration-[var(--duration-base)]',
                        isSticky ? 'min-h-[60px]' : 'min-h-[80px]',
                    ].join(' ')}
                >
                    <a
                        href={holdingUrl}
                        aria-label={
                            props.locale === 'en'
                                ? `${company?.name ?? 'PT Derco Bara Energy'}, Home`
                                : `${company?.name ?? 'PT Derco Bara Energy'}, Beranda`
                        }
                        className="inline-flex min-h-[44px] items-center focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2"
                    >
                        <Logo
                            company={company}
                            on="dark"
                            size={isSticky ? 36 : 48}
                            priority={true}
                            className="transition-all duration-300 motion-reduce:transition-none"
                        />
                    </a>

                    <nav aria-label={t('nav.main')} className="hidden lg:flex items-center gap-7">
                        {middleNavItems.map((item) => (
                            <a
                                key={item.key}
                                href={item.href}
                                aria-current={item.active ? 'page' : undefined}
                                className={[
                                    'inline-flex min-h-[44px] items-center border-b-2 text-xs font-bold uppercase tracking-[0.12em] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2',
                                    item.active
                                        ? 'border-accent text-accent-on-dark'
                                        : 'border-transparent text-ink-on-dark/80 hover:text-ink-on-dark',
                                ].join(' ')}
                            >
                                {item.label}
                            </a>
                        ))}
                    </nav>

                    <div className="hidden lg:flex items-center gap-6">
                        <LanguageSwitch />
                        {contactNavItem ? (
                            <a
                                key={contactNavItem.key}
                                href={contactNavItem.href}
                                aria-current={contactNavItem.active ? 'page' : undefined}
                                className={[
                                    'inline-flex min-h-[44px] items-center border-b-2 text-xs font-bold uppercase tracking-[0.12em] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2',
                                    contactNavItem.active
                                        ? 'border-accent text-accent-on-dark'
                                        : 'border-transparent text-ink-on-dark/80 hover:text-ink-on-dark',
                                ].join(' ')}
                            >
                                {contactNavItem.label}
                            </a>
                        ) : null}
                    </div>

                    <div className="flex items-center gap-2 lg:hidden">
                        <LanguageSwitch />
                        <button
                            type="button"
                            onClick={openMobileMenu}
                            aria-expanded={isMobileMenuOpen}
                            aria-controls="holding-mobile-menu"
                            aria-label={t('nav.open_menu')}
                            className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-[var(--radius-button)] text-ink-on-dark hover:bg-ink-on-dark/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                        >
                            <Menu className="h-5 w-5" aria-hidden="true" />
                        </button>
                    </div>
                </Container>
            </header>

            <AnimatePresence>
                {isMobileMenuOpen ? (
                    <div className="fixed inset-0 z-[var(--z-index-modal)] lg:hidden">
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{
                                duration: prefersReducedMotion ? DURATION.fast : DURATION.base,
                            }}
                            onClick={closeMobileMenu}
                            className="fixed inset-0 bg-surface-deeper/70"
                            aria-hidden="true"
                        />
                        <motion.aside
                            id="holding-mobile-menu"
                            role="dialog"
                            aria-modal="true"
                            aria-label={t('nav.main')}
                            initial={prefersReducedMotion ? { opacity: 0 } : { x: '100%' }}
                            animate={prefersReducedMotion ? { opacity: 1 } : { x: 0 }}
                            exit={prefersReducedMotion ? { opacity: 0 } : { x: '100%' }}
                            transition={{
                                duration: prefersReducedMotion ? DURATION.fast : DURATION.base,
                                ease: EASE_OUT,
                            }}
                            className="fixed inset-y-0 right-0 flex w-full max-w-xs flex-col justify-between bg-surface-dark p-6 text-ink-on-dark shadow-md"
                        >
                            <div className="space-y-8">
                                <div className="flex items-center justify-between border-b border-ink-on-dark/10 pb-4">
                                    <Logo company={company} on="dark" size={32} />
                                    <button
                                        type="button"
                                        onClick={closeMobileMenu}
                                        aria-label={t('nav.close_menu')}
                                        className="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-[var(--radius-button)] text-ink-on-dark hover:bg-ink-on-dark/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                    >
                                        <X className="h-5 w-5" aria-hidden="true" />
                                    </button>
                                </div>

                                <nav aria-label={t('nav.main')} className="flex flex-col gap-2">
                                    {orderedMobileItems.map((item) => (
                                        <a
                                            key={item.key}
                                            href={item.href}
                                            onClick={closeMobileMenu}
                                            aria-current={item.active ? 'page' : undefined}
                                            className={[
                                                'flex min-h-[44px] items-center border-l-2 px-3 text-xs font-bold uppercase tracking-[0.12em] transition-colors',
                                                item.active
                                                    ? 'border-accent text-accent-on-dark bg-ink-on-dark/5'
                                                    : 'border-transparent text-ink-on-dark/80 hover:text-ink-on-dark',
                                            ].join(' ')}
                                        >
                                            {item.label}
                                        </a>
                                    ))}
                                </nav>
                            </div>
                        </motion.aside>
                    </div>
                ) : null}
            </AnimatePresence>

            <main id="main-content" className="flex-1">
                {children}
            </main>

            <footer className="bg-surface-deeper text-ink-on-dark pt-16 pb-12">
                <Container>
                    <div className="grid grid-cols-1 gap-10 md:grid-cols-12 pb-12 border-b border-ink-on-dark/10">
                        <div className="md:col-span-5 space-y-5">
                            <Logo company={company} on="dark" size={44} />
                            <p className="max-w-[44ch] text-sm leading-6 text-muted-on-dark">
                                {company?.about_short || company?.tagline}
                            </p>
                        </div>

                        <div className="md:col-span-4 space-y-3">
                            <h2 className="text-xs font-bold uppercase tracking-[0.12em] text-ink-on-dark">
                                {t('footer.address_heading')}
                            </h2>
                            {primaryContact ? (
                                <div className="space-y-2 text-xs leading-6 text-muted-on-dark">
                                    <p>{primaryContact.address}</p>
                                    {primaryContact.phone ? <p>{primaryContact.phone}</p> : null}
                                    {primaryContact.email ? <p>{primaryContact.email}</p> : null}
                                </div>
                            ) : null}
                        </div>

                        <div className="md:col-span-3 space-y-3">
                            <h2 className="text-xs font-bold uppercase tracking-[0.12em] text-ink-on-dark">
                                {t('footer.hours_heading')}
                            </h2>
                            {primaryContact?.operating_hours ? (
                                <p className="text-xs leading-6 text-muted-on-dark">
                                    {primaryContact.operating_hours}
                                </p>
                            ) : null}
                        </div>
                    </div>

                    <div className="flex flex-col gap-4 pt-8 text-xs text-muted-on-dark md:flex-row md:items-center md:justify-between">
                        <p>
                            © {currentYear} {company?.name ?? 'PT Derco Bara Energy'}.{' '}
                            {t('footer.rights_reserved')}
                        </p>

                        {subsidiaries.length > 0 ? (
                            <div className="flex flex-wrap items-center gap-x-6 gap-y-2">
                                {subsidiaries.map((sub) => (
                                    <a
                                        key={sub.slug}
                                        href={sub.url ?? holdingUrl}
                                        className="inline-flex min-h-[44px] items-center px-2 py-2 text-xs text-accent-on-dark hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                    >
                                        {sub.name}
                                    </a>
                                ))}
                            </div>
                        ) : null}
                    </div>
                </Container>
            </footer>
        </div>
    );
}
