import { usePage } from '@inertiajs/react';
import { Clock, Mail, MapPin, Phone } from 'lucide-react';
import { useLocale, type ContactInfoBlockData, type SharedPageProps } from '@/core';
import { Container, Reveal, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';

export interface ContactInfoBlockProps {
    data: ContactInfoBlockData;
    embedded?: boolean;
    headingLevel?: 1 | 2;
}

/**
 * Beramal Sukses Sejahtera Contact Info in Three Capsules ("Calm Flow", DESIGN.md Section 7.4).
 * Renders Address, Phone & WhatsApp, and Email & Operating Hours as three rounded capsules.
 */
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

    return (
        <div className="space-y-10">
            <CalmHeader
                eyebrow={data.eyebrow}
                title={data.title}
                description={data.description}
                level={headingLevel}
                centered
            />

            {primaryContact ? (
                <div
                    className="grid grid-cols-1 gap-6 md:grid-cols-3"
                    data-testid="beramal-contact-capsules"
                >
                    {/* Capsule 1: Address */}
                    <Reveal
                        preset="fadeUp"
                        className="flex flex-col justify-between rounded-[32px] border border-line bg-surface p-6 shadow-sm sm:p-7 space-y-4"
                    >
                        <div className="flex items-center gap-3">
                            <span
                                aria-hidden="true"
                                className="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-accent text-on-accent"
                            >
                                <MapPin className="h-5 w-5" />
                            </span>
                            <h2 className="font-heading text-base font-bold text-ink">
                                {t('contact.address_label')}
                            </h2>
                        </div>
                        <p className="text-sm leading-6 text-muted">{primaryContact.address}</p>
                    </Reveal>

                    {/* Capsule 2: Phone & WhatsApp */}
                    <Reveal
                        preset="fadeUp"
                        className="flex flex-col justify-between rounded-[32px] border border-line bg-surface p-6 shadow-sm sm:p-7 space-y-4"
                    >
                        <div className="flex items-center gap-3">
                            <span
                                aria-hidden="true"
                                className="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-accent text-on-accent"
                            >
                                <Phone className="h-5 w-5" />
                            </span>
                            <h2 className="font-heading text-base font-bold text-ink">
                                {t('contact.phone_label')}
                            </h2>
                        </div>

                        <div className="space-y-2 text-sm">
                            {primaryContact.phone ? (
                                <p>
                                    <a
                                        href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                        className="font-heading text-lg font-bold text-ink hover:underline"
                                    >
                                        {primaryContact.phone}
                                    </a>
                                </p>
                            ) : null}
                            {primaryContact.whatsapp ? (
                                <p className="text-xs font-medium text-muted">
                                    {`${t('contact.whatsapp_label')}: ${primaryContact.whatsapp}`}
                                </p>
                            ) : null}
                        </div>
                    </Reveal>

                    {/* Capsule 3: Email & Operating Hours */}
                    <Reveal
                        preset="fadeUp"
                        className="flex flex-col justify-between rounded-[32px] border border-line bg-surface p-6 shadow-sm sm:p-7 space-y-4"
                    >
                        <div className="flex items-center gap-3">
                            <span
                                aria-hidden="true"
                                className="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-accent text-on-accent"
                            >
                                <Mail className="h-5 w-5" />
                            </span>
                            <h2 className="font-heading text-base font-bold text-ink">
                                {t('contact.email_label')} &amp; {t('contact.hours_label')}
                            </h2>
                        </div>

                        <div className="space-y-2 text-sm">
                            {primaryContact.email ? (
                                <p>
                                    <a
                                        href={`mailto:${primaryContact.email}`}
                                        className="font-bold text-ink hover:underline"
                                    >
                                        {primaryContact.email}
                                    </a>
                                </p>
                            ) : null}
                            {primaryContact.operating_hours ? (
                                <p className="flex items-start gap-2 text-xs leading-5 text-muted">
                                    <Clock
                                        className="mt-0.5 h-3.5 w-3.5 shrink-0 text-ink"
                                        aria-hidden="true"
                                    />
                                    <span>{primaryContact.operating_hours}</span>
                                </p>
                            ) : null}
                        </div>
                    </Reveal>
                </div>
            ) : null}
        </div>
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
        <Section tone="default">
            <Container>
                <ContactInfoContent data={data} headingLevel={headingLevel} />
            </Container>
        </Section>
    );
}
