import type { HTMLAttributes, ReactNode } from 'react';
import { Eyebrow, type EyebrowTone } from '@/ui';

export interface EyebrowLineProps extends HTMLAttributes<HTMLDivElement> {
    tone?: EyebrowTone;
    withRule?: boolean;
    children: ReactNode;
}

/**
 * Signature Holding eyebrow label: 12px uppercase tracking-[0.12em] with a 40x2px copper bar underneath (DESIGN.md 4.2 & 4.6).
 */
export function EyebrowLine({
    tone = 'light',
    withRule = true,
    className = '',
    children,
    ...rest
}: EyebrowLineProps) {
    return (
        <Eyebrow tone={tone} withRule={withRule} className={className} {...rest}>
            {children}
        </Eyebrow>
    );
}
