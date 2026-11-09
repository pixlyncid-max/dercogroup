import { useLocale, type MissionListBlockData } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';
import { GoldRule } from '../GoldRule';

export interface MissionListBlockProps {
    data: MissionListBlockData;
}

/**
 * Korin Berkat Two-Column Vision-Mission + Ownership Structure ("Editorial Index", DESIGN.md Section 5.4).
 */
export function MissionListBlock({ data }: MissionListBlockProps) {
    const { locale } = useLocale();
    const items = data.items ?? [];

    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                <EditorialHeader eyebrow={data.eyebrow} title={data.title} />

                <div className="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-stretch">
                    {/* Left 5 Cols: Vision & Parent Group Ownership Panel */}
                    <Reveal
                        preset="fadeUp"
                        className="lg:col-span-5 flex flex-col justify-between rounded-[var(--radius-card)] bg-surface-dark p-7 text-ink-on-dark border-t-4 border-accent sm:p-8 space-y-6"
                    >
                        {data.vision ? (
                            <div className="space-y-3">
                                <p className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                    {locale === 'en' ? 'CORPORATE VISION' : 'VISI PERUSAHAAN'}
                                </p>
                                <GoldRule tone="dark" className="w-12" />
                                <p className="font-heading text-xl font-medium leading-8 text-ink-on-dark">
                                    {data.vision}
                                </p>
                            </div>
                        ) : null}

                        {data.body ? (
                            <div className="border-t border-ink-on-dark/15 pt-5 space-y-2">
                                <p className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                    {locale === 'en'
                                        ? 'GROUP GOVERNANCE & OWNERSHIP'
                                        : 'TATA KELOLA & KEPEMILIKAN GRUP'}
                                </p>
                                <p className="text-sm leading-6 text-muted-on-dark">{data.body}</p>
                            </div>
                        ) : null}
                    </Reveal>

                    {/* Right 7 Cols: Numbered Editorial Mission Index */}
                    <div className="lg:col-span-7 divide-y divide-line border-y border-line">
                        {items.map((item, idx) => (
                            <Reveal
                                key={idx}
                                preset="fadeUp"
                                className="grid grid-cols-1 gap-3 py-6 sm:grid-cols-12 sm:gap-6"
                            >
                                <div className="sm:col-span-2">
                                    <span className="font-heading text-xl font-semibold text-accent-strong">
                                        {String(idx + 1).padStart(2, '0')}
                                    </span>
                                </div>
                                <div className="sm:col-span-10 space-y-1.5">
                                    <Heading level={3} size="h3" className="font-semibold">
                                        {item.title}
                                    </Heading>
                                    {item.description ? (
                                        <p className="text-sm leading-6 text-muted">
                                            {item.description}
                                        </p>
                                    ) : null}
                                </div>
                            </Reveal>
                        ))}
                    </div>
                </div>
            </Container>
        </Section>
    );
}
