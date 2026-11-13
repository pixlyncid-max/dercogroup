import { usePage } from '@inertiajs/react';
import { Clock, Mail, MapPin, Phone } from 'lucide-react';
import { useLocale, type ContactInfoBlockData, type SharedPageProps } from '@/core';
import { Container, Reveal } from '@/ui';

export interface ContactInfoBlockProps {
    data: ContactInfoBlockData;
    embedded?: boolean;
    headingLevel?: 1 | 2;
}

export function ContactInfoContent({
    data,
    headingLevel = 2,
}: {
    data: ContactInfoBlockData;
    headingLevel?: 1 | 2;
}) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const contacts = props.contacts ?? props.company?.contacts ?? [];
    const primaryContact = contacts[0];

    const HeadingTag = `h${headingLevel}` as const;

    return (
        <Reveal
            preset="fadeUp"
            className="rounded-xl border border-line bg-white p-6 sm:p-8 shadow-md space-y-8"
        >
            <div className="space-y-2 border-b border-line pb-6">
                {data.eyebrow ? (
                    <div>
                        <span className="kb-eyebrow">{data.eyebrow}</span>
                    </div>
                ) : null}
                <HeadingTag className="font-heading text-2xl font-bold tracking-tight text-[#101F38]">
                    {data.title}
                </HeadingTag>
                {data.description ? (
                    <p className="text-sm text-slate-600 leading-relaxed">
                        {data.description}
                    </p>
                ) : null}
            </div>

            {primaryContact ? (
                <div className="space-y-6">
                    {/* Address */}
                    <div className="flex items-start gap-4">
                        <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-[#8A6212]">
                            <MapPin className="h-5 w-5 stroke-[2]" aria-hidden="true" />
                        </div>
                        <div className="space-y-1">
                            <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                                {t('contact.address_label')}
                            </span>
                            <p className="text-sm font-medium leading-relaxed text-[#101F38]">
                                {primaryContact.address}
                            </p>
                        </div>
                    </div>

                    {/* Phone */}
                    {primaryContact.phone ? (
                        <div className="flex items-start gap-4">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-[#8A6212]">
                                <Phone className="h-5 w-5 stroke-[2]" aria-hidden="true" />
                            </div>
                            <div className="space-y-1">
                                <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    {t('contact.phone_label')}
                                </span>
                                <p className="font-heading text-base font-bold text-[#101F38]">
                                    <a
                                        href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                        className="hover:underline"
                                    >
                                        {primaryContact.phone}
                                    </a>
                                </p>
                            </div>
                        </div>
                    ) : null}

                    {/* Email */}
                    {primaryContact.email ? (
                        <div className="flex items-start gap-4">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-[#8A6212]">
                                <Mail className="h-5 w-5 stroke-[2]" aria-hidden="true" />
                            </div>
                            <div className="space-y-1">
                                <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    {t('contact.email_label')}
                                </span>
                                <p className="font-heading text-base font-bold text-[#101F38]">
                                    <a
                                        href={`mailto:${primaryContact.email}`}
                                        className="hover:underline"
                                    >
                                        {primaryContact.email}
                                    </a>
                                </p>
                            </div>
                        </div>
                    ) : null}

                    {/* Operating Hours */}
                    {primaryContact.operating_hours ? (
                        <div className="flex items-start gap-4">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-[#8A6212]">
                                <Clock className="h-5 w-5 stroke-[2]" aria-hidden="true" />
                            </div>
                            <div className="space-y-1">
                                <span className="text-xs font-bold uppercase tracking-wider text-slate-500">
                                    {t('contact.hours_label')}
                                </span>
                                <p className="text-sm font-medium text-[#101F38]">
                                    {primaryContact.operating_hours}
                                </p>
                            </div>
                        </div>
                    ) : null}
                </div>
            ) : null}
        </Reveal>
    );
}

export function ContactInfoBlock({
    data,
    embedded = false,
    headingLevel = 2,
}: ContactInfoBlockProps) {
    if (embedded) {
        return <ContactInfoContent data={data} headingLevel={headingLevel} />;
    }

    return (
        <section className="py-16 bg-[#F8FAFC] border-b border-line">
            <Container>
                <div className="max-w-2xl mx-auto">
                    <ContactInfoContent data={data} headingLevel={headingLevel} />
                </div>
            </Container>
        </section>
    );
}
