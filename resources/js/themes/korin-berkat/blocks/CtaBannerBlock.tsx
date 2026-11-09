import { ArrowRight, Phone } from 'lucide-react';
import type { CtaBannerBlockData } from '@/core';
import { Button, Container, Heading, Reveal, Section } from '@/ui';
import { GoldRule } from '../GoldRule';

export interface CtaBannerBlockProps {
    data: CtaBannerBlockData;
}

/**
 * Korin Berkat Editorial Navy CTA Banner (DESIGN.md Section 5.4).
 */
export function CtaBannerBlock({ data }: CtaBannerBlockProps) {
    return (
        <Section tone="dark" className="border-t-4 border-accent">
            <Container>
                <Reveal
                    preset="fadeUp"
                    className="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center"
                >
                    <div className="lg:col-span-8 space-y-4">
                        {data.eyebrow ? (
                            <p className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                {data.eyebrow}
                            </p>
                        ) : null}
                        <GoldRule tone="dark" thick className="w-16" />
                        <Heading level={2} size="h2" className="text-ink-on-dark">
                            {data.title}
                        </Heading>
                        {data.subtitle ? (
                            <p className="max-w-[58ch] text-base leading-7 text-muted-on-dark">
                                {data.subtitle}
                            </p>
                        ) : null}
                    </div>

                    <div className="lg:col-span-4 flex flex-col items-start gap-4 lg:items-end">
                        {data.phone ? (
                            <a
                                href={`tel:${data.phone.replaceAll(/\s+/g, '')}`}
                                className="inline-flex items-center gap-2.5 font-heading text-xl font-semibold text-accent-on-dark transition-colors hover:text-ink-on-dark"
                            >
                                <Phone className="h-4 w-4" aria-hidden="true" />
                                <span>{data.phone}</span>
                            </a>
                        ) : null}

                        <Button
                            href={data.cta_href}
                            variant="inverse"
                            size="lg"
                            className="text-xs font-semibold uppercase tracking-[0.12em]"
                        >
                            <span>{data.cta_label}</span>
                            <ArrowRight className="h-4 w-4" aria-hidden="true" />
                        </Button>
                    </div>
                </Reveal>
            </Container>
        </Section>
    );
}
