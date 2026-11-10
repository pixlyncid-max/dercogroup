import type { HTMLAttributes, ReactNode } from 'react';

export type EyebrowTone = 'light' | 'dark';

export interface EyebrowProps extends HTMLAttributes<HTMLDivElement> {
    tone?: EyebrowTone;
    withRule?: boolean;
    children: ReactNode;
}

const toneMap: Record<EyebrowTone, string> = {
    light: 'text-accent-strong',
    dark: 'text-accent-on-dark',
};

export function Eyebrow({
    tone = 'light',
    withRule = true,
    className = '',
    children,
    ...rest
}: EyebrowProps) {
    const classes = ['inline-flex flex-col gap-2', className].filter(Boolean).join(' ');

    return (
        <div className={classes} {...rest}>
            <span
                className={['text-xs font-bold uppercase tracking-[0.12em]', toneMap[tone]].join(
                    ' ',
                )}
            >
                {children}
            </span>
            {withRule ? <span aria-hidden="true" className="h-[2px] w-10 bg-accent" /> : null}
        </div>
    );
}
