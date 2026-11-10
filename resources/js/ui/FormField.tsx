import type { ReactNode } from 'react';

export interface FormFieldProps {
    id: string;
    label: string;
    required?: boolean;
    error?: string;
    className?: string;
    children: ReactNode;
}

export function FormField({
    id,
    label,
    required = false,
    error,
    className = '',
    children,
}: FormFieldProps) {
    const errorId = `${id}-error`;

    return (
        <div className={['flex flex-col gap-2', className].filter(Boolean).join(' ')}>
            <label htmlFor={id} className="text-sm font-bold text-ink">
                {label}
                {required ? (
                    <span aria-hidden="true" className="ml-1 text-accent-strong">
                        *
                    </span>
                ) : null}
            </label>
            {children}
            {error ? (
                <p id={errorId} role="alert" className="text-xs font-normal text-accent-strong">
                    {error}
                </p>
            ) : null}
        </div>
    );
}
