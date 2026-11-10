import { usePage } from '@inertiajs/react';
import type { MissionListBlockData, SharedPageProps } from '@/core';
import { Button, Container, Heading, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';
import { HoldingIcon } from '../IconResolver';
import { StackedImages } from '../StackedImages';

export interface MissionListBlockProps {
    data: MissionListBlockData;
}

export function MissionListBlock({ data }: MissionListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const isHome = props.page?.key === 'home';

    return (
        <Section tone="default">
            <Container>
                <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12 lg:gap-12">
                    <div className="lg:col-span-6">
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

                    <Reveal preset="fadeUp" className="lg:col-span-6 space-y-6 lg:pl-6">
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

                        <ul className="space-y-4 pt-1">
                            {(data.items ?? []).map((item, idx) => (
                                <li key={idx} className="flex items-start gap-3.5">
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
                                </li>
                            ))}
                        </ul>

                        {data.vision ? (
                            <p className="max-w-[62ch] text-sm leading-7 text-muted pt-1">
                                {data.vision}
                            </p>
                        ) : null}

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
