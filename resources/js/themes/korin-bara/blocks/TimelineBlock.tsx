import type { TimelineBlockData } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { FieldSectionHeader } from '../FieldSectionHeader';

export interface TimelineBlockProps {
    data: TimelineBlockData;
}

export function TimelineBlock({ data }: TimelineBlockProps) {
    const items = data.items ?? [];

    return (
        <Section tone="surface" className="border-b-2 border-line">
            <Container>
                <FieldSectionHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                />

                <div className="mt-12 grid grid-cols-1 gap-6 md:grid-cols-3">
                    {items.map((item, idx) => (
                        <Reveal
                            key={idx}
                            preset="fadeUp"
                            className="border-2 border-ink border-t-4 border-t-accent bg-bg p-6 sm:p-7 space-y-4"
                        >
                            <div className="flex items-center justify-between">
                                <span className="bg-accent px-3 py-1 font-heading text-lg font-bold text-ink">
                                    {item.period}
                                </span>
                                <span className="font-heading text-xs font-bold uppercase tracking-widest text-muted">
                                    {`STAGE 0${idx + 1}`}
                                </span>
                            </div>
                            <Heading level={3} size="h3" className="text-ink">
                                {item.title}
                            </Heading>
                            <p className="text-sm leading-6 text-muted">{item.description}</p>
                        </Reveal>
                    ))}
                </div>
            </Container>
        </Section>
    );
}
