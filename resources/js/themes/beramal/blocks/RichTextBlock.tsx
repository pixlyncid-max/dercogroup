import type { RichTextBlockData } from '@/core';
import { Container, Heading, Prose, Reveal, Section } from '@/ui';

export interface RichTextBlockProps {
    data: RichTextBlockData;
}

export function RichTextBlock({ data }: RichTextBlockProps) {
    return (
        <Section tone="default">
            <Container size="narrow">
                <Reveal
                    preset="fadeUp"
                    className="rounded-[var(--radius-card)] border border-line bg-surface p-7 sm:p-10 space-y-5"
                >
                    {data.eyebrow ? (
                        <span className="inline-flex items-center rounded-full border border-line bg-bg px-4 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-accent-strong">
                            {data.eyebrow}
                        </span>
                    ) : null}
                    {data.title ? (
                        <Heading level={2} size="h2">
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
