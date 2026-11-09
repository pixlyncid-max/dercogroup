import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, MapPin } from 'lucide-react';
import {
    useLocale,
    type OperationData,
    type OperationListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';
import { GoldRule } from '../GoldRule';

export interface OperationListBlockProps {
    data: OperationListBlockData;
    isFirstBlock?: boolean;
}

const PIN_POSITIONS: ReadonlyArray<{ x: number; y: number }> = [
    { x: 245, y: 88 },
    { x: 280, y: 122 },
    { x: 215, y: 158 },
    { x: 315, y: 98 },
];

/**
 * Korin Berkat Operation List ("Editorial Index", DESIGN.md Section 5.4).
 * Displays a schematic coordinate map with numbered pins alongside the location index,
 * followed by a vertical Pit-to-Vessel production flow timeline.
 */
export function OperationListBlock({ data, isFirstBlock = false }: OperationListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const operations: OperationData[] = props.operations ?? [];

    const isHomePage = props.page?.key === 'home';
    const operationsNavHref = props.navigation?.items?.find(
        (item) => item.key === 'operations',
    )?.href;

    const flowSteps = [
        {
            code: t('operations.flow_steps.pit.code'),
            title: t('operations.flow_steps.pit.title'),
            description: t('operations.flow_steps.pit.description'),
        },
        {
            code: t('operations.flow_steps.crushing.code'),
            title: t('operations.flow_steps.crushing.title'),
            description: t('operations.flow_steps.crushing.description'),
        },
        {
            code: t('operations.flow_steps.jetty.code'),
            title: t('operations.flow_steps.jetty.title'),
            description: t('operations.flow_steps.jetty.description'),
        },
        {
            code: t('operations.flow_steps.vessel.code'),
            title: t('operations.flow_steps.vessel.title'),
            description: t('operations.flow_steps.vessel.description'),
        },
    ];

    return (
        <Section tone="surface" className="border-b border-line">
            <Container>
                <EditorialHeader
                    indexLabel="04"
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                    level={isFirstBlock ? 1 : 2}
                    action={
                        isHomePage && operationsNavHref ? (
                            <Link
                                href={operationsNavHref}
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

                {/* Split: Schematic Coordinate Pin Map (5 cols) + Location Index List (7 cols) */}
                <div className="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                    {data.show_map !== false ? (
                        <Reveal
                            preset="fadeIn"
                            className="lg:col-span-5 rounded-[var(--radius-card)] bg-surface-dark p-6 text-ink-on-dark border-t-4 border-accent sm:p-7"
                        >
                            <div className="flex items-center justify-between border-b border-ink-on-dark/15 pb-4">
                                <span className="text-xs font-semibold uppercase tracking-[0.12em] text-accent-on-dark">
                                    {t('operations.map_heading')}
                                </span>
                                <MapPin
                                    className="h-4 w-4 text-accent-on-dark"
                                    aria-hidden="true"
                                />
                            </div>

                            <div className="mt-5 overflow-hidden rounded-[var(--radius-card)] border border-ink-on-dark/15 bg-surface-deeper p-4">
                                <svg
                                    viewBox="0 0 420 240"
                                    className="h-56 w-full text-accent-on-dark"
                                    role="img"
                                    aria-label={t('operations.map_heading')}
                                >
                                    {/* Coordinate grid lines */}
                                    <path
                                        d="M0 60 H420 M0 120 H420 M0 180 H420 M84 0 V240 M168 0 V240 M252 0 V240 M336 0 V240"
                                        stroke="currentColor"
                                        strokeOpacity="0.14"
                                        strokeWidth="1"
                                    />
                                    {/* Stylized Kalimantan & Sea Corridor */}
                                    <path
                                        d="M150 42 L265 36 L310 78 L292 142 L245 186 L168 175 L138 118 Z"
                                        fill="currentColor"
                                        fillOpacity="0.1"
                                        stroke="currentColor"
                                        strokeOpacity="0.45"
                                        strokeWidth="1.5"
                                    />
                                    {/* Hauling & Barge Route */}
                                    <path
                                        d="M245 88 L280 122 L215 158"
                                        fill="none"
                                        stroke="currentColor"
                                        strokeDasharray="4 4"
                                        strokeWidth="1.5"
                                    />
                                    {operations.map((op, idx) => {
                                        const pos = PIN_POSITIONS[idx % PIN_POSITIONS.length];

                                        return (
                                            <g
                                                key={op.id}
                                                transform={`translate(${pos.x}, ${pos.y})`}
                                            >
                                                <circle
                                                    r="14"
                                                    fill="currentColor"
                                                    fillOpacity="0.22"
                                                />
                                                <circle r="5" fill="currentColor" />
                                                <text
                                                    x="10"
                                                    y="4"
                                                    fill="currentColor"
                                                    fontSize="10"
                                                    fontWeight="700"
                                                >
                                                    {`0${idx + 1}`}
                                                </text>
                                            </g>
                                        );
                                    })}
                                </svg>
                            </div>

                            <div className="mt-5 divide-y divide-ink-on-dark/10 text-xs">
                                {operations.map((op, idx) => (
                                    <div
                                        key={op.id}
                                        className="flex items-center justify-between gap-3 py-2.5"
                                    >
                                        <span className="inline-flex items-center gap-2 text-ink-on-dark font-medium">
                                            <span className="font-heading text-accent-on-dark font-semibold">
                                                {`0${idx + 1}`}
                                            </span>
                                            <span>{op.location_label}</span>
                                        </span>
                                        {op.latitude != null && op.longitude != null ? (
                                            <span className="font-mono text-[11px] text-muted-on-dark">
                                                {`${Number(op.latitude).toFixed(4)}°, ${Number(op.longitude).toFixed(4)}°`}
                                            </span>
                                        ) : null}
                                    </div>
                                ))}
                            </div>
                        </Reveal>
                    ) : null}

                    {/* Right 7 Columns: Editorial Location Directory */}
                    <div className={data.show_map !== false ? 'lg:col-span-7' : 'lg:col-span-12'}>
                        <div className="divide-y divide-line border-y border-line bg-bg">
                            {operations.map((op, idx) => (
                                <Reveal
                                    key={op.id}
                                    preset="fadeUp"
                                    className="p-6 sm:p-7 space-y-3"
                                >
                                    <div className="flex flex-wrap items-center justify-between gap-2">
                                        <div className="inline-flex items-center gap-3">
                                            <span className="font-heading text-lg font-semibold text-accent-strong">
                                                {String(idx + 1).padStart(2, '0')}
                                            </span>
                                            <span className="rounded-[var(--radius-button)] bg-surface px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.1em] text-ink">
                                                {op.type_label}
                                            </span>
                                        </div>
                                        <span className="text-xs font-medium text-muted">
                                            {op.location_label}
                                        </span>
                                    </div>

                                    <Heading
                                        level={isFirstBlock ? 2 : 3}
                                        size="h3"
                                        className="font-semibold"
                                    >
                                        {op.name}
                                    </Heading>

                                    <p className="text-sm leading-6 text-muted">{op.description}</p>
                                </Reveal>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Vertical Production Flow Timeline from Pit to Vessel (Full Operations Page) */}
                {!isHomePage ? (
                    <Reveal preset="fadeUp" className="mt-16 pt-14 border-t border-line">
                        <div className="max-w-[640px] space-y-3">
                            <p className="text-xs font-semibold uppercase tracking-[0.14em] text-ink">
                                {t('operations.flow_eyebrow')}
                            </p>
                            <GoldRule thick className="w-14" />
                            <Heading level={2} size="h2">
                                {t('operations.flow_heading')}
                            </Heading>
                        </div>

                        <ol className="mt-10 relative border-l-2 border-accent ml-4 space-y-8 pl-8">
                            {flowSteps.map((step) => (
                                <li
                                    key={step.code}
                                    className="relative rounded-[var(--radius-card)] border border-line bg-bg p-6 sm:p-7"
                                >
                                    <span
                                        aria-hidden="true"
                                        className="absolute -left-[43px] top-6 flex h-8 w-8 items-center justify-center rounded-full bg-surface-dark font-heading text-xs font-semibold text-accent-on-dark ring-4 ring-surface"
                                    >
                                        {step.code}
                                    </span>
                                    <Heading level={3} size="h3" className="font-semibold">
                                        {step.title}
                                    </Heading>
                                    <p className="mt-2 text-sm leading-6 text-muted">
                                        {step.description}
                                    </p>
                                </li>
                            ))}
                        </ol>
                    </Reveal>
                ) : null}
            </Container>
        </Section>
    );
}
