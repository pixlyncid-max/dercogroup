import { usePage } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { useLocale, type HeroBlockData, type SharedPageProps } from '@/core';
import { Button, Container, Counter, Heading, Reveal, Section } from '@/ui';
import { GoldRule } from '../GoldRule';

export interface HeroBlockProps {
    data: HeroBlockData;
}

/**
 * Korin Berkat Hero ("Editorial Index" Split 7/5, DESIGN.md Section 5.4).
 * Left 7 columns: editorial serif typography & actions on white.
 * Right 5 columns: deep navy panel containing 3 large stacked operational statistics.
 */
export function HeroBlock({ data }: HeroBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale } = useLocale();
    const stats = (props.stats ?? props.company?.stats ?? []).slice(0, 3);

    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                <div className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-stretch lg:gap-12">
                    {/* Left 7 Columns: Editorial Introduction */}
                    <Reveal
                        preset="fadeUp"
                        className="flex flex-col justify-between space-y-8 lg:col-span-7 lg:pr-6"
                    >
                        <div className="space-y-6">
                            <div className="flex items-center gap-3">
                                <span className="font-heading text-xs font-semibold tracking-[0.14em] text-accent-strong">
                                    01 / INDEX
                                </span>
                                {data.eyebrow ? (
                                    <span className="text-xs font-semibold uppercase tracking-[0.14em] text-ink">
                                        {data.eyebrow}
                                    </span>
                                ) : null}
                            </div>

                            <GoldRule thick className="w-20" />

                            <Heading
                                level={1}
                                size="display"
                                className="font-semibold tracking-tight"
                            >
                                {data.title}
                            </Heading>

                            <p className="max-w-[58ch] text-base leading-7 text-muted sm:text-lg sm:leading-8">
                                {data.description}
                            </p>
                        </div>

                        {data.primary_cta_label || data.secondary_cta_label ? (
                            <div className="flex flex-wrap items-center gap-4 pt-2">
                                {data.primary_cta_label && data.primary_cta_href ? (
                                    <Button
                                        href={data.primary_cta_href}
                                        variant="solid"
                                        size="lg"
                                        className="bg-surface-dark text-ink-on-dark hover:bg-surface-deeper text-xs font-semibold uppercase tracking-[0.12em]"
                                    >
                                        <span>{data.primary_cta_label}</span>
                                        <ArrowRight
                                            className="h-4 w-4 text-accent-on-dark"
                                            aria-hidden="true"
                                        />
                                    </Button>
                                ) : null}

                                {data.secondary_cta_label && data.secondary_cta_href ? (
                                    <Button
                                        href={data.secondary_cta_href}
                                        variant="outline"
                                        size="lg"
                                        className="text-xs font-semibold uppercase tracking-[0.12em]"
                                    >
                                        {data.secondary_cta_label}
                                    </Button>
                                ) : null}
                            </div>
                        ) : null}
                    </Reveal>

                    {/* Right 5 Columns: Navy Panel with 3 Large Stacked Statistics */}
                    <Reveal
                        preset="fadeIn"
                        className="lg:col-span-5 flex flex-col justify-between rounded-[var(--radius-card)] bg-surface-dark p-7 text-ink-on-dark border-t-4 border-accent sm:p-9"
                    >
                        <div className="flex items-center justify-between border-b border-ink-on-dark/15 pb-4">
                            <span className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                {locale === 'en'
                                    ? 'KEY OPERATIONAL FIGURES'
                                    : 'DATA KUNCI OPERASIONAL'}
                            </span>
                            <span className="font-heading text-xs font-semibold text-muted-on-dark">
                                I – III
                            </span>
                        </div>

                        <div className="divide-y divide-ink-on-dark/15 my-2">
                            {stats.map((stat, idx) => (
                                <div key={stat.id} className="py-5 first:pt-4 last:pb-2">
                                    <div className="flex items-baseline justify-between gap-4">
                                        <div className="flex items-baseline gap-2">
                                            <Counter
                                                value={stat.value}
                                                prefix={stat.prefix}
                                                suffix={stat.suffix}
                                                locale={locale}
                                                className="font-heading text-3xl font-semibold tracking-tight text-ink-on-dark sm:text-4xl"
                                            />
                                            {stat.unit ? (
                                                <span className="text-xs font-semibold uppercase tracking-[0.08em] text-accent-on-dark">
                                                    {stat.unit}
                                                </span>
                                            ) : null}
                                        </div>
                                        <span className="font-heading text-xs font-semibold text-accent-on-dark">
                                            {`0${idx + 1}`}
                                        </span>
                                    </div>
                                    <p className="mt-1.5 text-xs font-medium uppercase tracking-[0.08em] text-muted-on-dark">
                                        {stat.label}
                                    </p>
                                </div>
                            ))}
                        </div>

                        <GoldRule tone="dark" className="w-full opacity-50" />
                    </Reveal>
                </div>
            </Container>
        </Section>
    );
}
