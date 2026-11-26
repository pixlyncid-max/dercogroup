import {
    BarChart3,
    Gem,
    GraduationCap,
    Handshake,
    Network,
    TrendingUp,
    type LucideIcon,
} from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import type { ValuesGridBlockData } from '@/core';
import { useGsapStagger } from '@/motion/gsapLenis';
import { Container, Heading, Section } from '@/ui';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

const STRATEGY_ICONS_BY_NAME: Record<string, LucideIcon> = {
    gem: Gem,
    handshake: Handshake,
    network: Network,
    'trending-up': TrendingUp,
    'graduation-cap': GraduationCap,
    'bar-chart-3': BarChart3,
};

const STRATEGY_ICONS_FALLBACK: ReadonlyArray<LucideIcon> = [
    Gem,
    Handshake,
    Network,
    TrendingUp,
    GraduationCap,
    BarChart3,
];

/**
 * PT Beramal Sukses Sejahtera — Business Strategy Grid (Compro layout).
 * 2-column horizontal cards with deep-navy circular icon badges, numbered 01..06,
 * soft ice-blue card surfaces, GSAP scroll stagger, and Motion hover.
 */
export function ValuesGridBlock({ data }: ValuesGridBlockProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const gridRef = useRef<HTMLDivElement | null>(null);
    const items = data.items ?? [];

    useGsapStagger(gridRef, {
        y: 22,
        stagger: 0.08,
        duration: 0.65,
    });

    return (
        <Section tone="default">
            <Container>
                {/* Centered Short Navy Accent Bar + Cerulean Strategy Title */}
                <div className="mx-auto max-w-[680px] text-center space-y-3">
                    <span
                        aria-hidden="true"
                        className="mx-auto block h-1 w-9 rounded-full bg-surface-dark"
                    />
                    <Heading level={2} size="h2" className="text-accent-strong">
                        {data.title}
                    </Heading>
                    {data.description ? (
                        <p className="mx-auto max-w-[58ch] text-base leading-7 text-muted">
                            {data.description}
                        </p>
                    ) : null}
                </div>

                {/* 2-Column Horizontal Business Strategy Cards */}
                <div
                    ref={gridRef}
                    className="mt-10 grid grid-cols-1 gap-5 md:grid-cols-2"
                >
                    {items.map((item, idx) => {
                        const Icon =
                            (item.icon_name
                                ? STRATEGY_ICONS_BY_NAME[item.icon_name]
                                : undefined) ??
                            STRATEGY_ICONS_FALLBACK[idx % STRATEGY_ICONS_FALLBACK.length];

                        return (
                            <motion.article
                                key={`${item.title}-${idx}`}
                                data-gsap-item
                                whileHover={prefersReducedMotion ? undefined : { y: -3 }}
                                transition={{ type: 'spring', stiffness: 300, damping: 24 }}
                                className="flex items-start gap-5 rounded-2xl border border-accent/25 bg-[#c5e3f3]/75 p-5 transition-colors hover:border-accent/45 hover:bg-[#c5e3f3] sm:p-6"
                            >
                                <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-surface-dark text-white shadow-xs">
                                    <Icon className="h-6 w-6" aria-hidden="true" />
                                </div>

                                <div className="min-w-0 space-y-1.5">
                                    <span className="block font-heading text-xs font-bold tabular-nums tracking-[0.06em] text-[#C86D3B]">
                                        {String(idx + 1).padStart(2, '0')}
                                    </span>
                                    <Heading
                                        level={3}
                                        size="h3"
                                        className="font-heading text-lg font-bold leading-snug text-ink sm:text-xl"
                                    >
                                        {item.title}
                                    </Heading>
                                    <p className="text-sm leading-6 text-ink/80">
                                        {item.description}
                                    </p>
                                </div>
                            </motion.article>
                        );
                    })}
                </div>
            </Container>
        </Section>
    );
}
