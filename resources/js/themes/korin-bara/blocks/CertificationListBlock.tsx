import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Download, FileCheck, ShieldCheck } from 'lucide-react';
import {
    useLocale,
    type CertificationData,
    type CertificationListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { FieldSectionHeader } from '../FieldSectionHeader';

export interface CertificationListBlockProps {
    data: CertificationListBlockData;
    isFirstBlock?: boolean;
}

/**
 * Korin Bara Certification & Compliance Grid ("Field Rail", DESIGN.md Section 6.4).
 * Renders a grid of industrial document cards with icon, name, validity period, and PDF download button.
 */
export function CertificationListBlock({
    data,
    isFirstBlock = false,
}: CertificationListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const certifications: CertificationData[] = props.certifications ?? [];

    const isHomePage = props.page?.key === 'home';
    const complianceNavHref = props.navigation?.items?.find(
        (item) => item.key === 'compliance',
    )?.href;

    return (
        <Section tone="default" className="border-b-2 border-line">
            <Container>
                <FieldSectionHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                    level={isFirstBlock ? 1 : 2}
                    action={
                        isHomePage && complianceNavHref ? (
                            <Link
                                href={complianceNavHref}
                                className="inline-flex min-h-11 items-center gap-2 border-2 border-ink bg-surface px-4 py-2 font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink transition-colors hover:bg-surface-dark hover:text-ink-on-dark"
                            >
                                <span>{t('see_details')}</span>
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        ) : null
                    }
                />

                <div className="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {certifications.map((cert) => (
                        <Reveal
                            key={cert.id}
                            preset="fadeUp"
                            className="flex flex-col justify-between border-2 border-ink border-t-4 border-t-accent bg-bg p-6 sm:p-7 space-y-6"
                        >
                            <div className="space-y-4">
                                <div className="flex items-center justify-between gap-3">
                                    <span className="inline-flex h-10 w-10 items-center justify-center bg-surface border border-line text-ink">
                                        {cert.category === 'permit' ? (
                                            <ShieldCheck className="h-5 w-5" aria-hidden="true" />
                                        ) : (
                                            <FileCheck className="h-5 w-5" aria-hidden="true" />
                                        )}
                                    </span>
                                    <span className="bg-accent px-3 py-1 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                        {cert.category_label}
                                    </span>
                                </div>

                                <Heading
                                    level={isFirstBlock ? 2 : 3}
                                    size="h3"
                                    className="text-ink"
                                >
                                    {cert.name}
                                </Heading>

                                <dl className="space-y-2 border-t border-line pt-4 text-xs">
                                    <div className="flex justify-between gap-2">
                                        <dt className="font-heading font-bold uppercase tracking-wider text-muted">
                                            {t('compliance.issuer_label')}
                                        </dt>
                                        <dd className="font-semibold text-ink text-right">
                                            {cert.issuer}
                                        </dd>
                                    </div>
                                    {cert.number ? (
                                        <div className="flex justify-between gap-2">
                                            <dt className="font-heading font-bold uppercase tracking-wider text-muted">
                                                {t('compliance.number_label')}
                                            </dt>
                                            <dd className="font-mono text-ink text-right">
                                                {cert.number}
                                            </dd>
                                        </div>
                                    ) : null}
                                    <div className="flex justify-between gap-2">
                                        <dt className="font-heading font-bold uppercase tracking-wider text-muted">
                                            {t('compliance.validity_label')}
                                        </dt>
                                        <dd className="font-mono text-ink text-right">
                                            {cert.issued_at
                                                ? `${cert.issued_at} — ${cert.expires_at ?? t('compliance.permanent_label')}`
                                                : t('compliance.permanent_label')}
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            {cert.file_url ? (
                                <div className="pt-2">
                                    <a
                                        href={cert.file_url}
                                        download
                                        className="flex min-h-11 w-full items-center justify-center gap-2 bg-surface-dark px-4 py-2.5 font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink-on-dark transition-colors hover:bg-surface-deeper"
                                    >
                                        <Download
                                            className="h-4 w-4 text-accent-on-dark"
                                            aria-hidden="true"
                                        />
                                        <span>{t('download_pdf')}</span>
                                    </a>
                                </div>
                            ) : null}
                        </Reveal>
                    ))}
                </div>
            </Container>
        </Section>
    );
}
