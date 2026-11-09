import { Head, usePage } from '@inertiajs/react';
import { useLocale } from '@/core/i18n';
import type { SharedPageProps } from '@/core/types';
import { Button, Container, Eyebrow, Heading, Image, LanguageSwitch, Reveal, Section } from '@/ui';

export default function Home() {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const theme = props.company?.theme ?? 'holding';

    return (
        <div data-theme={theme} className="min-h-dvh flex flex-col">
            <Head title={t('foundation.title')} />

            <a
                href="#main-content"
                className="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[var(--z-index-modal)] focus:rounded-[var(--radius-button)] focus:bg-bg focus:px-4 focus:py-2.5 focus:text-sm focus:font-semibold focus:text-ink focus:ring-2 focus:ring-accent"
            >
                {t('skip_to_content')}
            </a>

            <header className="border-b border-line/15 bg-surface-dark text-ink-on-dark">
                <Container className="flex min-h-[72px] items-center justify-between gap-6">
                    <div className="flex items-center gap-3">
                        <span aria-hidden="true" className="inline-block h-3 w-3 bg-accent" />
                        <span className="font-heading text-base font-bold tracking-tight">
                            {props.company?.name ?? 'PT Derco Bara Energy'}
                        </span>
                    </div>

                    <div className="flex items-center gap-4">
                        <LanguageSwitch />
                        <Button variant="inverse" size="md" href={`/${locale}#contact`}>
                            {t('contact_us')}
                        </Button>
                    </div>
                </Container>
            </header>

            <main id="main-content" className="flex-1">
                <Section tone="dark">
                    <Container size="wide">
                        <div className="grid grid-cols-1 items-center gap-12 lg:grid-cols-12">
                            <Reveal preset="fadeUp" className="lg:col-span-6 space-y-6">
                                <Eyebrow tone="dark">{t('foundation.eyebrow')}</Eyebrow>
                                <Heading level={1} size="display">
                                    {t('foundation.title')}
                                </Heading>
                                <p className="max-w-[65ch] text-base leading-7 text-muted-on-dark">
                                    {t('foundation.description')}
                                </p>
                                <div className="flex flex-wrap items-center gap-4 pt-2">
                                    <Button variant="solid" size="lg" href={`/${locale}#overview`}>
                                        {t('foundation.cta_primary')}
                                    </Button>
                                </div>
                            </Reveal>

                            <Reveal preset="fadeIn" className="lg:col-span-6">
                                <Image ratio="16/9" priority alt={t('foundation.image_alt')} />
                            </Reveal>
                        </div>
                    </Container>
                </Section>

                <Section id="overview" tone="default" className="border-t border-line">
                    <Container>
                        <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12">
                            <Reveal preset="slideInLeft" className="lg:col-span-5">
                                <Image ratio="4/3" alt={t('foundation.image_alt')} />
                            </Reveal>

                            <Reveal preset="fadeUp" className="lg:col-span-7 space-y-6 lg:pl-6">
                                <Eyebrow tone="light">{t('foundation.section_eyebrow')}</Eyebrow>
                                <Heading level={2} size="h2">
                                    {t('foundation.section_title')}
                                </Heading>
                                <p className="max-w-[65ch] text-base leading-7 text-muted">
                                    {t('foundation.section_body')}
                                </p>
                            </Reveal>
                        </div>
                    </Container>
                </Section>
            </main>
        </div>
    );
}
