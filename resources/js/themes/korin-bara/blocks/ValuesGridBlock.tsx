import type { ValuesGridBlockData } from '@/core';
import { Container, Heading, Reveal, Section, ThemeIcon } from '@/ui';
import { FieldSectionHeader } from '../FieldSectionHeader';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

/**
 * Korin Bara Values Row with Gold Top Border ("Field Rail", DESIGN.md Section 6.4).
 */
export function ValuesGridBlock({ data }: ValuesGridBlockProps) {
    const items = data.items ?? [];

    return (
        <Section tone="surface" className="border-b-2 border-line">
            <Container>
                <FieldSectionHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                />

                <div
                    className={`mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 ${
                        items.length >= 4 ? 'lg:grid-cols-4' : 'lg:grid-cols-3'
                    }`}
                >
                    {items.map((item, idx) => (
                        <Reveal
                            key={idx}
                            preset="fadeUp"
                            className="border-2 border-line border-t-4 border-t-accent bg-bg p-6 sm:p-7 space-y-4"
                        >
                            <div className="flex items-center justify-between">
                                <span className="inline-flex h-10 w-10 items-center justify-center bg-accent text-ink">
                                    <ThemeIcon name={item.icon_name} className="h-5 w-5" />
                                </span>
                                <span className="font-heading text-lg font-bold text-accent-strong">
                                    {`0${idx + 1}`}
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
