import { Award, Handshake, ShieldCheck, Users } from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import type { ValuesGridBlockData } from '@/core';
import { Container, Heading, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';
import { useGsapStagger } from '../useKorinBerkatMotion';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

const VALUE_ICONS = [Handshake, ShieldCheck, Award, Users];

/**
 * PT Korin Berkat Energy Core Values Grid.
 * Light-slate section with warm gold square icon badges, numbered titles (01..04),
 * GSAP staggered scroll reveals, and Motion spring hover lift.
 */
export function ValuesGridBlock({ data }: ValuesGridBlockProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const gridRef = useRef<HTMLDivElement | null>(null);
    useGsapStagger(gridRef, { y: 24, stagger: 0.08, duration: 0.65 });

    const items = data.items ?? [];

    return (
        <Section tone="surface" className="border-b border-line">
            <Container>
                <div className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-start">
                    {/* Left 4 Cols: Editorial Header */}
                    <div className="lg:col-span-4">
                        <EditorialHeader
                            eyebrow={data.eyebrow}
                            title={data.title}
                            description={data.description}
                        />
                    </div>

                    {/* Right 8 Cols: 2x2 Rounded White Value Cards with Gold Icon Badges */}
                    <div
                        ref={gridRef}
                        className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:col-span-8"
                    >
                        {items.map((item, idx) => {
                            const IconComponent = VALUE_ICONS[idx % VALUE_ICONS.length];

                            return (
                                <motion.div
                                    key={idx}
                                    data-gsap-item
                                    whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                                    transition={{ type: 'spring', stiffness: 300, damping: 24 }}
                                    className="group flex items-start gap-4 rounded-[var(--radius-card)] border border-line bg-bg p-6 shadow-sm transition-shadow hover:border-accent/70 hover:shadow-md"
                                >
                                    <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-accent text-on-accent shadow-sm">
                                        <IconComponent className="h-6 w-6" aria-hidden="true" />
                                    </div>

                                    <div className="min-w-0 space-y-1.5">
                                        <Heading
                                            level={3}
                                            size="h3"
                                            className="font-heading text-base font-bold tracking-tight text-ink sm:text-lg"
                                        >
                                            <span className="mr-1.5 text-accent-strong">
                                                {`${String(idx + 1).padStart(2, '0')}.`}
                                            </span>
                                            {item.title}
                                        </Heading>

                                        <p className="text-sm leading-relaxed text-muted">
                                            {item.description}
                                        </p>
                                    </div>
                                </motion.div>
                            );
                        })}
                    </div>
                </div>
            </Container>
        </Section>
    );
}
