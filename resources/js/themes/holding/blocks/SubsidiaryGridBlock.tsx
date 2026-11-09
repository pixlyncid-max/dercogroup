import { usePage } from '@inertiajs/react';
import { motion, useReducedMotion } from 'motion/react';
import { useLocale, type SharedPageProps, type SubsidiaryGridBlockData } from '@/core';
import { resolvePreset, VIEWPORT_ONCE } from '@/motion/presets';
import { Button, Container, Counter, Heading, Image, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface SubsidiaryGridBlockProps {
    data: SubsidiaryGridBlockData;
}

export function SubsidiaryGridBlock({ data }: SubsidiaryGridBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const subsidiaries = props.subsidiaries ?? [];
    const variant = data.layout_variant ?? 'cards';

    if (variant === 'structure') {
        const holdingName = props.company?.name ?? 'PT Derco Bara Energy';
        const holdingTagline = props.company?.tagline ?? '';

        return (
            <Section tone="surface" className="border-t border-line">
                <Container>
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

                    <div className="mt-12 space-y-8">
                        <div className="rounded-[var(--radius-card)] border-2 border-accent bg-surface-dark p-6 md:p-8 text-ink-on-dark">
                            <span className="text-xs font-semibold uppercase tracking-[0.12em] text-accent-on-dark">
                                Holding Entity
                            </span>
                            <h3 className="mt-2 font-heading text-2xl font-bold">{holdingName}</h3>
                            {holdingTagline ? (
                                <p className="mt-2 max-w-[65ch] text-sm leading-6 text-muted-on-dark">
                                    {holdingTagline}
                                </p>
                            ) : null}
                        </div>

                        <div
                            className={[
                                'grid grid-cols-1 gap-6 pl-4 md:pl-8 border-l-2 border-accent',
                                subsidiaries.length >= 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-2',
                            ].join(' ')}
                        >
                            {subsidiaries.map((sub) => (
                                <div
                                    key={sub.slug}
                                    className="flex flex-col justify-between rounded-[var(--radius-card)] border border-line bg-bg p-6 shadow-sm"
                                >
                                    <div className="space-y-3">
                                        <span className="inline-block h-[2px] w-8 bg-accent" />
                                        <h4 className="font-heading text-lg font-bold text-ink">
                                            {sub.name}
                                        </h4>
                                        <p className="text-sm leading-6 text-muted">
                                            {sub.tagline || sub.about_short}
                                        </p>
                                    </div>
                                    {sub.url ? (
                                        <div className="pt-6">
                                            <a
                                                href={sub.url}
                                                className="inline-flex min-h-[44px] items-center text-xs font-bold uppercase tracking-[0.12em] text-accent-strong hover:underline"
                                            >
                                                {t('visit_website')} →
                                            </a>
                                        </div>
                                    ) : null}
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
                            const keyStats = (sub.stats ?? []).slice(0, 3);

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
                                            <p className="text-base font-semibold leading-7 text-ink">
                                                {sub.tagline}
                                            </p>
                                        ) : null}

                                        {sub.about_short ? (
                                            <p className="text-sm leading-7 text-muted">
                                                {sub.about_short}
                                            </p>
                                        ) : null}

                                        {keyStats.length > 0 ? (
                                            <dl className="grid grid-cols-3 gap-4 border-y border-line py-5">
                                                {keyStats.map((stat) => (
                                                    <div key={stat.id} className="space-y-1">
                                                        <dt className="text-xs font-medium text-muted">
                                                            {stat.unit}
                                                        </dt>
                                                        <dd className="font-heading text-xl md:text-2xl font-bold text-accent-strong">
                                                            <Counter
                                                                value={stat.value}
                                                                prefix={stat.prefix}
                                                                suffix={stat.suffix}
                                                                locale={
                                                                    locale === 'en'
                                                                        ? 'en-US'
                                                                        : 'id-ID'
                                                                }
                                                            />
                                                        </dd>
                                                    </div>
                                                ))}
                                            </dl>
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

    const staggerVariants = resolvePreset('stagger', prefersReducedMotion);
    const itemVariants = resolvePreset('fadeUp', prefersReducedMotion);
    const gridColsClass =
        subsidiaries.length >= 3
            ? 'grid-cols-1 md:grid-cols-3'
            : 'grid-cols-1 md:grid-cols-2 max-w-[920px] mx-auto';

    return (
        <section className="bg-surface-dark text-ink-on-dark pt-16 pb-16 md:pt-24 md:pb-24 lg:pt-32">
            <Container>
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

                <motion.div
                    initial={prefersReducedMotion ? 'visible' : 'hidden'}
                    whileInView="visible"
                    viewport={VIEWPORT_ONCE}
                    variants={staggerVariants}
                    className={['mt-12 grid gap-8 lg:gap-10', gridColsClass].join(' ')}
                >
                    {subsidiaries.map((sub) => (
                        <motion.article
                            key={sub.slug}
                            variants={itemVariants}
                            className="group flex flex-col text-center"
                        >
                            <a
                                href={sub.url ?? '#'}
                                className="block overflow-hidden rounded-[var(--radius-card)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
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

                            <div className="flex flex-1 flex-col justify-between px-3 pt-6">
                                <div className="space-y-3">
                                    <h3 className="font-heading text-xs md:text-sm font-bold uppercase tracking-[0.14em] text-ink-on-dark">
                                        <a
                                            href={sub.url ?? '#'}
                                            className="hover:text-accent-on-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                        >
                                            {sub.name}
                                        </a>
                                    </h3>
                                    <p className="text-xs md:text-sm leading-6 text-muted-on-dark">
                                        {sub.tagline || sub.about_short}
                                    </p>
                                </div>

                                {sub.url ? (
                                    <div className="pt-5">
                                        <a
                                            href={sub.url}
                                            className="inline-flex min-h-[44px] items-center justify-center text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                        >
                                            {t('see_details')} →
                                        </a>
                                    </div>
                                ) : null}
                            </div>
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
