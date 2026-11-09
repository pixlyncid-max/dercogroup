import type { HeroBlockData } from '@/core';
import { Button, Container, Heading, Image, Reveal } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface HeroBlockProps {
    data: HeroBlockData;
}

export function HeroBlock({ data }: HeroBlockProps) {
    const hasHeroImage = Boolean(data.image);

    if (!hasHeroImage) {
        return (
            <section className="bg-surface-dark text-ink-on-dark border-b border-ink-on-dark/10 py-14 md:py-20">
                <Container>
                    <Reveal preset="fadeUp" className="max-w-[720px] space-y-5">
                        {data.eyebrow ? (
                            <EyebrowLine tone="dark" withRule>
                                {data.eyebrow}
                            </EyebrowLine>
                        ) : null}
                        <Heading level={1} size="h2" className="text-ink-on-dark">
                            {data.title}
                        </Heading>
                        <p className="max-w-[62ch] text-base leading-7 text-muted-on-dark">
                            {data.description}
                        </p>
                    </Reveal>
                </Container>
            </section>
        );
    }

    return (
        <section className="relative bg-surface-dark text-ink-on-dark pt-10 pb-12 lg:pt-16 lg:pb-20 overflow-x-clip">
            <Container size="wide">
                <div className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-8">
                    <Reveal
                        preset="fadeUp"
                        className="relative z-[var(--z-index-overlay)] lg:col-span-6 space-y-6 lg:pr-6"
                    >
                        {data.eyebrow ? (
                            <EyebrowLine tone="dark" withRule={false}>
                                {data.eyebrow}
                            </EyebrowLine>
                        ) : null}

                        <Heading level={1} size="display" className="text-ink-on-dark">
                            {data.title}
                        </Heading>

                        <p className="max-w-[52ch] text-base leading-7 text-muted-on-dark">
                            {data.description}
                        </p>

                        {data.primary_cta_label && data.primary_cta_href ? (
                            <div className="flex flex-wrap items-center gap-4 pt-2">
                                <Button
                                    variant="solid"
                                    size="lg"
                                    href={data.primary_cta_href}
                                    className="text-xs font-bold uppercase tracking-[0.12em]"
                                >
                                    {data.primary_cta_label}
                                </Button>
                                {data.secondary_cta_label && data.secondary_cta_href ? (
                                    <Button
                                        variant="outline"
                                        size="lg"
                                        href={data.secondary_cta_href}
                                        className="text-xs font-bold uppercase tracking-[0.12em]"
                                    >
                                        {data.secondary_cta_label}
                                    </Button>
                                ) : null}
                            </div>
                        ) : null}
                    </Reveal>

                    <Reveal
                        preset="fadeIn"
                        className="relative z-[var(--z-index-sticky)] lg:col-span-6 lg:-mr-12 xl:-mr-20 lg:-mb-24"
                    >
                        <Image
                            src={data.image}
                            srcSet={data.image_srcset}
                            alt={data.image_alt || data.title}
                            ratio="4/3"
                            priority
                            sizes="(max-width: 1024px) 100vw, 55vw"
                            wrapperClassName="shadow-md border-0"
                        />
                    </Reveal>
                </div>
            </Container>
        </section>
    );
}
