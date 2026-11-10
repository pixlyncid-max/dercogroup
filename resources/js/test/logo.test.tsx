import { describe, expect, it } from 'vitest';
import { render, screen } from '@testing-library/react';
import { Logo } from '@/ui/Logo';
import type { CompanyLogoData, SharedCompany } from '@/core/types';

describe('ui/Logo Component', () => {
    const mockLogoData: CompanyLogoData = {
        light: '/images/logo/Logo-DercoBaraEnergy.png',
        dark: '/images/logo/Logo-DercoBaraEnergy-light.png',
        compact: '/images/logo/Logo-Derco-compact.png',
        width: 676,
        height: 369,
        alt: 'PT Derco Bara Energy',
    };

    const mockCompany: SharedCompany = {
        name: 'PT Derco Bara Energy',
        slug: 'derco-bara-energy',
        theme: 'holding',
        status: 'published',
        is_holding: true,
        logo: mockLogoData,
    };

    it('selects light-surface variant (dark-ink) when on="light"', () => {
        render(<Logo company={mockCompany} on="light" size={48} />);

        const img = screen.getByRole('img');
        expect(img).toHaveAttribute('src', '/images/logo/Logo-DercoBaraEnergy.png');
        expect(img).toHaveAttribute('alt', 'PT Derco Bara Energy');
        expect(img).toHaveAttribute('width', '676');
        expect(img).toHaveAttribute('height', '369');
        expect(img.style.height).toBe('48px');
        expect(img.style.aspectRatio).toBe('676 / 369');
    });

    it('selects dark-surface variant (light-ink) when on="dark"', () => {
        render(<Logo company={mockCompany} on="dark" size={36} />);

        const img = screen.getByRole('img');
        expect(img).toHaveAttribute('src', '/images/logo/Logo-DercoBaraEnergy-light.png');
        expect(img.style.height).toBe('36px');
    });

    it('prefers compact version when compact=true and compact src is available', () => {
        render(<Logo company={mockCompany} on="light" compact={true} size={40} />);

        const img = screen.getByRole('img');
        expect(img).toHaveAttribute('src', '/images/logo/Logo-Derco-compact.png');
    });

    it('renders a clean wordmark text fallback when logo is null without onError', () => {
        const companyWithoutLogo: SharedCompany = {
            name: 'PT Holding Fallback',
            slug: 'fallback',
            theme: 'holding',
            status: 'published',
            is_holding: true,
            logo: null,
        };

        render(<Logo company={companyWithoutLogo} on="dark" size={40} />);

        expect(screen.queryByRole('img')).toBeNull();
        const wordmark = screen.getByTestId('logo-wordmark');
        expect(wordmark).toBeInTheDocument();
        expect(wordmark).toHaveTextContent('PT Holding Fallback');
    });

    it('renders on a white plate when plat=true', () => {
        render(<Logo company={mockCompany} on="light" plat={true} size={44} />);

        const platContainer = screen.getByTestId('logo-plat');
        expect(platContainer).toBeInTheDocument();
        expect(platContainer).toHaveClass('bg-white');
        const img = screen.getByRole('img');
        expect(img).toBeInTheDocument();
    });
});
