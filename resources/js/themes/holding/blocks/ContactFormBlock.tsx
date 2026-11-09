import type { ContactFormBlockData } from '@/core';
import { ContactFormBody, Container, Heading, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface ContactFormBlockProps {
    data: ContactFormBlockData;
    embedded?: boolean;
}

export function ContactFormContent({ data }: { data: ContactFormBlockData }) {
    return (
        <Reveal
            preset="fadeUp"
            className="rounded-[var(--radius-card)] border border-line bg-bg p-6 md:p-8 lg:p-10 shadow-sm"
        >
            <div className="space-y-3 pb-6 border-b border-line">
                {data.eyebrow ? <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine> : null}
                <Heading level={2} size="h3">
                    {data.title}
                </Heading>
                {data.description ? (
                    <p className="text-sm leading-6 text-muted">{data.description}</p>
                ) : null}
            </div>

            <ContactFormBody submitLabel={data.submit_label} />
        </Reveal>
    );
}

export function ContactFormBlock({ data, embedded = false }: ContactFormBlockProps) {
    if (embedded) {
        return <ContactFormContent data={data} />;
    }

    return (
        <Section tone="surface">
            <Container size="narrow">
                <ContactFormContent data={data} />
            </Container>
        </Section>
    );
}
