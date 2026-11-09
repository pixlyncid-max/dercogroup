import type { TimelineBlockData } from '@/core';
import { Container, Section } from '@/ui';
import { CalmHeader } from '../CalmHeader';
import { WaveDivider } from '../WaveDivider';
import { WorkflowStepper } from '../WorkflowStepper';

export interface TimelineBlockProps {
    data: TimelineBlockData;
}

/**
 * Beramal Timeline Block ("Calm Flow", DESIGN.md Section 7.4).
 * Displays corporate milestones as a horizontal flow stepper with an Anime.js Cerulean path.
 */
export function TimelineBlock({ data }: TimelineBlockProps) {
    const items = data.items ?? [];
    const steps = items.map((item) => ({
        code: item.period,
        title: item.title,
        description: item.description,
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
