import { Link } from '@inertiajs/react';
import { ArrowUpRight, Phone } from 'lucide-react';
import { useRef } from 'react';
import type { CtaBannerBlockData } from '@/core';
import { Container, Heading, Section } from '@/ui';
import { useGsapStagger } from '../useKorinBerkatMotion';

export interface CtaBannerBlockProps {
    data: CtaBannerBlockData;
}

/**
 * PT Korin Berkat Energy Executive Navy CTA Banner.
 * Dark coal backdrop overlay, signature vertical gold accent bar on left,
 * diagonal gold wedge on right corner, and solid gold CTA button.
 */
export function CtaBannerBlock({ data }: CtaBannerBlockProps) {
    const contentRef = useRef<HTMLDivElement | null>(null);
    useGsapStagger(contentRef, { y: 20, stagger: 0.08, duration: 0.65 });

    return (
        <Section
            tone="dark"
            className="relative overflow-hidden border-t border-white/10 !py-12 md:!py-14"
        >
            {/* Subtle Background Coal Photo */}
            <div aria-hidden="true" className="pointer-events-none absolute inset-0 z-0">
                <img
                    src="/images/placeholders/coal-stockpile-1280.webp"
                    alt=""
                    className="h-full w-full object-cover object-center opacity-25"
                    loading="lazy"
                />
                <div className="absolute inset-0 bg-gradient-to-r from-[#081222]/95 via-[#0b172a]/90 to-[#0b172a]/80" />
            </div>

            {/* Diagonal Gold Wedge Right */}
            <div
                aria-hidden="true"
                className="kbe-diagonal-wedge-right pointer-events-none absolute top-0 right-0 bottom-0 z-0 w-16 opacity-90 sm:w-24"
            />

            <Container className="relative z-10 py-4 sm:py-6">
                <div
                    ref={contentRef}
                    className="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center"
                >
                    <div data-gsap-item className="flex items-stretch gap-5 lg:col-span-8">
                        <span
                            aria-hidden="true"
                            className="w-1.5 shrink-0 rounded-full bg-accent"
                        />
                        <div className="space-y-2.5">
                            {data.eyebrow ? (
                                <p className="text-xs font-bold uppercase tracking-[0.16em] text-accent-on-dark">
                                    {data.eyebrow}
                                </p>
                            ) : null}
                            <Heading
                                level={2}
                                size="h2"
                                className="font-bold tracking-tight text-white"
                            >
                                {data.title}
                            </Heading>
                            {data.subtitle ? (
                                <p className="max-w-[58ch] text-sm leading-6 text-slate-300 sm:text-base sm:leading-7">
                                    {data.subtitle}
                                </p>
                            ) : null}
                        </div>
                    </div>

                    <div
                        data-gsap-item
                        className="flex flex-col items-start gap-3.5 lg:col-span-4 lg:items-end lg:pr-8"
                    >
                        {data.phone ? (
                            <a
                                href={`tel:${data.phone.replaceAll(/\s+/g, '')}`}
                                className="inline-flex items-center gap-2 font-heading text-lg font-bold text-accent-on-dark transition-colors hover:text-white"
                            >
                                <Phone className="h-4 w-4" aria-hidden="true" />
                                <span>{data.phone}</span>
                            </a>
                        ) : null}

                        <Link href={data.cta_href} className="kbe-btn-gold px-6 py-3">
                            <span>{data.cta_label}</span>
                            <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                        </Link>
                    </div>
                </div>
            </Container>
        </Section>
    );
}
