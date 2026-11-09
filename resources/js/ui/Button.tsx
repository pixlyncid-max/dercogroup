import type { AnchorHTMLAttributes, ButtonHTMLAttributes, ReactNode } from 'react';

export type ButtonVariant = 'solid' | 'outline' | 'ghost' | 'inverse';
export type ButtonSize = 'md' | 'lg';

interface BaseButtonProps {
    variant?: ButtonVariant;
    size?: ButtonSize;
    className?: string;
    children: ReactNode;
}

export type ButtonAsButtonProps = BaseButtonProps &
    ButtonHTMLAttributes<HTMLButtonElement> & {
        href?: undefined;
    };

export type ButtonAsAnchorProps = BaseButtonProps &
    AnchorHTMLAttributes<HTMLAnchorElement> & {
        href: string;
    };

export type ButtonProps = ButtonAsButtonProps | ButtonAsAnchorProps;

const variantMap: Record<ButtonVariant, string> = {
    solid: 'bg-accent-strong text-on-accent hover:opacity-90 border border-transparent',
    outline: 'bg-transparent text-current border border-current hover:bg-surface/10',
    ghost: 'bg-transparent text-current hover:bg-surface/60 border border-transparent',
    inverse: 'bg-bg text-ink hover:opacity-90 border border-transparent',
};

const sizeMap: Record<ButtonSize, string> = {
    md: 'min-h-[44px] px-6 py-2.5 text-sm font-medium',
    lg: 'min-h-[48px] px-8 py-3.5 text-base font-medium',
};

const baseClasses =
    'inline-flex items-center justify-center gap-2 rounded-[var(--radius-button)] transition-opacity duration-[var(--duration-fast)] cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none';

export function Button(props: ButtonProps) {
    const { variant = 'solid', size = 'md', className = '', children } = props;
    const classes = [baseClasses, variantMap[variant], sizeMap[size], className]
        .filter(Boolean)
        .join(' ');

    if (typeof props.href === 'string') {
        const {
            href,
            variant: _variant,
            size: _size,
            className: _className,
            children: _children,
            ...anchorRest
        } = props;

        return (
            <a href={href} className={classes} {...anchorRest}>
                {children}
            </a>
        );
    }

    const {
        type = 'button',
        variant: _variant,
        size: _size,
        className: _className,
        children: _children,
        ...buttonRest
    } = props;

    return (
        <button type={type} className={classes} {...buttonRest}>
            {children}
        </button>
    );
}
