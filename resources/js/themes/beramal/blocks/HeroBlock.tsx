import { usePage } from '@inertiajs/react';
import { ArrowRight, CheckCircle2 } from 'lucide-react';
import { useLocale, type HeroBlockData, type SharedPageProps } from '@/core';
import { Button, Container, Heading, Image, Reveal, Section } from '@/ui';

export interface HeroBlockProps {
    data: HeroBlockData;
}

/**
 * Beramal Sukses Sejahtera Hero ("Calm Flow", DESIGN.md Section 7.4).
 * Left column: white background, calm pill eyebrow, Manrope display heading, description, and Cerulean pill CTA.
 * Right column: large 28px-rounded photo with an organic SVG Cerulean shape behind it.
 */
export function HeroBlock({ data }: HeroBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { locale } = useLocale();
    const heroImage = data.image ?? props.company?.featured_image_url ?? null;
    const heroAlt =
        data.image_alt || props.company?.featured_image_alt || props.company?.name || data.title;

    return (
        <Section tone="default" className="overflow-hidden pt-10 md:pt-14 lg:pt-20">
            <Container size="wide">
                <div className="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center lg:gap-12">
                    {/* Left 7 Columns: Calm Typography & Cerulean Pill Actions */}
                    <Reveal preset="fadeUp" className="space-y-7 lg:col-span-7 lg:pr-6">
                        {data.eyebrow ? (
                            <div className="inline-flex items-center gap-2.5 rounded-full border border-line bg-surface px-4 py-1.5 text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                <span
                                    aria-hidden="true"
                                    className="h-2 w-2 rounded-full bg-accent"
                                />
                                <span>{data.eyebrow}</span>
                            </div>
                        ) : null}

                        <Heading level={1} size="display">
                            {data.title}
                        </Heading>

                        <p className="max-w-[58ch] text-base leading-7 text-muted sm:text-lg sm:leading-8">
                            {data.description}
                        </p>

                        {data.primary_cta_label || data.secondary_cta_label ? (
                            <div className="flex flex-wrap items-center gap-4 pt-2">
                                {data.primary_cta_label && data.primary_cta_href ? (
                                    <Button
                                        href={data.primary_cta_href}
                                        variant="solid"
                                        size="lg"
                                        className="rounded-full bg-accent text-on-accent font-bold shadow-sm"
                                    >
                                        <span>{data.primary_cta_label}</span>
                                        <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                    </Button>
                                ) : null}

                                {data.secondary_cta_label && data.secondary_cta_href ? (
                                    <Button
                                        href={data.secondary_cta_href}
                                        variant="outline"
                                        size="lg"
                                        className="rounded-full border-line bg-surface/60 text-ink font-bold hover:bg-surface"
                                    >
                                        {data.secondary_cta_label}
                                    </Button>
                                ) : null}
                            </div>
                        ) : null}
                    </Reveal>

                    {/* Right 5 Columns: Rounded 28px Photo with Organic Cerulean Shape Behind It */}
                    <Reveal preset="fadeIn" className="relative lg:col-span-5">
                        <div className="relative mx-auto max-w-[540px] py-4 px-3 sm:px-5">
                            {/* Organic Cerulean SVG Shape Behind Rounded Photo */}
                            <svg
                                aria-hidden="true"
                                viewBox="0 0 560 460"
                                fill="none"
                                className="pointer-events-none absolute -inset-3 -z-10 h-[calc(100%+24px)] w-[calc(100%+24px)] text-accent"
                            >
                                <path
                                    d="M92 44C184 8 340 -6 438 48C536 102 568 232 518 334C468 436 336 462 212 444C88 426 12 356 8 242C4 128 0 80 92 44Z"
                                    fill="currentColor"
                                    fillOpacity="0.16"
                                />
                                <path
                                    d="M44 126C98 48 258 26 382 68C506 110 546 218 492 316C438 414 274 446 154 402C34 358 -10 204 44 126Z"
                                    stroke="currentColor"
                                    strokeOpacity="0.38"
                                    strokeWidth="2"
                                />
                            </svg>

                            <div className="relative z-10">
                                <Image
                                    src={heroImage}
                                    srcSet={data.image_srcset ?? undefined}
                                    alt={heroAlt}
                                    ratio="4/3"
                                    priority
                                    wrapperClassName="rounded-[var(--radius-image)] border-2 border-bg shadow-md"
                                />

                                {/* Calm Floating Operational Status Capsule */}
                                <div className="mt-4 flex items-center justify-between gap-3 rounded-full border border-line bg-bg px-5 py-3 shadow-sm">
                                    <div className="flex items-center gap-2.5">
                                        <span className="inline-flex h-8 w-8 items-center justify-center rounded-full bg-surface text-accent-strong">
                                            <CheckCircle2 className="h-4 w-4" aria-hidden="true" />
                                        </span>
                                        <span className="text-xs font-bold text-ink">
                                            {locale === 'en'
                                                ? 'Integrated Operational & Facility SLA'
                                                : 'Standar Layanan & Fasilitas Terpadu'}
                                        </span>
                                    </div>
                                    <span className="rounded-full bg-surface px-3 py-1 font-heading text-xs font-bold text-ink">
                                        24/7
                                    </span>
                                </div>
                            </div>
                        </div>
                    </Reveal>
                </div>
            </Container>
        </Section>
    );
}
