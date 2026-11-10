import type { ValuesGridBlockData } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

/**
 * Korin Berkat Editorial Values List (DESIGN.md Section 5.4 & Revision 2 B3).
 * Rendered as an editorial numbered list with hairline dividers, gold numbering,
 * bold titles, and factual 1-sentence descriptions (no icon card-boxes).
 */
export function ValuesGridBlock({ data }: ValuesGridBlockProps) {
    const items = data.items ?? [];

    return (
        <Section tone="surface" className="border-b border-line">
            <Container>
                <EditorialHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                />

                <div className="mt-12 divide-y divide-line border-y border-line">
                    {items.map((item, idx) => (
                        <Reveal key={idx} preset="fadeUp">
                            <div className="grid grid-cols-1 gap-4 py-8 lg:grid-cols-12 lg:items-baseline lg:gap-8 lg:py-10">
                                {/* Number with Gold Accent */}
                                <div className="lg:col-span-2">
                                    <span className="font-heading text-2xl lg:text-3xl font-semibold tracking-tight text-accent-strong">
                                        {String(idx + 1).padStart(2, '0')}
                                    </span>
                                </div>

                                {/* Bold Title */}
                                <div className="lg:col-span-4">
                                    <Heading
                                        level={3}
                                        size="h3"
                                        className="font-heading text-lg lg:text-xl font-semibold tracking-tight text-ink"
                                    >
                                        {item.title}
                                    </Heading>
                                </div>

                                {/* Factual 1-sentence description */}
                                <div className="lg:col-span-6">
                                    <p className="text-sm lg:text-base leading-relaxed text-muted">
                                        {item.description}
                                    </p>
                                </div>
                            </div>
                        </Reveal>
                    ))}
                </div>
            </Container>
        </Section>
    );
}
