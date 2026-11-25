import { motion, useReducedMotion } from 'motion/react';
import type { ValuesGridBlockData } from '@/core';
import { resolvePreset, VIEWPORT_ONCE } from '@/motion/presets';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';
import { HoldingIcon } from '../IconResolver';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

export function ValuesGridBlock({ data }: ValuesGridBlockProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const staggerVariants = resolvePreset('stagger', prefersReducedMotion);
    const itemVariants = resolvePreset('fadeUp', prefersReducedMotion);

    return (
        <Section tone="dark" className="border-t border-ink-on-dark/10">
            <Container>
                <Reveal preset="fadeUp" className="max-w-[640px] space-y-4">
                    {data.eyebrow ? <EyebrowLine tone="dark">{data.eyebrow}</EyebrowLine> : null}
                    <Heading level={2} size="h2" className="text-ink-on-dark">
                        {data.title}
                    </Heading>
                    {data.description ? (
                        <p className="text-base leading-7 text-muted-on-dark">{data.description}</p>
                    ) : null}
                </Reveal>

                <motion.div
                    initial={prefersReducedMotion ? 'visible' : 'hidden'}
                    whileInView="visible"
                    viewport={VIEWPORT_ONCE}
                    variants={staggerVariants}
                    className="mt-12 grid grid-cols-1 gap-6 md:grid-cols-3"
                >
                    {(data.items ?? []).map((item, idx) => (
                        <motion.article
                            key={idx}
                            variants={itemVariants}
                            whileHover={prefersReducedMotion ? undefined : { y: -5 }}
                            transition={{ type: 'spring', stiffness: 300, damping: 24 }}
                            className="flex flex-col justify-between rounded-[var(--radius-card)] border border-ink-on-dark/12 bg-surface-deeper/70 p-6 transition-colors hover:border-accent/50 md:p-8"
                        >
                            <div className="space-y-4">
                                <div className="flex items-center justify-between">
                                    <span className="inline-flex h-10 w-10 items-center justify-center rounded-[var(--radius-button)] border border-accent/40 text-accent-on-dark">
                                        <HoldingIcon name={item.icon_name} className="h-5 w-5" />
                                    </span>
                                    <span className="text-xs font-bold text-accent-on-dark">
                                        {`0${idx + 1}`}
                                    </span>
                                </div>
                                <Heading level={3} size="h3" className="text-ink-on-dark">
                                    {item.title}
                                </Heading>
                                <p className="text-sm leading-6 text-muted-on-dark">
                                    {item.description}
                                </p>
                            </div>
                        </motion.article>
                    ))}
                </motion.div>
            </Container>
        </Section>
    );
}
