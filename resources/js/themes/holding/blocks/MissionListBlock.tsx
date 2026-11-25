import { usePage } from '@inertiajs/react';
import { motion, useReducedMotion } from 'motion/react';
import { useRef } from 'react';
import type { MissionListBlockData, SharedPageProps } from '@/core';
import { useGsapScrollFloat, useGsapStagger } from '@/motion/gsapLenis';
import { Button, Container, Heading, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';
import { HoldingIcon } from '../IconResolver';
import { StackedImages } from '../StackedImages';

export interface MissionListBlockProps {
    data: MissionListBlockData;
}

export function MissionListBlock({ data }: MissionListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const prefersReducedMotion = Boolean(useReducedMotion());
    const isHome = props.page?.key === 'home';

    const gridRef = useRef<HTMLDivElement | null>(null);
    const imagesFloatRef = useRef<HTMLDivElement | null>(null);
    const copyRef = useRef<HTMLDivElement | null>(null);

    useGsapScrollFloat(gridRef, imagesFloatRef, { fromY: 22, toY: -22, scrub: 1.15 });
    useGsapStagger(copyRef, { y: 24, stagger: 0.08, duration: 0.65 });

    return (
        <Section tone="surface" className="border-t border-line">
            <Container>
                <div
                    ref={gridRef}
                    className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12 lg:gap-12"
                >
                    <div ref={imagesFloatRef} className="lg:col-span-6 will-change-transform">
                        <StackedImages
                            primarySrc={data.primary_image}
                            primarySrcSet={data.primary_image_srcset}
                            primaryAlt={data.primary_image_alt || data.title}
                            secondarySrc={data.secondary_image}
                            secondarySrcSet={data.secondary_image_srcset}
                            secondaryAlt={data.secondary_image_alt || data.title}
                            mirrored
                        />
                    </div>

                    <div ref={copyRef} className="lg:col-span-6 space-y-6 lg:pl-6">
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

                        <ul className="space-y-4 pt-1">
                            {(data.items ?? []).map((item, idx) => (
                                <motion.li
                                    key={idx}
                                    data-gsap-item
                                    whileHover={prefersReducedMotion ? undefined : { x: 4 }}
                                    transition={{ type: 'spring', stiffness: 320, damping: 24 }}
                                    className="flex items-start gap-3.5"
                                >
                                    <span className="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-accent text-accent-strong">
                                        <HoldingIcon
                                            name={item.icon_name || 'check-circle-2'}
                                            className="h-3.5 w-3.5"
                                        />
                                    </span>
                                    <div className="space-y-1">
                                        <p className="text-sm font-bold leading-6 text-ink">
                                            {item.title}
                                        </p>
                                        {item.description ? (
                                            <p className="text-xs leading-5 text-muted">
                                                {item.description}
                                            </p>
                                        ) : null}
                                    </div>
                                </motion.li>
                            ))}
                        </ul>

                        {data.vision ? (
                            <p
                                data-gsap-item
                                className="max-w-[62ch] text-sm leading-7 text-muted pt-1"
                            >
                                {data.vision}
                            </p>
                        ) : null}

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
        </Section>
    );
}
