import { Eye, Target } from 'lucide-react';
import { useLocale, type MissionListBlockData } from '@/core';
import { Container, Reveal } from '@/ui';

export interface MissionListBlockProps {
    data: MissionListBlockData;
}

export function MissionListBlock({ data }: MissionListBlockProps) {
    const { t } = useLocale();
    const items = data.items ?? [];

    const visionQuote =
        data.vision ||
        (data as { vision_quote?: string }).vision_quote ||
        '"Menjadi perusahaan tambang batubara terkemuka di Indonesia yang berkelanjutan." [PLACEHOLDER]';

    return (
        <section
            data-testid="korin-bara-mission-list"
            className="py-16 lg:py-24 bg-white border-b border-line"
        >
            <Container>
                <div className="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-16 lg:divide-x lg:divide-line">
                    {/* Vision Column */}
                    <Reveal preset="fadeUp" className="space-y-6">
                        <div className="flex items-center gap-4">
                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#8A6212] text-white shadow-xs">
                                <Eye className="h-6 w-6 stroke-[2]" aria-hidden="true" />
                            </div>
                            <h2 className="font-heading text-2xl font-bold text-[#101F38]">
                                {t('vision')}
                            </h2>
                        </div>

                        <blockquote className="text-base sm:text-lg font-medium leading-relaxed text-slate-700 italic border-l-2 border-line pl-4">
                            {visionQuote}
                        </blockquote>
                    </Reveal>

                    {/* Mission Column */}
                    <Reveal preset="fadeUp" className="space-y-6 lg:pl-16">
                        <div className="flex items-center gap-4">
                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#8A6212] text-white shadow-xs">
                                <Target className="h-6 w-6 stroke-[2]" aria-hidden="true" />
                            </div>
                            <h2 className="font-heading text-2xl font-bold text-[#101F38]">
                                {t('mission')}
                            </h2>
                        </div>

                        <ul className="space-y-4">
                            {items.map((item, idx) => (
                                <li key={idx} className="flex items-start gap-3 text-base text-slate-700 leading-relaxed">
                                    <span
                                        aria-hidden="true"
                                        className="mt-2 h-2 w-2 shrink-0 rounded-full bg-[#C59B27]"
                                    />
                                    <span>{item.title}</span>
                                </li>
                            ))}
                        </ul>
                    </Reveal>
                </div>
            </Container>
        </section>
    );
}
