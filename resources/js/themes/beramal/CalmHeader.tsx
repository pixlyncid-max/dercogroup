import type { ReactNode } from 'react';
import { Heading, Reveal, type HeadingLevel } from '@/ui';

export interface CalmHeaderProps {
    eyebrow?: string;
    title: string;
    description?: string;
    level?: HeadingLevel;
    centered?: boolean;
    action?: ReactNode;
    pillTone?: 'surface' | 'bg';
}

/**
 * Beramal Sukses Sejahtera ("Calm Flow") section header with a soft rounded pill badge.
 */
export function CalmHeader({
    eyebrow,
    title,
    description,
    level = 2,
    centered = false,
    action,
    pillTone = 'surface',
}: CalmHeaderProps) {
    const pillBgClass = pillTone === 'bg' ? 'bg-bg' : 'bg-surface';

    if (centered) {
        return (
            <Reveal preset="fadeUp" className="mx-auto max-w-[680px] text-center space-y-4">
                {eyebrow ? (
                    <div
                        className={`inline-flex items-center gap-2 rounded-full border border-line ${pillBgClass} px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.1em] text-ink`}
                    >
                        <span aria-hidden="true" className="h-2 w-2 rounded-full bg-accent" />
                        <span>{eyebrow}</span>
                    </div>
                ) : null}

                <Heading level={level} size={level === 1 ? 'display' : 'h2'}>
                    {title}
                </Heading>

                {description ? (
                    <p className="mx-auto max-w-[62ch] text-base leading-7 text-muted">
                        {description}
                    </p>
                ) : null}

                {action ? <div className="pt-2 flex justify-center">{action}</div> : null}
            </Reveal>
        );
    }

    return (
        <Reveal
            preset="fadeUp"
            className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between"
        >
            <div className="max-w-[680px] space-y-4">
                {eyebrow ? (
                    <div
                        className={`inline-flex items-center gap-2 rounded-full border border-line ${pillBgClass} px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.1em] text-ink`}
                    >
                        <span aria-hidden="true" className="h-2 w-2 rounded-full bg-accent" />
                        <span>{eyebrow}</span>
                    </div>
                ) : null}

                <Heading level={level} size={level === 1 ? 'display' : 'h2'}>
                    {title}
                </Heading>

                {description ? (
                    <p className="max-w-[62ch] text-base leading-7 text-muted">{description}</p>
                ) : null}
            </div>

            {action ? <div className="shrink-0">{action}</div> : null}
        </Reveal>
    );
}
