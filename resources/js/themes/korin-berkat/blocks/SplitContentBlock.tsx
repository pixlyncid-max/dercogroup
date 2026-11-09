import { ArrowRight } from 'lucide-react';
import type { SplitContentBlockData } from '@/core';
import { Button, Container, Heading, Image, Prose, Reveal, Section } from '@/ui';
import { GoldRule } from '../GoldRule';

export interface SplitContentBlockProps {
    data: SplitContentBlockData;
    isFirstBlock?: boolean;
}

/**
 * Korin Berkat Split Content ("Editorial Index", DESIGN.md Section 5.4).
 * Opening narrative with right/left documentary image framed by a gold top rule.
 */
export function SplitContentBlock({ data, isFirstBlock = false }: SplitContentBlockProps) {
    const imageOnLeft = data.image_position === 'left';

    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                <div className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-start lg:gap-12">
                    <Reveal
                        preset="fadeUp"
                        className={`space-y-6 lg:col-span-7 ${imageOnLeft ? 'lg:order-2' : 'lg:order-1'}`}
                    >
                        {data.eyebrow ? (
                            <p className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-strong">
                                {data.eyebrow}
                            </p>
                        ) : null}

                        <GoldRule thick className="w-16" />

                        <Heading level={isFirstBlock ? 1 : 2} size="h2">
                            {data.title}
                        </Heading>

                        {data.lead ? (
                            <p className="font-heading text-xl font-medium leading-8 text-ink">
                                {data.lead}
                            </p>
                        ) : null}

                        <Prose className="text-muted">
                            <p>{data.body}</p>
                        </Prose>

                        {data.cta_label && data.cta_href ? (
                            <div className="pt-2">
                                <Button
                                    href={data.cta_href}
                                    variant="outline"
                                    size="md"
                                    className="text-xs font-semibold uppercase tracking-[0.12em]"
                                >
                                    <span>{data.cta_label}</span>
                                    <ArrowRight
                                        className="h-4 w-4 text-accent-strong"
                                        aria-hidden="true"
                                    />
                                </Button>
                            </div>
                        ) : null}
                    </Reveal>

                    <Reveal
                        preset="fadeIn"
                        className={`lg:col-span-5 ${imageOnLeft ? 'lg:order-1' : 'lg:order-2'}`}
                    >
                        <div className="border-t-4 border-accent bg-surface p-3">
                            <Image
                                src={data.image}
                                srcSet={data.image_srcset ?? undefined}
                                alt={data.image_alt || data.title}
                                ratio="4/3"
                                className="rounded-[var(--radius-card)]"
                            />
                        </div>
                    </Reveal>
                </div>
            </Container>
        </Section>
    );
}
