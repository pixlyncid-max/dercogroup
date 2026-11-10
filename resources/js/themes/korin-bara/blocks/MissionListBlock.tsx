import { useLocale, type MissionListBlockData } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { FieldSectionHeader } from '../FieldSectionHeader';

export interface MissionListBlockProps {
    data: MissionListBlockData;
}

/**
 * Korin Bara Two-Column Vision & Mission ("Field Rail", DESIGN.md Section 6.4).
 */
export function MissionListBlock({ data }: MissionListBlockProps) {
    const { locale } = useLocale();
    const items = data.items ?? [];

    return (
        <Section tone="default" className="border-b-2 border-line">
            <Container>
                <FieldSectionHeader eyebrow={data.eyebrow} title={data.title} />

                <div className="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-stretch">
                    {/* Left Column: Vision on Brass Gold & Navy Panel */}
                    <Reveal
                        preset="fadeUp"
                        className="lg:col-span-5 flex flex-col justify-between border-2 border-ink bg-accent p-7 text-ink sm:p-9 space-y-6"
                    >
                        {data.vision ? (
                            <div className="space-y-3">
                                <p className="font-heading text-xs font-bold uppercase tracking-[0.16em] text-ink">
                                    {locale === 'en' ? 'CORPORATE VISION' : 'VISI PERUSAHAAN'}
                                </p>
                                <p className="font-heading text-2xl font-bold uppercase leading-8 text-ink">
                                    {data.vision}
                                </p>
                            </div>
                        ) : null}

                        {data.body ? (
                            <div className="border-t-2 border-ink pt-5 space-y-2">
                                <p className="font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink">
                                    {locale === 'en'
                                        ? 'GROUP MANDATE & OWNERSHIP'
                                        : 'MANDAT & KEPEMILIKAN GRUP'}
                                </p>
                                <p className="text-sm font-medium leading-6 text-ink">
                                    {data.body}
                                </p>
                            </div>
                        ) : null}
                    </Reveal>

                    {/* Right Column: Mission Items */}
                    <div className="lg:col-span-7 grid grid-cols-1 gap-4">
                        {items.map((item, idx) => (
                            <Reveal
                                key={idx}
                                preset="fadeUp"
                                className="flex items-start gap-4 border-2 border-line border-l-4 border-l-accent bg-surface p-6"
                            >
                                <span className="flex h-9 w-9 shrink-0 items-center justify-center bg-surface-dark font-heading text-sm font-bold text-accent-on-dark">
                                    {`0${idx + 1}`}
                                </span>
                                <div className="space-y-1.5">
                                    <Heading level={3} size="h3" className="text-ink">
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
