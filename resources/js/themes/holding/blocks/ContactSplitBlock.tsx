import type { ContactFormBlockData, ContactInfoBlockData } from '@/core';
import { Container, Section } from '@/ui';
import { ContactFormContent } from './ContactFormBlock';
import { ContactInfoContent } from './ContactInfoBlock';

export interface HoldingContactSplitProps {
    infoData: ContactInfoBlockData;
    formData: ContactFormBlockData;
    hasHeroH1: boolean;
}

/**
 * Two-column Contact layout per DESIGN.md 4.5:
 * Left: form (name, email, phone, organization, subject, message).
 * Right: address, phone, email, hours, static map.
 */
export function HoldingContactSplit({ infoData, formData, hasHeroH1 }: HoldingContactSplitProps) {
    return (
        <Section tone="default">
            <Container>
                <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12 lg:gap-12">
                    <div className="lg:col-span-7 order-2 lg:order-1">
                        <ContactFormContent data={formData} />
                    </div>
                    <div className="lg:col-span-5 order-1 lg:order-2">
                        <ContactInfoContent data={infoData} headingLevel={hasHeroH1 ? 2 : 1} />
                    </div>
                </div>
            </Container>
        </Section>
    );
}
