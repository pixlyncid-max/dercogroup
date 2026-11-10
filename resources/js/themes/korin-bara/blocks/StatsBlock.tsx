import { usePage } from '@inertiajs/react';
import { useLocale, type SharedPageProps, type StatsBlockData } from '@/core';
import { Container, Counter, Reveal } from '@/ui';

export interface StatsBlockProps {
    data: StatsBlockData;
}

/**
 * Korin Bara 4-Number Strip directly below the Hero (DESIGN.md Section 6.4).
 */
export function StatsBlock({ data }: StatsBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale } = useLocale();
    const stats = props.stats ?? props.company?.stats ?? [];

    if (stats.length === 0) {
        return null;
    }

    return (
        <section
            aria-label={data.title}
            className="border-b-2 border-line bg-surface text-ink py-10 md:py-14"
        >
            <Container>
                <div className="mb-8 flex flex-wrap items-end justify-between gap-4 border-b border-line pb-5">
                    <div className="space-y-1">
                        {data.eyebrow ? (
                            <p className="font-heading text-xs font-bold uppercase tracking-[0.14em] text-accent-strong">
                                {data.eyebrow}
                            </p>
                        ) : null}
                        <h2 className="font-heading text-2xl font-bold uppercase tracking-wide text-ink sm:text-3xl">
                            {data.title}
                        </h2>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {stats.map((stat, idx) => (
                        <Reveal
                            key={stat.id}
                            preset="fadeUp"
                            className="border-2 border-line border-t-4 border-t-accent bg-bg p-6 space-y-3"
                        >
                            <div className="flex items-center justify-between">
                                <span className="bg-accent px-2 py-0.5 font-heading text-xs font-bold text-ink">
                                    {`0${idx + 1}`}
                                </span>
                                {stat.unit ? (
                                    <span className="font-heading text-xs font-bold uppercase tracking-[0.12em] text-accent-strong">
                                        {stat.unit}
                                    </span>
                                ) : null}
                            </div>

                            <div className="pt-1">
                                <Counter
                                    value={stat.value}
                                    prefix={stat.prefix}
                                    suffix={stat.suffix}
                                    locale={locale}
                                    className="font-heading text-4xl font-bold tracking-tight text-ink sm:text-5xl"
                                />
                            </div>

                            <p className="font-heading text-sm font-bold uppercase tracking-[0.06em] text-muted">
                                {stat.label}
                            </p>
                        </Reveal>
                    ))}
                </div>
            </Container>
        </section>
    );
}
