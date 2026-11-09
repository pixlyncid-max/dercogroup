import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import type { NavigationItem, SharedPageProps } from '@/core/types';

export interface UseNavigationResult {
    items: NavigationItem[];
    holdingUrl: string;
    contactUrl: string;
    isSticky: boolean;
    isMobileMenuOpen: boolean;
    openMobileMenu: () => void;
    closeMobileMenu: () => void;
    toggleMobileMenu: () => void;
}

export function useNavigation(): UseNavigationResult {
    const { props } = usePage<SharedPageProps>();
    const locale = props.locale ?? 'id';
    const items = props.navigation?.items ?? [];
    const holdingUrl = props.navigation?.holdingUrl ?? `/${locale}`;

    const contactItem = items.find((item) => item.key === 'contact');
    const contactUrl = contactItem?.href ?? (locale === 'en' ? '/en/contact' : '/id/kontak');

    const [isSticky, setIsSticky] = useState(false);
    const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setIsSticky(window.scrollY > 24);
        };

        handleScroll();
        window.addEventListener('scroll', handleScroll, { passive: true });

        return () => {
            window.removeEventListener('scroll', handleScroll);
        };
    }, []);

    useEffect(() => {
        if (!isMobileMenuOpen) {
            return;
        }

        const handleKeyDown = (event: KeyboardEvent) => {
            if (event.key === 'Escape') {
                setIsMobileMenuOpen(false);
            }
        };

        window.addEventListener('keydown', handleKeyDown);

        return () => {
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [isMobileMenuOpen]);

    return {
        items,
        holdingUrl,
        contactUrl,
        isSticky,
        isMobileMenuOpen,
        openMobileMenu: () => setIsMobileMenuOpen(true),
        closeMobileMenu: () => setIsMobileMenuOpen(false),
        toggleMobileMenu: () => setIsMobileMenuOpen((prev) => !prev),
    };
}
