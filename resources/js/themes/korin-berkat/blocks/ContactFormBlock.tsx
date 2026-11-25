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
            className="rounded-[var(--radius-card)] border border-line bg-surface p-6 shadow-sm md:p-8 lg:p-10"
        >
            <div className="space-y-2.5 border-b border-line pb-6">
                {data.eyebrow ? (
                    <p className="text-xs font-bold uppercase tracking-[0.16em] text-accent-strong">
                        {data.eyebrow}
                    </p>
                ) : null}
                <Heading level={2} size="h3" className="font-bold text-ink">
                    {data.title}
                </Heading>
                <GoldRule className="w-12" />
                {data.description ? (
                    <p className="text-sm leading-6 text-muted">{data.description}</p>
                ) : null}
            </div>

            <ContactFormBody
                submitLabel={data.submit_label}
                buttonClassName="w-full justify-center bg-[#eab338] text-[#0b172a] hover:bg-[#d49b22] font-bold text-sm tracking-wide rounded-[var(--radius-button)] shadow-sm"
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
