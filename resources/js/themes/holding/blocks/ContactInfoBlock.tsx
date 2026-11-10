import { usePage } from '@inertiajs/react';
import { Clock, Mail, MapPin, Phone } from 'lucide-react';
import { useLocale, type ContactInfoBlockData, type SharedPageProps } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

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
                {data.eyebrow ? <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine> : null}
                <Heading level={headingLevel} size="h2">
                    {data.title}
                </Heading>
                {data.description ? (
                    <p className="max-w-[58ch] text-sm leading-6 text-muted">{data.description}</p>
                ) : null}
            </div>

            {primaryContact ? (
                <div className="space-y-5 rounded-[var(--radius-card)] border border-line bg-surface p-6">
                    <div className="flex items-start gap-3.5">
                        <MapPin
                            className="mt-1 h-5 w-5 shrink-0 text-accent-strong"
                            strokeWidth={1.75}
                            aria-hidden="true"
                        />
                        <div className="space-y-1">
                            <p className="text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                {t('contact.address_label')}
                            </p>
                            <p className="text-sm leading-6 text-muted">{primaryContact.address}</p>
                        </div>
                    </div>

                    {primaryContact.phone ? (
                        <div className="flex items-start gap-3.5">
                            <Phone
                                className="mt-1 h-5 w-5 shrink-0 text-accent-strong"
                                strokeWidth={1.75}
                                aria-hidden="true"
                            />
                            <div className="space-y-1">
                                <p className="text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                    {t('contact.phone_label')}
                                </p>
                                <p className="text-sm font-bold text-ink">
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

                    {primaryContact.email ? (
                        <div className="flex items-start gap-3.5">
                            <Mail
                                className="mt-1 h-5 w-5 shrink-0 text-accent-strong"
                                strokeWidth={1.75}
                                aria-hidden="true"
                            />
                            <div className="space-y-1">
                                <p className="text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                    {t('contact.email_label')}
                                </p>
                                <p className="text-sm font-bold text-ink">
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

                    {primaryContact.operating_hours ? (
                        <div className="flex items-start gap-3.5">
                            <Clock
                                className="mt-1 h-5 w-5 shrink-0 text-accent-strong"
                                strokeWidth={1.75}
                                aria-hidden="true"
                            />
                            <div className="space-y-1">
                                <p className="text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                    {t('contact.hours_label')}
                                </p>
                                <p className="text-sm leading-6 text-muted">
                                    {primaryContact.operating_hours}
                                </p>
                            </div>
                        </div>
                    ) : null}
                </div>
            ) : null}

            {data.show_map !== false && primaryContact ? (
                <div
                    role="region"
                    aria-label={t('contact.map_title')}
                    className="overflow-hidden rounded-[var(--radius-card)] border border-line bg-surface-dark p-6 text-ink-on-dark"
                >
                    <div className="flex items-center justify-between border-b border-ink-on-dark/15 pb-4">
                        <span className="text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                            {t('contact.map_title')}
                        </span>
                        {primaryContact.latitude && primaryContact.longitude ? (
                            <span className="text-xs text-muted-on-dark">
                                {`${primaryContact.latitude.toFixed(4)}, ${primaryContact.longitude.toFixed(4)}`}
                            </span>
                        ) : null}
                    </div>
                    <svg
                        viewBox="0 0 480 180"
                        className="mt-4 h-36 w-full text-accent-on-dark"
                        aria-hidden="true"
                    >
                        <rect width="480" height="180" fill="currentColor" fillOpacity="0.06" />
                        <path
                            d="M0 45 H480 M0 90 H480 M0 135 H480 M96 0 V180 M192 0 V180 M288 0 V180 M384 0 V180"
                            stroke="currentColor"
                            strokeOpacity="0.18"
                            strokeWidth="1"
                        />
                        <circle cx="240" cy="90" r="22" fill="currentColor" fillOpacity="0.2" />
                        <circle cx="240" cy="90" r="6" fill="currentColor" />
                    </svg>
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
        <Section tone="default">
            <Container>
                <ContactInfoContent data={data} headingLevel={headingLevel} />
            </Container>
        </Section>
    );
}
