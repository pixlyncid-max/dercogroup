import type { ReactNode } from 'react';
import { SectionHeader, type HeadingLevel } from '@/ui';
import { GoldRule } from './GoldRule';

export interface EditorialHeaderProps {
    indexLabel?: string;
    eyebrow?: string;
    title: string;
    description?: string;
    level?: HeadingLevel;
    tone?: 'light' | 'dark';
    action?: ReactNode;
}

export function EditorialHeader({
    indexLabel,
    eyebrow,
    title,
    description,
    level = 2,
    tone = 'light',
    action,
}: EditorialHeaderProps) {
    const isDark = tone === 'dark';

    const eyebrowNode = (
        <div className="flex items-center gap-3">
            {indexLabel ? (
                <span
                    className={`font-heading text-sm font-semibold tracking-[0.08em] ${
                        isDark ? 'text-accent-on-dark' : 'text-ink'
                    }`}
                >
                    {indexLabel}
                </span>
            ) : null}
            {eyebrow ? (
                <span
                    className={`text-xs font-semibold uppercase tracking-[0.14em] ${
                        isDark ? 'text-accent-on-dark' : 'text-ink'
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
            accentNode={<GoldRule tone={tone} thick className="w-16" />}
            title={title}
            description={description}
            level={level}
            tone={tone}
            action={action}
        />
    );
}
