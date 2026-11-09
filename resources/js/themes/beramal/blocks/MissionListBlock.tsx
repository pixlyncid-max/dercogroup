import { useLocale, type MissionListBlockData } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';

export interface MissionListBlockProps {
    data: MissionListBlockData;
}

/**
 * Beramal Vision-Mission & Group Governance ("Calm Flow", DESIGN.md Section 7.4).
 * Left: Dark Water rounded card with subtle SVG wave lines.
 * Right: Stack of rounded Mist capsules with numbered Cerulean badges.
 */
export function MissionListBlock({ data }: MissionListBlockProps) {
    const { locale } = useLocale();
    const items = data.items ?? [];

    return (
        <Section tone="default">
            <Container>
                <CalmHeader eyebrow={data.eyebrow} title={data.title} centered />

                <div className="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-stretch">
                    {/* Left 5 Cols: Dark Water Vision & Group Governance Capsule */}
                    <Reveal
                        preset="fadeUp"
                        className="relative flex flex-col justify-between overflow-hidden rounded-[var(--radius-card)] bg-surface-dark p-7 text-ink-on-dark shadow-sm sm:p-9 lg:col-span-5 space-y-6"
                    >
                        <svg
                            viewBox="0 0 600 220"
                            fill="none"
                            className="pointer-events-none absolute inset-x-0 bottom-0 h-32 w-full text-accent-on-dark/15"
                            aria-hidden="true"
                            preserveAspectRatio="none"
                        >
                            <path
                                d="M0,120 C160,60 340,180 600,90"
                                stroke="currentColor"
                                strokeWidth="1.5"
                            />
                            <path
                                d="M0,165 C200,115 390,210 600,140"
                                stroke="currentColor"
                                strokeWidth="1"
                            />
                        </svg>

                        {data.vision ? (
                            <div className="relative space-y-3">
                                <span className="inline-flex items-center rounded-full border border-accent-on-dark/30 bg-accent-on-dark/10 px-3.5 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-accent-on-dark">
                                    {locale === 'en' ? 'Corporate Vision' : 'Visi Perusahaan'}
                                </span>
                                <p className="font-heading text-xl font-medium leading-8 text-ink-on-dark">
                                    {data.vision}
                                </p>
                            </div>
                        ) : null}

                        {data.body ? (
                            <div className="relative border-t border-ink-on-dark/15 pt-5 space-y-2">
                                <p className="text-xs font-semibold uppercase tracking-[0.12em] text-accent-on-dark">
                                    {locale === 'en'
                                        ? 'Group Governance & Ownership'
                                        : 'Tata Kelola & Kepemilikan Grup'}
                                </p>
                                <p className="text-sm leading-6 text-muted-on-dark">{data.body}</p>
                            </div>
                        ) : null}
                    </Reveal>

                    {/* Right 7 Cols: Rounded Mist Mission Capsules */}
                    <div className="flex flex-col justify-between gap-4 lg:col-span-7">
                        {items.map((item, idx) => (
                            <Reveal
                                key={idx}
                                preset="fadeUp"
                                className="flex items-start gap-4 rounded-[var(--radius-card)] border border-line bg-surface p-6 transition-colors hover:border-accent/40 sm:p-7"
                            >
                                <span className="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent font-heading text-sm font-bold text-on-accent shadow-xs">
                                    {String(idx + 1).padStart(2, '0')}
                                </span>
                                <div className="space-y-1.5">
                                    <Heading level={3} size="h3" className="font-semibold text-ink">
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
