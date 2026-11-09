import type { TextareaHTMLAttributes } from 'react';

export interface TextareaProps extends TextareaHTMLAttributes<HTMLTextAreaElement> {
    hasError?: boolean;
    errorId?: string;
}

export function Textarea({ hasError = false, errorId, className = '', ...rest }: TextareaProps) {
    const classes = [
        'min-h-[132px] w-full rounded-[var(--radius-button)] border bg-bg px-4 py-3 text-sm leading-6 text-ink placeholder:text-muted/70 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-1',
        hasError ? 'border-accent-strong' : 'border-line',
        className,
    ]
        .filter(Boolean)
        .join(' ');

    return (
        <textarea
            aria-invalid={hasError ? 'true' : undefined}
            aria-describedby={hasError && errorId ? errorId : undefined}
            className={classes}
            {...rest}
        />
    );
}
