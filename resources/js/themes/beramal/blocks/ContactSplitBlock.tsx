import type { ContactFormBlockData, ContactInfoBlockData } from '@/core';
import { Container, Section } from '@/ui';
import { WaveDivider } from '../WaveDivider';
import { ContactFormContent } from './ContactFormBlock';
import { ContactInfoContent } from './ContactInfoBlock';

export interface BeramalContactSplitProps {
    infoData: ContactInfoBlockData;
    formData: ContactFormBlockData;
    hasHeroH1?: boolean;
}

/**
 * Beramal Sukses Sejahtera Contact Layout ("Calm Flow", DESIGN.md Section 7.4).
 * Three rounded contact capsules at the top, followed by the centered form card in a Mist section.
 */
export function BeramalContactSplit({
    infoData,
    formData,
    hasHeroH1 = false,
}: BeramalContactSplitProps) {
    return (
        <>
            <Section tone="default">
                <Container>
                    <ContactInfoContent data={infoData} headingLevel={hasHeroH1 ? 2 : 1} />
                </Container>
            </Section>

            <WaveDivider tone="surface" />

            <Section tone="surface">
                <Container size="narrow">
                    <ContactFormContent data={formData} />
                </Container>
            </Section>

            <WaveDivider tone="surface" flip />
        </>
    );
}
