import { usePage } from '@inertiajs/react';
import { Clock, Mail, MapPin, Phone } from 'lucide-react';
import { useLocale, type ContactInfoBlockData, type SharedPageProps } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';

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
            <div className="space-y-3">
                <div className="flex items-center gap-3">
                    <span aria-hidden="true" className="h-1.5 w-10 bg-accent" />
                    {data.eyebrow ? (
                        <span className="font-heading text-xs font-bold uppercase tracking-[0.14em] text-accent-strong">
                            {data.eyebrow}
                        </span>
                    ) : null}
                </div>
                <Heading level={headingLevel} size="h2" className="text-ink">
                    {data.title}
                </Heading>
                {data.description ? (
                    <p className="max-w-[58ch] text-base leading-7 text-muted">
                        {data.description}
                    </p>
                ) : null}
            </div>

            {primaryContact ? (
                <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
                    <dl className="lg:col-span-7 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div className="border-2 border-line border-t-4 border-t-accent bg-bg p-5 space-y-2 sm:col-span-2">
                            <dt className="flex items-center gap-2 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                <MapPin className="h-4 w-4 text-accent-strong" aria-hidden="true" />
                                <span>{t('contact.address_label')}</span>
                            </dt>
                            <dd className="text-sm leading-6 text-muted">
                                {primaryContact.address}
                            </dd>
                        </div>

                        {primaryContact.phone ? (
                            <div className="border-2 border-line border-t-4 border-t-accent bg-bg p-5 space-y-2">
                                <dt className="flex items-center gap-2 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                    <Phone
                                        className="h-4 w-4 text-accent-strong"
                                        aria-hidden="true"
                                    />
                                    <span>{t('contact.phone_label')}</span>
                                </dt>
                                <dd className="font-heading text-lg font-bold text-ink">
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
                            <div className="border-2 border-line border-t-4 border-t-accent bg-bg p-5 space-y-2">
                                <dt className="flex items-center gap-2 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                    <Mail
                                        className="h-4 w-4 text-accent-strong"
                                        aria-hidden="true"
                                    />
                                    <span>{t('contact.email_label')}</span>
                                </dt>
                                <dd className="text-sm font-semibold text-ink">
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
                            <div className="border-2 border-line border-t-4 border-t-accent bg-bg p-5 space-y-2 sm:col-span-2">
                                <dt className="flex items-center gap-2 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                    <Clock
                                        className="h-4 w-4 text-accent-strong"
                                        aria-hidden="true"
                                    />
                                    <span>{t('contact.hours_label')}</span>
                                </dt>
                                <dd className="text-sm leading-6 text-muted">
                                    {primaryContact.operating_hours}
                                </dd>
                            </div>
                        ) : null}
                    </dl>

                    {data.show_map !== false ? (
                        <div
                            role="region"
                            aria-label={t('contact.map_title')}
                            className="lg:col-span-5 border-2 border-ink bg-surface-dark p-6 text-ink-on-dark flex flex-col justify-between"
                        >
                            <div className="flex items-center justify-between border-b border-ink-on-dark/15 pb-3">
                                <span className="font-heading text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                    {t('contact.map_title')}
                                </span>
                                {primaryContact.latitude != null &&
                                primaryContact.longitude != null ? (
                                    <span className="font-mono text-xs text-muted-on-dark">
                                        {`${Number(primaryContact.latitude).toFixed(4)}°, ${Number(primaryContact.longitude).toFixed(4)}°`}
                                    </span>
                                ) : null}
                            </div>

                            <svg
                                viewBox="0 0 420 200"
                                className="mt-4 h-44 w-full text-accent-on-dark"
                                aria-hidden="true"
                            >
                                <rect
                                    width="420"
                                    height="200"
                                    fill="currentColor"
                                    fillOpacity="0.06"
                                />
                                <path
                                    d="M0 50 H420 M0 100 H420 M0 150 H420 M84 0 V200 M168 0 V200 M252 0 V200 M336 0 V200"
                                    stroke="currentColor"
                                    strokeOpacity="0.18"
                                    strokeWidth="1"
                                />
                                <circle
                                    cx="210"
                                    cy="100"
                                    r="22"
                                    fill="currentColor"
                                    fillOpacity="0.22"
                                />
                                <circle cx="210" cy="100" r="6" fill="currentColor" />
                            </svg>
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
        <Section tone="surface" className="border-b-2 border-line">
            <Container>
                <ContactInfoContent data={data} headingLevel={headingLevel} />
            </Container>
        </Section>
    );
}
