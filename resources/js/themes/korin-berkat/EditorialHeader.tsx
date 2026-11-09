import type { ReactNode } from 'react';
import { Heading, Reveal, type HeadingLevel } from '@/ui';
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

    return (
        <Reveal preset="fadeUp" className="space-y-5">
            <div className="flex flex-wrap items-center justify-between gap-4">
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
                {action ? <div>{action}</div> : null}
            </div>

            <GoldRule tone={tone} thick className="w-16" />

            <div className="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:items-end">
                <div className="lg:col-span-7">
                    <Heading
                        level={level}
                        size="h2"
                        className={isDark ? 'text-ink-on-dark' : 'text-ink'}
                    >
                        {title}
                    </Heading>
                </div>
                {description ? (
                    <div className="lg:col-span-5">
                        <p
                            className={`text-base leading-7 ${
                                isDark ? 'text-muted-on-dark' : 'text-muted'
                            }`}
                        >
                            {description}
                        </p>
                    </div>
                ) : null}
            </div>
        </Reveal>
    );
}
