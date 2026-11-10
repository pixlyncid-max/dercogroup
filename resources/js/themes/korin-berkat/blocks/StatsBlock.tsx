import { usePage } from '@inertiajs/react';
import { useLocale, type SharedPageProps, type StatsBlockData } from '@/core';
import { Container, Counter, Reveal, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';
import { GoldRule } from '../GoldRule';

export interface StatsBlockProps {
    data: StatsBlockData;
}

/**
 * Korin Berkat "Sekilas" 4-column editorial counter strip (DESIGN.md Section 5.4 & 5.5).
 */
export function StatsBlock({ data }: StatsBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale } = useLocale();
    const stats = props.stats ?? props.company?.stats ?? [];
    const isHomePage = props.page?.key === 'home';

    if (stats.length === 0) {
        return null;
    }

    return (
        <Section tone="surface" className="border-b border-line">
            <Container>
                {isHomePage ? (
                    <h2 className="sr-only">{data.title}</h2>
                ) : (
                    <EditorialHeader
                        indexLabel="02"
                        eyebrow={data.eyebrow}
                        title={data.title}
                        description={data.description}
                    />
                )}

                <div
                    className={`${
                        isHomePage ? '' : 'mt-12 '
                    }grid grid-cols-1 divide-y divide-line border-y border-line bg-bg sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x`}
                >
                    {stats.map((stat, idx) => (
                        <Reveal key={stat.id} preset="fadeUp" className="p-7 lg:p-8 space-y-4">
                            <div className="flex items-center justify-between">
                                <span className="font-heading text-xs font-semibold tracking-[0.12em] text-accent-strong">
                                    {`0${idx + 1}`}
                                </span>
                                <GoldRule className="w-10" />
                            </div>

                            <div className="flex items-baseline gap-2 pt-1">
                                <Counter
                                    value={stat.value}
                                    prefix={stat.prefix}
                                    suffix={stat.suffix}
                                    locale={locale}
                                    className="font-heading text-4xl font-semibold tracking-tight text-ink"
                                />
                                {stat.unit ? (
                                    <span className="text-xs font-semibold uppercase tracking-[0.08em] text-accent-strong">
                                        {stat.unit}
                                    </span>
                                ) : null}
                            </div>

                            <p className="text-sm font-medium leading-6 text-muted">{stat.label}</p>
                        </Reveal>
                    ))}
                </div>
            </Container>
        </Section>
    );
}
