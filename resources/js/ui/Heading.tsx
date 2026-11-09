import type { HTMLAttributes, ReactNode } from 'react';

export type HeadingLevel = 1 | 2 | 3 | 4;
export type HeadingSize = 'display' | 'h2' | 'h3' | 'h4';

export interface HeadingProps extends HTMLAttributes<HTMLHeadingElement> {
    level?: HeadingLevel;
    size?: HeadingSize;
    children: ReactNode;
}

const sizeMap: Record<HeadingSize, string> = {
    display: 'text-[40px] leading-[44px] lg:text-[64px] lg:leading-[68px] font-bold tracking-tight',
    h2: 'text-[30px] leading-[36px] lg:text-[40px] lg:leading-[46px] font-bold tracking-tight',
    h3: 'text-[20px] leading-[28px] lg:text-[24px] lg:leading-[32px] font-bold',
    h4: 'text-[18px] leading-[26px] lg:text-[20px] lg:leading-[28px] font-semibold',
};

const defaultSizeByLevel: Record<HeadingLevel, HeadingSize> = {
    1: 'display',
    2: 'h2',
    3: 'h3',
    4: 'h4',
};

export function Heading({ level = 2, size, className = '', children, ...rest }: HeadingProps) {
    const Tag = `h${level}` as const;
    const resolvedSize = size ?? defaultSizeByLevel[level];
    const classes = ['font-heading text-balance', sizeMap[resolvedSize], className]
        .filter(Boolean)
        .join(' ');

    return (
        <Tag className={classes} {...rest}>
            {children}
        </Tag>
    );
}
