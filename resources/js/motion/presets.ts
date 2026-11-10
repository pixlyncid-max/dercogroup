import type { Transition, Variants } from 'motion/react';

/**
 * Single source of truth for motion presets (DESIGN.md Section 10).
 * Only transform and opacity properties are animated.
 */
export const EASE_OUT: [number, number, number, number] = [0.22, 1, 0.36, 1];
export const EASE_IN_OUT: [number, number, number, number] = [0.65, 0, 0.35, 1];

export const DURATION = {
    fast: 0.15,
    theme: 0.25,
    base: 0.3,
    slow: 0.6,
} as const;

export const VIEWPORT_ONCE = {
    once: true,
    amount: 0.2,
} as const;

const slowTransition: Transition = {
    duration: DURATION.slow,
    ease: EASE_OUT,
};

const baseTransition: Transition = {
    duration: DURATION.base,
    ease: EASE_OUT,
};

const reducedTransition: Transition = {
    duration: DURATION.fast,
    ease: EASE_OUT,
};

export const fadeUp: Variants = {
    hidden: { opacity: 0, y: 24 },
    visible: {
        opacity: 1,
        y: 0,
        transition: slowTransition,
    },
};

export const fadeIn: Variants = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: baseTransition,
    },
};

export const stagger: Variants = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: {
            staggerChildren: 0.08,
        },
    },
};

export const slideInLeft: Variants = {
    hidden: { opacity: 0, x: -24 },
    visible: {
        opacity: 1,
        x: 0,
        transition: slowTransition,
    },
};

export const slideInRight: Variants = {
    hidden: { opacity: 0, x: 24 },
    visible: {
        opacity: 1,
        x: 0,
        transition: slowTransition,
    },
};

export const scaleX: Variants = {
    hidden: { opacity: 0, scaleX: 0, originX: 0 },
    visible: {
        opacity: 1,
        scaleX: 1,
        originX: 0,
        transition: slowTransition,
    },
};

export const themeFade: Variants = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: {
            duration: DURATION.theme,
            ease: EASE_OUT,
        },
    },
};

export const reducedMotionFade: Variants = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: reducedTransition,
    },
};

export const presets = {
    fadeUp,
    fadeIn,
    stagger,
    slideInLeft,
    slideInRight,
    scaleX,
    themeFade,
} as const;

export type PresetName = keyof typeof presets;

/**
 * Returns the requested preset or a simple 150ms fade when prefers-reduced-motion is active.
 */
export function resolvePreset(name: PresetName, prefersReducedMotion: boolean): Variants {
    if (prefersReducedMotion) {
        return reducedMotionFade;
    }

    return presets[name];
}
