import type { ContactFormBlockData, ContactInfoBlockData } from '@/core';
import { Container } from '@/ui';
import { ContactFormContent } from './ContactFormBlock';
import { ContactInfoContent } from './ContactInfoBlock';

export interface KorinBaraContactSplitProps {
    infoData: ContactInfoBlockData;
    formData: ContactFormBlockData;
    hasHeroH1?: boolean;
}

export function KorinBaraContactSplit({
    infoData,
    formData,
}: KorinBaraContactSplitProps) {
    return (
        <section
            data-testid="korin-bara-contact"
            className="py-16 lg:py-24 bg-[#F8FAFC] border-b border-line"
        >
            <Container>
                <div className="grid grid-cols-1 items-start gap-10 lg:grid-cols-12 lg:gap-12">
                    {/* Left Column: Form inside White Card (7 cols) */}
                    <div className="lg:col-span-7">
                        <ContactFormContent data={formData} headingLevel={2} />
                    </div>

                    {/* Right Column: Contact Info, Hours & Map inside White Card (5 cols) */}
                    <div className="lg:col-span-5">
                        <ContactInfoContent data={infoData} headingLevel={2} />
                    </div>
                </div>
            </Container>
        </section>
    );
}
