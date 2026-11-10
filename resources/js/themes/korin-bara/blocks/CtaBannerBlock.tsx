import { ArrowRight, Phone } from 'lucide-react';
import type { CtaBannerBlockData } from '@/core';
import { Button, Container, Heading, Reveal } from '@/ui';

export interface CtaBannerBlockProps {
    data: CtaBannerBlockData;
}

/**
 * Korin Bara Brass Gold CTA Banner (DESIGN.md Section 6.4).
 */
export function CtaBannerBlock({ data }: CtaBannerBlockProps) {
    return (
        <section className="border-t-4 border-ink bg-accent text-ink py-14 md:py-20">
            <Container>
                <Reveal
                    preset="fadeUp"
                    className="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center"
                >
                    <div className="lg:col-span-8 space-y-3">
                        {data.eyebrow ? (
                            <p className="font-heading text-xs font-bold uppercase tracking-[0.16em] text-ink">
                                {data.eyebrow}
                            </p>
                        ) : null}
                        <Heading level={2} size="h2" className="text-ink">
                            {data.title}
                        </Heading>
                        {data.subtitle ? (
                            <p className="max-w-[58ch] text-base font-medium leading-7 text-ink">
                                {data.subtitle}
                            </p>
                        ) : null}
                    </div>

                    <div className="lg:col-span-4 flex flex-col items-start gap-4 lg:items-end">
                        {data.phone ? (
                            <a
                                href={`tel:${data.phone.replaceAll(/\s+/g, '')}`}
                                className="inline-flex items-center gap-2 font-heading text-2xl font-bold text-ink hover:underline"
                            >
                                <Phone className="h-5 w-5" aria-hidden="true" />
                                <span>{data.phone}</span>
                            </a>
                        ) : null}

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
                </Reveal>
            </Container>
        </section>
    );
}
