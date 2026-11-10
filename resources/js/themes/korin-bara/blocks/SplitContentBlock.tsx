import { ArrowRight } from 'lucide-react';
import type { SplitContentBlockData } from '@/core';
import { Button, Container, Heading, Image, Prose, Reveal, Section } from '@/ui';

export interface SplitContentBlockProps {
    data: SplitContentBlockData;
    isFirstBlock?: boolean;
}

/**
 * Korin Bara Split Content ("Field Rail", DESIGN.md Section 6.4).
 * Large single-sentence opening statement followed by a wide documentary photograph and body text.
 */
export function SplitContentBlock({ data, isFirstBlock = false }: SplitContentBlockProps) {
    const wideImage = data.image || '/images/placeholders/pit-benches-1280.webp';

    return (
        <Section tone="default" className="border-b-2 border-line">
            <Container className="space-y-10">
                <Reveal preset="fadeUp" className="space-y-5">
                    <div className="flex items-center gap-3">
                        <span aria-hidden="true" className="h-1.5 w-10 bg-accent" />
                        {data.eyebrow ? (
                            <span className="font-heading text-sm font-bold uppercase tracking-[0.14em] text-accent-strong">
                                {data.eyebrow}
                            </span>
                        ) : null}
                    </div>

                    <Heading level={isFirstBlock ? 1 : 2} size="h2" className="text-ink">
                        {data.title}
                    </Heading>

                    {data.lead ? (
                        <p className="border-l-4 border-accent bg-surface p-6 font-heading text-2xl font-bold uppercase tracking-wide text-ink sm:text-3xl">
                            {data.lead}
                        </p>
                    ) : null}
                </Reveal>

                <Reveal preset="fadeIn" className="border-2 border-ink bg-surface-dark">
                    <Image
                        src={wideImage}
                        srcSet={data.image_srcset ?? undefined}
                        alt={data.image_alt || data.title}
                        ratio="16/9"
                        wrapperClassName="border-0 rounded-none"
                    />
                </Reveal>

                <Reveal
                    preset="fadeUp"
                    className="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center"
                >
                    <div className="lg:col-span-8">
                        <Prose className="text-muted">
                            <p>{data.body}</p>
                        </Prose>
                    </div>
                    {data.cta_label && data.cta_href ? (
                        <div className="lg:col-span-4 flex lg:justify-end">
                            <Button
                                href={data.cta_href}
                                variant="solid"
                                size="lg"
                                className="bg-surface-dark text-ink-on-dark hover:bg-surface-deeper font-heading text-sm font-bold uppercase tracking-[0.14em]"
                            >
                                <span>{data.cta_label}</span>
                                <ArrowRight
                                    className="h-4 w-4 text-accent-on-dark"
                                    aria-hidden="true"
                                />
                            </Button>
                        </div>
                    ) : null}
                </Reveal>
            </Container>
        </Section>
    );
}
