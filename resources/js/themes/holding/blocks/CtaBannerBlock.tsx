import { usePage } from '@inertiajs/react';
import type { CtaBannerBlockData, SharedPageProps } from '@/core';
import { Button, Container, Reveal } from '@/ui';

export interface CtaBannerBlockProps {
    data: CtaBannerBlockData;
}

/**
 * Holding dark contact card banner (DESIGN.md 4.4 #6).
 * Sits on the boundary between the white content area and the Charcoal Deep footer.
 */
export function CtaBannerBlock({ data }: CtaBannerBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const isHome = props.page?.key === 'home';

    return (
        <section className="relative bg-bg py-8 md:py-12 before:pointer-events-none before:absolute before:inset-x-0 before:bottom-0 before:h-1/2 before:bg-surface-deeper">
            <Container>
                <Reveal
                    preset="fadeUp"
                    className="relative z-[var(--z-index-sticky)] overflow-hidden rounded-[var(--radius-card)] bg-surface-dark px-6 py-12 text-ink-on-dark shadow-md md:px-12 lg:py-16"
                >
                    <svg
                        viewBox="0 0 180 120"
                        aria-hidden="true"
                        className="pointer-events-none absolute left-0 top-1/2 hidden h-32 w-44 -translate-y-1/2 text-accent opacity-85 md:block"
                        fill="none"
                    >
                        <path
                            d="M-8 36 C 16 28, 32 44, 56 36 C 80 28, 96 44, 120 36"
                            stroke="currentColor"
                            strokeWidth="2.5"
                        />
                        <path
                            d="M-8 54 C 16 46, 32 62, 56 54 C 80 46, 96 62, 120 54"
                            stroke="currentColor"
                            strokeWidth="2.5"
                        />
                        <path
                            d="M-8 72 C 16 64, 32 80, 56 72 C 80 64, 96 80, 120 72"
                            stroke="currentColor"
                            strokeWidth="2.5"
                        />
                        <rect
                            x="78"
                            y="20"
                            width="84"
                            height="80"
                            rx="4"
                            stroke="currentColor"
                            strokeWidth="2.5"
                            strokeDasharray="6 4"
                            fill="var(--color-surface-dark)"
                        />
                        <rect
                            x="90"
                            y="32"
                            width="60"
                            height="56"
                            rx="2"
                            stroke="currentColor"
                            strokeWidth="2"
                        />
                        <circle cx="120" cy="60" r="14" stroke="currentColor" strokeWidth="2" />
                    </svg>

                    <div className="mx-auto max-w-[560px] text-center space-y-4">
                        {isHome ? (
                            <h2 className="sr-only">{data.title}</h2>
                        ) : (
                            <h2 className="font-heading text-base md:text-lg font-bold text-ink-on-dark">
                                {data.title}
                            </h2>
                        )}

                        {data.subtitle ? (
                            <p className="text-xs md:text-sm leading-6 text-muted-on-dark">
                                {data.subtitle}
                            </p>
                        ) : null}

                        {data.phone ? (
                            <p className="pt-1 font-heading text-2xl md:text-3xl lg:text-4xl font-bold tracking-tight text-accent-on-dark">
                                <a
                                    href={`tel:${data.phone.replaceAll(/\s+/g, '')}`}
                                    className="hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                >
                                    {data.phone}
                                </a>
                            </p>
                        ) : null}

                        <div className="pt-3">
                            <Button
                                variant="inverse"
                                size="md"
                                href={data.cta_href}
                                className="text-xs font-bold uppercase tracking-[0.12em]"
                            >
                                {data.cta_label}
                            </Button>
                        </div>
                    </div>
                </Reveal>
            </Container>
        </section>
    );
}
