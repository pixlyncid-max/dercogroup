import { usePage } from '@inertiajs/react';
import { Clock, Mail, MapPin, Phone } from 'lucide-react';
import { useRef } from 'react';
import { useLocale, type ContactInfoBlockData, type SharedPageProps } from '@/core';
import { Container, Heading, Section } from '@/ui';
import { GoldRule } from '../GoldRule';
import { useGsapStagger } from '../useKorinBerkatMotion';

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
    const containerRef = useRef<HTMLDivElement | null>(null);
    useGsapStagger(containerRef, { y: 20, stagger: 0.08, duration: 0.6 });

    const contacts = props.contacts ?? props.company?.contacts ?? [];
    const primaryContact = contacts[0];

    return (
        <div ref={containerRef} className="space-y-8">
            <div data-gsap-item className="space-y-3.5">
                {data.eyebrow ? (
                    <div className="inline-flex items-center gap-3">
                        <span className="text-xs font-bold uppercase tracking-[0.16em] text-accent-strong">
                            {data.eyebrow}
                        </span>
                        <span
                            aria-hidden="true"
                            className="inline-block h-[2px] w-10 rounded-full bg-accent"
                        />
                    </div>
                ) : null}
                <Heading level={headingLevel} size="h2" className="font-bold tracking-tight text-ink">
                    {data.title}
                </Heading>
                <GoldRule thick className="w-14" />
                {data.description ? (
                    <p className="max-w-[52ch] text-base leading-7 text-muted">
                        {data.description}
                    </p>
                ) : null}
            </div>

            {primaryContact ? (
                <ul className="divide-y divide-line border-t border-line">
                    <li data-gsap-item className="flex items-start gap-4 py-5">
                        <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100/80 text-accent-strong">
                            <MapPin className="h-5 w-5" aria-hidden="true" />
                        </div>
                        <div className="space-y-1">
                            <p className="text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                {t('contact.address_label')}
                            </p>
                            <p className="text-sm leading-6 text-muted">
                                {primaryContact.address}
                            </p>
                        </div>
                    </li>

                    {primaryContact.email ? (
                        <li data-gsap-item className="flex items-start gap-4 py-5">
                            <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100/80 text-accent-strong">
                                <Mail className="h-5 w-5" aria-hidden="true" />
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                    {t('contact.email_label')}
                                </p>
                                <p className="text-sm font-semibold text-ink">
                                    <a
                                        href={`mailto:${primaryContact.email}`}
                                        className="transition-colors hover:text-accent-strong hover:underline"
                                    >
                                        {primaryContact.email}
                                    </a>
                                </p>
                            </div>
                        </li>
                    ) : null}

                    {primaryContact.phone ? (
                        <li data-gsap-item className="flex items-start gap-4 py-5">
                            <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100/80 text-accent-strong">
                                <Phone className="h-5 w-5" aria-hidden="true" />
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                    {t('contact.phone_label')}
                                </p>
                                <p className="font-heading text-lg font-bold text-ink">
                                    <a
                                        href={`tel:${primaryContact.phone.replaceAll(/\s+/g, '')}`}
                                        className="transition-colors hover:text-accent-strong hover:underline"
                                    >
                                        {primaryContact.phone}
                                    </a>
                                </p>
                            </div>
                        </li>
                    ) : null}

                    {primaryContact.operating_hours ? (
                        <li data-gsap-item className="flex items-start gap-4 py-5">
                            <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100/80 text-accent-strong">
                                <Clock className="h-5 w-5" aria-hidden="true" />
                            </div>
                            <div className="space-y-1">
                                <p className="text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                    {t('contact.hours_label')}
                                </p>
                                <p className="text-sm leading-6 text-muted">
                                    {primaryContact.operating_hours}
                                </p>
                            </div>
                        </li>
                    ) : null}
                </ul>
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
        <Section tone="default" className="border-b border-line">
            <Container>
                <ContactInfoContent data={data} headingLevel={headingLevel} />
            </Container>
        </Section>
    );
}
