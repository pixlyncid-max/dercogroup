import { Container, Heading, Section } from '@/ui';

export interface FallbackBlockProps {
    data: Record<string, unknown>;
}

export function FallbackBlock({ data }: FallbackBlockProps) {
    const title = typeof data.title === 'string' ? data.title : '';
    const description = typeof data.description === 'string' ? data.description : '';

    if (!title && !description) {
        return null;
    }

    return (
        <Section tone="default">
            <Container>
                <div className="space-y-3 rounded-[var(--radius-card)] border border-line p-6">
                    {title ? (
                        <Heading level={2} size="h3">
                            {title}
                        </Heading>
                    ) : null}
                    {description ? (
                        <p className="text-sm leading-6 text-muted">{description}</p>
                    ) : null}
                </div>
            </Container>
        </Section>
    );
}
