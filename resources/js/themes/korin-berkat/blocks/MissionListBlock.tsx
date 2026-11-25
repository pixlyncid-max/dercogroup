import { Eye, Target } from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import { useLocale, type MissionListBlockData } from '@/core';
import { Container, Heading, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';
import { GoldRule } from '../GoldRule';
import { useGsapStagger } from '../useKorinBerkatMotion';

export interface MissionListBlockProps {
    data: MissionListBlockData;
}

/**
 * PT Korin Berkat Energy Vision & Mission Split Cards.
 * Left: Deep Navy "Our Vision" card with gold Eye icon badge & governance note.
 * Right: Clean numbered "Our Mission" cards with gold Target icon badges & Motion hover.
 */
export function MissionListBlock({ data }: MissionListBlockProps) {
    const { locale } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const gridRef = useRef<HTMLDivElement | null>(null);
    useGsapStagger(gridRef, { y: 24, stagger: 0.09, duration: 0.65 });

    const items = data.items ?? [];

    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                <EditorialHeader eyebrow={data.eyebrow} title={data.title} />

                <div
                    ref={gridRef}
                    className="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-stretch"
                >
                    {/* Left 5 Cols: Executive Dark Navy Vision Card */}
                    <motion.div
                        data-gsap-item
                        whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                        transition={{ type: 'spring', stiffness: 280, damping: 24 }}
                        className="relative flex flex-col justify-between space-y-6 overflow-hidden rounded-[var(--radius-card)] border-t-4 border-accent bg-surface-dark p-7 text-ink-on-dark shadow-xl sm:p-8 lg:col-span-5"
                    >
                        <div
                            aria-hidden="true"
                            className="kbe-card-corner-gold pointer-events-none absolute right-0 bottom-0 h-12 w-12 opacity-90"
                        />

                        {data.vision ? (
                            <div className="space-y-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-xl border border-accent/40 bg-accent/15 text-accent-on-dark">
                                    <Eye className="h-6 w-6" aria-hidden="true" />
                                </div>
                                <p className="text-xs font-bold uppercase tracking-[0.16em] text-accent-on-dark">
                                    {locale === 'en' ? 'OUR VISION' : 'VISI PERUSAHAAN'}
                                </p>
                                <GoldRule tone="dark" className="w-12" />
                                <p className="font-heading text-xl font-semibold leading-8 text-white">
                                    {data.vision}
                                </p>
                            </div>
                        ) : null}

                        {data.body ? (
                            <div className="space-y-2 border-t border-white/15 pt-5">
                                <p className="text-[11px] font-bold uppercase tracking-[0.15em] text-accent-on-dark">
                                    {locale === 'en'
                                        ? 'GROUP GOVERNANCE & OWNERSHIP'
                                        : 'TATA KELOLA & KEPEMILIKAN GRUP'}
                                </p>
                                <p className="text-sm leading-6 text-slate-300">{data.body}</p>
                            </div>
                        ) : null}
                    </motion.div>

                    {/* Right 7 Cols: Numbered Mission Cards */}
                    <div className="grid grid-cols-1 gap-4 lg:col-span-7">
                        {items.map((item, idx) => (
                            <motion.div
                                key={idx}
                                data-gsap-item
                                whileHover={prefersReducedMotion ? undefined : { x: 4 }}
                                transition={{ type: 'spring', stiffness: 320, damping: 24 }}
                                className="flex items-start gap-4 rounded-[var(--radius-card)] border border-line bg-surface p-6 transition-colors hover:border-accent/60 hover:bg-bg hover:shadow-md"
                            >
                                <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-accent/15 text-accent-strong">
                                    <Target className="h-5 w-5" aria-hidden="true" />
                                </div>

                                <div className="min-w-0 space-y-1.5">
                                    <div className="flex items-center gap-2">
                                        <span className="font-heading text-xs font-bold tracking-[0.1em] text-accent-strong">
                                            {`${String(idx + 1).padStart(2, '0')}.`}
                                        </span>
                                        <Heading level={3} size="h3" className="text-lg font-bold">
                                            {item.title}
                                        </Heading>
                                    </div>
                                    {item.description ? (
                                        <p className="text-sm leading-6 text-muted">
                                            {item.description}
                                        </p>
                                    ) : null}
                                </div>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </Container>
        </Section>
    );
}
