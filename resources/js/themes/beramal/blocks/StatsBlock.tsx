import { usePage } from '@inertiajs/react';
import { useLocale, type SharedPageProps, type StatsBlockData } from '@/core';
import { Container, Counter, Reveal, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';
import { WaveDivider } from '../WaveDivider';

export interface StatsBlockProps {
    data: StatsBlockData;
}

/**
 * Beramal Sukses Sejahtera Key Figures in Capsules ("Calm Flow", DESIGN.md Section 7.4).
 * Renders each operational metric inside a rounded pill capsule on a Mist surface.
 */
export function StatsBlock({ data }: StatsBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale } = useLocale();
    const stats = props.stats ?? props.company?.stats ?? [];

    if (stats.length === 0) {
        return null;
    }

    return (
        <>
            <WaveDivider tone="surface" />
            <Section tone="surface">
                <Container>
                    <CalmHeader
                        eyebrow={data.eyebrow}
                        title={data.title}
                        description={data.description}
                        pillTone="bg"
                    />

                    <div className="mt-12 grid grid-cols-1 gap-5 md:grid-cols-2">
                        {stats.map((stat, idx) => (
                            <Reveal
                                key={stat.id}
                                preset="fadeUp"
                                className="flex flex-col justify-between gap-4 rounded-[var(--radius-card)] border border-line bg-bg px-6 py-6 shadow-sm sm:flex-row sm:items-center sm:rounded-full sm:px-8 sm:py-5"
                            >
                                <div className="flex items-center gap-4">
                                    <span
                                        aria-hidden="true"
                                        className="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-surface font-heading text-sm font-bold text-ink"
                                    >
                                        {String(idx + 1).padStart(2, '0')}
                                    </span>
                                    <div>
                                        <div className="flex flex-wrap items-baseline gap-2">
                                            <Counter
                                                value={stat.value}
                                                prefix={stat.prefix}
                                                suffix={stat.suffix}
                                                locale={locale}
                                                className="font-heading text-3xl font-bold tracking-tight text-ink sm:text-4xl"
                                            />
                                            {stat.unit ? (
                                                <span className="rounded-full bg-surface px-3 py-0.5 text-xs font-bold text-ink">
                                                    {stat.unit}
                                                </span>
                                            ) : null}
                                        </div>
                                        <p className="mt-1 text-sm font-medium text-muted">
                                            {stat.label}
                                        </p>
                                    </div>
                                </div>
                            </Reveal>
                        ))}
                    </div>
                </Container>
            </Section>
            <WaveDivider tone="surface" flip />
        </>
    );
}
