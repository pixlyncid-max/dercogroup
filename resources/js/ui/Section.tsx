import type { ElementType, HTMLAttributes, ReactNode } from 'react';

export type SectionTone = 'default' | 'surface' | 'dark' | 'deeper' | 'accent';

export interface SectionProps extends HTMLAttributes<HTMLElement> {
    as?: ElementType;
    tone?: SectionTone;
    children: ReactNode;
}

const toneMap: Record<SectionTone, string> = {
    default: 'bg-bg text-ink',
    surface: 'bg-surface text-ink',
    dark: 'bg-surface-dark text-ink-on-dark',
    deeper: 'bg-surface-deeper text-ink-on-dark',
    accent: 'bg-accent text-on-accent',
};

export function Section({
    as: Component = 'section',
    tone = 'default',
    className = '',
    children,
    ...rest
}: SectionProps) {
    const classes = ['py-12 md:py-16 lg:py-24', toneMap[tone], className].filter(Boolean).join(' ');

    return (
        <Component className={classes} {...rest}>
            {children}
        </Component>
    );
}
