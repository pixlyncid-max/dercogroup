import type { ContactFormBlockData } from '@/core';
import { ContactFormBody, Container, Reveal } from '@/ui';

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
    const HeadingTag = `h${headingLevel}` as const;

    return (
        <Reveal
            preset="fadeUp"
            className="rounded-xl border border-line bg-white p-6 sm:p-8 lg:p-10 shadow-md space-y-6"
        >
            <div className="space-y-2 border-b border-line pb-6">
                {data.eyebrow ? (
                    <div>
                        <span className="kb-eyebrow">{data.eyebrow}</span>
                    </div>
                ) : null}
                <HeadingTag className="font-heading text-2xl sm:text-3xl font-bold tracking-tight text-[#101F38]">
                    {data.title}
                </HeadingTag>
                {data.description ? (
                    <p className="text-base text-slate-600 leading-relaxed">
                        {data.description}
                    </p>
                ) : null}
            </div>

            <div>
                <ContactFormBody
                    submitLabel={data.submit_label}
                    buttonClassName="kb-btn-solid w-full sm:w-auto"
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
        <section className="py-16 bg-[#F8FAFC] border-b border-line">
            <Container>
                <div className="max-w-2xl mx-auto">
                    <ContactFormContent data={data} headingLevel={headingLevel} />
                </div>
            </Container>
        </section>
    );
}
