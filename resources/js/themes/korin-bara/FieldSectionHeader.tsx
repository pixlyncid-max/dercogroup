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
    const eyebrowNode = eyebrow ? (
        <div>
            <span className="kb-eyebrow">{eyebrow}</span>
        </div>
    ) : null;

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
