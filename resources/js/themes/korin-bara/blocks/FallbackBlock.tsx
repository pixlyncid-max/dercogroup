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
        <Section tone="default" className="border-b-2 border-line">
            <Container>
                <Reveal preset="fadeUp" className="space-y-4">
                    <span aria-hidden="true" className="block h-1.5 w-10 bg-accent" />
                    {title ? (
                        <Heading level={2} size="h2" className="text-ink">
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
