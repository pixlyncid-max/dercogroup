import type { ValuesGridBlockData } from '@/core';
import { Container, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';
import { WaveDivider } from '../WaveDivider';
import { WorkflowStepper } from '../WorkflowStepper';

export interface ValuesGridBlockProps {
    data: ValuesGridBlockData;
}

/**
 * Beramal Corporate Values Stepper ("Calm Flow", DESIGN.md Section 7.4).
 * Renders the 3 core values in a horizontal Cerulean stepper.
 */
export function ValuesGridBlock({ data }: ValuesGridBlockProps) {
    const items = data.items ?? [];
    const steps = items.map((item, idx) => ({
        code: String(idx + 1).padStart(2, '0'),
        title: item.title,
        description: item.description,
        iconName: item.icon_name,
    }));

    return (
        <>
            <WaveDivider tone="surface" />
            <Section tone="surface">
                <Container>
                    <CalmHeader
                        eyebrow={data.eyebrow}
                        title={data.title}
                        description={data.description}
                        centered
                        pillTone="bg"
                    />

                    <div className="mt-12">
                        <WorkflowStepper steps={steps} cardTone="bg" />
                    </div>
                </Container>
            </Section>
            <WaveDivider tone="surface" flip />
        </>
    );
}
