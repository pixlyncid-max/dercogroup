import { animate } from 'animejs';
import { useReducedMotion } from 'motion/react';
import { useEffect, useRef } from 'react';
import { Heading, Reveal, ThemeIcon } from '@/ui';

export interface WorkflowStepItem {
    code: string;
    title: string;
    description: string;
    iconName?: string;
}

export interface WorkflowStepperProps {
    steps: WorkflowStepItem[];
    cardTone?: 'bg' | 'surface';
}

/**
 * Calm Flow numbered workflow stepper along an animated SVG Cerulean line (DESIGN.md Section 7.4 & 10).
 * Uses Anime.js to draw the SVG path on scroll reveal and respects prefers-reduced-motion.
 */
export function WorkflowStepper({ steps, cardTone = 'bg' }: WorkflowStepperProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const containerRef = useRef<HTMLDivElement | null>(null);
    const desktopPathRef = useRef<SVGPathElement | null>(null);
    const hasAnimatedRef = useRef(false);

    useEffect(() => {
        const pathEl = desktopPathRef.current;
        if (!pathEl) {
            return;
        }

        if (prefersReducedMotion || typeof IntersectionObserver === 'undefined') {
            pathEl.style.strokeDashoffset = '0';
            return;
        }

        const container = containerRef.current;
        if (!container || hasAnimatedRef.current) {
            return;
        }

        pathEl.style.strokeDashoffset = '1000';

        const observer = new IntersectionObserver(
            (entries) => {
                const entry = entries[0];
                if (!entry?.isIntersecting || hasAnimatedRef.current) {
                    return;
                }

                hasAnimatedRef.current = true;
                animate(pathEl, {
                    strokeDashoffset: [1000, 0],
                    duration: 1100,
                    ease: 'inOutQuad',
                });

                observer.disconnect();
            },
            { threshold: 0.2 },
        );

        observer.observe(container);

        return () => {
            observer.disconnect();
        };
    }, [prefersReducedMotion]);

    const colsClass = steps.length === 3 ? 'lg:grid-cols-3' : 'sm:grid-cols-2 lg:grid-cols-4';
    const cardBgClass = cardTone === 'surface' ? 'bg-surface' : 'bg-bg';

    return (
        <div ref={containerRef} className="relative">
            {/* Horizontal SVG Cerulean Connector Line (Desktop) */}
            <div
                aria-hidden="true"
                className="pointer-events-none absolute inset-x-12 top-7 hidden lg:block"
            >
                <svg
                    viewBox="0 0 1000 12"
                    fill="none"
                    preserveAspectRatio="none"
                    className="h-3 w-full overflow-visible"
                >
                    <path
                        d="M 0 6 L 1000 6"
                        stroke="currentColor"
                        strokeWidth="3"
                        strokeLinecap="round"
                        className="text-line"
                    />
                    <path
                        ref={desktopPathRef}
                        d="M 0 6 L 1000 6"
                        pathLength={1000}
                        stroke="currentColor"
                        strokeWidth="3.5"
                        strokeLinecap="round"
                        strokeDasharray="1000"
                        strokeDashoffset="0"
                        className="text-accent"
                    />
                </svg>
            </div>

            <ol className={`relative grid grid-cols-1 gap-6 ${colsClass}`}>
                {steps.map((step, idx) => (
                    <li key={`${step.code}-${idx}`} className="relative flex flex-col">
                        <Reveal preset="fadeUp" className="flex h-full flex-col">
                            {/* Stepper Node on Cerulean Line */}
                            <div className="mb-5 flex items-center gap-3">
                                <span className="relative z-10 inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-accent font-heading text-base font-bold text-on-accent shadow-sm ring-8 ring-bg">
                                    {step.code}
                                </span>
                                <span
                                    aria-hidden="true"
                                    className="h-0.5 flex-1 bg-accent/40 lg:hidden"
                                />
                            </div>

                            {/* Rounded 24px Step Card */}
                            <div
                                className={`flex flex-1 flex-col justify-between rounded-[var(--radius-card)] border border-line ${cardBgClass} p-6 shadow-sm space-y-3`}
                            >
                                <div className="space-y-2.5">
                                    {step.iconName ? (
                                        <span className="inline-flex h-10 w-10 items-center justify-center rounded-full bg-surface text-ink">
                                            <ThemeIcon name={step.iconName} className="h-5 w-5" />
                                        </span>
                                    ) : null}
                                    <Heading level={3} size="h3" className="font-bold">
                                        {step.title}
                                    </Heading>
                                    <p className="text-sm leading-6 text-muted">
                                        {step.description}
                                    </p>
                                </div>
                            </div>
                        </Reveal>
                    </li>
                ))}
            </ol>
        </div>
    );
}
