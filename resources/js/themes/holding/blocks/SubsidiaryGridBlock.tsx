import { usePage } from '@inertiajs/react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import { useLocale, type SharedPageProps, type SubsidiaryGridBlockData } from '@/core';
import { useGsapStagger } from '@/motion/gsapLenis';
import { resolvePreset, VIEWPORT_ONCE } from '@/motion/presets';
import { Button, Container, Heading, Image, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface SubsidiaryGridBlockProps {
    data: SubsidiaryGridBlockData;
}

export function SubsidiaryGridBlock({ data }: SubsidiaryGridBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const subsidiaries = props.subsidiaries ?? [];
    const variant = data.layout_variant ?? 'cards';
    const structureGridRef = useRef<HTMLDivElement | null>(null);

    useGsapStagger(structureGridRef, {
        y: 20,
        stagger: 0.09,
        duration: 0.6,
    });

    if (variant === 'structure') {
        const holdingName = props.company?.name ?? 'PT Derco Bara Energy';
        const holdingTagline = props.company?.tagline ?? '';
        const isThreeCols = subsidiaries.length >= 3;
        const railInsetClass = isThreeCols
            ? 'left-[calc(100%/6)] right-[calc(100%/6)]'
            : 'left-1/4 right-1/4';

        return (
            <Section tone="surface" className="border-t border-line">
                <Container>
                    <Reveal preset="fadeUp" className="mx-auto max-w-[680px] text-center space-y-4">
                        {data.eyebrow ? (
                            <div className="flex justify-center">
                                <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine>
                            </div>
                        ) : null}
                        <Heading level={2} size="h2">
                            {data.title}
                        </Heading>
                        {data.description ? (
                            <p className="mx-auto max-w-[58ch] text-base leading-7 text-muted">
                                {data.description}
                            </p>
                        ) : null}
                    </Reveal>

                    <div className="mt-12">
                        {/* Parent Holding Node */}
                        <div className="relative mx-auto max-w-2xl">
                            <div className="rounded-2xl border border-accent/60 bg-surface-dark p-6 text-center text-ink-on-dark shadow-md md:p-8">
                                <span className="text-xs font-semibold uppercase tracking-[0.1em] text-accent-on-dark">
                                    {t('footer.parent_group_label')}
                                </span>
                                <h3 className="mt-2 font-heading text-2xl font-bold text-white sm:text-3xl">
                                    {holdingName}
                                </h3>
                                {holdingTagline ? (
                                    <p className="mx-auto mt-2.5 max-w-[56ch] text-sm leading-6 text-muted-on-dark">
                                        {holdingTagline}
                                    </p>
                                ) : null}
                            </div>

                            {/* Bottom Center Anchor Dot */}
                            {subsidiaries.length > 0 ? (
                                <span
                                    aria-hidden="true"
                                    className="hidden lg:block absolute left-1/2 -bottom-1.5 h-3 w-3 -translate-x-1/2 rounded-full border-2 border-surface bg-accent"
                                />
                            ) : null}
                        </div>

                        {/* Desktop Org-Chart Tree Connector (Parent Stem + Horizontal Distribution Rail) */}
                        {subsidiaries.length > 0 ? (
                            <div aria-hidden="true" className="relative hidden lg:block h-10">
                                {/* Center vertical trunk from Holding down to rail */}
                                <div className="absolute left-1/2 top-0 bottom-0 w-[2px] -translate-x-1/2 bg-accent" />
                                {/* Horizontal rail spanning from first child center to last child center */}
                                {subsidiaries.length > 1 ? (
                                    <div
                                        className={`absolute bottom-0 h-[2px] bg-accent ${railInsetClass}`}
                                    />
                                ) : null}
                            </div>
                        ) : null}

                        {/* Subsidiary Child Cards with Branch Connectors */}
                        <div
                            ref={structureGridRef}
                            className={[
                                'relative mt-8 ml-4 pl-6 border-l-2 border-accent space-y-5 lg:mt-0 lg:ml-0 lg:pl-0 lg:border-l-0 lg:space-y-0 lg:grid lg:gap-6',
                                isThreeCols ? 'lg:grid-cols-3' : 'lg:grid-cols-2',
                            ].join(' ')}
                        >
                            {subsidiaries.map((sub, idx) => (
                                <div key={sub.slug} data-gsap-item className="relative flex flex-col lg:pt-8">
                                    {/* Mobile horizontal branch tick */}
                                    <span
                                        aria-hidden="true"
                                        className="lg:hidden absolute -left-6 top-8 h-[2px] w-6 bg-accent"
                                    />
                                    <span
                                        aria-hidden="true"
                                        className="lg:hidden absolute -left-[29px] top-[27px] h-2.5 w-2.5 rounded-full border-2 border-surface bg-accent"
                                    />

                                    {/* Desktop vertical branch stem from horizontal rail into top center of card */}
                                    <span
                                        aria-hidden="true"
                                        className="hidden lg:block absolute left-1/2 top-0 h-8 w-[2px] -translate-x-1/2 bg-accent"
                                    />
                                    <span
                                        aria-hidden="true"
                                        className="hidden lg:block absolute left-1/2 top-[27px] z-10 h-2.5 w-2.5 -translate-x-1/2 rounded-full border-2 border-bg bg-accent"
                                    />

                                    <motion.article
                                        whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                                        transition={{ type: 'spring', stiffness: 300, damping: 24 }}
                                        className="flex flex-1 flex-col justify-between rounded-2xl border border-line bg-bg p-6 shadow-xs transition-[border-color,box-shadow] hover:border-accent/60 hover:shadow-md"
                                    >
                                        <div className="space-y-3">
                                            <div className="flex items-center justify-between gap-2">
                                                <span className="font-heading text-xs font-bold tabular-nums tracking-[0.08em] text-accent-strong">
                                                    {String(idx + 1).padStart(2, '0')}
                                                </span>
                                                <span className="h-[2px] flex-1 bg-line" aria-hidden="true" />
                                            </div>
                                            <h4 className="font-heading text-lg font-bold text-ink">
                                                {sub.name}
                                            </h4>
                                            <p className="text-sm leading-6 text-muted">
                                                {sub.tagline || sub.about_short}
                                            </p>
                                        </div>
                                        {sub.url ? (
                                            <div className="mt-5 border-t border-line pt-4">
                                                <a
                                                    href={sub.url}
                                                    className="inline-flex min-h-[44px] items-center text-xs font-bold uppercase tracking-[0.1em] text-accent-strong hover:underline"
                                                >
                                                    {t('visit_website')}
                                                </a>
                                            </div>
                                        ) : null}
                                    </motion.article>
                                </div>
                            ))}
                        </div>
                    </div>
                </Container>
            </Section>
        );
    }

    if (variant === 'alternating') {
        return (
            <Section tone="default">
                <Container className="space-y-16 lg:space-y-24">
                    <Reveal preset="fadeUp" className="max-w-[680px] space-y-4">
                        {data.eyebrow ? (
                            <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine>
                        ) : null}
                        <Heading level={2} size="h2">
                            {data.title}
                        </Heading>
                        {data.description ? (
                            <p className="text-base leading-7 text-muted">{data.description}</p>
                        ) : null}
                    </Reveal>

                    <div className="space-y-20 lg:space-y-28">
                        {subsidiaries.map((sub, index) => {
                            const isReversed = index % 2 === 1;

                            return (
                                <article
                                    key={sub.slug}
                                    className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12 border-t border-line pt-12 first:border-t-0 first:pt-0"
                                >
                                    <Reveal
                                        preset="fadeUp"
                                        className={[
                                            'lg:col-span-6',
                                            isReversed ? 'lg:order-2' : 'lg:order-1',
                                        ].join(' ')}
                                    >
                                        <Image
                                            src={sub.featured_image_url}
                                            srcSet={sub.featured_image_srcset}
                                            alt={sub.featured_image_alt || sub.name}
                                            ratio="4/3"
                                            wrapperClassName="shadow-md border-0"
                                        />
                                    </Reveal>

                                    <Reveal
                                        preset="fadeUp"
                                        className={[
                                            'lg:col-span-6 space-y-6',
                                            isReversed
                                                ? 'lg:order-1 lg:pr-6'
                                                : 'lg:order-2 lg:pl-6',
                                        ].join(' ')}
                                    >
                                        <EyebrowLine tone="light">
                                            {`0${index + 1} · ${sub.slug.replaceAll('-', ' ').toUpperCase()}`}
                                        </EyebrowLine>

                                        <Heading level={3} size="h2">
                                            {sub.name}
                                        </Heading>

                                        {sub.tagline ? (
                                            <p className="text-base font-bold leading-7 text-ink">
                                                {sub.tagline}
                                            </p>
                                        ) : null}

                                        {sub.about_short ? (
                                            <p className="text-sm leading-7 text-muted">
                                                {sub.about_short}
                                            </p>
                                        ) : null}

                                        {sub.url ? (
                                            <div className="pt-2">
                                                <Button
                                                    variant="solid"
                                                    size="md"
                                                    href={sub.url}
                                                    className="text-xs font-bold uppercase tracking-[0.12em]"
                                                >
                                                    {t('visit_website')}
                                                </Button>
                                            </div>
                                        ) : null}
                                    </Reveal>
                                </article>
                            );
                        })}
                    </div>
                </Container>
            </Section>
        );
    }

    const isHome = props.page?.key === 'home';
    const staggerVariants = resolvePreset('stagger', prefersReducedMotion);
    const itemVariants = resolvePreset('fadeUp', prefersReducedMotion);
    const gridColsClass =
        subsidiaries.length >= 3
            ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3'
            : 'grid-cols-1 md:grid-cols-2 max-w-[960px] mx-auto';

    return (
        <section
            className={[
                'bg-surface-dark text-ink-on-dark',
                isHome ? 'py-16 md:py-20 lg:py-24' : 'pt-16 pb-16 md:pt-24 md:pb-24 lg:pt-32',
            ].join(' ')}
        >
            <Container>
                {isHome ? (
                    <h2 className="sr-only">{data.title}</h2>
                ) : (
                    <Reveal preset="fadeUp" className="mx-auto max-w-[640px] text-center space-y-4">
                        <Heading level={2} size="h2" className="text-accent-on-dark">
                            {data.title}
                        </Heading>
                        {data.description ? (
                            <p className="text-sm md:text-base leading-7 text-muted-on-dark">
                                {data.description}
                            </p>
                        ) : null}
                    </Reveal>
                )}

                <motion.div
                    initial={prefersReducedMotion ? 'visible' : 'hidden'}
                    whileInView="visible"
                    viewport={VIEWPORT_ONCE}
                    variants={staggerVariants}
                    className={[
                        isHome ? 'grid gap-8 lg:gap-10' : 'mt-12 grid gap-8 lg:gap-10',
                        gridColsClass,
                    ].join(' ')}
                >
                    {subsidiaries.map((sub) => (
                        <motion.article
                            key={sub.slug}
                            variants={itemVariants}
                            whileHover={prefersReducedMotion ? undefined : { y: -8 }}
                            transition={{ type: 'spring', stiffness: 280, damping: 20 }}
                            className="group flex flex-col justify-between overflow-hidden rounded-[var(--radius-card)] border border-ink-on-dark/15 bg-surface-deeper p-6 md:p-7 text-left shadow-md transition-colors hover:border-accent/50 hover:shadow-2xl"
                        >
                            <div>
                                <a
                                    href={sub.url ?? '#'}
                                    className="block overflow-hidden rounded-[calc(var(--radius-card)-4px)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                >
                                    <Image
                                        src={sub.featured_image_url}
                                        srcSet={sub.featured_image_srcset}
                                        alt={sub.featured_image_alt || sub.name}
                                        ratio="4/3"
                                        wrapperClassName="border-0 rounded-none"
                                        className="transition-transform duration-[var(--duration-slow)] group-hover:scale-[1.03]"
                                    />
                                </a>

                                <div className="pt-5 space-y-2.5">
                                    <h3 className="font-heading text-lg md:text-xl font-bold tracking-tight text-ink-on-dark">
                                        <a
                                            href={sub.url ?? '#'}
                                            className="inline-flex min-h-[44px] items-center hover:text-accent-on-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent rounded-[var(--radius-button)]"
                                        >
                                            {sub.name}
                                        </a>
                                    </h3>
                                    <p className="line-clamp-3 text-xs md:text-sm leading-6 text-muted-on-dark">
                                        {sub.about_short || sub.tagline}
                                    </p>
                                </div>
                            </div>

                            {sub.url ? (
                                <div className="pt-6">
                                    <Button
                                        variant="solid"
                                        size="md"
                                        href={sub.url}
                                        className="w-full text-xs font-bold uppercase tracking-[0.12em]"
                                    >
                                        {t('see_details')}
                                    </Button>
                                </div>
                            ) : null}
                        </motion.article>
                    ))}
                </motion.div>

                {data.cta_label && data.cta_href ? (
                    <Reveal preset="fadeUp" className="mt-12 flex justify-center">
                        <Button
                            variant="inverse"
                            size="md"
                            href={data.cta_href}
                            className="text-xs font-bold uppercase tracking-[0.12em]"
                        >
                            {data.cta_label}
                        </Button>
                    </Reveal>
                ) : null}
            </Container>
        </section>
    );
}
