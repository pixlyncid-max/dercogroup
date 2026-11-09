import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Download, FileCheck } from 'lucide-react';
import {
    useLocale,
    type CertificationCategoryValue,
    type CertificationData,
    type CertificationListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';

export interface CertificationListBlockProps {
    data: CertificationListBlockData;
    isFirstBlock?: boolean;
}

const CATEGORY_ORDER: readonly CertificationCategoryValue[] = [
    'permit',
    'certificate',
    'policy',
    'report',
] as const;

/**
 * Korin Berkat Certification & Compliance List ("Editorial Index", DESIGN.md Section 5.4).
 * Grouped rows by category with certificate name, issuer, number, validity period, and PDF download button.
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

    const groups = CATEGORY_ORDER.map((categoryKey) => {
        const items = certifications.filter((cert) => cert.category === categoryKey);
        return {
            key: categoryKey,
            label: items[0]?.category_label ?? categoryKey.toUpperCase(),
            items,
        };
    }).filter((group) => group.items.length > 0);

    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                <EditorialHeader
                    indexLabel="05"
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                    level={isFirstBlock ? 1 : 2}
                    action={
                        isHomePage && complianceNavHref ? (
                            <Link
                                href={complianceNavHref}
                                className="inline-flex min-h-11 items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-ink transition-colors hover:text-accent-strong"
                            >
                                <span>{t('see_details')}</span>
                                <ArrowRight
                                    className="h-4 w-4 text-accent-strong"
                                    aria-hidden="true"
                                />
                            </Link>
                        ) : null
                    }
                />

                <div className="mt-12 space-y-10">
                    {groups.map((group, groupIdx) => (
                        <Reveal key={group.key} preset="fadeUp" className="space-y-4">
                            {/* Category Header Bar */}
                            <div className="flex items-center justify-between border-b-2 border-ink pb-3">
                                <div className="flex items-center gap-3">
                                    <span className="font-heading text-sm font-semibold text-accent-strong">
                                        {`0${groupIdx + 1}`}
                                    </span>
                                    <Heading
                                        level={isFirstBlock ? 2 : 3}
                                        size="h3"
                                        className="text-base font-semibold uppercase tracking-[0.1em]"
                                    >
                                        {group.label}
                                    </Heading>
                                </div>
                                <span className="font-mono text-xs font-semibold text-muted">
                                    {`[${group.items.length}]`}
                                </span>
                            </div>

                            {/* Category Rows */}
                            <div className="divide-y divide-line border-b border-line">
                                {group.items.map((cert) => (
                                    <div
                                        key={cert.id}
                                        className="grid grid-cols-1 gap-4 py-5 lg:grid-cols-12 lg:items-center lg:gap-6"
                                    >
                                        {/* Certificate Name & Number */}
                                        <div className="lg:col-span-5 space-y-1">
                                            <div className="flex items-start gap-2.5">
                                                <FileCheck
                                                    className="mt-1 h-4 w-4 shrink-0 text-accent-strong"
                                                    aria-hidden="true"
                                                />
                                                <div>
                                                    <p className="font-heading text-lg font-semibold text-ink">
                                                        {cert.name}
                                                    </p>
                                                    {cert.number ? (
                                                        <p className="font-mono text-xs text-muted">
                                                            {`${t('compliance.number_label')}: ${cert.number}`}
                                                        </p>
                                                    ) : null}
                                                </div>
                                            </div>
                                        </div>

                                        {/* Issuer */}
                                        <div className="lg:col-span-3 text-sm">
                                            <p className="text-[11px] font-semibold uppercase tracking-[0.1em] text-muted">
                                                {t('compliance.issuer_label')}
                                            </p>
                                            <p className="mt-0.5 font-medium text-ink">
                                                {cert.issuer}
                                            </p>
                                        </div>

                                        {/* Validity Period */}
                                        <div className="lg:col-span-2 text-sm">
                                            <p className="text-[11px] font-semibold uppercase tracking-[0.1em] text-muted">
                                                {t('compliance.validity_label')}
                                            </p>
                                            <p className="mt-0.5 font-mono text-xs text-ink">
                                                {cert.issued_at
                                                    ? `${cert.issued_at} — ${cert.expires_at ?? t('compliance.permanent_label')}`
                                                    : t('compliance.permanent_label')}
                                            </p>
                                        </div>

                                        {/* PDF Download Button */}
                                        <div className="lg:col-span-2 flex items-center justify-start lg:justify-end">
                                            {cert.file_url ? (
                                                <a
                                                    href={cert.file_url}
                                                    download
                                                    className="inline-flex min-h-11 items-center gap-2 rounded-[var(--radius-button)] border border-ink bg-bg px-4 py-2 text-xs font-semibold uppercase tracking-[0.1em] text-ink transition-colors hover:bg-surface-dark hover:text-ink-on-dark"
                                                >
                                                    <Download
                                                        className="h-3.5 w-3.5 text-accent-strong"
                                                        aria-hidden="true"
                                                    />
                                                    <span>{t('download_pdf')}</span>
                                                </a>
                                            ) : null}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </Reveal>
                    ))}
                </div>
            </Container>
        </Section>
    );
}
