import type { ReactNode } from 'react';
import { Heading, type HeadingLevel } from './Heading';
import { Reveal } from './Reveal';

export interface SectionHeaderProps {
    eyebrow?: ReactNode;
    accentNode?: ReactNode;
    title: string;
    description?: string | null;
    level?: HeadingLevel;
    tone?: 'light' | 'dark';
    action?: ReactNode;
    className?: string;
}

/**
 * Shared responsive 12-column section header layout (PRD 7.4 & DESIGN.md 1.1).
 * Title in 7-column span, description in 5-column span, with optional eyebrow & decorative accent node.
 */
export function SectionHeader({
    eyebrow,
    accentNode,
    title,
    description,
    level = 2,
    tone = 'light',
    action,
    className = '',
}: SectionHeaderProps) {
    const isDark = tone === 'dark';

    return (
        <Reveal preset="fadeUp" className={['space-y-4', className].filter(Boolean).join(' ')}>
            {eyebrow || action ? (
                <div className="flex flex-wrap items-center justify-between gap-4">
                    {eyebrow ? <div>{eyebrow}</div> : <div />}
                    {action ? <div>{action}</div> : null}
                </div>
            ) : null}

            {accentNode ? <div>{accentNode}</div> : null}

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
                            className={[
                                'text-base leading-7',
                                isDark ? 'text-muted-on-dark' : 'text-muted',
                            ].join(' ')}
                        >
                            {description}
                        </p>
                    </div>
                ) : null}
            </div>
        </Reveal>
    );
}

