import { Link } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import type { SplitContentBlockData } from '@/core';
import { Container, Heading, Image, Prose, Section } from '@/ui';
import { GoldRule } from '../GoldRule';
import { useGsapParallax, useGsapStagger } from '../useKorinBerkatMotion';

export interface SplitContentBlockProps {
    data: SplitContentBlockData;
    isFirstBlock?: boolean;
}

/**
 * PT Korin Berkat Energy Company Overview / Split Content.
 * When rendered as the first block (e.g. Tentang Kami), displays an executive dark-navy
 * parallax banner above the crisp white narrative split with diagonal gold photo accent.
 */
export function SplitContentBlock({ data, isFirstBlock = false }: SplitContentBlockProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const bannerRef = useRef<HTMLDivElement | null>(null);
    const bannerBgRef = useRef<HTMLDivElement | null>(null);
    const containerRef = useRef<HTMLDivElement | null>(null);

    useGsapParallax(bannerRef, bannerBgRef, 12);
    useGsapStagger(containerRef, { y: 24, stagger: 0.1, duration: 0.65 });

    const imageOnLeft = data.image_position === 'left';

    return (
        <>
            {isFirstBlock ? (
                <div
                    ref={bannerRef}
                    className="relative overflow-hidden bg-surface-dark text-ink-on-dark border-b border-white/10"
                >
                    <div
                        ref={bannerBgRef}
                        aria-hidden="true"
                        className="pointer-events-none absolute inset-0 z-0 will-change-transform"
                    >
                        <img
                            src="/images/placeholders/barge-jetty-1280.webp"
                            alt=""
                            className="h-full w-full object-cover object-center opacity-50"
                        />
                        <div className="absolute inset-0 bg-gradient-to-r from-[#081222]/95 via-[#0B172A]/82 to-[#0B172A]/45" />
                    </div>
                    <div
                        aria-hidden="true"
                        className="kbe-diagonal-wedge-left pointer-events-none absolute bottom-0 left-0 z-10 h-14 w-12 opacity-90"
                    />
                    <Container className="relative z-10 py-14 sm:py-16 lg:py-20">
                        <div className="flex items-stretch gap-5 max-w-3xl">
                            <span
                                aria-hidden="true"
                                className="w-1 shrink-0 rounded-full bg-accent"
                            />
                            <div className="space-y-3">
                                {data.eyebrow ? (
                                    <p className="text-xs font-bold uppercase tracking-[0.18em] text-accent-on-dark">
                                        {data.eyebrow}
                                    </p>
                                ) : null}
                                <Heading
                                    level={1}
                                    size="h2"
                                    className="font-bold tracking-tight text-white sm:text-4xl lg:text-5xl"
                                >
                                    {data.title}
                                </Heading>
                                {data.lead ? (
                                    <p className="max-w-[58ch] text-base leading-7 text-slate-200">
                                        {data.lead}
                                    </p>
                                ) : null}
                            </div>
                        </div>
                    </Container>
                </div>
            ) : null}

            <Section tone="default" className="border-b border-line">
                <Container>
                    <div
                        ref={containerRef}
                        className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-center lg:gap-12"
                    >
                        <div
                            data-gsap-item
                            className={`space-y-5 lg:col-span-6 ${imageOnLeft ? 'lg:order-2' : 'lg:order-1'}`}
                        >
                            {data.eyebrow ? (
                                <div className="inline-flex items-center gap-3">
                                    <span className="text-xs font-bold uppercase tracking-[0.16em] text-accent-strong">
                                        {data.eyebrow}
                                    </span>
                                    <span
                                        aria-hidden="true"
                                        className="inline-block h-[2px] w-10 rounded-full bg-accent"
                                    />
                                </div>
                            ) : null}

                            <Heading
                                level={2}
                                size="h2"
                                className="font-bold tracking-tight text-ink"
                            >
                                {data.title}
                            </Heading>

                            <GoldRule thick className="w-14" />

                            {data.lead && !isFirstBlock ? (
                                <p className="font-heading text-lg font-semibold leading-7 text-ink/90 sm:text-xl sm:leading-8">
                                    {data.lead}
                                </p>
                            ) : null}

                            <Prose className="text-muted">
                                <p>{data.body}</p>
                            </Prose>

                            {data.cta_label && data.cta_href ? (
                                <div className="pt-2">
                                    <Link href={data.cta_href} className="kbe-btn-gold">
                                        <span>{data.cta_label}</span>
                                        <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                                    </Link>
                                </div>
                            ) : null}
                        </div>

                        <motion.div
                            data-gsap-item
                            whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                            transition={{ type: 'spring', stiffness: 280, damping: 24 }}
                            className={`lg:col-span-6 ${imageOnLeft ? 'lg:order-1' : 'lg:order-2'}`}
                        >
                            <div className="relative overflow-hidden rounded-[var(--radius-card)] bg-surface shadow-lg">
                                <div
                                    aria-hidden="true"
                                    className="kbe-card-corner-gold pointer-events-none absolute right-0 bottom-0 z-10 h-14 w-14 opacity-90"
                                />
                                <div className="overflow-hidden rounded-[var(--radius-card)] border border-line">
                                    <Image
                                        src={data.image}
                                        srcSet={data.image_srcset ?? undefined}
                                        alt={data.image_alt || data.title}
                                        ratio="4/3"
                                        className="transition-transform duration-500 hover:scale-[1.03]"
                                    />
                                </div>
                            </div>
                        </motion.div>
                    </div>
                </Container>
            </Section>
        </>
    );
}
