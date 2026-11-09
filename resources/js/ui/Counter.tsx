import { useEffect, useRef, useState } from 'react';
import { animate } from 'animejs';
import { useReducedMotion } from 'motion/react';

export interface CounterProps {
    value: number;
    prefix?: string | null;
    suffix?: string | null;
    locale?: string;
    className?: string;
}

export function Counter({
    value,
    prefix = '',
    suffix = '',
    locale = 'id-ID',
    className = '',
}: CounterProps) {
    const prefersReducedMotion = Boolean(useReducedMotion());
    const [displayValue, setDisplayValue] = useState<number>(value);
    const hasAnimatedRef = useRef(false);
    const spanRef = useRef<HTMLSpanElement | null>(null);

    useEffect(() => {
        if (prefersReducedMotion || typeof IntersectionObserver === 'undefined') {
            setDisplayValue(value);
            return;
        }

        const node = spanRef.current;
        if (!node || hasAnimatedRef.current) {
            return;
        }

        const observer = new IntersectionObserver(
            (entries) => {
                const entry = entries[0];
                if (!entry?.isIntersecting || hasAnimatedRef.current) {
                    return;
                }

                hasAnimatedRef.current = true;
                const state = { current: 0 };

                animate(state, {
                    current: value,
                    duration: 900,
                    ease: 'outCubic',
                    onUpdate: () => {
                        setDisplayValue(Math.round(state.current));
                    },
                });

                observer.disconnect();
            },
            { threshold: 0.25 },
        );

        observer.observe(node);

        return () => {
            observer.disconnect();
        };
    }, [prefersReducedMotion, value]);

    const formatted =
        value >= 1000 && value !== 2016 && value !== 2019 && value !== 2022 && value !== 2025
            ? new Intl.NumberFormat(locale).format(displayValue)
            : String(displayValue);

    return (
        <span ref={spanRef} className={['tabular-nums', className].filter(Boolean).join(' ')}>
            {prefix ?? ''}
            {formatted}
            {suffix ?? ''}
        </span>
    );
}
