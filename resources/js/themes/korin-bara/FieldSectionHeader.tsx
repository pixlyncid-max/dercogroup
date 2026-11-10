import type { ReactNode } from 'react';
import { SectionHeader, type HeadingLevel } from '@/ui';

export interface FieldSectionHeaderProps {
    eyebrow?: string;
    title: string;
    description?: string;
    level?: HeadingLevel;
    tone?: 'light' | 'dark';
    action?: ReactNode;
}

export function FieldSectionHeader({
    eyebrow,
    title,
    description,
    level = 2,
    tone = 'light',
    action,
}: FieldSectionHeaderProps) {
    const isDark = tone === 'dark';

    const eyebrowNode = (
        <div className="flex items-center gap-3">
            <span aria-hidden="true" className="h-1.5 w-10 bg-accent" />
            {eyebrow ? (
                <span
                    className={`font-heading text-sm font-bold uppercase tracking-[0.14em] ${
                        isDark ? 'text-accent-on-dark' : 'text-accent-strong'
                    }`}
                >
                    {eyebrow}
                </span>
            ) : null}
        </div>
    );

    return (
        <SectionHeader
            eyebrow={eyebrowNode}
            title={title}
            description={description}
            level={level}
            tone={tone}
            action={action}
        />
    );
}
