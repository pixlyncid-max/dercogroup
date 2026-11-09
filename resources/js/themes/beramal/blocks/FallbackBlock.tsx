import { Container, Heading, Reveal, Section } from '@/ui';

export interface FallbackBlockProps {
    data: Record<string, unknown>;
}

export function FallbackBlock({ data }: FallbackBlockProps) {
    const title = typeof data.title === 'string' ? data.title : null;
    const description =
        typeof data.description === 'string'
            ? data.description
            : typeof data.body === 'string'
              ? data.body
              : null;

    if (!title && !description) {
        return null;
    }

    return (
        <Section tone="default">
            <Container>
                <Reveal
                    preset="fadeUp"
                    className="rounded-[var(--radius-card)] border border-line bg-surface p-7 space-y-3"
                >
                    {title ? (
                        <Heading level={2} size="h2">
                            {title}
                        </Heading>
                    ) : null}
                    {description ? (
                        <p className="max-w-[65ch] text-base leading-7 text-muted">{description}</p>
                    ) : null}
                </Reveal>
            </Container>
        </Section>
    );
}
