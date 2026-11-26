import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, CheckCircle2, Compass, MapPin, Navigation } from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import {
    useLocale,
    type OperationData,
    type OperationTypeValue,
    type SharedPageProps,
} from '@/core';
import { useGsapStagger } from '@/motion/gsapLenis';
import { Container, Heading, Section } from '@/ui';
import { CalmHeader } from './CalmHeader';
import { WaveDivider } from './WaveDivider';

const FALLBACK_IMAGES: Record<OperationTypeValue, string> = {
    mine: '/images/placeholders/pit-benches-1280.webp',
    stockpile: '/images/placeholders/aerial-pit-1280.webp',
    facility: '/images/placeholders/crushing-plant-1280.webp',
    port: '/images/placeholders/barge-jetty-1280.webp',
    fleet: '/images/placeholders/haul-trucks-1280.webp',
};

/**
 * Beramal Sukses Sejahtera Home Page Mining Locations Showcase ("Calm Flow").
 * Displays active nickel laterite mining sites, ETO/EFO stockpiles, XRF lab, and jetty
 * directly on Beranda using live CMS/database operations data.
 */
export function MiningLocationsSection() {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const operations: OperationData[] = props.operations ?? [];
    const cardsRef = useRef<HTMLDivElement | null>(null);

    useGsapStagger(cardsRef, {
        y: 24,
        stagger: 0.09,
        duration: 0.65,
    });

    if (operations.length === 0) {
        return null;
    }

    const operationsNavHref = props.navigation?.items?.find(
        (item) => item.key === 'operations',
    )?.href;

    const primaryMine = operations.find((op) => op.type === 'mine') ?? operations[0];
    const secondaryNodes = operations.filter((op) => op.id !== primaryMine.id);

    return (
        <>
            <WaveDivider tone="surface" />
            <Section tone="surface">
                <Container>
                    <CalmHeader
                        eyebrow={t('operations.nickel.home_locations_eyebrow')}
                        title={t('operations.nickel.home_locations_heading')}
                        description={t('operations.nickel.home_locations_description')}
                        level={2}
                        pillTone="bg"
                        action={
                            operationsNavHref ? (
                                <Link
                                    href={operationsNavHref}
                                    className="inline-flex min-h-11 items-center gap-2 rounded-full bg-accent px-5 py-2.5 text-xs font-bold text-on-accent shadow-sm transition-colors hover:bg-accent-strong"
                                >
                                    <span>{t('operations.nickel.home_locations_cta')}</span>
                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                </Link>
                            ) : null
                        }
                    />

                    {/* Primary Mining Site Highlight Card + Supporting Corridor Nodes */}
                    <div ref={cardsRef} className="mt-10 grid grid-cols-1 gap-6 lg:grid-cols-12">
                        {/* Featured Primary Nickel Mining Pit Card */}
                        <motion.article
                            data-gsap-item
                            whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                            transition={{ type: 'spring', stiffness: 280, damping: 24 }}
                            className="group relative flex flex-col justify-between overflow-hidden rounded-[var(--radius-card)] bg-surface-dark text-ink-on-dark shadow-md lg:col-span-6"
                        >
                            <div className="relative aspect-[16/10] w-full overflow-hidden">
                                <img
                                    src={
                                        primaryMine.image_url ||
                                        FALLBACK_IMAGES[primaryMine.type] ||
                                        FALLBACK_IMAGES.mine
                                    }
                                    alt={primaryMine.image_alt || primaryMine.name}
                                    loading="lazy"
                                    decoding="async"
                                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                />
                                <div
                                    aria-hidden="true"
                                    className="absolute inset-0 bg-gradient-to-t from-surface-dark via-surface-dark/40 to-transparent"
                                />

                                <div className="absolute left-5 top-5 flex flex-wrap items-center gap-2">
                                    <span className="inline-flex items-center gap-1.5 rounded-full bg-accent px-3.5 py-1 text-xs font-bold text-on-accent shadow-sm">
                                        <Compass className="h-3.5 w-3.5" aria-hidden="true" />
                                        <span>{primaryMine.type_label}</span>
                                    </span>
                                    <span className="inline-flex items-center gap-1.5 rounded-full bg-surface-deeper/95 px-3 py-1 text-[11px] font-semibold text-accent-on-dark">
                                        <span>
                                            {t('operations.nickel.home_locations_region_badge')}
                                        </span>
                                    </span>
                                </div>
                            </div>

                            <div className="flex flex-1 flex-col justify-between space-y-5 p-6 sm:p-7">
                                <div className="space-y-3">
                                    <Heading level={3} size="h3" className="text-ink-on-dark">
                                        {primaryMine.name}
                                    </Heading>
                                    <p className="text-sm leading-6 text-muted-on-dark">
                                        {primaryMine.description}
                                    </p>
                                </div>

                                <div className="flex flex-wrap items-center justify-between gap-3 border-t border-ink-on-dark/15 pt-4 text-xs">
                                    <div className="flex items-center gap-2 font-semibold text-ink-on-dark">
                                        <MapPin
                                            className="h-4 w-4 shrink-0 text-accent-on-dark"
                                            aria-hidden="true"
                                        />
                                        <span>{primaryMine.location_label}</span>
                                    </div>

                                    {primaryMine.latitude != null &&
                                        primaryMine.longitude != null ? (
                                        <span className="inline-flex items-center gap-1.5 rounded-full bg-surface-deeper px-3 py-1 font-mono text-[11px] text-accent-on-dark">
                                            <Navigation className="h-3 w-3" aria-hidden="true" />
                                            <span>
                                                {`${Number(primaryMine.latitude).toFixed(4)}°, ${Number(primaryMine.longitude).toFixed(4)}°`}
                                            </span>
                                        </span>
                                    ) : null}
                                </div>
                            </div>
                        </motion.article>

                        {/* Supporting Nickel Corridor Facility Locations */}
                        <div className="flex flex-col justify-between gap-4 lg:col-span-6">
                            {secondaryNodes.map((op, idx) => (
                                <motion.article
                                    key={op.id}
                                    data-gsap-item
                                    whileHover={prefersReducedMotion ? undefined : { x: 4 }}
                                    transition={{ type: 'spring', stiffness: 320, damping: 24 }}
                                    className="flex flex-col justify-between gap-3 rounded-[var(--radius-card)] border border-line bg-bg p-5 shadow-sm transition-shadow hover:shadow-md sm:p-6"
                                >
                                    <div className="flex flex-wrap items-center justify-between gap-2">
                                        <span className="inline-flex items-center gap-2 rounded-full bg-surface px-3 py-1 text-xs font-bold text-ink">
                                            <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-accent font-heading text-[11px] text-on-accent">
                                                {String(idx + 2).padStart(2, '0')}
                                            </span>
                                            <span>{op.type_label}</span>
                                        </span>

                                        {op.latitude != null && op.longitude != null ? (
                                            <span className="inline-flex items-center gap-1 font-mono text-[11px] text-muted">
                                                <Navigation
                                                    className="h-3 w-3 text-accent-strong"
                                                    aria-hidden="true"
                                                />
                                                <span>
                                                    {`${Number(op.latitude).toFixed(4)}°, ${Number(op.longitude).toFixed(4)}°`}
                                                </span>
                                            </span>
                                        ) : null}
                                    </div>

                                    <div className="space-y-1.5">
                                        <Heading level={3} size="h3" className="text-lg font-bold">
                                            {op.name}
                                        </Heading>
                                        <p className="text-sm leading-6 text-muted">
                                            {op.description}
                                        </p>
                                    </div>

                                    <div className="flex flex-wrap items-center justify-between gap-2 border-t border-line pt-3 text-xs">
                                        <span className="inline-flex items-center gap-1.5 font-semibold text-ink">
                                            <MapPin
                                                className="h-3.5 w-3.5 shrink-0 text-accent-strong"
                                                aria-hidden="true"
                                            />
                                            <span>{op.location_label}</span>
                                        </span>

                                        <span className="inline-flex items-center gap-1 text-[11px] font-semibold text-accent-strong">
                                            <CheckCircle2
                                                className="h-3.5 w-3.5"
                                                aria-hidden="true"
                                            />
                                            <span>{t('operations.nickel.active_status')}</span>
                                        </span>
                                    </div>
                                </motion.article>
                            ))}
                        </div>
                    </div>
                </Container>
            </Section>
            <WaveDivider tone="surface" flip />
        </>
    );
}

