import { Handshake, Leaf, Settings, ShieldCheck, type LucideIcon } from 'lucide-react';
import type { ValuesGridBlockData } from '@/core';
import { Container, Heading, Reveal } from '@/ui';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

const iconMap: Record<string, LucideIcon> = {
    'shield-check': ShieldCheck,
    shield: ShieldCheck,
    leaf: Leaf,
    cog: Settings,
    settings: Settings,
    handshake: Handshake,
};

export function ValuesGridBlock({ data }: ValuesGridBlockProps) {
    const items = data.items ?? [];
    const isStrip = (data as { variant?: string }).variant === 'strip' || items.length <= 4;

    if (isStrip) {
        return (
            <section data-testid="korin-bara-values-strip" className="relative z-20 -mt-10 lg:-mt-14 mb-12">
                <Container>
                    <Reveal
                        preset="fadeUp"
                        className="rounded-xl border border-line bg-[#F8FAFC] p-6 shadow-md lg:p-8"
                    >
                        <div className="grid grid-cols-2 gap-6 lg:grid-cols-4 lg:gap-8 lg:divide-x lg:divide-line">
                            {items.map((item, idx) => {
                                const IconComponent =
                                    iconMap[item.icon_name?.toLowerCase() ?? ''] ||
                                    (idx === 0
                                        ? ShieldCheck
                                        : idx === 1
                                          ? Leaf
                                          : idx === 2
                                            ? Settings
                                            : Handshake);

                                return (
                                    <div
                                        key={idx}
                                        className={`flex items-center gap-4 ${idx > 0 ? 'lg:pl-6' : ''}`}
                                    >
                                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-[#8A6212]">
                                            <IconComponent className="h-6 w-6 stroke-[1.75]" aria-hidden="true" />
                                        </div>
                                        <span className="font-heading text-sm font-bold leading-snug text-[#101F38] sm:text-base">
                                            {item.title}
                                        </span>
                                    </div>
                                );
                            })}
                        </div>
                    </Reveal>
                </Container>
            </section>
        );
    }

    // Standard card grid fallback
    return (
        <section className="py-16 bg-[#F8FAFC] border-b border-line">
            <Container>
                {data.title ? (
                    <div className="mb-12">
                        {data.eyebrow ? <span className="kb-eyebrow">{data.eyebrow}</span> : null}
                        <Heading level={2} size="h2" className="mt-3 text-[#101F38]">
                            {data.title}
                        </Heading>
                    </div>
                ) : null}

                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    {items.map((item, idx) => {
                        const IconComponent =
                            iconMap[item.icon_name?.toLowerCase() ?? ''] || ShieldCheck;

                        return (
                            <Reveal
                                key={idx}
                                preset="fadeUp"
                                className="rounded-xl border border-line bg-white p-6 shadow-xs space-y-4"
                            >
                                <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-[#8A6212]">
                                    <IconComponent className="h-6 w-6" aria-hidden="true" />
                                </div>
                                <Heading level={3} size="h3" className="text-[#101F38]">
                                    {item.title}
                                </Heading>
                                <p className="text-sm leading-relaxed text-slate-600">{item.description}</p>
                            </Reveal>
                        );
                    })}
                </div>
            </Container>
        </section>
    );
}
