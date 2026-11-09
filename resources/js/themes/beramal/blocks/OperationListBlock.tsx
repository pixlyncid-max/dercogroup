import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, MapPin } from 'lucide-react';
import {
    useLocale,
    type OperationData,
    type OperationListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';
import { WaveDivider } from '../WaveDivider';
import { WorkflowStepper } from '../WorkflowStepper';

export interface OperationListBlockProps {
    data: OperationListBlockData;
    isFirstBlock?: boolean;
}

const NODE_COORDS: ReadonlyArray<{ x: number; y: number }> = [
    { x: 110, y: 120 },
    { x: 235, y: 84 },
    { x: 355, y: 136 },
    { x: 475, y: 96 },
];

/**
 * Beramal Sukses Sejahtera Operations ("Calm Flow", DESIGN.md Section 7.4).
 * Combines the SVG Cerulean Workflow Stepper (Anime.js path animation) with rounded Facility Cards.
 */
export function OperationListBlock({ data, isFirstBlock = false }: OperationListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const operations: OperationData[] = props.operations ?? [];

    const isHomePage = props.page?.key === 'home';
    const operationsNavHref = props.navigation?.items?.find(
        (item) => item.key === 'operations',
    )?.href;

    const workflowSteps = [
        {
            code: t('operations.calm_flow_steps.planning.code'),
            title: t('operations.calm_flow_steps.planning.title'),
            description: t('operations.calm_flow_steps.planning.description'),
            iconName: 'compass',
        },
        {
            code: t('operations.calm_flow_steps.coordination.code'),
            title: t('operations.calm_flow_steps.coordination.title'),
            description: t('operations.calm_flow_steps.coordination.description'),
            iconName: 'layers',
        },
        {
            code: t('operations.calm_flow_steps.verification.code'),
            title: t('operations.calm_flow_steps.verification.title'),
            description: t('operations.calm_flow_steps.verification.description'),
            iconName: 'shield-check',
        },
        {
            code: t('operations.calm_flow_steps.handover.code'),
            title: t('operations.calm_flow_steps.handover.title'),
            description: t('operations.calm_flow_steps.handover.description'),
            iconName: 'file-check',
        },
    ];

    return (
        <>
            <WaveDivider tone="surface" />
            <Section tone="surface">
                <Container>
                    <CalmHeader
                        eyebrow={data.eyebrow}
                        title={data.title}
                        description={data.description}
                        level={isFirstBlock ? 1 : 2}
                        pillTone="bg"
                        action={
                            isHomePage && operationsNavHref ? (
                                <Link
                                    href={operationsNavHref}
                                    className="inline-flex min-h-11 items-center gap-2 rounded-full border border-line bg-bg px-5 py-2.5 text-xs font-bold text-ink transition-colors hover:bg-accent hover:text-on-accent"
                                >
                                    <span>{t('see_details')}</span>
                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                </Link>
                            ) : null
                        }
                    />

                    {/* Stepper Alur Kerja (Numbered steps on Cerulean SVG line animated via Anime.js) */}
                    <div className="mt-12 space-y-6">
                        <div className="flex flex-wrap items-center justify-between gap-2">
                            <p className="text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                {t('operations.calm_flow_eyebrow')} —{' '}
                                {t('operations.calm_flow_heading')}
                            </p>
                        </div>

                        <WorkflowStepper steps={workflowSteps} cardTone="bg" />
                    </div>

                    {/* Facility Cards (Kartu Fasilitas) + Calm Schematic Corridor Map */}
                    <div className="mt-14 space-y-8">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <Heading level={2} size="h3">
                                {t('operations.locations_heading')}
                            </Heading>
                        </div>

                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            {operations.map((op, idx) => (
                                <Reveal
                                    key={op.id}
                                    preset="fadeUp"
                                    className="flex flex-col justify-between rounded-[var(--radius-card)] border border-line bg-bg p-6 shadow-sm sm:p-7 space-y-4"
                                >
                                    <div className="space-y-3">
                                        <div className="flex flex-wrap items-center justify-between gap-2">
                                            <span className="inline-flex items-center gap-2 rounded-full bg-surface px-3.5 py-1 text-xs font-bold text-ink">
                                                <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-accent font-heading text-[11px] text-on-accent">
                                                    {String(idx + 1).padStart(2, '0')}
                                                </span>
                                                <span>{op.type_label}</span>
                                            </span>

                                            {op.latitude != null && op.longitude != null ? (
                                                <span className="rounded-full border border-line px-3 py-1 font-mono text-[11px] text-muted">
                                                    {`${Number(op.latitude).toFixed(4)}°, ${Number(op.longitude).toFixed(4)}°`}
                                                </span>
                                            ) : null}
                                        </div>

                                        <Heading
                                            level={isFirstBlock ? 2 : 3}
                                            size="h3"
                                            className="font-bold"
                                        >
                                            {op.name}
                                        </Heading>

                                        <p className="text-sm leading-6 text-muted">
                                            {op.description}
                                        </p>
                                    </div>

                                    <div className="flex items-center gap-2 border-t border-line pt-4 text-xs font-semibold text-ink">
                                        <MapPin
                                            className="h-4 w-4 shrink-0 text-accent-strong"
                                            aria-hidden="true"
                                        />
                                        <span>{op.location_label}</span>
                                    </div>
                                </Reveal>
                            ))}
                        </div>

                        {data.show_map !== false && operations.length > 0 ? (
                            <Reveal
                                preset="fadeIn"
                                className="rounded-[var(--radius-card)] bg-surface-dark p-6 text-ink-on-dark shadow-sm sm:p-8"
                            >
                                <div className="flex flex-wrap items-center justify-between gap-4 border-b border-ink-on-dark/15 pb-4">
                                    <span className="text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                        {t('operations.map_heading')}
                                    </span>
                                    <span className="rounded-full bg-surface-deeper px-3.5 py-1 text-xs font-semibold text-muted-on-dark">
                                        {`${operations.length} ${t('operations.locations_heading')}`}
                                    </span>
                                </div>

                                <div className="mt-5 overflow-hidden rounded-[var(--radius-card)] bg-surface-deeper p-4">
                                    <svg
                                        viewBox="0 0 580 200"
                                        className="h-44 w-full text-accent-on-dark"
                                        role="img"
                                        aria-label={t('operations.map_heading')}
                                    >
                                        <path
                                            d="M 20 120 C 140 60, 260 160, 380 100 C 460 60, 520 110, 560 90"
                                            fill="none"
                                            stroke="currentColor"
                                            strokeOpacity="0.35"
                                            strokeWidth="2.5"
                                            strokeDasharray="6 6"
                                        />
                                        {operations.map((op, idx) => {
                                            const pos = NODE_COORDS[idx % NODE_COORDS.length];
                                            return (
                                                <g
                                                    key={op.id}
                                                    transform={`translate(${pos.x}, ${pos.y})`}
                                                >
                                                    <circle
                                                        r="18"
                                                        fill="currentColor"
                                                        fillOpacity="0.2"
                                                    />
                                                    <circle r="6" fill="currentColor" />
                                                    <text
                                                        x="12"
                                                        y="4"
                                                        fill="currentColor"
                                                        fontSize="11"
                                                        fontWeight="700"
                                                    >
                                                        {`0${idx + 1}`}
                                                    </text>
                                                </g>
                                            );
                                        })}
                                    </svg>
                                </div>
                            </Reveal>
                        ) : null}
                    </div>
                </Container>
            </Section>
            <WaveDivider tone="surface" flip />
        </>
    );
}
