import type { ReactNode } from 'react';
import { Heading, Reveal, type HeadingLevel } from '@/ui';

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

    return (
        <Reveal preset="fadeUp" className="space-y-4">
            <div className="flex flex-wrap items-center justify-between gap-4">
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
                {action ? <div>{action}</div> : null}
            </div>

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
