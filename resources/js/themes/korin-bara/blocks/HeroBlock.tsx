import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { motion, useReducedMotion, type Variants } from 'motion/react';
import { useLocale, type HeroBlockData } from '@/core';
import { Container, Image } from '@/ui';

export interface HeroBlockProps {
    data: HeroBlockData;
}

export function HeroBlock({ data }: HeroBlockProps) {
    const { t } = useLocale();
    const prefersReducedMotion = Boolean(useReducedMotion());

    const heroImage = data.image || '/images/placeholders/hero-excavator-1280.webp';
    const primaryLabel = data.primary_cta_label || t('read_more');
    const primaryHref = data.primary_cta_href || '#';
    const secondaryLabel = data.secondary_cta_label || t('contact_us');
    const secondaryHref = data.secondary_cta_href || '#';

    // Animation variants
    const containerVariants: Variants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: prefersReducedMotion ? 0 : 0.08,
            },
        },
    };

    const itemVariants: Variants = {
        hidden: { opacity: 0, y: prefersReducedMotion ? 0 : 16 },
        visible: {
            opacity: 1,
            y: 0,
            transition: { duration: prefersReducedMotion ? 0.15 : 0.4 },
        },
    };

    const photoVariants: Variants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: { duration: prefersReducedMotion ? 0.15 : 0.6 },
        },
    };

    return (
        <section
            data-testid="korin-bara-hero"
            className="relative overflow-hidden bg-white pt-8 pb-20 lg:pt-14 lg:pb-28"
        >
            {/* Flat semi-transparent diagonal shape behind left/center content */}
            <div
                aria-hidden="true"
                className="pointer-events-none absolute inset-y-0 left-0 w-3/4 bg-slate-100/60 kb-hero-bg-diagonal"
            />

            <Container className="relative z-10">
                <div className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                    {/* Left Column: Text & CTAs (7 columns on desktop) */}
                    <motion.div
                        initial="hidden"
                        animate="visible"
                        variants={containerVariants}
                        className="flex flex-col space-y-6 lg:col-span-7"
                    >
                        {data.eyebrow ? (
                            <motion.div variants={itemVariants}>
                                <span className="kb-eyebrow">{data.eyebrow}</span>
                            </motion.div>
                        ) : null}

                        <motion.h1
                            variants={itemVariants}
                            className="font-heading text-4xl font-bold tracking-tight text-[#101F38] sm:text-5xl lg:text-6xl lg:leading-[1.15]"
                        >
                            <span>{data.title}</span>
                            {data.title_emphasis ? (
                                <span className="block font-normal italic text-[#101F38]">
                                    {data.title_emphasis}
                                </span>
                            ) : null}
                        </motion.h1>

                        <motion.p
                            variants={itemVariants}
                            className="max-w-[65ch] text-base leading-relaxed text-slate-600 sm:text-lg"
                        >
                            {data.description}
                        </motion.p>

                        {(data.primary_cta_label && data.primary_cta_href) ||
                        (data.secondary_cta_label && data.secondary_cta_href) ? (
                            <motion.div
                                variants={itemVariants}
                                className="flex flex-wrap items-center gap-4 pt-2"
                            >
                                {data.primary_cta_label && data.primary_cta_href ? (
                                    <Link
                                        href={primaryHref}
                                        className="kb-btn-solid min-h-[44px]"
                                    >
                                        <span>{primaryLabel}</span>
                                        <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                    </Link>
                                ) : null}

                                {data.secondary_cta_label && data.secondary_cta_href ? (
                                    <Link
                                        href={secondaryHref}
                                        className="kb-btn-outline min-h-[44px]"
                                    >
                                        <span>{secondaryLabel}</span>
                                        <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                    </Link>
                                ) : null}
                            </motion.div>
                        ) : null}
                    </motion.div>

                    {/* Right Column: Full-Bleed Cut Heavy Machinery Photo (5 columns, breaking to right viewport edge) */}
                    <motion.div
                        initial="hidden"
                        animate="visible"
                        variants={photoVariants}
                        className="relative lg:col-span-5 lg:-mr-[calc((100vw-1200px)/2)] xl:-mr-[calc((100vw-1280px)/2)]"
                    >
                        <div className="relative overflow-hidden rounded-2xl shadow-xl lg:rounded-l-2xl lg:rounded-r-none kb-hero-image-cut">
                            <Image
                                src={heroImage}
                                srcSet={data.image_srcset ?? undefined}
                                alt={data.image_alt || data.title}
                                ratio="16/9"
                                priority
                                fetchPriority="high"
                                wrapperClassName="border-0 rounded-none h-full w-full"
                                className="h-[300px] w-full object-cover sm:h-[400px] lg:h-[480px]"
                            />
                        </div>
                    </motion.div>
                </div>
            </Container>
        </section>
    );
}
