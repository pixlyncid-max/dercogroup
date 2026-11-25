import { motion, useReducedMotion } from 'motion/react';
import { useState } from 'react';
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
 * Signature Holding two-photo offset stack (DESIGN.md 4.4 & 4.6)
 * with smooth interactive depth-swap on click (Motion spring physics).
 * Clicking the back photo brings it smoothly to the front; clicking the front photo
 * sends it smoothly to the back.
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
    const [primaryInFront, setPrimaryInFront] = useState(false);
    const prefersReducedMotion = Boolean(useReducedMotion());

    const springTransition = prefersReducedMotion
        ? { duration: 0 }
        : {
              type: 'spring' as const,
              stiffness: 260,
              damping: 26,
              mass: 0.85,
          };

    const handleSwap = () => {
        setPrimaryInFront((prev) => !prev);
    };

    const horizontalShift = mirrored ? -10 : 10;

    return (
        <div className="relative pb-12 md:pb-16">
            {/* Primary (Top/Large) Photo */}
            <Reveal
                preset="fadeUp"
                className={[
                    'relative',
                    mirrored ? 'w-[86%] ml-auto' : 'w-[86%] mr-auto',
                    primaryInFront ? 'z-20' : 'z-10',
                ].join(' ')}
            >
                <motion.button
                    type="button"
                    onClick={handleSwap}
                    aria-pressed={primaryInFront}
                    aria-label={primaryAlt}
                    animate={{
                        scale: primaryInFront ? 1.025 : 0.985,
                        x: primaryInFront ? horizontalShift : 0,
                        y: primaryInFront ? 8 : 0,
                        opacity: primaryInFront ? 1 : 0.94,
                    }}
                    whileHover={
                        prefersReducedMotion
                            ? undefined
                            : { scale: primaryInFront ? 1.03 : 1.0, opacity: 1 }
                    }
                    whileTap={prefersReducedMotion ? undefined : { scale: 0.98 }}
                    transition={springTransition}
                    className={[
                        'group block w-full cursor-pointer rounded-[var(--radius-card)] text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-4',
                        primaryInFront ? 'shadow-2xl' : 'shadow-md',
                    ].join(' ')}
                >
                    <Image
                        src={primarySrc}
                        srcSet={primarySrcSet}
                        alt={primaryAlt}
                        ratio="4/3"
                        wrapperClassName={[
                            'transition-[border-color,box-shadow] duration-300',
                            primaryInFront
                                ? 'border-4 border-bg shadow-2xl'
                                : 'border-4 border-transparent shadow-md',
                        ].join(' ')}
                    />
                </motion.button>
            </Reveal>

            {/* Secondary (Bottom/Offset) Photo */}
            <Reveal
                preset={mirrored ? 'slideInLeft' : 'slideInRight'}
                className={[
                    'relative -mt-12 md:-mt-20 w-[62%]',
                    mirrored ? 'mr-auto ml-0 md:ml-4' : 'ml-auto mr-0 md:mr-4',
                    primaryInFront ? 'z-10' : 'z-20',
                ].join(' ')}
            >
                <motion.button
                    type="button"
                    onClick={handleSwap}
                    aria-pressed={!primaryInFront}
                    aria-label={secondaryAlt}
                    animate={{
                        scale: primaryInFront ? 0.95 : 1.01,
                        x: primaryInFront ? -horizontalShift : 0,
                        y: primaryInFront ? -8 : 0,
                        opacity: primaryInFront ? 0.9 : 1,
                    }}
                    whileHover={
                        prefersReducedMotion
                            ? undefined
                            : { scale: primaryInFront ? 0.98 : 1.025, opacity: 1 }
                    }
                    whileTap={prefersReducedMotion ? undefined : { scale: 0.97 }}
                    transition={springTransition}
                    className={[
                        'group block w-full cursor-pointer rounded-[var(--radius-card)] text-left focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-4',
                        primaryInFront ? 'shadow-md' : 'shadow-2xl',
                    ].join(' ')}
                >
                    <Image
                        src={secondarySrc}
                        srcSet={secondarySrcSet}
                        alt={secondaryAlt}
                        ratio="4/3"
                        wrapperClassName={[
                            'transition-[border-color,box-shadow] duration-300',
                            primaryInFront
                                ? 'border-4 border-bg/80 shadow-md'
                                : 'border-4 border-bg shadow-2xl',
                        ].join(' ')}
                    />
                </motion.button>
            </Reveal>
        </div>
    );
}
