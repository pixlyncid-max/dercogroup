import { Image, Reveal } from '@/ui';

export interface StackedImagesProps {
    primarySrc?: string | null;
    primarySrcSet?: string | null;
    primaryAlt: string;
    secondarySrc?: string | null;
    secondarySrcSet?: string | null;
    secondaryAlt: string;
    mirrored?: boolean;
}

/**
 * Signature Holding two-photo offset stack (DESIGN.md 4.4 & 4.6).
 * Default (Section 3): large top-right image, smaller bottom-left image overlapping by 48px.
 * Mirrored (Section 5): large top-left image, smaller bottom-right image overlapping by 48px.
 */
export function StackedImages({
    primarySrc,
    primarySrcSet,
    primaryAlt,
    secondarySrc,
    secondarySrcSet,
    secondaryAlt,
    mirrored = false,
}: StackedImagesProps) {
    return (
        <div className="relative pb-12 md:pb-16">
            <Reveal preset="fadeUp" className={mirrored ? 'w-[86%] ml-auto' : 'w-[86%] mr-auto'}>
                <Image
                    src={primarySrc}
                    srcSet={primarySrcSet}
                    alt={primaryAlt}
                    ratio="4/3"
                    wrapperClassName="shadow-md border-0"
                />
            </Reveal>

            <Reveal
                preset={mirrored ? 'slideInLeft' : 'slideInRight'}
                className={[
                    'relative z-[var(--z-index-sticky)] -mt-12 md:-mt-20 w-[62%]',
                    mirrored ? 'mr-auto ml-0 md:ml-4' : 'ml-auto mr-0 md:mr-4',
                ].join(' ')}
            >
                <Image
                    src={secondarySrc}
                    srcSet={secondarySrcSet}
                    alt={secondaryAlt}
                    ratio="4/3"
                    wrapperClassName="shadow-md border-4 border-bg"
                />
            </Reveal>
        </div>
    );
}
