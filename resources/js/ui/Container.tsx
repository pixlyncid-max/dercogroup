import type { ElementType, HTMLAttributes, ReactNode } from 'react';

export type ContainerSize = 'narrow' | 'default' | 'wide';

export interface ContainerProps extends HTMLAttributes<HTMLElement> {
    as?: ElementType;
    size?: ContainerSize;
    children: ReactNode;
}

const sizeMap: Record<ContainerSize, string> = {
    narrow: 'max-w-[var(--container-narrow)]',
    default: 'max-w-[var(--container-default)]',
    wide: 'max-w-[var(--container-wide)]',
};

export function Container({
    as: Component = 'div',
    size = 'default',
    className = '',
    children,
    ...rest
}: ContainerProps) {
    const classes = ['mx-auto w-full px-6 md:px-10 lg:px-12', sizeMap[size], className]
        .filter(Boolean)
        .join(' ');

    return (
        <Component className={classes} {...rest}>
            {children}
        </Component>
    );
}
