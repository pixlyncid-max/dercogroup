import type { InputHTMLAttributes } from 'react';

export interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    hasError?: boolean;
    errorId?: string;
}

export function Input({ hasError = false, errorId, className = '', ...rest }: InputProps) {
    const classes = [
        'min-h-[44px] w-full rounded-[var(--radius-button)] border bg-bg px-4 py-2.5 text-sm text-ink placeholder:text-muted/70 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-1',
        hasError ? 'border-accent-strong' : 'border-line',
        className,
    ]
        .filter(Boolean)
        .join(' ');

    return (
        <input
            aria-invalid={hasError ? 'true' : undefined}
            aria-describedby={hasError && errorId ? errorId : undefined}
            className={classes}
            {...rest}
        />
    );
}
