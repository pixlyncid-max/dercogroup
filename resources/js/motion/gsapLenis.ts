import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import { useEffect, type RefObject } from 'react';

let gsapRegistered = false;

export function ensureGsapRegistered(): void {
    if (typeof window === 'undefined' || gsapRegistered) {
        return;
    }

    gsap.registerPlugin(ScrollTrigger);
    gsapRegistered = true;
}

export function prefersReducedMotion(): boolean {
    if (typeof window === 'undefined') {
        return true;
    }

    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/**
 * Shared Lenis smooth scrolling synchronized with GSAP ticker & ScrollTrigger,
 * plus an optional GSAP-scrubbed top scroll-progress bar.
 * Safe for SSR and automatically bypassed when `prefers-reduced-motion: reduce` is active.
 */
export function useSmoothScroll(
    progressBarRef?: RefObject<HTMLDivElement | null>,
    refreshKey?: string | number,
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
                duration: 1.15,
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

        const rafId = window.requestAnimationFrame(() => {
            ScrollTrigger.refresh();
        });

        return () => {
            window.cancelAnimationFrame(rafId);
            ctx.revert();
            if (tickerCallback) {
                gsap.ticker.remove(tickerCallback);
            }
            if (lenis) {
                lenis.destroy();
            }
        };
    }, [progressBarRef, refreshKey]);
}

/**
 * Shared page-level GSAP ScrollTrigger choreography applied automatically across
 * ALL pages of Holding (PT Derco Bara Energy) and all Subsidiaries:
 * - Subtle parallax on first-fold full-bleed hero/banner backdrop photos
 * - Smooth vertical glide + fade reveal on below-the-fold sections
 * - Automatic staggered card reveal for article grids without explicit `[data-gsap-item]`
 * - Automatic ScrollTrigger refresh on Inertia route transitions
 */
export function usePageGsapEffects(
    mainRef: RefObject<HTMLElement | null>,
    pageKey?: string,
): void {
    useEffect(() => {
        if (typeof window === 'undefined' || !mainRef.current || prefersReducedMotion()) {
            return;
        }

        ensureGsapRegistered();

        const container = mainRef.current;
        const ctx = gsap.context(() => {
            // 1. Subtle parallax on full-bleed first-section hero backdrops (if not manually handled)
            const firstSection = container.querySelector(':scope > section');
            if (firstSection && !firstSection.querySelector('[data-gsap-item]')) {
                const heroBackdrop = firstSection.querySelector(':scope > div.absolute');
                if (heroBackdrop) {
                    gsap.fromTo(
                        heroBackdrop,
                        { yPercent: -4 },
                        {
                            yPercent: 6,
                            ease: 'none',
                            scrollTrigger: {
                                trigger: firstSection,
                                start: 'top top',
                                end: 'bottom top',
                                scrub: true,
                            },
                        },
                    );
                }
            }

            // 2. Smooth scroll entrance for sections below the fold
            const sections = Array.from(container.querySelectorAll(':scope > section'));
            sections.slice(1).forEach((section) => {
                if (section.hasAttribute('data-gsap-skip')) {
                    return;
                }

                gsap.fromTo(
                    section,
                    { y: 26, opacity: 0.01 },
                    {
                        y: 0,
                        opacity: 1,
                        duration: 0.7,
                        ease: 'power3.out',
                        clearProps: 'transform,opacity',
                        scrollTrigger: {
                            trigger: section,
                            start: 'top 88%',
                            once: true,
                        },
                    },
                );

                // 3. Staggered reveal on card grids inside each section (when not already using data-gsap-item)
                const cards = Array.from(section.querySelectorAll('article')).filter(
                    (el) => !el.hasAttribute('data-gsap-item'),
                );
                if (cards.length >= 2) {
                    gsap.fromTo(
                        cards,
                        { y: 22, scale: 0.985 },
                        {
                            y: 0,
                            scale: 1,
                            duration: 0.6,
                            stagger: 0.08,
                            ease: 'power3.out',
                            clearProps: 'transform',
                            scrollTrigger: {
                                trigger: cards[0],
                                start: 'top 88%',
                                once: true,
                            },
                        },
                    );
                }
            });
        }, container);

        const rafId = window.requestAnimationFrame(() => {
            ScrollTrigger.refresh();
        });

        return () => {
            window.cancelAnimationFrame(rafId);
            ctx.revert();
        };
    }, [mainRef, pageKey]);
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
