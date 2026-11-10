import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, MapPin } from 'lucide-react';
import { useRef } from 'react';
import {
    useLocale,
    type OperationData,
    type OperationListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Image, Reveal, Section } from '@/ui';
import { FieldSectionHeader } from '../FieldSectionHeader';

export interface OperationListBlockProps {
    data: OperationListBlockData;
    isFirstBlock?: boolean;
}

const PROCESS_IMAGES = [
    '/images/placeholders/excavator-loading-1280.webp',
    '/images/placeholders/haul-trucks-1280.webp',
    '/images/placeholders/coal-stockpile-1280.webp',
    '/images/placeholders/barge-jetty-1280.webp',
] as const;

/**
 * Korin Bara Operation List ("Field Rail", DESIGN.md Section 6.4).
 * Features a horizontal scroll-snap process strip (Mine -> Hauling -> Stockpile -> Barge),
 * where each stage is a documentary photo card, followed below by the structured location directory.
 */
export function OperationListBlock({ data, isFirstBlock = false }: OperationListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const scrollContainerRef = useRef<HTMLDivElement | null>(null);
    const operations: OperationData[] = props.operations ?? [];

    const isHomePage = props.page?.key === 'home';
    const operationsNavHref = props.navigation?.items?.find(
        (item) => item.key === 'operations',
    )?.href;

    const processStages = [
        {
            code: t('operations.flow_steps.pit.code'),
            title: t('operations.flow_steps.pit.title'),
            description: t('operations.flow_steps.pit.description'),
            image: PROCESS_IMAGES[0],
        },
        {
            code: t('operations.flow_steps.crushing.code'),
            title: t('operations.flow_steps.crushing.title'),
            description: t('operations.flow_steps.crushing.description'),
            image: PROCESS_IMAGES[1],
        },
        {
            code: t('operations.flow_steps.jetty.code'),
            title: t('operations.flow_steps.jetty.title'),
            description: t('operations.flow_steps.jetty.description'),
            image: PROCESS_IMAGES[2],
        },
        {
            code: t('operations.flow_steps.vessel.code'),
            title: t('operations.flow_steps.vessel.title'),
            description: t('operations.flow_steps.vessel.description'),
            image: PROCESS_IMAGES[3],
        },
    ];

    const scrollByStep = (direction: 'prev' | 'next') => {
        const el = scrollContainerRef.current;
        if (!el) {
            return;
        }
        const delta = direction === 'next' ? 380 : -380;
        el.scrollBy({ left: delta, behavior: 'smooth' });
    };

    return (
        <Section tone="surface" className="border-b-2 border-line">
            <Container className="space-y-14">
                <FieldSectionHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                    level={isFirstBlock ? 1 : 2}
                    action={
                        isHomePage && operationsNavHref ? (
                            <Link
                                href={operationsNavHref}
                                className="inline-flex min-h-11 items-center gap-2 border-2 border-ink bg-bg px-4 py-2 font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink transition-colors hover:bg-surface-dark hover:text-ink-on-dark"
                            >
                                <span>{t('see_details')}</span>
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        ) : (
                            <div className="flex items-center gap-2">
                                <button
                                    type="button"
                                    onClick={() => scrollByStep('prev')}
                                    aria-label={t('operations.scroll_prev')}
                                    className="inline-flex min-h-11 min-w-11 items-center justify-center border-2 border-ink bg-bg text-ink hover:bg-accent"
                                >
                                    <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                                </button>
                                <button
                                    type="button"
                                    onClick={() => scrollByStep('next')}
                                    aria-label={t('operations.scroll_next')}
                                    className="inline-flex min-h-11 min-w-11 items-center justify-center border-2 border-ink bg-bg text-ink hover:bg-accent"
                                >
                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                </button>
                            </div>
                        )
                    }
                />

                {/* Horizontal Scroll-Snap Process Strip (Mine -> Hauling -> Stockpile -> Barge) */}
                <Reveal preset="fadeUp" className="space-y-4">
                    <div
                        ref={scrollContainerRef}
                        data-testid="korin-bara-process-strip"
                        tabIndex={0}
                        role="region"
                        aria-label={t('operations.flow_heading')}
                        className="flex snap-x snap-mandatory gap-6 overflow-x-auto pb-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                    >
                        {processStages.map((stage) => (
                            <article
                                key={stage.code}
                                className="w-[300px] sm:w-[360px] shrink-0 snap-start border-2 border-ink bg-bg flex flex-col justify-between"
                            >
                                <div>
                                    <div className="relative border-b-2 border-ink">
                                        <Image
                                            src={stage.image}
                                            alt={stage.title}
                                            ratio="16/9"
                                            wrapperClassName="border-0 rounded-none"
                                        />
                                        <span className="absolute top-0 left-0 bg-accent px-3.5 py-1.5 font-heading text-sm font-bold uppercase tracking-wider text-ink border-r-2 border-b-2 border-ink">
                                            {`STAGE ${stage.code}`}
                                        </span>
                                    </div>

                                    <div className="p-6 space-y-2.5">
                                        <Heading
                                            level={isFirstBlock ? 2 : 3}
                                            size="h3"
                                            className="text-ink"
                                        >
                                            {stage.title}
                                        </Heading>
                                        <p className="text-sm leading-6 text-muted">
                                            {stage.description}
                                        </p>
                                    </div>
                                </div>
                            </article>
                        ))}
                    </div>
                </Reveal>

                {/* Location Directory Below Horizontal Strip */}
                <Reveal preset="fadeUp" className="space-y-6 pt-4 border-t-2 border-line">
                    <div className="flex items-center gap-3">
                        <span aria-hidden="true" className="h-1.5 w-10 bg-accent" />
                        <Heading level={2} size="h3" className="text-ink">
                            {t('operations.locations_heading')}
                        </Heading>
                    </div>

                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {operations.map((op, idx) => (
                            <article
                                key={op.id}
                                className="border-2 border-line border-l-4 border-l-accent bg-bg p-6 space-y-3"
                            >
                                <div className="flex flex-wrap items-center justify-between gap-2">
                                    <span className="bg-surface px-3 py-1 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink border border-line">
                                        {`0${idx + 1} · ${op.type_label}`}
                                    </span>
                                    <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-accent-strong">
                                        <MapPin className="h-3.5 w-3.5" aria-hidden="true" />
                                        <span>{op.location_label}</span>
                                    </span>
                                </div>

                                <Heading level={3} size="h3" className="text-ink">
                                    {op.name}
                                </Heading>

                                <p className="text-sm leading-6 text-muted">{op.description}</p>

                                {op.latitude != null && op.longitude != null ? (
                                    <p className="pt-2 border-t border-line font-mono text-xs text-muted">
                                        {`${t('operations.coordinates_label')}: ${Number(op.latitude).toFixed(4)}°, ${Number(op.longitude).toFixed(4)}°`}
                                    </p>
                                ) : null}
                            </article>
                        ))}
                    </div>
                </Reveal>
            </Container>
        </Section>
    );
}
