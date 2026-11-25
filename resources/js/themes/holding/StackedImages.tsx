import { LayoutGroup, motion, useReducedMotion } from 'motion/react';
import { useId, useState } from 'react';
import { Image } from '@/ui';

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
 * Signature Holding two-photo offset stack (DESIGN.md 4.4 & 4.6)
 * with smooth physical position & size swap on click (Motion layout FLIP spring).
 * Clicking either image smoothly swaps their positions, sizes, and depth layers.
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
    const [swapped, setSwapped] = useState(false);
    const prefersReducedMotion = Boolean(useReducedMotion());
    const groupId = useId();

    const layoutTransition = prefersReducedMotion
        ? { duration: 0 }
        : {
              type: 'spring' as const,
              stiffness: 220,
              damping: 26,
              mass: 0.9,
          };

    const handleSwap = () => {
        setSwapped((prev) => !prev);
    };

    const backSlotClasses = [
        'absolute top-0 z-10 w-[86%]',
        mirrored ? 'right-0' : 'left-0',
    ].join(' ');

    const frontSlotClasses = [
        'absolute bottom-0 z-20 w-[62%]',
        mirrored ? 'left-0 md:left-4' : 'right-0 md:right-4',
    ].join(' ');

    const photos = [
        {
            id: 'primary',
            src: primarySrc,
            srcSet: primarySrcSet,
            alt: primaryAlt,
            isFront: swapped,
        },
        {
            id: 'secondary',
            src: secondarySrc,
            srcSet: secondarySrcSet,
            alt: secondaryAlt,
            isFront: !swapped,
        },
    ];

    return (
        <LayoutGroup id={groupId}>
            <div className="relative w-full aspect-[4/3.65] sm:aspect-[4/3.55]">
                {photos.map((photo) => (
                    <motion.button
                        key={photo.id}
                        type="button"
                        layout
                        onClick={handleSwap}
                        aria-pressed={photo.isFront}
                        aria-label={photo.alt}
                        transition={{ layout: layoutTransition }}
                        whileHover={
                            prefersReducedMotion
                                ? undefined
                                : { scale: photo.isFront ? 1.02 : 1.015 }
                        }
                        whileTap={prefersReducedMotion ? undefined : { scale: 0.985 }}
                        className={[
                            'group block cursor-pointer rounded-[var(--radius-card)] text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-4',
                            photo.isFront ? frontSlotClasses : backSlotClasses,
                        ].join(' ')}
                    >
                        <Image
                            src={photo.src}
                            srcSet={photo.srcSet}
                            alt={photo.alt}
                            ratio="4/3"
                            wrapperClassName={[
                                'transition-[border-color,box-shadow] duration-300',
                                photo.isFront
                                    ? 'border-4 border-bg shadow-2xl'
                                    : 'border-0 shadow-md',
                            ].join(' ')}
                        />
                    </motion.button>
                ))}
            </div>
        </LayoutGroup>
    );
}
