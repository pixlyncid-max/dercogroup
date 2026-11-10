import { useCallback, useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import { Image } from './Image';

export interface HeroSlide {
    image: string;
    image_srcset?: string | null;
    image_alt?: string | Record<string, string>;
}

export interface HeroCarouselProps {
    slides: HeroSlide[];
    className?: string;
    intervalMs?: number;
    overlayClassName?: string;
    locale?: string;
    reducedMotion?: boolean;
}

export function HeroCarousel({
    slides,
    className = '',
    intervalMs = 6000,
    overlayClassName = '',
    locale = 'id',
    reducedMotion,
}: HeroCarouselProps) {
    const prefersReducedMotionHook = Boolean(useReducedMotion());
    const prefersReducedMotion = reducedMotion ?? prefersReducedMotionHook;
    const [currentIndex, setCurrentIndex] = useState(0);
    const [isPaused, setIsPaused] = useState(false);
    const containerRef = useRef<HTMLDivElement>(null);

    const slideCount = slides.length;
    const hasMultipleSlides = slideCount > 1;

    const nextSlide = useCallback(() => {
        if (!hasMultipleSlides) {
            return;
        }
        setCurrentIndex((prev) => (prev + 1) % slideCount);
    }, [hasMultipleSlides, slideCount]);

    useEffect(() => {
        if (prefersReducedMotion || !hasMultipleSlides || isPaused) {
            return;
        }

        const timer = setInterval(() => {
            nextSlide();
        }, intervalMs);

        return () => {
            clearInterval(timer);
        };
    }, [prefersReducedMotion, hasMultipleSlides, isPaused, intervalMs, nextSlide]);

    if (slideCount === 0) {
        return null;
    }

    const currentSlide = slides[currentIndex] ?? slides[0];

    const resolveAlt = (alt?: string | Record<string, string>): string => {
        if (!alt) {
            return `Slide ${currentIndex + 1}`;
        }
        if (typeof alt === 'string') {
            return alt;
        }
        if (locale && alt[locale]) {
            return alt[locale];
        }
        return alt.id || alt.en || Object.values(alt)[0] || `Slide ${currentIndex + 1}`;
    };

    return (
        <div
            ref={containerRef}
            role="region"
            aria-roledescription="carousel"
            aria-label="Hero Image Carousel"
            onMouseEnter={() => setIsPaused(true)}
            onMouseLeave={() => setIsPaused(false)}
            onFocusCapture={() => setIsPaused(true)}
            onBlurCapture={(e) => {
                if (!e.currentTarget.contains(e.relatedTarget as Node)) {
                    setIsPaused(false);
                }
            }}
            className={['relative h-full w-full overflow-hidden', className]
                .filter(Boolean)
                .join(' ')}
        >
            <div className="absolute inset-0">
                <AnimatePresence initial={false} mode="sync">
                    <motion.div
                        key={currentIndex}
                        id={`hero-slide-${currentIndex}`}
                        role="tabpanel"
                        aria-label={`Slide ${currentIndex + 1} of ${slideCount}`}
                        initial={prefersReducedMotion ? { opacity: 1 } : { opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={prefersReducedMotion ? { opacity: 1 } : { opacity: 0 }}
                        transition={{
                            duration: prefersReducedMotion ? 0 : 0.8,
                            ease: 'easeInOut',
                        }}
                        className="absolute inset-0 h-full w-full"
                    >
                        <Image
                            src={currentSlide.image}
                            srcSet={currentSlide.image_srcset}
                            alt={resolveAlt(currentSlide.image_alt)}
                            priority={currentIndex === 0}
                            wrapperClassName="h-full w-full rounded-none border-0"
                            className="h-full w-full object-cover"
                        />
                    </motion.div>
                </AnimatePresence>
            </div>

            {/* Dark overlay ensuring WCAG AA contrast (≥ 4.5:1) for text on top */}
            <div
                aria-hidden="true"
                className={[
                    'pointer-events-none absolute inset-0 z-10 bg-gradient-to-r from-surface-deeper/90 via-surface-deeper/80 to-surface-deeper/65',
                    overlayClassName,
                ]
                    .filter(Boolean)
                    .join(' ')}
            />

            {/* Pagination dots (only rendered if more than 1 image) */}
            {hasMultipleSlides ? (
                <div
                    role="tablist"
                    aria-label="Hero carousel pagination"
                    className="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2.5"
                >
                    {slides.map((_, idx) => {
                        const isActive = idx === currentIndex;

                        return (
                            <button
                                key={idx}
                                type="button"
                                role="tab"
                                aria-selected={isActive}
                                aria-controls={`hero-slide-${idx}`}
                                onClick={() => setCurrentIndex(idx)}
                                aria-label={`Slide ${idx + 1}`}
                                className={[
                                    'h-3 rounded-full transition-all duration-[var(--duration-base)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2',
                                    isActive
                                        ? 'w-8 bg-accent'
                                        : 'w-3 bg-ink-on-dark/40 hover:bg-ink-on-dark/70',
                                ].join(' ')}
                            />
                        );
                    })}
                </div>
            ) : null}
        </div>
    );
}
