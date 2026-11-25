import { Link, usePage } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import { useLocale, type HeroBlockData, type SharedPageProps } from '@/core';
import { Container, Counter, Heading } from '@/ui';
import { GoldRule } from '../GoldRule';
import { useGsapParallax, useGsapStagger } from '../useKorinBerkatMotion';

export interface HeroBlockProps {
    data: HeroBlockData;
}

/**
 * PT Korin Berkat Energy Cinematic Executive Hero.
 * Full-bleed dark-navy coal port backdrop with GSAP parallax, gold diagonal edge motif,
 * bold editorial display typography, gold CTA buttons, and a glassmorphic dark-navy Key Figures panel.
 */
export function HeroBlock({ data }: HeroBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale, t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const sectionRef = useRef<HTMLElement | null>(null);
    const bgRef = useRef<HTMLDivElement | null>(null);
    const contentRef = useRef<HTMLDivElement | null>(null);

    useGsapParallax(sectionRef, bgRef, 14);
    useGsapStagger(contentRef, { y: 24, stagger: 0.08, duration: 0.7, start: 'top 95%' });

    const stats = (props.stats ?? props.company?.stats ?? []).slice(0, 3);
    const heroImage =
        data.image && !data.image.includes('placeholder-image.svg')
            ? data.image
            : '/images/placeholders/barge-jetty-1280.webp';

    return (
        <section
            ref={sectionRef}
            className="relative overflow-hidden bg-surface-dark text-ink-on-dark border-b border-white/10"
        >
            {/* Parallax Background Photo */}
            <div
                ref={bgRef}
                aria-hidden="true"
                className="pointer-events-none absolute inset-0 z-0 will-change-transform"
            >
                <img
                    src={heroImage}
                    alt=""
                    className="h-full w-full object-cover object-center opacity-55"
                    loading="eager"
                />
                <div className="absolute inset-0 bg-gradient-to-r from-[#081222]/95 via-[#0B172A]/82 to-[#0B172A]/50" />
                <div className="absolute inset-0 bg-gradient-to-t from-[#081222]/90 via-transparent to-[#081222]/40" />
            </div>

            {/* Signature Diagonal Gold Wedge on Bottom-Left Edge */}
            <div
                aria-hidden="true"
                className="kbe-diagonal-wedge-left pointer-events-none absolute bottom-0 left-0 z-10 h-16 w-12 opacity-95 sm:h-20 sm:w-16"
            />

            <Container className="relative z-10 py-16 sm:py-20 lg:py-24">
                <div
                    ref={contentRef}
                    className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-center lg:gap-12"
                >
                    {/* Left 7 Columns: Cinematic Editorial Copy */}
                    <div className="flex flex-col justify-center space-y-6 lg:col-span-7">
                        <div data-gsap-item className="inline-flex items-center gap-3">
                            <span
                                aria-hidden="true"
                                className="inline-block h-[2px] w-8 rounded-full bg-accent"
                            />
                            <span className="text-xs font-bold uppercase tracking-[0.18em] text-accent-on-dark">
                                {data.eyebrow || t('nav.index_heading')}
                            </span>
                        </div>

                        <div data-gsap-item>
                            <Heading
                                level={1}
                                size="display"
                                className="font-bold tracking-tight text-white"
                            >
                                {data.title}
                            </Heading>
                        </div>

                        <p
                            data-gsap-item
                            className="max-w-[56ch] text-base leading-7 text-slate-200 sm:text-lg sm:leading-8"
                        >
                            {data.description}
                        </p>

                        {data.primary_cta_label || data.secondary_cta_label ? (
                            <div
                                data-gsap-item
                                className="flex flex-wrap items-center gap-3.5 pt-2"
                            >
                                {data.primary_cta_label && data.primary_cta_href ? (
                                    <Link
                                        href={data.primary_cta_href}
                                        className="kbe-btn-gold px-6 py-3"
                                    >
                                        <span>{data.primary_cta_label}</span>
                                        <ArrowUpRight className="h-4 w-4" aria-hidden="true" />
                                    </Link>
                                ) : null}

                                {data.secondary_cta_label && data.secondary_cta_href ? (
                                    <Link
                                        href={data.secondary_cta_href}
                                        className="kbe-btn-outline-dark px-6 py-3"
                                    >
                                        <span>{data.secondary_cta_label}</span>
                                    </Link>
                                ) : null}
                            </div>
                        ) : null}
                    </div>

                    {/* Right 5 Columns: Glassmorphic Dark Navy Operational Figures Panel */}
                    {stats.length > 0 ? (
                        <motion.div
                            data-gsap-item
                            whileHover={prefersReducedMotion ? undefined : { y: -4 }}
                            transition={{ type: 'spring', stiffness: 300, damping: 24 }}
                            className="relative overflow-hidden rounded-[var(--radius-card)] border border-white/15 border-t-4 border-t-accent bg-[#0b172a]/85 p-7 text-ink-on-dark shadow-2xl backdrop-blur-md sm:p-8 lg:col-span-5"
                        >
                            <div
                                aria-hidden="true"
                                className="kbe-card-corner-gold pointer-events-none absolute right-0 bottom-0 h-10 w-10 opacity-85"
                            />

                            <div className="flex items-center justify-between border-b border-white/15 pb-4">
                                <span className="text-[11px] font-bold uppercase tracking-[0.16em] text-accent-on-dark">
                                    {locale === 'en'
                                        ? 'KEY OPERATIONAL FIGURES'
                                        : 'DATA KUNCI OPERASIONAL'}
                                </span>
                                <span className="font-heading text-xs font-semibold text-slate-300">
                                    {`01 — 0${stats.length}`}
                                </span>
                            </div>

                            <div className="my-2 divide-y divide-white/10">
                                {stats.map((stat, idx) => (
                                    <div key={stat.id} className="py-4 first:pt-3 last:pb-2">
                                        <div className="flex items-baseline justify-between gap-4">
                                            <div className="flex items-baseline gap-2">
                                                <Counter
                                                    value={stat.value}
                                                    prefix={stat.prefix}
                                                    suffix={stat.suffix}
                                                    locale={locale}
                                                    className="font-heading text-3xl font-bold tracking-tight text-white sm:text-4xl"
                                                />
                                                {stat.unit ? (
                                                    <span className="text-xs font-bold uppercase tracking-[0.08em] text-accent-on-dark">
                                                        {stat.unit}
                                                    </span>
                                                ) : null}
                                            </div>
                                            <span className="font-heading text-xs font-bold text-accent-on-dark/80">
                                                {`0${idx + 1}`}
                                            </span>
                                        </div>
                                        <p className="mt-1 text-xs font-medium uppercase tracking-[0.08em] text-slate-300">
                                            {stat.label}
                                        </p>
                                    </div>
                                ))}
                            </div>

                            <GoldRule tone="dark" className="mt-2 w-full opacity-50" />
                        </motion.div>
                    ) : null}
                </div>
            </Container>
        </section>
    );
}
