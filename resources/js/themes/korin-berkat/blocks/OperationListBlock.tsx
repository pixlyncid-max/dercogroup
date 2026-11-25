import { usePage } from '@inertiajs/react';
import {
    ClipboardCheck,
    Layers,
    MapPin,
    Mountain,
    Ship,
} from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import {
    useLocale,
    type OperationData,
    type OperationListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Section } from '@/ui';
import { useGsapParallax, useGsapStagger } from '../useKorinBerkatMotion';

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

const OPERATION_FALLBACK_IMAGES = [
    '/images/placeholders/excavator-loading-1280.webp',
    '/images/placeholders/crushing-plant-1280.webp',
    '/images/placeholders/haul-trucks-1280.webp',
    '/images/placeholders/barge-jetty-1280.webp',
];

const FLOW_ICONS = [Mountain, ClipboardCheck, Layers, Ship];

/**
 * PT Korin Berkat Energy Operations Network & Integrated Supply Chain Flow.
 * - Preserves sr-only heading on Beranda, schematic coordinate map, and facility directory.
 * - On Operations Page: dark-navy executive banner + numbered photo cards + dark-navy
 *   Supply Chain Process Flow band with circular gold-outlined icon steps.
 */
export function OperationListBlock({ data, isFirstBlock = false }: OperationListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const bannerRef = useRef<HTMLDivElement | null>(null);
    const bannerBgRef = useRef<HTMLDivElement | null>(null);
    const cardsRef = useRef<HTMLDivElement | null>(null);
    const flowRef = useRef<HTMLOListElement | null>(null);

    useGsapParallax(bannerRef, bannerBgRef, 12);
    useGsapStagger(cardsRef, { y: 24, stagger: 0.08, duration: 0.65 });
    useGsapStagger(flowRef, { y: 22, stagger: 0.1, duration: 0.65 });

    const operations: OperationData[] = props.operations ?? [];
    const isHomePage = props.page?.key === 'home';

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
        <>
            {/* Dark Navy Executive Operations Banner on Full Operations Page */}
            {!isHomePage ? (
                <div
                    ref={bannerRef}
                    className="relative overflow-hidden bg-surface-dark text-ink-on-dark border-b border-white/10"
                >
                    <div
                        ref={bannerBgRef}
                        aria-hidden="true"
                        className="pointer-events-none absolute inset-0 z-0 will-change-transform"
                    >
                        <img
                            src="/images/placeholders/barge-jetty-1280.webp"
                            alt=""
                            className="h-full w-full object-cover object-center opacity-50"
                        />
                        <div className="absolute inset-0 bg-gradient-to-r from-[#081222]/95 via-[#0B172A]/82 to-[#0B172A]/45" />
                    </div>
                    <div
                        aria-hidden="true"
                        className="kbe-diagonal-wedge-left pointer-events-none absolute bottom-0 left-0 z-10 h-14 w-12 opacity-90"
                    />
                    <Container className="relative z-10 py-14 sm:py-16 lg:py-20">
                        <div className="flex items-stretch gap-5 max-w-3xl">
                            <span
                                aria-hidden="true"
                                className="w-1 shrink-0 rounded-full bg-accent"
                            />
                            <div className="space-y-3">
                                {data.eyebrow ? (
                                    <p className="text-xs font-bold uppercase tracking-[0.18em] text-accent-on-dark">
                                        {data.eyebrow}
                                    </p>
                                ) : null}
                                <Heading
                                    level={isFirstBlock ? 1 : 2}
                                    size="h2"
                                    className="font-bold tracking-tight text-white sm:text-4xl lg:text-5xl"
                                >
                                    {data.title}
                                </Heading>
                                {data.description ? (
                                    <p className="max-w-[58ch] text-base leading-7 text-slate-200">
                                        {data.description}
                                    </p>
                                ) : null}
                            </div>
                        </div>
                    </Container>
                </div>
            ) : null}

            <Section tone="surface" className="border-b border-line">
                <Container>
                    {isHomePage ? <h2 className="sr-only">{data.title}</h2> : null}

                    {/* Split: Schematic Coordinate Pin Map (5 cols) + Modern Operational Facility Cards (7 cols) */}
                    <div
                        ref={cardsRef}
                        className="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start"
                    >
                        {data.show_map !== false ? (
                            <motion.div
                                data-gsap-item
                                whileHover={prefersReducedMotion ? undefined : { y: -3 }}
                                transition={{ type: 'spring', stiffness: 280, damping: 24 }}
                                className="relative overflow-hidden rounded-[var(--radius-card)] border-t-4 border-accent bg-surface-dark p-6 text-ink-on-dark shadow-lg sm:p-7 lg:col-span-5"
                            >
                                <div
                                    aria-hidden="true"
                                    className="kbe-card-corner-gold pointer-events-none absolute right-0 bottom-0 h-10 w-10 opacity-85"
                                />

                                <div className="flex items-center justify-between border-b border-white/15 pb-4">
                                    <span className="text-xs font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                        {t('operations.map_heading')}
                                    </span>
                                    <MapPin
                                        className="h-4 w-4 text-accent-on-dark"
                                        aria-hidden="true"
                                    />
                                </div>

                                <div className="mt-5 overflow-hidden rounded-lg border border-white/15 bg-surface-deeper p-4">
                                    <svg
                                        viewBox="0 0 420 240"
                                        className="h-56 w-full text-accent-on-dark"
                                        role="img"
                                        aria-label={t('operations.map_heading')}
                                    >
                                        <path
                                            d="M0 60 H420 M0 120 H420 M0 180 H420 M84 0 V240 M168 0 V240 M252 0 V240 M336 0 V240"
                                            stroke="currentColor"
                                            strokeOpacity="0.14"
                                            strokeWidth="1"
                                        />
                                        <path
                                            d="M150 42 L265 36 L310 78 L292 142 L245 186 L168 175 L138 118 Z"
                                            fill="currentColor"
                                            fillOpacity="0.1"
                                            stroke="currentColor"
                                            strokeOpacity="0.45"
                                            strokeWidth="1.5"
                                        />
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

                                <div className="mt-5 divide-y divide-white/10 text-xs">
                                    {operations.map((op, idx) => (
                                        <div
                                            key={op.id}
                                            className="flex items-center justify-between gap-3 py-2.5"
                                        >
                                            <span className="inline-flex items-center gap-2 font-medium text-white">
                                                <span className="font-heading font-bold text-accent-on-dark">
                                                    {`0${idx + 1}`}
                                                </span>
                                                <span>{op.location_label}</span>
                                            </span>
                                            {op.latitude != null && op.longitude != null ? (
                                                <span className="font-mono text-[11px] text-slate-300">
                                                    {`${Number(op.latitude).toFixed(4)}°, ${Number(op.longitude).toFixed(4)}°`}
                                                </span>
                                            ) : null}
                                        </div>
                                    ))}
                                </div>
                            </motion.div>
                        ) : null}

                        {/* Right 7 Columns: Rounded White Operational Cards with Top Photos */}
                        <div
                            className={`grid grid-cols-1 gap-5 sm:grid-cols-2 ${
                                data.show_map !== false ? 'lg:col-span-7' : 'lg:col-span-12 lg:grid-cols-4'
                            }`}
                        >
                            {operations.map((op, idx) => {
                                const opImage =
                                    op.image_url && !op.image_url.includes('placeholder-image.svg')
                                        ? op.image_url
                                        : OPERATION_FALLBACK_IMAGES[
                                              idx % OPERATION_FALLBACK_IMAGES.length
                                          ];

                                return (
                                    <motion.article
                                        key={op.id}
                                        data-gsap-item
                                        whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                                        transition={{
                                            type: 'spring',
                                            stiffness: 300,
                                            damping: 22,
                                        }}
                                        className="group flex flex-col overflow-hidden rounded-[var(--radius-card)] border border-line bg-bg shadow-sm transition-shadow hover:border-accent/70 hover:shadow-md"
                                    >
                                        <div className="relative h-36 overflow-hidden bg-surface-dark">
                                            <img
                                                src={opImage}
                                                alt={op.name}
                                                className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                loading="lazy"
                                            />
                                        </div>

                                        <div className="flex flex-1 flex-col p-5 space-y-2.5">
                                            <div className="flex flex-wrap items-center justify-between gap-2">
                                                <span className="font-heading text-[11px] font-bold uppercase tracking-[0.12em] text-accent-strong">
                                                    {`${String(idx + 1).padStart(2, '0')} — ${op.type_label}`}
                                                </span>
                                                <span className="text-[11px] font-medium text-muted">
                                                    {op.location_label}
                                                </span>
                                            </div>

                                            <Heading
                                                level={isFirstBlock ? 2 : 3}
                                                size="h3"
                                                className="text-base font-bold text-ink sm:text-lg"
                                            >
                                                {op.name}
                                            </Heading>

                                            <p className="text-xs leading-6 text-muted sm:text-sm">
                                                {op.description}
                                            </p>
                                        </div>
                                    </motion.article>
                                );
                            })}
                        </div>
                    </div>
                </Container>
            </Section>

            {/* Dark Navy Horizontal Process Flow Band (Full Operations Page) */}
            {!isHomePage ? (
                <Section
                    tone="dark"
                    className="relative overflow-hidden border-b border-white/10"
                >
                    <Container className="relative z-10">
                        <div className="max-w-2xl space-y-2.5">
                            <p className="text-xs font-bold uppercase tracking-[0.18em] text-accent-on-dark">
                                {t('operations.flow_eyebrow')}
                            </p>
                            <Heading
                                level={2}
                                size="h2"
                                className="font-bold tracking-tight text-white"
                            >
                                {t('operations.flow_heading')}
                            </Heading>
                        </div>

                        <ol
                            ref={flowRef}
                            className="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                        >
                            {flowSteps.map((step, idx) => {
                                const StepIcon = FLOW_ICONS[idx % FLOW_ICONS.length];

                                return (
                                    <li
                                        key={step.code}
                                        data-gsap-item
                                        className="relative flex flex-col items-center rounded-[var(--radius-card)] border border-white/10 bg-white/[0.03] p-6 text-center backdrop-blur-xs transition-colors hover:border-accent/50 hover:bg-white/[0.06]"
                                    >
                                        <div className="flex h-16 w-16 items-center justify-center rounded-full border-2 border-accent bg-surface-dark text-accent-on-dark shadow-lg">
                                            <StepIcon className="h-7 w-7" aria-hidden="true" />
                                        </div>

                                        <span className="mt-4 font-heading text-[11px] font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                            {step.code}
                                        </span>

                                        <Heading
                                            level={3}
                                            size="h3"
                                            className="mt-1 text-base font-bold text-white"
                                        >
                                            {step.title}
                                        </Heading>

                                        <p className="mt-2 text-xs leading-5 text-slate-300 sm:text-sm">
                                            {step.description}
                                        </p>
                                    </li>
                                );
                            })}
                        </ol>
                    </Container>
                </Section>
            ) : null}
        </>
    );
}
