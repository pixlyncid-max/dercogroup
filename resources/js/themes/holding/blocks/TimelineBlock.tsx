import type { TimelineBlockData } from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EyebrowLine } from '../EyebrowLine';

export interface TimelineBlockProps {
    data: TimelineBlockData;
}

export function TimelineBlock({ data }: TimelineBlockProps) {
    return (
        <Section tone="default">
            <Container>
                <div className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-12">
                    <Reveal preset="fadeUp" className="lg:col-span-5 space-y-4">
                        {data.eyebrow ? (
                            <EyebrowLine tone="light">{data.eyebrow}</EyebrowLine>
                        ) : null}
                        <Heading level={2} size="h2">
                            {data.title}
                        </Heading>
                        {data.description ? (
                            <p className="max-w-[50ch] text-base leading-7 text-muted">
                                {data.description}
                            </p>
                        ) : null}
                    </Reveal>

                    <div className="lg:col-span-7">
                        <ol className="relative border-l-2 border-line pl-6 md:pl-8 space-y-10">
                            {(data.items ?? []).map((item, idx) => (
                                <li key={`${item.period}-${idx}`} className="relative">
                                    <span
                                        aria-hidden="true"
                                        className="absolute -left-[31px] md:-left-[39px] top-1.5 h-3.5 w-3.5 rounded-full border-2 border-accent bg-bg"
                                    />
                                    <Reveal preset="fadeUp" className="space-y-2">
                                        <span className="inline-block rounded-[var(--radius-button)] bg-surface px-2.5 py-0.5 text-xs font-bold uppercase tracking-[0.12em] text-accent-strong">
                                            {item.period}
                                        </span>
                                        <Heading level={3} size="h3">
                                            {item.title}
                                        </Heading>
                                        <p className="max-w-[60ch] text-sm leading-6 text-muted">
                                            {item.description}
                                        </p>
                                    </Reveal>
                                </li>
                            ))}
                        </ol>
                    </div>
                </div>
            </Container>
        </Section>
    );
}
