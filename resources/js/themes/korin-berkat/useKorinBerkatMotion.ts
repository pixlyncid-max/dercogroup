import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { useEffect, type RefObject } from 'react';

let gsapRegistered = false;

function ensureGsapRegistered(): void {
    if (typeof window === 'undefined' || gsapRegistered) {
        return;
    }

    gsap.registerPlugin(ScrollTrigger);
    gsapRegistered = true;
}

function prefersReducedMotion(): boolean {
    if (typeof window === 'undefined') {
        return true;
    }

    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Smooth scrolling via Lenis synchronized with GSAP ticker & ScrollTrigger,
 * plus an optional top scroll-progress bar driven by GSAP.
 */
export function useKorinBerkatSmoothScroll(
    progressBarRef?: RefObject<HTMLDivElement | null>,
): void {
    useEffect(() => {
        if (typeof window === 'undefined') {
            return;
        }

        ensureGsapRegistered();

        const reduced = prefersReducedMotion();
        let lenis: Lenis | null = null;
        let tickerCallback: ((time: number) => void) | null = null;

        if (!reduced) {
            lenis = new Lenis({
                duration: 1.1,
                easing: (t: number) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                orientation: 'vertical',
                gestureOrientation: 'vertical',
                smoothWheel: true,
                wheelMultiplier: 1,
                touchMultiplier: 1.5,
            });

            lenis.on('scroll', ScrollTrigger.update);

            tickerCallback = (time: number) => {
                lenis?.raf(time * 1000);
            };

            gsap.ticker.add(tickerCallback);
            gsap.ticker.lagSmoothing(0);
        }

        const ctx = gsap.context(() => {
            if (progressBarRef?.current && !reduced) {
                gsap.fromTo(
                    progressBarRef.current,
                    { scaleX: 0 },
                    {
                        scaleX: 1,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: document.documentElement,
                            start: 'top top',
                            end: 'bottom bottom',
                            scrub: 0.2,
                        },
                    },
                );
            }
        });

        return () => {
            ctx.revert();
            if (tickerCallback) {
                gsap.ticker.remove(tickerCallback);
            }
            if (lenis) {
                lenis.destroy();
            }
        };
    }, [progressBarRef]);
}

/**
 * GSAP ScrollTrigger staggered reveal for child elements matching `[data-gsap-item]`
 * inside the given container ref. Automatically respects `prefers-reduced-motion`.
 */
export function useGsapStagger(
    containerRef: RefObject<HTMLElement | null>,
    options?: {
        y?: number;
        stagger?: number;
        duration?: number;
        start?: string;
    },
): void {
    const y = options?.y ?? 26;
    const stagger = options?.stagger ?? 0.09;
    const duration = options?.duration ?? 0.65;
    const start = options?.start ?? 'top 86%';

    useEffect(() => {
        if (typeof window === 'undefined' || !containerRef.current || prefersReducedMotion()) {
            return;
        }

        ensureGsapRegistered();

        const container = containerRef.current;
        const ctx = gsap.context(() => {
            const items = container.querySelectorAll('[data-gsap-item]');
            if (items.length === 0) {
                return;
            }

            gsap.fromTo(
                items,
                { y, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    duration,
                    stagger,
                    ease: 'power3.out',
                    clearProps: 'transform,opacity',
                    scrollTrigger: {
                        trigger: container,
                        start,
                        once: true,
                    },
                },
            );
        }, container);

        return () => {
            ctx.revert();
        };
    }, [containerRef, y, stagger, duration, start]);
}

/**
 * Subtle GSAP ScrollTrigger parallax on a hero/banner background image.
 */
export function useGsapParallax(
    triggerRef: RefObject<HTMLElement | null>,
    imageRef: RefObject<HTMLElement | null>,
    distancePercent = 12,
): void {
    useEffect(() => {
        if (
            typeof window === 'undefined' ||
            !triggerRef.current ||
            !imageRef.current ||
            prefersReducedMotion()
        ) {
            return;
        }

        ensureGsapRegistered();

        const trigger = triggerRef.current;
        const target = imageRef.current;

        const ctx = gsap.context(() => {
            gsap.fromTo(
                target,
                { yPercent: -distancePercent / 2, scale: 1.08 },
                {
                    yPercent: distancePercent / 2,
                    scale: 1.08,
                    ease: 'none',
                    scrollTrigger: {
                        trigger,
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: true,
                    },
                },
            );
        }, trigger);

        return () => {
            ctx.revert();
        };
    }, [triggerRef, imageRef, distancePercent]);
}
