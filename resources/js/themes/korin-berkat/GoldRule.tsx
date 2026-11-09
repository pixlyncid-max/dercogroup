import { motion, useReducedMotion } from 'motion/react';
import { resolvePreset, VIEWPORT_ONCE } from '@/motion/presets';

export interface GoldRuleProps {
    className?: string;
    thick?: boolean;
    tone?: 'light' | 'dark';
}

/**
 * Animated horizontal gold rule for the "Editorial Index" theme (DESIGN.md 5.5).
 */
export function GoldRule({ className = '', thick = false, tone = 'light' }: GoldRuleProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const variants = resolvePreset('scaleX', prefersReducedMotion);

    const heightClass = thick ? 'h-0.5' : 'h-px';
    const colorClass = tone === 'dark' ? 'bg-accent-on-dark' : 'bg-accent-strong';

    return (
        <motion.div
            aria-hidden="true"
            initial={prefersReducedMotion ? 'visible' : 'hidden'}
            whileInView="visible"
            viewport={VIEWPORT_ONCE}
            variants={variants}
            className={` origin-left ${heightClass} ${colorClass} ${className}`.trim()}
        />
    );
}
