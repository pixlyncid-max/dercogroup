import { ArrowRight } from 'lucide-react';
import type { SplitContentBlockData } from '@/core';
import { Button, Container, Heading, Image, Prose, Reveal, Section } from '@/ui';

export interface SplitContentBlockProps {
    data: SplitContentBlockData;
    isFirstBlock?: boolean;
}

/**
 * Beramal Split Content ("Calm Flow", DESIGN.md Section 7.4).
 * Centered heading, opening narrative paragraphs, and a wide 28px-rounded photo
 * framed by a soft organic Cerulean wave backdrop.
 */
export function SplitContentBlock({ data, isFirstBlock = false }: SplitContentBlockProps) {
    return (
        <Section tone="default">
            <Container>
                <div className="mx-auto max-w-3xl text-center">
                    <Reveal preset="fadeUp" className="space-y-5">
                        {data.eyebrow ? (
                            <span className="inline-flex items-center rounded-full border border-line bg-surface px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-ink">
                                {data.eyebrow}
                            </span>
                        ) : null}

                        <Heading level={isFirstBlock ? 1 : 2} size="h2">
                            {data.title}
                        </Heading>

                        {data.lead ? (
                            <p className="mx-auto max-w-[62ch] font-heading text-xl font-medium leading-8 text-ink">
                                {data.lead}
                            </p>
                        ) : null}

                        <Prose className="mx-auto max-w-[65ch] text-center text-muted">
                            <p>{data.body}</p>
                        </Prose>

                        {data.cta_label && data.cta_href ? (
                            <div className="pt-2">
                                <Button
                                    href={data.cta_href}
                                    variant="solid"
                                    size="md"
                                    className="rounded-full px-6 font-semibold shadow-sm"
                                >
                                    <span>{data.cta_label}</span>
                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                </Button>
                            </div>
                        ) : null}
                    </Reveal>
                </div>

                <Reveal preset="fadeIn" className="relative mt-12">
                    {/* Organic Cerulean wave shape behind wide rounded photo */}
                    <svg
                        viewBox="0 0 1200 420"
                        fill="none"
                        className="pointer-events-none absolute -inset-4 -z-10 h-[calc(100%+2rem)] w-[calc(100%+2rem)] text-surface"
                        aria-hidden="true"
                        preserveAspectRatio="none"
                    >
                        <path
                            d="M48,54 C280,-12 520,88 780,32 C980,-10 1152,44 1176,160 C1200,276 1128,382 940,404 C690,432 410,368 180,398 C36,416 -12,310 12,184 C26,110 16,66 48,54 Z"
                            fill="currentColor"
                        />
                    </svg>

                    <div className="overflow-hidden rounded-[var(--radius-image)] border border-line bg-surface p-2 shadow-sm sm:p-3">
                        <Image
                            src={data.image}
                            srcSet={data.image_srcset ?? undefined}
                            alt={data.image_alt || data.title}
                            ratio="16/9"
                            className="rounded-[var(--radius-image)]"
                        />
                    </div>
                </Reveal>
            </Container>
        </Section>
    );
}
