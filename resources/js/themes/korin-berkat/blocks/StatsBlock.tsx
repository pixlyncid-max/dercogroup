import { usePage } from '@inertiajs/react';
import { Award, Handshake, ShieldCheck, Ship } from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import { useLocale, type SharedPageProps, type StatsBlockData } from '@/core';
import { Container, Counter, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';
import { useGsapStagger } from '../useKorinBerkatMotion';

export interface StatsBlockProps {
    data: StatsBlockData;
}

const STAT_ICONS = [Handshake, ShieldCheck, Ship, Award];

/**
 * PT Korin Berkat Energy Highlight Stats Bar.
 * Clean horizontal white/surface strip with gold outline icons, hairline dividers,
 * GSAP staggered entrance, and Motion micro-hover lift. Preserves sr-only heading on Beranda.
 */
export function StatsBlock({ data }: StatsBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const gridRef = useRef<HTMLDivElement | null>(null);

    useGsapStagger(gridRef, { y: 20, stagger: 0.08, duration: 0.6 });

    const stats = props.stats ?? props.company?.stats ?? [];
    const isHomePage = props.page?.key === 'home';

    if (stats.length === 0) {
        return null;
    }

    return (
        <Section
            tone="surface"
            className={`border-b border-line ${isHomePage ? '!py-10 md:!py-12' : ''}`.trim()}
        >
            <Container>
                {isHomePage ? (
                    <h2 className="sr-only">{data.title}</h2>
                ) : (
                    <EditorialHeader
                        indexLabel="02"
                        eyebrow={data.eyebrow}
                        title={data.title}
                        description={data.description}
                    />
                )}

                <div
                    ref={gridRef}
                    className={`${
                        isHomePage ? '' : 'mt-10 '
                    }grid grid-cols-1 divide-y divide-line overflow-hidden rounded-[var(--radius-card)] border border-line bg-bg shadow-sm sm:grid-cols-2 sm:divide-y-0 lg:grid-cols-4 lg:divide-x`}
                >
                    {stats.map((stat, idx) => {
                        const IconComponent = STAT_ICONS[idx % STAT_ICONS.length];

                        return (
                            <motion.div
                                key={stat.id}
                                data-gsap-item
                                whileHover={prefersReducedMotion ? undefined : { y: -3 }}
                                transition={{ type: 'spring', stiffness: 320, damping: 24 }}
                                className="group flex items-start gap-4 p-6 transition-colors hover:bg-amber-50/30 lg:p-7"
                            >
                                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-amber-200/80 bg-amber-50 text-accent-strong transition-colors group-hover:bg-accent group-hover:text-on-accent">
                                    <IconComponent className="h-5 w-5" aria-hidden="true" />
                                </div>

                                <div className="min-w-0 space-y-1">
                                    <div className="flex items-baseline gap-1.5">
                                        <Counter
                                            value={stat.value}
                                            prefix={stat.prefix}
                                            suffix={stat.suffix}
                                            locale={locale}
                                            className="font-heading text-2xl font-bold tracking-tight text-ink sm:text-3xl"
                                        />
                                        {stat.unit ? (
                                            <span className="text-[11px] font-bold uppercase tracking-[0.08em] text-accent-strong">
                                                {stat.unit}
                                            </span>
                                        ) : null}
                                    </div>
                                    <p className="text-xs font-semibold leading-5 text-muted">
                                        {stat.label}
                                    </p>
                                </div>
                            </motion.div>
                        );
                    })}
                </div>
            </Container>
        </Section>
    );
}
