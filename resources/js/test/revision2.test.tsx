import type { AnchorHTMLAttributes, ReactNode } from 'react';
import { render, screen } from '@testing-library/react';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import type { SharedPageProps } from '@/core';
import { Layout as BeramalLayout } from '@/themes/beramal/Layout';
import { Layout as KorinBerkatLayout } from '@/themes/korin-berkat/Layout';

const mockUsePage = vi.fn();

interface MockLinkProps extends AnchorHTMLAttributes<HTMLAnchorElement> {
    children?: ReactNode;
    href?: string;
}

vi.mock('@inertiajs/react', () => ({
    Link: ({ children, href, ...props }: MockLinkProps) => (
        <a href={href} {...props}>
            {children}
        </a>
    ),
    usePage: () => mockUsePage(),
}));

describe('Revision 2 Frontend Navigation & Editorial Layout Tests', () => {
    beforeEach(() => {
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

    it('dynamically formats Korin Berkat menu index based on active 5 items (01..05 without 06)', () => {
        const pageProps: Partial<SharedPageProps> = {
            locale: 'id',
            locales: ['id', 'en'],
            company: {
                id: 2,
                name: 'PT Korin Berkat Energy',
                slug: 'korin-berkat-energy',
                theme: 'korin-berkat',
                status: 'published',
                tagline: 'Tagline',
                about_short: 'About short',
                logo_alt: 'Logo',
                is_holding: false,
                contacts: [],
            },
            navigation: {
                items: [
                    {
                        key: 'home',
                        label: 'Beranda',
                        href: '/id/bisnis-kami/korin-berkat-energy',
                        active: true,
                    },
                    {
                        key: 'about',
                        label: 'Tentang Kami',
                        href: '/id/bisnis-kami/korin-berkat-energy/tentang-kami',
                        active: false,
                    },
                    {
                        key: 'products',
                        label: 'Produk',
                        href: '/id/bisnis-kami/korin-berkat-energy/produk',
                        active: false,
                    },
                    {
                        key: 'operations',
                        label: 'Operasi',
                        href: '/id/bisnis-kami/korin-berkat-energy/operasi',
                        active: false,
                    },
                    {
                        key: 'contact',
                        label: 'Kontak',
                        href: '/id/bisnis-kami/korin-berkat-energy/kontak',
                        active: false,
                    },
                ],
                holdingUrl: '/id',
                alternateUrls: {
                    id: '/id/bisnis-kami/korin-berkat-energy',
                    en: '/en/our-business/korin-berkat-energy',
                },
            },
            translations: {
                skip_to_content: 'Lewati ke konten utama',
                holding_short: 'PT Derco Bara Energy',
                'nav.main': 'Navigasi utama',
                'nav.open_menu': 'Buka menu navigasi',
                'nav.close_menu': 'Tutup menu navigasi',
                'nav.index_heading': 'Indeks Halaman',
                'footer.address_heading': 'Alamat Kantor',
                'footer.rights_reserved': 'Seluruh hak cipta dilindungi.',
                'footer.parent_group_label': 'Bagian dari Grup Induk',
                back_to_holding: 'Kembali ke PT Derco Bara Energy',
                back_to_top: 'Kembali ke atas',
            },
        };

        mockUsePage.mockReturnValue({ props: pageProps });

        render(
            <KorinBerkatLayout>
                <div>Page Content</div>
            </KorinBerkatLayout>,
        );

        // Check editorial numbers 01 through 05 exist in navigation (both header and footer)
        expect(screen.getAllByText('01')).toHaveLength(2);
        expect(screen.getAllByText('02')).toHaveLength(2);
        expect(screen.getAllByText('03')).toHaveLength(2);
        expect(screen.getAllByText('04')).toHaveLength(2);
        expect(screen.getAllByText('05')).toHaveLength(2);

        // 06 must NOT exist because compliance was removed
        expect(screen.queryAllByText('06')).toHaveLength(0);
    });

    it('renders Beramal "Hubungi Kami" as a text link with proper aria-current, not a button', () => {
        const pageProps: Partial<SharedPageProps> = {
            locale: 'id',
            locales: ['id', 'en'],
            company: {
                id: 3,
                name: 'PT Beramal Sukses Sejahtera',
                slug: 'beramal-sukses-sejahtera',
                theme: 'beramal',
                status: 'published',
                tagline: 'Tagline',
                about_short: 'About short',
                logo_alt: 'Logo',
                is_holding: false,
                contacts: [],
            },
            navigation: {
                items: [
                    {
                        key: 'home',
                        label: 'Beranda',
                        href: '/id/bisnis-kami/beramal-sukses-sejahtera',
                        active: false,
                    },
                    {
                        key: 'about',
                        label: 'Tentang Kami',
                        href: '/id/bisnis-kami/beramal-sukses-sejahtera/tentang-kami',
                        active: false,
                    },
                    {
                        key: 'products',
                        label: 'Layanan & Produk',
                        href: '/id/bisnis-kami/beramal-sukses-sejahtera/produk',
                        active: false,
                    },
                    {
                        key: 'operations',
                        label: 'Operasi',
                        href: '/id/bisnis-kami/beramal-sukses-sejahtera/operasi',
                        active: false,
                    },
                    {
                        key: 'contact',
                        label: 'Hubungi Kami',
                        href: '/id/bisnis-kami/beramal-sukses-sejahtera/hubungi-kami',
                        active: true, // currently on contact page
                    },
                ],
                holdingUrl: '/id',
                alternateUrls: {
                    id: '/id/bisnis-kami/beramal-sukses-sejahtera/hubungi-kami',
                    en: '/en/our-business/beramal-sukses-sejahtera/contact-us',
                },
            },
            translations: {
                skip_to_content: 'Lewati ke konten utama',
                holding_short: 'PT Derco Bara Energy',
                'nav.main': 'Navigasi utama',
                'nav.open_menu': 'Buka menu navigasi',
                'nav.close_menu': 'Tutup menu navigasi',
                'nav.index_heading': 'Indeks Halaman',
                'footer.address_heading': 'Alamat Kantor',
                'footer.rights_reserved': 'Seluruh hak cipta dilindungi.',
                'footer.parent_group_label': 'Bagian dari Grup Induk',
                back_to_holding: 'Kembali ke PT Derco Bara Energy',
                contact_us: 'Hubungi Kami',
            },
        };

        mockUsePage.mockReturnValue({ props: pageProps });

        render(
            <BeramalLayout>
                <div>Beramal Content</div>
            </BeramalLayout>,
        );

        // Find all elements with text "Hubungi Kami"
        const contactLinks = screen.getAllByRole('link', { name: /hubungi kami/i });
        expect(contactLinks.length).toBeGreaterThan(0);

        // The top right nav link for contact
        const headerContactLink = contactLinks.find((link) =>
            link.getAttribute('href')?.includes('/hubungi-kami'),
        );
        expect(headerContactLink).toBeInTheDocument();
        expect(headerContactLink?.tagName.toLowerCase()).toBe('a');
        expect(headerContactLink).toHaveAttribute('aria-current', 'page');

        // It must NOT be styled with button solid/pill class (bg-accent text-on-accent)
        expect(headerContactLink?.className).not.toContain('bg-accent text-on-accent');
    });
});
