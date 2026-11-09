import { render, screen } from '@testing-library/react';
import { describe, expect, it } from 'vitest';
import { Button, Container, Eyebrow, Heading, Image, Section } from '@/ui';

describe('ui primitive components', () => {
    it('renders Container and Section with semantic structure', () => {
        render(
            <Section tone="dark" data-testid="section">
                <Container size="wide">
                    <Eyebrow tone="dark">OPERATIONS</Eyebrow>
                    <Heading level={1} size="display">
                        PT Derco Bara Energy
                    </Heading>
                </Container>
            </Section>,
        );

        expect(screen.getByTestId('section')).toBeInTheDocument();
        expect(screen.getByRole('heading', { level: 1 })).toHaveTextContent('PT Derco Bara Energy');
        expect(screen.getByText('OPERATIONS')).toBeInTheDocument();
    });

    it('renders Button as button or anchor depending on href prop', () => {
        render(
            <div>
                <Button type="button">Action Button</Button>
                <Button href="/id/kontak" variant="outline">
                    Link Button
                </Button>
            </div>,
        );

        expect(screen.getByRole('button', { name: 'Action Button' })).toBeInTheDocument();
        expect(screen.getByRole('link', { name: 'Link Button' })).toHaveAttribute(
            'href',
            '/id/kontak',
        );
    });

    it('renders local SVG placeholder with aspect ratio label when src is omitted', () => {
        render(<Image ratio="16/9" alt="Mining site placeholder" />);

        expect(screen.getByRole('img', { name: 'Mining site placeholder' })).toBeInTheDocument();
        expect(screen.getByText('16:9')).toBeInTheDocument();
    });
});
