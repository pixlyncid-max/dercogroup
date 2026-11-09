import type { HTMLAttributes, ReactNode } from 'react';

export interface ProseProps extends HTMLAttributes<HTMLDivElement> {
    children?: ReactNode;
    html?: string;
}

export function Prose({ html, className = '', children, ...rest }: ProseProps) {
    const classes = [
        'max-w-[65ch] space-y-4 text-base leading-7 text-muted [&_h2]:font-heading [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-ink [&_h3]:font-heading [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-ink [&_strong]:font-semibold [&_strong]:text-ink [&_ul]:list-disc [&_ul]:pl-5',
        className,
    ]
        .filter(Boolean)
        .join(' ');

    if (typeof html === 'string') {
        return <div className={classes} dangerouslySetInnerHTML={{ __html: html }} {...rest} />;
    }

    return (
        <div className={classes} {...rest}>
            {children}
        </div>
    );
}
