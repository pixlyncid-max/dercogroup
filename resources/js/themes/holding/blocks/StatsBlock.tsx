import { usePage } from '@inertiajs/react';
import { useLocale, type SharedPageProps, type StatsBlockData } from '@/core';
import { Container, Counter, Heading, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface StatsBlockProps {
    data: StatsBlockData;
}

export function StatsBlock({ data }: StatsBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale } = useLocale();
    const stats = props.stats ?? props.company?.stats ?? [];

    return (
        <Section tone="dark">
            <Container>
                <Reveal preset="fadeUp" className="max-w-[640px] space-y-4">
                    {data.eyebrow ? <EyebrowLine tone="dark">{data.eyebrow}</EyebrowLine> : null}
                    <Heading level={2} size="h2" className="text-ink-on-dark">
                        {data.title}
                    </Heading>
                    {data.description ? (
                        <p className="text-sm leading-6 text-muted-on-dark">{data.description}</p>
                    ) : null}
                </Reveal>

                <dl className="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {stats.map((stat) => (
                        <div key={stat.id} className="border-t-2 border-accent pt-5 space-y-2">
                            <dd className="font-heading text-3xl md:text-4xl font-bold text-accent-on-dark">
                                <Counter
                                    value={stat.value}
                                    prefix={stat.prefix}
                                    suffix={stat.suffix}
                                    locale={locale === 'en' ? 'en-US' : 'id-ID'}
                                />
                            </dd>
                            <dt className="text-xs font-bold uppercase tracking-[0.12em] text-ink-on-dark">
                                {stat.unit}
                            </dt>
                            <p className="text-xs leading-5 text-muted-on-dark">{stat.label}</p>
                        </div>
                    ))}
                </dl>
            </Container>
        </Section>
    );
}
