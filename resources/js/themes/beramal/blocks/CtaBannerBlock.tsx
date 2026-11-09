import { ArrowRight, Phone } from 'lucide-react';
import type { CtaBannerBlockData } from '@/core';
import { Button, Container, Heading, Reveal, Section } from '@/ui';

export interface CtaBannerBlockProps {
    data: CtaBannerBlockData;
}

/**
 * Beramal Dark Water Rounded CTA Banner ("Calm Flow", DESIGN.md Section 7.4).
 * Enclosed 24px-rounded Dark Water card with thin wave pattern and Cerulean pill button.
 */
export function CtaBannerBlock({ data }: CtaBannerBlockProps) {
    return (
        <Section tone="default">
            <Container>
                <Reveal
                    preset="fadeUp"
                    className="relative overflow-hidden rounded-[var(--radius-card)] bg-surface-dark p-8 text-ink-on-dark shadow-md sm:p-12"
                >
                    {/* Subtle thin SVG wave lines inside Dark Water card */}
                    <svg
                        viewBox="0 0 1200 260"
                        fill="none"
                        className="pointer-events-none absolute inset-0 h-full w-full text-accent-on-dark/15"
                        aria-hidden="true"
                        preserveAspectRatio="none"
                    >
                        <path
                            d="M0,180 C320,90 680,250 1200,120"
                            stroke="currentColor"
                            strokeWidth="1.5"
                        />
                        <path
                            d="M0,220 C400,150 760,260 1200,175"
                            stroke="currentColor"
                            strokeWidth="1"
                        />
                    </svg>

                    <div className="relative grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center">
                        <div className="lg:col-span-8 space-y-4">
                            {data.eyebrow ? (
                                <span className="inline-flex items-center rounded-full border border-accent-on-dark/30 bg-accent-on-dark/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-accent-on-dark">
                                    {data.eyebrow}
                                </span>
                            ) : null}
                            <Heading level={2} size="h2" className="text-ink-on-dark">
                                {data.title}
                            </Heading>
                            {data.subtitle ? (
                                <p className="max-w-[58ch] text-base leading-7 text-muted-on-dark">
                                    {data.subtitle}
                                </p>
                            ) : null}
                        </div>

                        <div className="flex flex-col items-start gap-4 lg:col-span-4 lg:items-end">
                            {data.phone ? (
                                <a
                                    href={`tel:${data.phone.replaceAll(/\s+/g, '')}`}
                                    className="inline-flex items-center gap-2.5 rounded-full border border-ink-on-dark/15 bg-ink-on-dark/5 px-4 py-2 font-heading text-base font-semibold text-accent-on-dark transition-colors hover:border-accent-on-dark/40 hover:text-ink-on-dark"
                                >
                                    <Phone className="h-4 w-4" aria-hidden="true" />
                                    <span>{data.phone}</span>
                                </a>
                            ) : null}

                            <Button
                                href={data.cta_href}
                                variant="solid"
                                size="lg"
                                className="rounded-full bg-accent px-7 font-semibold text-on-accent shadow-sm hover:bg-accent-strong"
                            >
                                <span>{data.cta_label}</span>
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Button>
                        </div>
                    </div>
                </Reveal>
            </Container>
        </Section>
    );
}
