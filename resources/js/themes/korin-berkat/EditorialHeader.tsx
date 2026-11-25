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
        <div className="inline-flex items-center gap-2.5">
            {indexLabel ? (
                <span
                    className={`font-heading text-xs font-bold tracking-[0.12em] ${
                        isDark ? 'text-accent-on-dark' : 'text-accent-strong'
                    }`}
                >
                    {indexLabel}
                </span>
            ) : null}
            {eyebrow ? (
                <span
                    className={`text-xs font-bold uppercase tracking-[0.16em] ${
                        isDark ? 'text-accent-on-dark' : 'text-accent-strong'
                    }`}
                >
                    {eyebrow}
                </span>
            ) : null}
            <span
                aria-hidden="true"
                className={`inline-block h-[2px] w-8 rounded-full ${
                    isDark ? 'bg-accent-on-dark/80' : 'bg-accent'
                }`}
            />
        </div>
    );

    return (
        <SectionHeader
            eyebrow={eyebrowNode}
            accentNode={<GoldRule tone={tone} thick className="w-14" />}
            title={title}
            description={description}
            level={level}
            tone={tone}
            action={action}
        />
    );
}
