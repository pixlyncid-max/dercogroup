import type { ContactFormBlockData } from '@/core';
import { ContactFormBody, Container, Heading, Reveal, Section } from '@/ui';

export interface ContactFormBlockProps {
    data: ContactFormBlockData;
    embedded?: boolean;
}

/**
 * Beramal Sukses Sejahtera Centered Contact Form Card ("Calm Flow", DESIGN.md Section 7.4).
 */
export function ContactFormContent({ data }: { data: ContactFormBlockData }) {
    return (
        <Reveal
            preset="fadeUp"
            className="mx-auto max-w-[760px] rounded-[var(--radius-card)] border border-line bg-bg p-7 shadow-md sm:p-10"
        >
            <div className="space-y-3 border-b border-line pb-6 text-center">
                {data.eyebrow ? (
                    <div className="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-1 text-xs font-bold uppercase tracking-[0.1em] text-ink">
                        <span aria-hidden="true" className="h-2 w-2 rounded-full bg-accent" />
                        <span>{data.eyebrow}</span>
                    </div>
                ) : null}
                <Heading level={2} size="h3">
                    {data.title}
                </Heading>
                {data.description ? (
                    <p className="mx-auto max-w-[54ch] text-sm leading-6 text-muted">
                        {data.description}
                    </p>
                ) : null}
            </div>

            <ContactFormBody
                submitLabel={data.submit_label}
                buttonClassName="w-full sm:w-auto rounded-full bg-accent text-on-accent hover:opacity-90 text-sm font-bold px-8"
            />
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
