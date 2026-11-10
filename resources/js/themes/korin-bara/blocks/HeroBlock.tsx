import { ArrowRight } from 'lucide-react';
import type { HeroBlockData } from '@/core';
import { Button, Heading, Image, Reveal } from '@/ui';

export interface HeroBlockProps {
    data: HeroBlockData;
}

/**
 * Korin Bara Hero ("Field Rail", DESIGN.md Section 6.3 & 6.4).
 * Full-bleed edge-to-edge mining photograph with a bold Brass Gold panel anchored at the bottom-left.
 */
export function HeroBlock({ data }: HeroBlockProps) {
    const heroImage = data.image || '/images/placeholders/pit-benches-1280.webp';

    return (
        <section className="relative overflow-hidden border-b-4 border-ink bg-surface-dark">
            {/* Full-bleed mining photograph */}
            <div className="relative min-h-[520px] w-full lg:min-h-[640px]">
                <div className="absolute inset-0">
                    <Image
                        src={heroImage}
                        srcSet={data.image_srcset ?? undefined}
                        alt={data.image_alt || data.title}
                        ratio="16/9"
                        priority
                        wrapperClassName="h-full w-full border-0 rounded-none"
                        className="h-full w-full object-cover"
                    />
                    <div aria-hidden="true" className="absolute inset-0 bg-surface-deeper/45" />
                </div>

                {/* Bottom-Left Brass Gold Panel */}
                <div className="relative z-10 flex min-h-[520px] items-end p-4 sm:p-8 lg:min-h-[640px] lg:p-14">
                    <Reveal
                        preset="fadeUp"
                        className="w-full max-w-2xl border-t-4 border-ink bg-accent p-7 text-ink shadow-md sm:p-10 lg:p-12 space-y-6"
                    >
                        {data.eyebrow ? (
                            <div className="inline-flex items-center gap-2.5 border-b-2 border-ink pb-1.5">
                                <span className="font-heading text-xs font-bold uppercase tracking-[0.16em] text-ink">
                                    {data.eyebrow}
                                </span>
                            </div>
                        ) : null}

                        <Heading
                            level={1}
                            size="display"
                            className="text-ink uppercase tracking-[0.02em]"
                        >
                            {data.title}
                        </Heading>

                        <p className="max-w-[56ch] text-base font-medium leading-7 text-ink sm:text-lg">
                            {data.description}
                        </p>

                        {data.primary_cta_label || data.secondary_cta_label ? (
                            <div className="flex flex-wrap items-center gap-4 pt-2">
                                {data.primary_cta_label && data.primary_cta_href ? (
                                    <Button
                                        href={data.primary_cta_href}
                                        variant="solid"
                                        size="lg"
                                        className="bg-surface-dark text-ink-on-dark hover:bg-surface-deeper font-heading text-sm font-bold uppercase tracking-[0.14em]"
                                    >
                                        <span>{data.primary_cta_label}</span>
                                        <ArrowRight
                                            className="h-4 w-4 text-accent-on-dark"
                                            aria-hidden="true"
                                        />
                                    </Button>
                                ) : null}

                                {data.secondary_cta_label && data.secondary_cta_href ? (
                                    <Button
                                        href={data.secondary_cta_href}
                                        variant="outline"
                                        size="lg"
                                        className="border-2 border-ink text-ink hover:bg-ink hover:text-ink-on-dark font-heading text-sm font-bold uppercase tracking-[0.14em]"
                                    >
                                        {data.secondary_cta_label}
                                    </Button>
                                ) : null}
                            </div>
                        ) : null}
                    </Reveal>
                </div>
            </div>
        </section>
    );
}
