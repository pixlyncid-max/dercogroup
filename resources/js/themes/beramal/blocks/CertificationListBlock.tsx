import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, ChevronDown, Download, FileCheck } from 'lucide-react';
import { useRef, useState, type KeyboardEvent } from 'react';
import {
    useLocale,
    type CertificationCategoryValue,
    type CertificationData,
    type CertificationListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Reveal, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';

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
 * Beramal Sukses Sejahtera Quality & Compliance Accordion ("Calm Flow", DESIGN.md Section 7.4).
 * Accessible WAI-ARIA Accordion per category: native <button>, aria-expanded, aria-controls,
 * role="region", aria-labelledby, and full keyboard navigation (Enter, Space, ArrowDown, ArrowUp, Home, End).
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

    const [openCategories, setOpenCategories] = useState<Record<string, boolean>>(() => {
        const firstKey = groups[0]?.key;
        return firstKey ? { [firstKey]: true } : {};
    });

    const triggerRefs = useRef<Array<HTMLButtonElement | null>>([]);

    const toggleCategory = (categoryKey: string) => {
        setOpenCategories((prev) => ({
            ...prev,
            [categoryKey]: !prev[categoryKey],
        }));
    };

    const handleKeyDown = (event: KeyboardEvent<HTMLButtonElement>, currentIndex: number) => {
        const total = groups.length;
        if (total === 0) {
            return;
        }

        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            toggleCategory(groups[currentIndex].key);
            return;
        }

        let nextIndex: number | null = null;

        if (event.key === 'ArrowDown') {
            nextIndex = (currentIndex + 1) % total;
        } else if (event.key === 'ArrowUp') {
            nextIndex = (currentIndex - 1 + total) % total;
        } else if (event.key === 'Home') {
            nextIndex = 0;
        } else if (event.key === 'End') {
            nextIndex = total - 1;
        }

        if (nextIndex !== null) {
            event.preventDefault();
            triggerRefs.current[nextIndex]?.focus();
        }
    };

    const HeadingTag = isFirstBlock ? 'h2' : 'h3';

    return (
        <Section tone="default">
            <Container>
                <CalmHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                    level={isFirstBlock ? 1 : 2}
                    action={
                        isHomePage && complianceNavHref ? (
                            <Link
                                href={complianceNavHref}
                                className="inline-flex min-h-11 items-center gap-2 rounded-full border border-line bg-surface px-5 py-2.5 text-xs font-bold text-ink transition-colors hover:bg-accent hover:text-on-accent"
                            >
                                <span>{t('see_details')}</span>
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        ) : null
                    }
                />

                <div className="mt-12 space-y-4" data-testid="compliance-accordion">
                    {groups.map((group, groupIdx) => {
                        const isOpen = Boolean(openCategories[group.key]);
                        const triggerId = `beramal-accordion-trigger-${group.key}`;
                        const panelId = `beramal-accordion-panel-${group.key}`;

                        return (
                            <Reveal
                                key={group.key}
                                preset="fadeUp"
                                className="overflow-hidden rounded-[var(--radius-card)] border border-line bg-bg shadow-sm"
                            >
                                <HeadingTag className="m-0">
                                    <button
                                        ref={(el) => {
                                            triggerRefs.current[groupIdx] = el;
                                        }}
                                        id={triggerId}
                                        type="button"
                                        aria-expanded={isOpen}
                                        aria-controls={panelId}
                                        onClick={() => toggleCategory(group.key)}
                                        onKeyDown={(e) => handleKeyDown(e, groupIdx)}
                                        className={[
                                            'flex min-h-16 w-full cursor-pointer items-center justify-between gap-4 px-6 py-4 text-left transition-colors sm:px-8 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-accent',
                                            isOpen ? 'bg-surface' : 'bg-bg hover:bg-surface/60',
                                        ].join(' ')}
                                    >
                                        <span className="flex items-center gap-3.5">
                                            <span
                                                aria-hidden="true"
                                                className="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-accent font-heading text-xs font-bold text-on-accent"
                                            >
                                                {String(groupIdx + 1).padStart(2, '0')}
                                            </span>
                                            <span className="font-heading text-lg font-bold text-ink">
                                                {group.label}
                                            </span>
                                        </span>

                                        <span className="flex items-center gap-3">
                                            <span className="rounded-full border border-line bg-bg px-3.5 py-1 text-xs font-bold text-ink">
                                                {`${group.items.length} ${t('compliance.documents_count')}`}
                                            </span>
                                            <span
                                                aria-hidden="true"
                                                className={[
                                                    'inline-flex h-9 w-9 items-center justify-center rounded-full border border-line bg-bg text-ink transition-transform duration-300',
                                                    isOpen ? 'rotate-180' : 'rotate-0',
                                                ].join(' ')}
                                            >
                                                <ChevronDown className="h-4 w-4" />
                                            </span>
                                        </span>
                                    </button>
                                </HeadingTag>

                                <div
                                    id={panelId}
                                    role="region"
                                    aria-labelledby={triggerId}
                                    hidden={!isOpen}
                                    className="divide-y divide-line border-t border-line bg-bg px-6 sm:px-8"
                                >
                                    {group.items.map((cert) => (
                                        <div
                                            key={cert.id}
                                            className="grid grid-cols-1 gap-4 py-5 lg:grid-cols-12 lg:items-center lg:gap-6"
                                        >
                                            {/* Document Name & Registration Number */}
                                            <div className="lg:col-span-5 flex items-start gap-3">
                                                <span className="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-surface text-accent-strong">
                                                    <FileCheck
                                                        className="h-4 w-4"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                                <div className="space-y-1">
                                                    <p className="font-heading text-base font-bold text-ink">
                                                        {cert.name}
                                                    </p>
                                                    {cert.number ? (
                                                        <p className="font-mono text-xs text-muted">
                                                            {`${t('compliance.number_label')}: ${cert.number}`}
                                                        </p>
                                                    ) : null}
                                                </div>
                                            </div>

                                            {/* Issuing Authority */}
                                            <div className="lg:col-span-3 text-sm">
                                                <p className="text-[11px] font-bold uppercase tracking-[0.08em] text-muted">
                                                    {t('compliance.issuer_label')}
                                                </p>
                                                <p className="mt-0.5 font-medium text-ink">
                                                    {cert.issuer}
                                                </p>
                                            </div>

                                            {/* Validity Period */}
                                            <div className="lg:col-span-2 text-sm">
                                                <p className="text-[11px] font-bold uppercase tracking-[0.08em] text-muted">
                                                    {t('compliance.validity_label')}
                                                </p>
                                                <p className="mt-0.5 font-mono text-xs text-ink">
                                                    {cert.issued_at
                                                        ? `${cert.issued_at} — ${cert.expires_at ?? t('compliance.permanent_label')}`
                                                        : t('compliance.permanent_label')}
                                                </p>
                                            </div>

                                            {/* Pill PDF Download Button */}
                                            <div className="lg:col-span-2 flex items-center justify-start lg:justify-end">
                                                {cert.file_url ? (
                                                    <a
                                                        href={cert.file_url}
                                                        download
                                                        className="inline-flex min-h-11 items-center gap-2 rounded-full bg-accent px-5 py-2 text-xs font-bold text-on-accent transition-opacity hover:opacity-90"
                                                    >
                                                        <Download
                                                            className="h-3.5 w-3.5"
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
                        );
                    })}
                </div>
            </Container>
        </Section>
    );
}
