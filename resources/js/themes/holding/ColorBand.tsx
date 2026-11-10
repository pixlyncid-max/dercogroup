import type { ColorBandBlockData } from '@/core';
import { Button, Heading, Image, Reveal } from '@/ui';
import { HoldingIcon } from './IconResolver';

export interface ColorBandProps {
    data: ColorBandBlockData;
}

/**
 * Signature Holding full-bleed copper commitment band (DESIGN.md 4.4 #4 & 4.6).
 * Left: solid copper panel with white heading, certificate line icon + commitment copy, and white button.
 * Right: edge-to-edge aerial mine photograph without borders.
 */
export function ColorBand({ data }: ColorBandProps) {
    return (
        <section className="bg-accent-strong text-on-accent overflow-hidden">
            <div className="grid grid-cols-1 lg:grid-cols-12">
                <div className="lg:col-span-6 flex items-center justify-end px-6 py-12 md:px-10 md:py-16 lg:py-24 lg:pr-16 xl:pl-24">
                    <Reveal preset="fadeUp" className="w-full max-w-[520px] space-y-6">
                        <Heading level={2} size="h2" className="text-on-accent">
                            {data.title}
                        </Heading>

                        <div className="flex items-start gap-5 pt-1">
                            <div className="mt-0.5 flex h-14 w-14 shrink-0 items-center justify-center rounded-[var(--radius-card)] border border-on-accent/35">
                                <HoldingIcon
                                    name={data.icon_name || 'award'}
                                    className="h-8 w-8 text-on-accent"
                                    strokeWidth={1.5}
                                />
                            </div>
                            <p className="text-sm leading-6 text-on-accent max-w-[46ch]">
                                {data.description}
                            </p>
                        </div>

                        {data.cta_label && data.cta_href ? (
                            <div className="pt-2">
                                <Button
                                    variant="inverse"
                                    size="md"
                                    href={data.cta_href}
                                    className="text-xs font-bold uppercase tracking-[0.12em]"
                                >
                                    {data.cta_label}
                                </Button>
                            </div>
                        ) : null}
                    </Reveal>
                </div>

                <div className="lg:col-span-6 flex">
                    <Image
                        src={data.image}
                        srcSet={data.image_srcset}
                        alt={data.image_alt || data.title}
                        ratio="4/3"
                        wrapperClassName="h-full w-full rounded-none border-0 lg:aspect-auto"
                        className="h-full w-full object-cover"
                    />
                </div>
            </div>
        </section>
    );
}
