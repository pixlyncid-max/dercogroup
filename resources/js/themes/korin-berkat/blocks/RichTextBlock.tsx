import type { RichTextBlockData } from '@/core';
import { Container, Heading, Prose, Reveal, Section } from '@/ui';
import { GoldRule } from '../GoldRule';

export interface RichTextBlockProps {
    data: RichTextBlockData;
}

export function RichTextBlock({ data }: RichTextBlockProps) {
    return (
        <Section tone="default" className="border-b border-line">
            <Container size="narrow">
                <Reveal preset="fadeUp" className="space-y-5">
                    {data.eyebrow ? (
                        <p className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-strong">
                            {data.eyebrow}
                        </p>
                    ) : null}
                    {data.title ? (
                        <>
                            <GoldRule thick className="w-14" />
                            <Heading level={2} size="h2">
                                {data.title}
                            </Heading>
                        </>
                    ) : null}
                    <Prose className="text-muted">
                        <p>{data.content}</p>
                    </Prose>
                </Reveal>
            </Container>
        </Section>
    );
}
