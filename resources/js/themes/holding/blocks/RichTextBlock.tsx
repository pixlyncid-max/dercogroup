import type { RichTextBlockData } from '@/core';
import { Container, Heading, Prose, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface RichTextBlockProps {
    data: RichTextBlockData;
}

export function RichTextBlock({ data }: RichTextBlockProps) {
    return (
        <Section tone="default">
            <Container size="narrow">
                <Reveal preset="fadeUp" className="space-y-6">
                    {data.eyebrow ? <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine> : null}
                    {data.title ? (
                        <Heading level={2} size="h2">
                            {data.title}
                        </Heading>
                    ) : null}
                    <Prose html={data.content} />
                </Reveal>
            </Container>
        </Section>
    );
}
