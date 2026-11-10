import { act, fireEvent, render, screen } from '@testing-library/react';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { HeroCarousel } from './HeroCarousel';

describe('HeroCarousel', () => {
    const mockSlides = [
        {
            image: '/images/test1.webp',
            image_alt: { id: 'Slide 1 ID', en: 'Slide 1 EN' },
        },
        {
            image: '/images/test2.webp',
            image_alt: { id: 'Slide 2 ID', en: 'Slide 2 EN' },
        },
        {
            image: '/images/test3.webp',
            image_alt: { id: 'Slide 3 ID', en: 'Slide 3 EN' },
        },
    ];

    beforeEach(() => {
        vi.useFakeTimers();
        window.matchMedia = vi.fn().mockImplementation((query: string) => ({
            matches: false,
            media: query,
            onchange: null,
            addListener: vi.fn(),
            removeListener: vi.fn(),
            addEventListener: vi.fn(),
            removeEventListener: vi.fn(),
            dispatchEvent: vi.fn(),
        }));
    });

    afterEach(() => {
        vi.useRealTimers();
        vi.clearAllMocks();
    });

    it('renders single slide without pagination dots', () => {
        render(<HeroCarousel slides={[mockSlides[0]]} locale="id" />);

        expect(screen.getByAltText('Slide 1 ID')).toBeInTheDocument();
        expect(screen.queryByRole('tablist')).not.toBeInTheDocument();
    });

    it('renders 3 slides and pagination dots with proper accessibility', () => {
        render(<HeroCarousel slides={mockSlides} locale="id" />);

        const dots = screen.getAllByRole('tab');
        expect(dots).toHaveLength(3);
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');
        expect(dots[1]).toHaveAttribute('aria-selected', 'false');
    });

    it('navigates to another slide when dot is clicked', () => {
        render(<HeroCarousel slides={mockSlides} locale="id" />);

        const dots = screen.getAllByRole('tab');
        fireEvent.click(dots[1]);

        expect(dots[1]).toHaveAttribute('aria-selected', 'true');
        expect(dots[0]).toHaveAttribute('aria-selected', 'false');
    });

    it('advances automatically after 6 seconds', () => {
        render(<HeroCarousel slides={mockSlides} locale="id" />);

        const dots = screen.getAllByRole('tab');
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');

        act(() => {
            vi.advanceTimersByTime(6000);
        });

        expect(dots[1]).toHaveAttribute('aria-selected', 'true');
    });

    it('pauses autoplay on mouseEnter and resumes on mouseLeave', () => {
        render(<HeroCarousel slides={mockSlides} locale="id" />);

        const region = screen.getByRole('region', { name: /hero image carousel/i });
        const dots = screen.getAllByRole('tab');
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');

        // Hover pauses
        fireEvent.mouseEnter(region);
        act(() => {
            vi.advanceTimersByTime(6000);
        });
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');

        // Leave resumes
        fireEvent.mouseLeave(region);
        act(() => {
            vi.advanceTimersByTime(6000);
        });
        expect(dots[1]).toHaveAttribute('aria-selected', 'true');
    });

    it('pauses autoplay on focus and resumes on blur', () => {
        render(<HeroCarousel slides={mockSlides} locale="id" />);

        const region = screen.getByRole('region', { name: /hero image carousel/i });
        const dots = screen.getAllByRole('tab');
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');

        // Focus in
        fireEvent.focus(region);
        act(() => {
            vi.advanceTimersByTime(6000);
        });
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');

        // Blur out
        fireEvent.blur(region);
        act(() => {
            vi.advanceTimersByTime(6000);
        });
        expect(dots[1]).toHaveAttribute('aria-selected', 'true');
    });

    it('disables autoplay when prefers-reduced-motion matches', () => {
        render(<HeroCarousel slides={mockSlides} locale="id" reducedMotion={true} />);

        const dots = screen.getAllByRole('tab');
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');

        act(() => {
            vi.advanceTimersByTime(12000);
        });

        // Does not advance because motion is reduced
        expect(dots[0]).toHaveAttribute('aria-selected', 'true');
    });
});
