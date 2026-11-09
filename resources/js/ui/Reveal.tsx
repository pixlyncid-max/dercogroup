import type { ReactNode } from 'react';
import { motion, useReducedMotion } from 'motion/react';
import { resolvePreset, VIEWPORT_ONCE, type PresetName } from '@/motion/presets';

export interface RevealProps {
    preset?: PresetName;
    className?: string;
    children: ReactNode;
}

export function Reveal({ preset = 'fadeUp', className = '', children }: RevealProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const variants = resolvePreset(preset, prefersReducedMotion);

    return (
        <motion.div
            initial={prefersReducedMotion ? 'visible' : 'hidden'}
            whileInView="visible"
            viewport={VIEWPORT_ONCE}
            variants={variants}
            style={prefersReducedMotion ? { opacity: 1, transform: 'none' } : undefined}
            className={className || undefined}
        >
            {children}
        </motion.div>
    );
}
