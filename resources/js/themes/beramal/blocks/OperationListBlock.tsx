import { Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    CheckCircle2,
    Droplets,
    FlaskConical,
    Layers,
    MapPin,
    Navigation,
    Ship,
} from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef, useState } from 'react';
import {
    useLocale,
    type OperationData,
    type OperationListBlockData,
    type OperationTypeValue,
    type SharedPageProps,
} from '@/core';
import { useGsapScrollFloat, useGsapStagger } from '@/motion/gsapLenis';
import { Container, Heading, Reveal, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';
import { WaveDivider } from '../WaveDivider';
import { WorkflowStepper } from '../WorkflowStepper';

export interface OperationListBlockProps {
    data: OperationListBlockData;
    isFirstBlock?: boolean;
}

const NODE_COORDS: ReadonlyArray<{ x: number; y: number }> = [
    { x: 96, y: 128 },
    { x: 226, y: 78 },
    { x: 356, y: 132 },
    { x: 484, y: 86 },
];

const FALLBACK_IMAGES: Record<OperationTypeValue, string> = {
    mine: '/images/placeholders/pit-benches-1280.webp',
    stockpile: '/images/placeholders/aerial-pit-1280.webp',
    facility: '/images/placeholders/crushing-plant-1280.webp',
    port: '/images/placeholders/barge-jetty-1280.webp',
    fleet: '/images/placeholders/haul-trucks-1280.webp',
};

/**
 * Beramal Sukses Sejahtera Nickel Laterite Operations ("Calm Flow").
 * Features nickel quality-control highlights, animated 4-stage nickel value-chain stepper,
 * visual facility cards with GSAP + Motion depth, and an interactive dark-water corridor map.
 */
export function OperationListBlock({ data, isFirstBlock = false }: OperationListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const operations: OperationData[] = props.operations ?? [];
    const [activeNodeIndex, setActiveNodeIndex] = useState(0);

    const paramsGridRef = useRef<HTMLDivElement | null>(null);
    const facilitiesGridRef = useRef<HTMLDivElement | null>(null);
    const mapTriggerRef = useRef<HTMLDivElement | null>(null);
    const mapFloatRef = useRef<HTMLDivElement | null>(null);

    useGsapStagger(paramsGridRef, {
        y: 22,
        stagger: 0.08,
        duration: 0.65,
    });
    useGsapStagger(facilitiesGridRef, {
        y: 28,
        stagger: 0.1,
        duration: 0.7,
    });
    useGsapScrollFloat(mapTriggerRef, mapFloatRef, { fromY: 12, toY: -12 });

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
            iconName: 'ship',
        },
    ];

    const nickelParams = [
        {
            key: 'ore_type',
            icon: Layers,
            label: t('operations.nickel.params.ore_type.label'),
            value: t('operations.nickel.params.ore_type.value'),
            detail: t('operations.nickel.params.ore_type.detail'),
        },
        {
            key: 'grade_control',
            icon: FlaskConical,
            label: t('operations.nickel.params.grade_control.label'),
            value: t('operations.nickel.params.grade_control.value'),
            detail: t('operations.nickel.params.grade_control.detail'),
        },
        {
            key: 'moisture',
            icon: Droplets,
            label: t('operations.nickel.params.moisture.label'),
            value: t('operations.nickel.params.moisture.value'),
            detail: t('operations.nickel.params.moisture.detail'),
        },
        {
            key: 'shipment',
            icon: Ship,
            label: t('operations.nickel.params.shipment.label'),
            value: t('operations.nickel.params.shipment.value'),
            detail: t('operations.nickel.params.shipment.detail'),
        },
    ];

    const safeActiveIndex =
        operations.length > 0 ? Math.min(activeNodeIndex, operations.length - 1) : 0;
    const activeOperation = operations[safeActiveIndex] ?? null;

    const sectionHeadingLevel = isFirstBlock ? 2 : 3;
    const cardHeadingLevel = isFirstBlock ? 3 : 4;

    return (
        <>
            {!isFirstBlock ? <WaveDivider tone="surface" /> : null}
            <Section
                tone="surface"
                className={isFirstBlock ? 'pt-28 sm:pt-32 lg:pt-36' : undefined}
            >
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
                            ) : (
                                <span className="inline-flex items-center gap-2 rounded-full bg-surface-dark px-4 py-2 text-xs font-semibold text-ink-on-dark shadow-sm">
                                    <span
                                        aria-hidden="true"
                                        className="h-2 w-2 rounded-full bg-accent-on-dark"
                                    />
                                    <span>{t('operations.nickel.badge')}</span>
                                </span>
                            )
                        }
                    />

                    {/* Nickel Laterite Technical Quality-Control Highlights */}
                    <div className="mt-12 space-y-5">
                        <div className="space-y-1.5">
                            <p className="text-xs font-bold uppercase tracking-[0.12em] text-accent-strong">
                                {t('operations.nickel.params_eyebrow')}
                            </p>
                            <Heading level={sectionHeadingLevel} size="h3">
                                {t('operations.nickel.params_heading')}
                            </Heading>
                        </div>

                        <div
                            ref={paramsGridRef}
                            className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                        >
                            {nickelParams.map((param) => {
                                const Icon = param.icon;
                                return (
                                    <motion.div
                                        key={param.key}
                                        data-gsap-item
                                        whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                                        transition={{ type: 'spring', stiffness: 320, damping: 24 }}
                                        className="flex flex-col justify-between rounded-[var(--radius-card)] border border-line bg-bg p-5 shadow-sm transition-shadow hover:shadow-md"
                                    >
                                        <div className="space-y-3">
                                            <div className="flex items-center justify-between gap-2">
                                                <span className="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-surface text-accent-strong">
                                                    <Icon className="h-5 w-5" aria-hidden="true" />
                                                </span>
                                                <span className="text-[11px] font-bold uppercase tracking-[0.08em] text-muted">
                                                    {param.label}
                                                </span>
                                            </div>
                                            <p className="font-heading text-lg font-bold text-ink">
                                                {param.value}
                                            </p>
                                            <p className="text-sm leading-6 text-muted">
                                                {param.detail}
                                            </p>
                                        </div>
                                    </motion.div>
                                );
                            })}
                        </div>
                    </div>

                    {/* 4-Stage Nickel Laterite Value-Chain Stepper */}
                    <div className="mt-16 space-y-6">
                        <div className="space-y-1.5">
                            <p className="text-xs font-bold uppercase tracking-[0.12em] text-accent-strong">
                                {t('operations.calm_flow_eyebrow')}
                            </p>
                            <Heading level={sectionHeadingLevel} size="h3">
                                {t('operations.calm_flow_heading')}
                            </Heading>
                        </div>

                        <WorkflowStepper steps={workflowSteps} cardTone="bg" />
                    </div>

                    {/* Nickel Facility Showcase Cards */}
                    <div className="mt-16 space-y-8">
                        <div className="flex flex-wrap items-end justify-between gap-4">
                            <div className="space-y-1.5">
                                <p className="text-xs font-bold uppercase tracking-[0.12em] text-accent-strong">
                                    {t('operations.directory_eyebrow')}
                                </p>
                                <Heading level={sectionHeadingLevel} size="h3">
                                    {t('operations.locations_heading')}
                                </Heading>
                            </div>
                        </div>

                        <div
                            ref={facilitiesGridRef}
                            className="grid grid-cols-1 gap-6 md:grid-cols-2"
                        >
                            {operations.map((op, idx) => {
                                const imageSrc =
                                    op.image_url || FALLBACK_IMAGES[op.type] || FALLBACK_IMAGES.mine;

                                return (
                                    <motion.article
                                        key={op.id}
                                        data-gsap-item
                                        whileHover={prefersReducedMotion ? undefined : { y: -5 }}
                                        transition={{ type: 'spring', stiffness: 280, damping: 24 }}
                                        className="group flex flex-col overflow-hidden rounded-[var(--radius-card)] border border-line bg-bg shadow-sm transition-shadow hover:shadow-lg"
                                    >
                                        {/* Facility Visual Header */}
                                        <div className="relative aspect-[16/9] w-full overflow-hidden bg-surface-dark">
                                            <img
                                                src={imageSrc}
                                                alt={op.image_alt || op.name}
                                                loading="lazy"
                                                decoding="async"
                                                className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            />
                                            <div
                                                aria-hidden="true"
                                                className="absolute inset-0 bg-gradient-to-t from-surface-dark/75 via-surface-dark/15 to-transparent"
                                            />

                                            <div className="absolute left-4 top-4 flex flex-wrap items-center gap-2">
                                                <span className="inline-flex items-center gap-2 rounded-full bg-bg/95 px-3.5 py-1 text-xs font-bold text-ink shadow-sm backdrop-blur-sm">
                                                    <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-accent font-heading text-[11px] text-on-accent">
                                                        {String(idx + 1).padStart(2, '0')}
                                                    </span>
                                                    <span>{op.type_label}</span>
                                                </span>
                                            </div>

                                            {op.latitude != null && op.longitude != null ? (
                                                <span className="absolute bottom-3.5 right-4 inline-flex items-center gap-1.5 rounded-full bg-surface-deeper/90 px-3 py-1 font-mono text-[11px] text-ink-on-dark backdrop-blur-sm">
                                                    <Navigation
                                                        className="h-3 w-3 text-accent-on-dark"
                                                        aria-hidden="true"
                                                    />
                                                    <span>
                                                        {`${Number(op.latitude).toFixed(4)}°, ${Number(op.longitude).toFixed(4)}°`}
                                                    </span>
                                                </span>
                                            ) : null}
                                        </div>

                                        {/* Facility Content Body */}
                                        <div className="flex flex-1 flex-col justify-between space-y-5 p-6 sm:p-7">
                                            <div className="space-y-3">
                                                <Heading
                                                    level={cardHeadingLevel}
                                                    size="h3"
                                                    className="font-bold"
                                                >
                                                    {op.name}
                                                </Heading>

                                                <p className="text-sm leading-6 text-muted">
                                                    {op.description}
                                                </p>
                                            </div>

                                            <div className="flex flex-wrap items-center justify-between gap-3 border-t border-line pt-4">
                                                <div className="flex items-center gap-2 text-xs font-semibold text-ink">
                                                    <MapPin
                                                        className="h-4 w-4 shrink-0 text-accent-strong"
                                                        aria-hidden="true"
                                                    />
                                                    <span>{op.location_label}</span>
                                                </div>

                                                <span className="inline-flex items-center gap-1.5 rounded-full bg-surface px-3 py-1 text-[11px] font-semibold text-ink">
                                                    <CheckCircle2
                                                        className="h-3.5 w-3.5 text-accent-strong"
                                                        aria-hidden="true"
                                                    />
                                                    <span>{t('operations.nickel.active_status')}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </motion.article>
                                );
                            })}
                        </div>

                        {/* Interactive Dark-Water Nickel Logistics Corridor Map */}
                        {data.show_map !== false && operations.length > 0 ? (
                            <Reveal
                                preset="fadeIn"
                                className="rounded-[var(--radius-card)] bg-surface-dark p-6 text-ink-on-dark shadow-md sm:p-8"
                            >
                                <div ref={mapTriggerRef}>
                                    <div ref={mapFloatRef}>
                                    <div className="flex flex-wrap items-start justify-between gap-4 border-b border-ink-on-dark/15 pb-5">
                                        <div className="max-w-[60ch] space-y-1.5">
                                            <span className="text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                                {t('operations.map_heading')}
                                            </span>
                                            <p className="text-sm leading-6 text-muted-on-dark">
                                                {t('operations.nickel.map_subtitle')}
                                            </p>
                                        </div>
                                        <span className="rounded-full bg-surface-deeper px-3.5 py-1.5 text-xs font-semibold text-muted-on-dark">
                                            {`${operations.length} ${t('operations.locations_heading')}`}
                                        </span>
                                    </div>

                                    <div className="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12 lg:items-center">
                                        {/* Interactive SVG Corridor Schematic */}
                                        <div className="overflow-hidden rounded-[var(--radius-card)] bg-surface-deeper p-5 lg:col-span-7">
                                            <p className="mb-3 text-xs font-medium text-muted-on-dark">
                                                {t('operations.nickel.interactive_hint')}
                                            </p>
                                            <svg
                                                viewBox="0 0 580 200"
                                                className="h-44 w-full text-accent-on-dark sm:h-48"
                                                role="img"
                                                aria-label={t('operations.map_heading')}
                                            >
                                                <path
                                                    d="M 24 134 C 128 62, 248 164, 368 106 C 452 66, 512 112, 558 84"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    strokeOpacity="0.28"
                                                    strokeWidth="3"
                                                    strokeDasharray="7 7"
                                                />
                                                <path
                                                    d="M 96 128 L 226 78 L 356 132 L 484 86"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    strokeOpacity="0.7"
                                                    strokeWidth="2.5"
                                                />
                                                {operations.map((op, idx) => {
                                                    const pos =
                                                        NODE_COORDS[idx % NODE_COORDS.length];
                                                    const isSelected = idx === safeActiveIndex;
                                                    return (
                                                        <g
                                                            key={op.id}
                                                            transform={`translate(${pos.x}, ${pos.y})`}
                                                            className="cursor-pointer"
                                                            onClick={() => setActiveNodeIndex(idx)}
                                                        >
                                                            <circle
                                                                r={isSelected ? 24 : 16}
                                                                fill="currentColor"
                                                                fillOpacity={isSelected ? 0.28 : 0.14}
                                                            />
                                                            <circle
                                                                r={isSelected ? 9 : 6}
                                                                fill="currentColor"
                                                            />
                                                            <text
                                                                x="15"
                                                                y="4"
                                                                fill="currentColor"
                                                                fontSize="12"
                                                                fontWeight="700"
                                                            >
                                                                {String(idx + 1).padStart(2, '0')}
                                                            </text>
                                                        </g>
                                                    );
                                                })}
                                            </svg>

                                            {/* Accessible Node Selector Buttons */}
                                            <div className="mt-4 flex flex-wrap gap-2">
                                                {operations.map((op, idx) => {
                                                    const isSelected = idx === safeActiveIndex;
                                                    return (
                                                        <button
                                                            key={op.id}
                                                            type="button"
                                                            aria-pressed={isSelected}
                                                            onClick={() => setActiveNodeIndex(idx)}
                                                            className={`inline-flex min-h-9 items-center gap-2 rounded-full px-3.5 py-1.5 text-xs font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent-on-dark ${
                                                                isSelected
                                                                    ? 'bg-accent text-on-accent shadow-sm'
                                                                    : 'bg-surface-dark/80 text-muted-on-dark hover:bg-surface-dark hover:text-ink-on-dark'
                                                            }`}
                                                        >
                                                            <span>
                                                                {String(idx + 1).padStart(2, '0')}
                                                            </span>
                                                            <span>{op.type_label}</span>
                                                        </button>
                                                    );
                                                })}
                                            </div>
                                        </div>

                                        {/* Selected Nickel Node Inspector Panel */}
                                        {activeOperation ? (
                                            <div className="flex flex-col justify-between rounded-[var(--radius-card)] border border-ink-on-dark/15 bg-surface-deeper/90 p-5 sm:p-6 lg:col-span-5">
                                                <div className="space-y-3">
                                                    <div className="flex flex-wrap items-center justify-between gap-2">
                                                        <span className="inline-flex items-center gap-2 rounded-full bg-accent/25 px-3 py-1 text-xs font-bold text-accent-on-dark">
                                                            <span>
                                                                {`${t('operations.nickel.stage_label')} ${String(safeActiveIndex + 1).padStart(2, '0')}`}
                                                            </span>
                                                            <span aria-hidden="true">·</span>
                                                            <span>{activeOperation.type_label}</span>
                                                        </span>
                                                    </div>

                                                    <Heading
                                                        level={cardHeadingLevel}
                                                        size="h3"
                                                        className="text-ink-on-dark"
                                                    >
                                                        {activeOperation.name}
                                                    </Heading>

                                                    <p className="text-sm leading-6 text-muted-on-dark">
                                                        {activeOperation.description}
                                                    </p>
                                                </div>

                                                <div className="mt-5 space-y-2 border-t border-ink-on-dark/15 pt-4 text-xs text-muted-on-dark">
                                                    <div className="flex items-center gap-2 font-semibold text-ink-on-dark">
                                                        <MapPin
                                                            className="h-4 w-4 shrink-0 text-accent-on-dark"
                                                            aria-hidden="true"
                                                        />
                                                        <span>{activeOperation.location_label}</span>
                                                    </div>
                                                    {activeOperation.latitude != null &&
                                                    activeOperation.longitude != null ? (
                                                        <p className="font-mono text-[11px] text-accent-on-dark">
                                                            {`${t('operations.coordinates_label')}: ${Number(activeOperation.latitude).toFixed(4)}°, ${Number(activeOperation.longitude).toFixed(4)}°`}
                                                        </p>
                                                    ) : null}
                                                </div>
                                            </div>
                                        ) : null}
                                    </div>
                                    </div>
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
