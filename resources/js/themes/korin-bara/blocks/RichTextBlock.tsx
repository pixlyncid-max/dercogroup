import type { RichTextBlockData } from '@/core';
import { Container, Heading, Prose, Reveal, Section } from '@/ui';

export interface RichTextBlockProps {
    data: RichTextBlockData;
}

export function RichTextBlock({ data }: RichTextBlockProps) {
    return (
        <Section tone="default" className="border-b-2 border-line">
            <Container size="narrow">
                <Reveal preset="fadeUp" className="space-y-5">
                    {data.eyebrow ? (
                        <p className="font-heading text-xs font-bold uppercase tracking-[0.14em] text-accent-strong">
                            {data.eyebrow}
                        </p>
                    ) : null}
                    {data.title ? (
                        <Heading level={2} size="h2" className="text-ink">
                            {data.title}
                        </Heading>
                    ) : null}
                    <Prose className="text-muted">
                        <p>{data.content}</p>
                    </Prose>
                </Reveal>
            </Container>
        </Section>
    );
}
