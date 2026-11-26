import { usePage } from '@inertiajs/react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import {
    useLocale,
    type ProductData,
    type ProductListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Section } from '@/ui';
import { GoldRule } from '../GoldRule';
import { useGsapParallax, useGsapStagger } from '../useKorinBerkatMotion';

export interface ProductListBlockProps {
    data: ProductListBlockData;
    isFirstBlock?: boolean;
}

const PRODUCT_FALLBACK_IMAGES = [
    '/images/placeholders/coal-stockpile-1280.webp',
    '/images/placeholders/crushing-plant-1280.webp',
    '/images/placeholders/barge-jetty-1280.webp',
];

/**
 * PT Korin Berkat Energy Product Portfolio & Technical Specifications.
 * - On Home: preserves sr-only section heading, no download buttons or arrow-right icons;
 *   renders dark-navy executive cards with top coal imagery, gold accent bottom bar & corner wedge.
 * - On Products Page: dark-navy coal hero banner + dark-navy product cards + full technical
 *   specification tables directly visible with dark-navy table headers and alternating rows.
 */
export function ProductListBlock({ data, isFirstBlock = false }: ProductListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const bannerRef = useRef<HTMLDivElement | null>(null);
    const bannerBgRef = useRef<HTMLDivElement | null>(null);
    const gridRef = useRef<HTMLDivElement | null>(null);

    useGsapParallax(bannerRef, bannerBgRef, 12);
    useGsapStagger(gridRef, { y: 26, stagger: 0.09, duration: 0.65 });

    const allProducts: ProductData[] = props.products ?? [];
    const isHomePage = props.page?.key === 'home';
    const limit = isHomePage && data.limit && data.limit > 0 ? data.limit : allProducts.length;
    const products = allProducts.slice(0, limit);

    return (
        <>
            {/* Dark Navy Coal Banner on Full Products Page */}
            {!isHomePage ? (
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
                            src="/images/placeholders/coal-stockpile-1280.webp"
                            alt=""
                            className="h-full w-full object-cover object-center opacity-50"
                        />
                        <div className="absolute inset-0 bg-gradient-to-r from-[#081222]/95 via-[#0B172A]/82 to-[#0B172A]/45" />
                    </div>
                    <div
                        aria-hidden="true"
                        className="kbe-diagonal-wedge-left pointer-events-none absolute bottom-0 left-0 z-10 h-14 w-12 opacity-90"
                    />
                    <Container className="relative z-10 pt-28 pb-14 sm:pt-32 sm:pb-16 lg:pt-36 lg:pb-20">
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
                                    level={isFirstBlock ? 1 : 2}
                                    size="h2"
                                    className="font-bold tracking-tight text-white sm:text-4xl lg:text-5xl"
                                >
                                    {data.title}
                                </Heading>
                                {data.description ? (
                                    <p className="max-w-[58ch] text-base leading-7 text-slate-200">
                                        {data.description}
                                    </p>
                                ) : null}
                            </div>
                        </div>
                    </Container>
                </div>
            ) : null}

            <Section tone={isHomePage ? 'default' : 'surface'} className="border-b border-line">
                <Container>
                    {isHomePage ? <h2 className="sr-only">{data.title}</h2> : null}

                    {isHomePage ? (
                        /* Home Page: Dark-Navy Executive Product Cards (no download or arrow-right icons) */
                        <div ref={gridRef} className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                            {products.map((product, idx) => {
                                const highlightSpecs = product.specs.filter(
                                    (spec) => spec.is_highlight,
                                );
                                const baseSpecs =
                                    highlightSpecs.length > 0
                                        ? highlightSpecs.slice(0, 4)
                                        : product.specs.slice(0, 4);
                                const sulfurSpec = product.specs.find(
                                    (spec) =>
                                        spec.code?.toUpperCase() === 'TS' ||
                                        /sulfur/i.test(spec.label),
                                );
                                const hasSulfur = sulfurSpec
                                    ? baseSpecs.some((spec) => spec.id === sulfurSpec.id)
                                    : true;
                                const inlineSpecs =
                                    sulfurSpec && !hasSulfur
                                        ? [...baseSpecs.slice(0, 3), sulfurSpec]
                                        : baseSpecs;
                                const cardImage =
                                    product.image_url &&
                                    !product.image_url.includes('placeholder-image.svg')
                                        ? product.image_url
                                        : PRODUCT_FALLBACK_IMAGES[
                                              idx % PRODUCT_FALLBACK_IMAGES.length
                                          ];

                                return (
                                    <motion.article
                                        key={product.id}
                                        data-gsap-item
                                        whileHover={prefersReducedMotion ? undefined : { y: -6 }}
                                        transition={{
                                            type: 'spring',
                                            stiffness: 280,
                                            damping: 22,
                                        }}
                                        className="group relative flex flex-col overflow-hidden rounded-[var(--radius-card)] border border-white/10 border-b-4 border-b-accent bg-surface-dark text-ink-on-dark shadow-lg"
                                    >
                                        <div
                                            aria-hidden="true"
                                            className="kbe-card-corner-gold pointer-events-none absolute right-0 bottom-0 z-10 h-10 w-10 opacity-90"
                                        />

                                        {/* Top Coal Visual */}
                                        <div className="relative h-44 overflow-hidden bg-surface-deeper">
                                            <img
                                                src={cardImage}
                                                alt={product.image_alt || product.name}
                                                className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                loading="lazy"
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-[#0b172a] via-[#0b172a]/20 to-transparent" />
                                            <span className="absolute top-3.5 left-4 rounded-md bg-surface-dark/95 px-2.5 py-1 font-heading text-xs font-bold tabular-nums tracking-[0.08em] text-accent-on-dark">
                                                {String(idx + 1).padStart(2, '0')}
                                            </span>
                                        </div>

                                        {/* Card Body */}
                                        <div className="flex flex-1 flex-col justify-between p-6 space-y-5">
                                            <div className="space-y-2.5">
                                                {product.category_label ? (
                                                    <p className="text-[11px] font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                                        {product.category_label}
                                                    </p>
                                                ) : null}
                                                <Heading
                                                    level={3}
                                                    size="h3"
                                                    className="font-heading text-xl font-bold tracking-tight text-white"
                                                >
                                                    {product.name}
                                                </Heading>
                                                <p className="text-sm leading-6 text-slate-300">
                                                    {product.summary}
                                                </p>
                                            </div>

                                            {/* Inline Highlight Specs (CV, TM, ASH, SULFUR) */}
                                            <dl className="grid grid-cols-2 gap-x-4 gap-y-3 rounded-lg border border-white/10 bg-white/5 p-3.5">
                                                {inlineSpecs.map((spec) => {
                                                    const isSulfur =
                                                        spec.code?.toUpperCase() === 'TS' ||
                                                        /sulfur/i.test(spec.label);
                                                    const specHeading = isSulfur
                                                        ? 'SULFUR'
                                                        : spec.code || spec.label;

                                                    return (
                                                        <div
                                                            key={spec.id}
                                                            className="border-l-2 border-accent/45 pl-2.5"
                                                        >
                                                            <dt className="truncate text-[10px] font-bold uppercase tracking-[0.08em] text-slate-400">
                                                                {specHeading}
                                                            </dt>
                                                            <dd className="mt-0.5 font-heading text-sm font-bold text-white tabular-nums">
                                                                {spec.value}
                                                                {spec.unit ? (
                                                                    <span className="ml-0.5 font-body text-[10px] font-normal text-accent-on-dark">
                                                                        {spec.unit}
                                                                    </span>
                                                                ) : null}
                                                            </dd>
                                                        </div>
                                                    );
                                                })}
                                            </dl>
                                        </div>
                                    </motion.article>
                                );
                            })}
                        </div>
                    ) : (
                        /* Products Page: Dark-Navy Portfolio Summary + Full Technical Specification Tables */
                        <div ref={gridRef} className="space-y-12">
                            {products.map((product, idx) => {
                                const cardImage =
                                    product.image_url &&
                                    !product.image_url.includes('placeholder-image.svg')
                                        ? product.image_url
                                        : PRODUCT_FALLBACK_IMAGES[
                                              idx % PRODUCT_FALLBACK_IMAGES.length
                                          ];

                                return (
                                    <div
                                        key={product.id}
                                        data-gsap-item
                                        className="grid grid-cols-1 gap-8 rounded-[var(--radius-card)] border border-line bg-bg p-6 shadow-sm sm:p-8 lg:grid-cols-12 lg:items-start lg:gap-10"
                                    >
                                        {/* Left 5 Cols: Dark-Navy Product Card */}
                                        <motion.div
                                            whileHover={
                                                prefersReducedMotion ? undefined : { y: -3 }
                                            }
                                            transition={{
                                                type: 'spring',
                                                stiffness: 280,
                                                damping: 22,
                                            }}
                                            className="relative overflow-hidden rounded-[var(--radius-card)] border-b-4 border-b-accent bg-surface-dark text-ink-on-dark shadow-md lg:col-span-5"
                                        >
                                            <div
                                                aria-hidden="true"
                                                className="kbe-card-corner-gold pointer-events-none absolute right-0 bottom-0 z-10 h-10 w-10 opacity-90"
                                            />
                                            <div className="relative h-48 overflow-hidden">
                                                <img
                                                    src={cardImage}
                                                    alt={product.image_alt || product.name}
                                                    className="h-full w-full object-cover"
                                                    loading="lazy"
                                                />
                                                <div className="absolute inset-0 bg-gradient-to-t from-[#0b172a] via-[#0b172a]/20 to-transparent" />
                                            </div>
                                            <div className="p-6 space-y-3">
                                                {product.category_label ? (
                                                    <p className="text-[11px] font-bold uppercase tracking-[0.14em] text-accent-on-dark">
                                                        {product.category_label}
                                                    </p>
                                                ) : null}
                                                <Heading
                                                    level={2}
                                                    size="h3"
                                                    className="font-heading text-xl font-bold text-white lg:text-2xl"
                                                >
                                                    {product.name}
                                                </Heading>
                                                <GoldRule tone="dark" className="w-12" />
                                                <p className="text-sm leading-6 text-slate-300">
                                                    {product.summary}
                                                </p>
                                            </div>
                                        </motion.div>

                                        {/* Right 7 Cols: Full Technical Specifications Table */}
                                        <div className="lg:col-span-7">
                                            <div className="overflow-hidden rounded-[var(--radius-card)] border border-line bg-bg shadow-xs">
                                                <div className="flex items-center justify-between bg-surface-deeper px-5 py-3.5 text-xs font-bold uppercase tracking-[0.12em] text-white">
                                                    <span>{t('products.specifications')}</span>
                                                    <span className="text-accent-on-dark">
                                                        {t('products.parameter_value')}
                                                    </span>
                                                </div>
                                                <div className="divide-y divide-line">
                                                    {product.specs.map((spec, specIdx) => (
                                                        <div
                                                            key={spec.id}
                                                            className={`flex items-center justify-between gap-4 px-5 py-3.5 text-sm transition-colors hover:bg-amber-50/40 ${
                                                                specIdx % 2 === 1
                                                                    ? 'bg-surface/70'
                                                                    : 'bg-bg'
                                                            }`}
                                                        >
                                                            <span className="font-semibold text-ink/90">
                                                                {spec.label || spec.code}
                                                            </span>
                                                            <span className="font-heading font-bold text-ink tabular-nums">
                                                                {spec.value}
                                                                {spec.unit ? (
                                                                    <span className="ml-1 font-body text-xs font-medium text-muted">
                                                                        {spec.unit}
                                                                    </span>
                                                                ) : null}
                                                            </span>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </Container>
            </Section>
        </>
    );
}
