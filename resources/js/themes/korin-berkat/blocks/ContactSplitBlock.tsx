import type { ContactFormBlockData, ContactInfoBlockData } from '@/core';
import { Container, Section } from '@/ui';
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
    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                <div className="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-start">
                    <div className="lg:col-span-5">
                        <ContactInfoContent data={infoData} headingLevel={hasHeroH1 ? 2 : 1} />
                    </div>
                    <div className="lg:col-span-7">
                        <ContactFormContent data={formData} />
                    </div>
                </div>
            </Container>
        </Section>
    );
}
