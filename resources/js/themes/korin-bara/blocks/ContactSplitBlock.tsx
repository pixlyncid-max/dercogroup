import type { ContactFormBlockData, ContactInfoBlockData } from '@/core';
import { Container, Section } from '@/ui';
import { ContactFormContent } from './ContactFormBlock';
import { ContactInfoContent } from './ContactInfoBlock';

export interface KorinBaraContactSplitProps {
    infoData: ContactInfoBlockData;
    formData: ContactFormBlockData;
    hasHeroH1?: boolean;
}

/**
 * Korin Bara Contact Page Layout ("Field Rail", DESIGN.md Section 6.4).
 * Form on top of a wide Brass Gold panel, followed by Company Info and Map below.
 */
export function KorinBaraContactSplit({
    infoData,
    formData,
    hasHeroH1 = false,
}: KorinBaraContactSplitProps) {
    return (
        <>
            {/* Top: Form on Wide Brass Gold Panel */}
            <Section tone="default" className="border-b-2 border-line">
                <Container>
                    <ContactFormContent
                        data={{
                            ...formData,
                            title: infoData.title || formData.title,
                            description: infoData.description || formData.description,
                        }}
                        headingLevel={hasHeroH1 ? 2 : 1}
                    />
                </Container>
            </Section>

            {/* Bottom: Contact Info & Coordinate Map */}
            <Section tone="surface" className="border-b-2 border-line">
                <Container>
                    <ContactInfoContent
                        data={{
                            ...infoData,
                            title: formData.title || infoData.title,
                        }}
                        headingLevel={2}
                    />
                </Container>
            </Section>
        </>
    );
}
