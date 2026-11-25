import { usePage } from '@inertiajs/react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import type { ColorBandBlockData, SharedPageProps } from '@/core';
import { useGsapParallax, useGsapStagger } from '@/motion/gsapLenis';
import { Button, Heading, Image } from '@/ui';
import { HoldingIcon } from './IconResolver';

export interface ColorBandProps {
    data: ColorBandBlockData;
}

/**
 * Signature Holding full-bleed copper commitment band (DESIGN.md 4.4 #4 & 4.6).
 * Enhanced with GSAP staggered copy reveal, continuous scroll-scrubbed photo parallax,
 * and tactile Motion hover feedback.
 */
export function ColorBand({ data }: ColorBandProps) {
    const { props } = usePage<SharedPageProps>();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const isHome = props.page?.key === 'home';

    const sectionRef = useRef<HTMLElement | null>(null);
    const copyRef = useRef<HTMLDivElement | null>(null);
    const photoRef = useRef<HTMLDivElement | null>(null);

    useGsapStagger(copyRef, { y: 28, stagger: 0.1, duration: 0.7 });
    useGsapParallax(sectionRef, photoRef, 14);

    return (
        <section ref={sectionRef} className="bg-accent-strong text-on-accent overflow-hidden">
            <div className="grid grid-cols-1 lg:grid-cols-12">
                <div className="lg:col-span-6 flex items-center justify-end px-6 py-12 md:px-10 md:py-16 lg:py-24 lg:pr-16 xl:pl-24">
                    <div ref={copyRef} className="w-full max-w-[520px] space-y-6">
                        {isHome ? (
                            <h2 className="sr-only">{data.title}</h2>
                        ) : (
                            <div data-gsap-item>
                                <Heading level={2} size="h2" className="text-on-accent">
                                    {data.title}
                                </Heading>
                            </div>
                        )}

                        <div data-gsap-item className="flex items-start gap-5 pt-1">
                            <motion.div
                                whileHover={prefersReducedMotion ? undefined : { rotate: 6, scale: 1.06 }}
                                transition={{ type: 'spring', stiffness: 320, damping: 20 }}
                                className="mt-0.5 flex h-14 w-14 shrink-0 items-center justify-center rounded-[var(--radius-card)] border border-on-accent/35"
                            >
                                <HoldingIcon
                                    name={data.icon_name || 'award'}
                                    className="h-8 w-8 text-on-accent"
                                    strokeWidth={1.5}
                                />
                            </motion.div>
                            <p className="text-sm leading-6 text-on-accent max-w-[46ch]">
                                {data.description}
                            </p>
                        </div>

                        {data.cta_label && data.cta_href ? (
                            <motion.div
                                data-gsap-item
                                whileHover={prefersReducedMotion ? undefined : { x: 4 }}
                                transition={{ type: 'spring', stiffness: 320, damping: 22 }}
                                className="inline-block pt-2"
                            >
                                <Button
                                    variant="inverse"
                                    size="md"
                                    href={data.cta_href}
                                    className="text-xs font-bold uppercase tracking-[0.12em]"
                                >
                                    {data.cta_label}
                                </Button>
                            </motion.div>
                        ) : null}
                    </div>
                </div>

                <div className="lg:col-span-6 flex overflow-hidden">
                    <div ref={photoRef} className="h-full w-full will-change-transform">
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
            </div>
        </section>
    );
}
