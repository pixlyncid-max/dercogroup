import type { TimelineBlockData } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';
import { GoldRule } from '../GoldRule';

export interface TimelineBlockProps {
    data: TimelineBlockData;
}

/**
 * Korin Berkat Editorial Timeline (Horizontal milestone grid on About page, DESIGN.md Section 5.4).
 */
export function TimelineBlock({ data }: TimelineBlockProps) {
    const items = data.items ?? [];
    const isHorizontal = data.orientation !== 'vertical';

    return (
        <Section tone="surface" className="border-b border-line">
            <Container>
                <EditorialHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                />

                {isHorizontal ? (
                    <div className="mt-12 grid grid-cols-1 divide-y divide-line border-y border-line bg-bg md:grid-cols-3 md:divide-y-0 md:divide-x">
                        {items.map((item, idx) => (
                            <Reveal
                                key={idx}
                                preset="fadeUp"
                                className="flex flex-col justify-between p-7 lg:p-8 space-y-5"
                            >
                                <div className="space-y-4">
                                    <div className="flex items-center justify-between">
                                        <span className="font-heading text-2xl font-semibold text-accent-strong">
                                            {item.period}
                                        </span>
                                        <span className="font-mono text-xs text-muted">
                                            {`0${idx + 1}`}
                                        </span>
                                    </div>
                                    <GoldRule className="w-12" />
                                    <Heading level={3} size="h3" className="font-semibold">
                                        {item.title}
                                    </Heading>
                                    <p className="text-sm leading-6 text-muted">
                                        {item.description}
                                    </p>
                                </div>
                            </Reveal>
                        ))}
                    </div>
                ) : (
                    <ol className="mt-12 border-l-2 border-accent ml-4 space-y-8 pl-8">
                        {items.map((item, idx) => (
                            <li
                                key={idx}
                                className="relative rounded-[var(--radius-card)] border border-line bg-bg p-6"
                            >
                                <span className="font-heading text-lg font-semibold text-accent-strong">
                                    {item.period}
                                </span>
                                <Heading level={3} size="h3" className="mt-1 font-semibold">
                                    {item.title}
                                </Heading>
                                <p className="mt-2 text-sm leading-6 text-muted">
                                    {item.description}
                                </p>
                            </li>
                        ))}
                    </ol>
                )}
            </Container>
        </Section>
    );
}
