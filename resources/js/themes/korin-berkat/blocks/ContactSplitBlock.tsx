import { useRef } from 'react';
import type { ContactFormBlockData, ContactInfoBlockData } from '@/core';
import { Container, Heading, Section } from '@/ui';
import { useGsapParallax } from '../useKorinBerkatMotion';
import { ContactFormContent } from './ContactFormBlock';
import { ContactInfoContent } from './ContactInfoBlock';

export interface KorinBerkatContactSplitProps {
    infoData: ContactInfoBlockData;
    formData: ContactFormBlockData;
    hasHeroH1?: boolean;
}

export function KorinBerkatContactSplit({
    infoData,
    formData,
    hasHeroH1 = false,
}: KorinBerkatContactSplitProps) {
    const bannerRef = useRef<HTMLDivElement | null>(null);
    const bannerBgRef = useRef<HTMLDivElement | null>(null);
    useGsapParallax(bannerRef, bannerBgRef, 12);

    return (
        <>
            {!hasHeroH1 ? (
                <div
                    ref={bannerRef}
                    className="relative overflow-hidden bg-surface-dark text-ink-on-dark border-b border-white/10"
                >
                    <div
                        ref={bannerBgRef}
                        aria-hidden="true"
                        className="pointer-events-none absolute inset-0 z-0 will-change-transform"
                    >
                        <img
                            src="/images/placeholders/aerial-pit-1280.webp"
                            alt=""
                            className="h-full w-full object-cover object-center opacity-45"
                        />
                        <div className="absolute inset-0 bg-gradient-to-r from-[#081222]/95 via-[#0B172A]/85 to-[#0B172A]/50" />
                    </div>
                    <div
                        aria-hidden="true"
                        className="kbe-diagonal-wedge-left pointer-events-none absolute bottom-0 left-0 z-10 h-14 w-12 opacity-90"
                    />
                    <Container className="relative z-10 pt-28 pb-14 sm:pt-32 sm:pb-16 lg:pt-36 lg:pb-20">
                        <div className="flex items-stretch gap-5 max-w-3xl">
                            <span
                                aria-hidden="true"
                                className="w-1 shrink-0 rounded-full bg-accent"
                            />
                            <div className="space-y-3">
                                {infoData.eyebrow ? (
                                    <p className="text-xs font-bold uppercase tracking-[0.18em] text-accent-on-dark">
                                        {infoData.eyebrow}
                                    </p>
                                ) : null}
                                <Heading
                                    level={1}
                                    size="h2"
                                    className="font-bold tracking-tight text-white sm:text-4xl lg:text-5xl"
                                >
                                    {infoData.title}
                                </Heading>
                                {infoData.description ? (
                                    <p className="max-w-[58ch] text-base leading-7 text-slate-200">
                                        {infoData.description}
                                    </p>
                                ) : null}
                            </div>
                        </div>
                    </Container>
                </div>
            ) : null}

            <Section tone="default" className="border-b border-line">
                <Container>
                    <div className="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-start">
                        <div className="lg:col-span-5">
                            <ContactInfoContent data={infoData} headingLevel={2} />
                        </div>
                        <div className="lg:col-span-7">
                            <ContactFormContent data={formData} />
                        </div>
                    </div>
                </Container>
            </Section>
        </>
    );
}
