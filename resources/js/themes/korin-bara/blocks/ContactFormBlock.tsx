import type { ContactFormBlockData } from '@/core';
import { ContactFormBody, Container, Heading, Reveal } from '@/ui';

export interface ContactFormBlockProps {
    data: ContactFormBlockData;
    embedded?: boolean;
    headingLevel?: 1 | 2;
}

export function ContactFormContent({
    data,
    headingLevel = 2,
}: {
    data: ContactFormBlockData;
    headingLevel?: 1 | 2;
}) {
    return (
        <Reveal
            preset="fadeUp"
            className="border-4 border-ink bg-accent p-6 text-ink sm:p-10 lg:p-12 space-y-8"
        >
            <div className="space-y-3 border-b-2 border-ink pb-6">
                {data.eyebrow ? (
                    <p className="font-heading text-xs font-bold uppercase tracking-[0.16em] text-ink">
                        {data.eyebrow}
                    </p>
                ) : null}
                <Heading level={headingLevel} size="h2" className="text-ink">
                    {data.title}
                </Heading>
                {data.description ? (
                    <p className="max-w-[58ch] text-base font-medium leading-7 text-ink">
                        {data.description}
                    </p>
                ) : null}
            </div>

            <div className="border-2 border-ink bg-bg p-6 text-ink sm:p-8">
                <ContactFormBody
                    submitLabel={data.submit_label}
                    buttonClassName="bg-surface-dark text-ink-on-dark hover:bg-surface-deeper font-heading text-sm font-bold uppercase tracking-[0.14em]"
                />
            </div>
        </Reveal>
    );
}

export function ContactFormBlock({
    data,
    embedded = false,
    headingLevel = 2,
}: ContactFormBlockProps) {
    if (embedded) {
        return <ContactFormContent data={data} headingLevel={headingLevel} />;
    }

    return (
        <section className="border-b-2 border-line bg-bg py-12 md:py-16">
            <Container>
                <ContactFormContent data={data} headingLevel={headingLevel} />
            </Container>
        </section>
    );
}
