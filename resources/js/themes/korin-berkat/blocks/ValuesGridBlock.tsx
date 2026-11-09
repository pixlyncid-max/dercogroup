import type { ValuesGridBlockData } from '@/core';
import { Container, Heading, Reveal, Section, ThemeIcon } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

/**
 * Korin Berkat Editorial Values Index (DESIGN.md Section 5.4).
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

                <div className="mt-12 grid grid-cols-1 divide-y divide-line border-y border-line bg-bg md:grid-cols-3 md:divide-y-0 md:divide-x">
                    {items.map((item, idx) => (
                        <Reveal key={idx} preset="fadeUp" className="p-7 lg:p-8 space-y-4">
                            <div className="flex items-center justify-between">
                                <span className="font-heading text-lg font-semibold text-accent-strong">
                                    {String(idx + 1).padStart(2, '0')}
                                </span>
                                <ThemeIcon name={item.icon_name} className="h-5 w-5 text-ink" />
                            </div>
                            <Heading level={3} size="h3" className="font-semibold">
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
