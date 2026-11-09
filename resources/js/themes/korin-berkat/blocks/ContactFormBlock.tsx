import type { ContactFormBlockData } from '@/core';
import { ContactFormBody, Container, Heading, Reveal, Section } from '@/ui';
import { GoldRule } from '../GoldRule';

export interface ContactFormBlockProps {
    data: ContactFormBlockData;
    embedded?: boolean;
}

export function ContactFormContent({ data }: { data: ContactFormBlockData }) {
    return (
        <Reveal
            preset="fadeUp"
            className="rounded-[var(--radius-card)] border border-line bg-surface p-6 md:p-8 lg:p-10 border-t-4 border-t-ink"
        >
            <div className="space-y-3 border-b border-line pb-6">
                {data.eyebrow ? (
                    <p className="text-xs font-semibold uppercase tracking-[0.14em] text-ink">
                        {data.eyebrow}
                    </p>
                ) : null}
                <GoldRule className="w-12" />
                <Heading level={2} size="h3">
                    {data.title}
                </Heading>
                {data.description ? (
                    <p className="text-sm leading-6 text-muted">{data.description}</p>
                ) : null}
            </div>

            <ContactFormBody
                submitLabel={data.submit_label}
                buttonClassName="bg-surface-dark text-ink-on-dark hover:bg-surface-deeper text-xs font-semibold uppercase tracking-[0.12em]"
            />
        </Reveal>
    );
}

export function ContactFormBlock({ data, embedded = false }: ContactFormBlockProps) {
    if (embedded) {
        return <ContactFormContent data={data} />;
    }

    return (
        <Section tone="default">
            <Container size="narrow">
                <ContactFormContent data={data} />
            </Container>
        </Section>
    );
}
