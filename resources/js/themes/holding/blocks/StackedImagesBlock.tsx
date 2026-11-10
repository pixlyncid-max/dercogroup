import { usePage } from '@inertiajs/react';
import type { SharedPageProps, StackedImagesBlockData } from '@/core';
import { Button, Container, Heading, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';
import { StackedImages } from '../StackedImages';

export interface StackedImagesBlockProps {
    data: StackedImagesBlockData;
}

export function StackedImagesBlock({ data }: StackedImagesBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const isHome = props.page?.key === 'home';
    const isMirrored = Boolean(data.mirrored);

    return (
        <Section
            tone="default"
            className={
                isMirrored
                    ? ''
                    : 'relative lg:pt-8 before:pointer-events-none before:hidden lg:before:block before:absolute before:inset-x-0 before:top-0 before:h-24 before:bg-surface-dark'
            }
        >
            <Container className="relative z-[var(--z-index-sticky)]">
                <div className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                    <div className="lg:col-span-6">
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

                    <Reveal
                        preset="fadeUp"
                        className={['lg:col-span-6 space-y-6 lg:pl-6', isMirrored ? '' : 'lg:pt-20']
                            .filter(Boolean)
                            .join(' ')}
                    >
                        {isHome ? (
                            <h2 className="sr-only">{data.title}</h2>
                        ) : (
                            <>
                                {data.eyebrow ? (
                                    <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine>
                                ) : null}

                                <Heading level={2} size="h2">
                                    {data.title}
                                </Heading>
                            </>
                        )}

                        <p className="max-w-[60ch] text-base font-bold leading-7 text-ink">
                            {data.lead}
                        </p>

                        {data.body ? (
                            <p className="max-w-[62ch] text-sm leading-7 text-muted">{data.body}</p>
                        ) : null}

                        {data.cta_label && data.cta_href ? (
                            <div className="pt-2">
                                <Button
                                    variant="solid"
                                    size="md"
                                    href={data.cta_href}
                                    className="text-xs font-bold uppercase tracking-[0.12em]"
                                >
                                    {data.cta_label}
                                </Button>
                            </div>
                        ) : null}
                    </Reveal>
                </div>
            </Container>
        </Section>
    );
}
