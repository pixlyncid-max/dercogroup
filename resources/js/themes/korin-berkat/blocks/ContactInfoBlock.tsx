import { usePage } from '@inertiajs/react';
import { Clock, Mail, MapPin, Phone } from 'lucide-react';
import { useLocale, type ContactInfoBlockData, type SharedPageProps } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { GoldRule } from '../GoldRule';

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

    return (
        <Reveal preset="fadeUp" className="space-y-8">
            <div className="space-y-4">
                <div className="flex items-center gap-3">
                    <span className="font-heading text-xs font-semibold tracking-[0.12em] text-accent-strong">
                        06
                    </span>
                    {data.eyebrow ? (
                        <span className="text-xs font-semibold uppercase tracking-[0.14em] text-ink">
                            {data.eyebrow}
                        </span>
                    ) : null}
                </div>
                <GoldRule thick className="w-16" />
                <Heading level={headingLevel} size="h2">
                    {data.title}
                </Heading>
                {data.description ? (
                    <p className="max-w-[54ch] text-base leading-7 text-muted">
                        {data.description}
                    </p>
                ) : null}
            </div>

            {primaryContact ? (
                <dl className="divide-y divide-line border-y border-line">
                    <div className="py-5 space-y-1">
                        <dt className="flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.12em] text-ink">
                            <MapPin
                                className="h-4 w-4 shrink-0 text-accent-strong"
                                aria-hidden="true"
                            />
                            <span>{t('contact.address_label')}</span>
                        </dt>
                        <dd className="pl-6.5 text-sm leading-6 text-muted">
                            {primaryContact.address}
                        </dd>
                    </div>

                    {primaryContact.phone ? (
                        <div className="py-5 space-y-1">
                            <dt className="flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.12em] text-ink">
                                <Phone
                                    className="h-4 w-4 shrink-0 text-accent-strong"
                                    aria-hidden="true"
                                />
                                <span>{t('contact.phone_label')}</span>
                            </dt>
                            <dd className="pl-6.5 font-heading text-lg font-semibold text-ink">
                                <a
                                    href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                    className="hover:underline"
                                >
                                    {primaryContact.phone}
                                </a>
                            </dd>
                        </div>
                    ) : null}

                    {primaryContact.email ? (
                        <div className="py-5 space-y-1">
                            <dt className="flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.12em] text-ink">
                                <Mail
                                    className="h-4 w-4 shrink-0 text-accent-strong"
                                    aria-hidden="true"
                                />
                                <span>{t('contact.email_label')}</span>
                            </dt>
                            <dd className="pl-6.5 text-sm font-semibold text-ink">
                                <a
                                    href={`mailto:${primaryContact.email}`}
                                    className="hover:underline"
                                >
                                    {primaryContact.email}
                                </a>
                            </dd>
                        </div>
                    ) : null}

                    {primaryContact.operating_hours ? (
                        <div className="py-5 space-y-1">
                            <dt className="flex items-center gap-2.5 text-xs font-semibold uppercase tracking-[0.12em] text-ink">
                                <Clock
                                    className="h-4 w-4 shrink-0 text-accent-strong"
                                    aria-hidden="true"
                                />
                                <span>{t('contact.hours_label')}</span>
                            </dt>
                            <dd className="pl-6.5 text-sm leading-6 text-muted">
                                {primaryContact.operating_hours}
                            </dd>
                        </div>
                    ) : null}
                </dl>
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
        <Section tone="default" className="border-b border-line">
            <Container>
                <ContactInfoContent data={data} headingLevel={headingLevel} />
            </Container>
        </Section>
    );
}
