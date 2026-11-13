import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import type { SplitContentBlockData } from '@/core';
import { Container, Image, Reveal } from '@/ui';

export interface SplitContentBlockProps {
    data: SplitContentBlockData;
    isFirstBlock?: boolean;
}

export function SplitContentBlock({ data }: SplitContentBlockProps) {
    const splitImage = data.image || '/images/placeholders/aerial-mine-1280.webp';
    const isImageLeft = data.image_position === 'left';
    const hasOffsetBorder = !isImageLeft; // Offset border on right-side photo (Tentang Kami)

    const paragraphs = data.body ? data.body.split('\n\n').filter(Boolean) : [];

    return (
        <section
            data-testid="korin-bara-split-content"
            className="py-16 lg:py-24 bg-white border-b border-line overflow-hidden"
        >
            <Container>
                <div className="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-16">
                    {/* Image Column */}
                    <Reveal
                        preset="fadeIn"
                        className={`lg:col-span-6 ${isImageLeft ? 'lg:order-1' : 'lg:order-2'}`}
                    >
                        <div className={`relative ${hasOffsetBorder ? 'kb-offset-border' : ''}`}>
                            <div className="relative z-10 overflow-hidden rounded-xl shadow-md">
                                <Image
                                    src={splitImage}
                                    srcSet={data.image_srcset ?? undefined}
                                    alt={data.image_alt || data.title}
                                    ratio="4/3"
                                    wrapperClassName="border-0 rounded-none h-full w-full"
                                    className="h-full w-full object-cover"
                                />
                            </div>
                        </div>
                    </Reveal>

                    {/* Text Column */}
                    <Reveal
                        preset="fadeUp"
                        className={`space-y-6 lg:col-span-6 ${isImageLeft ? 'lg:order-2' : 'lg:order-1'}`}
                    >
                        {data.eyebrow ? (
                            <div>
                                <span className="kb-eyebrow">{data.eyebrow}</span>
                            </div>
                        ) : null}

                        <h2 className="font-heading text-3xl font-bold tracking-tight text-[#101F38] sm:text-4xl">
                            {data.title}
                        </h2>

                        <div className="space-y-4 text-base leading-relaxed text-slate-600 sm:text-lg">
                            {paragraphs.length > 0 ? (
                                paragraphs.map((p, idx) => <p key={idx}>{p}</p>)
                            ) : (
                                <p>{data.body}</p>
                            )}
                        </div>

                        {data.cta_label && data.cta_href ? (
                            <div className="pt-2">
                                <Link
                                    href={data.cta_href}
                                    className="kb-btn-solid min-h-[44px]"
                                >
                                    <span>{data.cta_label}</span>
                                    <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                </Link>
                            </div>
                        ) : null}
                    </Reveal>
                </div>
            </Container>
        </section>
    );
}
