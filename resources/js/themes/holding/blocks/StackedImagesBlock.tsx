import { usePage } from '@inertiajs/react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import type { SharedPageProps, StackedImagesBlockData } from '@/core';
import { useGsapScrollFloat, useGsapStagger } from '@/motion/gsapLenis';
import { Button, Container, Heading, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';
import { StackedImages } from '../StackedImages';

export interface StackedImagesBlockProps {
    data: StackedImagesBlockData;
}

export function StackedImagesBlock({ data }: StackedImagesBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const isHome = props.page?.key === 'home';
    const isMirrored = Boolean(data.mirrored);

    const gridRef = useRef<HTMLDivElement | null>(null);
    const imagesFloatRef = useRef<HTMLDivElement | null>(null);
    const copyRef = useRef<HTMLDivElement | null>(null);

    useGsapScrollFloat(gridRef, imagesFloatRef, { fromY: 22, toY: -22, scrub: 1.15 });
    useGsapStagger(copyRef, { y: 26, stagger: 0.09, duration: 0.7 });

    const showDarkTopBand = isHome && !isMirrored;

    return (
        <Section
            tone="default"
            className={
                showDarkTopBand
                    ? 'relative lg:pt-8 before:pointer-events-none before:hidden lg:before:block before:absolute before:inset-x-0 before:top-0 before:h-24 before:bg-surface-dark'
                    : ''
}
        >
    <Container className="relative z-[var(--z-index-sticky)]">
        <div
            ref={gridRef}
            className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12"
        >
            <div ref={imagesFloatRef} className="lg:col-span-6 will-change-transform">
                <StackedImages
                    primarySrc={data.primary_image}
                    primarySrcSet={data.primary_image_srcset}
                    primaryAlt={data.primary_image_alt || data.title}
                    secondarySrc={data.secondary_image}
                    secondarySrcSet={data.secondary_image_srcset}
                    secondaryAlt={data.secondary_image_alt || data.title}
                    mirrored={isMirrored}
                />
            </div>

            <div
                ref={copyRef}
                        className={[
                    'lg:col-span-6 space-y-6 lg:pl-6',
                    showDarkTopBand ? 'lg:pt-20' : '',
                ]
                    .filter(Boolean)
                    .join(' ')}
            >
                {isHome ? (
                    <h2 className="sr-only">{data.title}</h2>
                ) : (
                    <>
                        {data.eyebrow ? (
                            <div data-gsap-item>
                                <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine>
                            </div>
                        ) : null}

                        <div data-gsap-item>
                            <Heading level={2} size="h2">
                                {data.title}
                            </Heading>
                        </div>
                    </>
                )}

                <p
                    data-gsap-item
                    className="max-w-[60ch] text-base font-bold leading-7 text-ink"
                >
                    {data.lead}
                </p>

                {data.body ? (
                    <p
                        data-gsap-item
                        className="max-w-[62ch] text-sm leading-7 text-muted"
                    >
                        {data.body}
                    </p>
                ) : null}

                {data.cta_label && data.cta_href ? (
                    <motion.div
                        data-gsap-item
                        whileHover={prefersReducedMotion ? undefined : { y: -2 }}
                        transition={{ type: 'spring', stiffness: 320, damping: 22 }}
                        className="inline-block pt-2"
                    >
                        <Button
                            variant="solid"
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
    </Container>
        </Section >
    );
}
