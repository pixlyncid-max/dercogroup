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
        <Section tone="surface" className="border-t border-line">
            <Container>
                <Reveal preset="fadeUp" className="max-w-[640px] space-y-4">
                    {data.eyebrow ? <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine> : null}
                    <Heading level={2} size="h2">
                        {data.title}
                    </Heading>
                    {data.description ? (
                        <p className="text-base leading-7 text-muted">{data.description}</p>
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
                            className="flex flex-col justify-between rounded-[var(--radius-card)] border border-line bg-bg p-6 md:p-8"
                        >
                            <div className="space-y-4">
                                <div className="flex items-center justify-between">
                                    <span className="inline-flex h-10 w-10 items-center justify-center rounded-[var(--radius-button)] border border-accent/40 text-accent-strong">
                                        <HoldingIcon name={item.icon_name} className="h-5 w-5" />
                                    </span>
                                    <span className="font-mono text-xs font-semibold text-muted">
                                        {`0${idx + 1}`}
                                    </span>
                                </div>
                                <Heading level={3} size="h3">
                                    {item.title}
                                </Heading>
                                <p className="text-sm leading-6 text-muted">{item.description}</p>
                            </div>
                        </motion.article>
                    ))}
                </motion.div>
            </Container>
        </Section>
    );
}
