import type { SplitContentBlockData } from '@/core';
import { Button, Container, Heading, Image, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface SplitContentBlockProps {
    data: SplitContentBlockData;
}

export function SplitContentBlock({ data }: SplitContentBlockProps) {
    const isImageRight = data.image_position === 'right';

    return (
        <Section tone="default">
            <Container>
                <div className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                    <Reveal
                        preset="fadeUp"
                        className={[
                            'lg:col-span-6',
                            isImageRight ? 'lg:order-2' : 'lg:order-1',
                        ].join(' ')}
                    >
                        <Image
                            src={data.image}
                            srcSet={data.image_srcset}
                            alt={data.image_alt || data.title}
                            ratio="4/3"
                        />
                    </Reveal>

                    <Reveal
                        preset="fadeUp"
                        className={[
                            'lg:col-span-6 space-y-6',
                            isImageRight ? 'lg:order-1 lg:pr-6' : 'lg:order-2 lg:pl-6',
                        ].join(' ')}
                    >
                        {data.eyebrow ? (
                            <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine>
                        ) : null}
                        <Heading level={2} size="h2">
                            {data.title}
                        </Heading>
                        {data.lead ? (
                            <p className="max-w-[62ch] text-base font-semibold leading-7 text-ink">
                                {data.lead}
                            </p>
                        ) : null}
                        <p className="max-w-[62ch] text-sm leading-7 text-muted">{data.body}</p>
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
