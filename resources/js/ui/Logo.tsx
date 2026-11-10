import React from 'react';
import type { CompanyLogoData, SharedCompany } from '@/core/types';

export interface LogoProps {
    company?: SharedCompany | null;
    logo?: CompanyLogoData | null;
    /**
     * The background surface tone the logo is placed on:
     * - 'light': Light surface (e.g., white header/capsule), selects logo.light (dark ink logo)
     * - 'dark': Dark surface (e.g., charcoal/navy/dark water), selects logo.dark (light/white ink logo)
     */
    on: 'light' | 'dark';
    /**
     * Display height in pixels or predefined token.
     */
    size?: number | 'sm' | 'md' | 'lg' | 'xl';
    /**
     * Prefer compact/icon version if available (e.g. for narrow rails).
     */
    compact?: boolean;
    /**
     * Render logo on a white rounded plate (for dark footers if symbol contrast fails).
     */
    plat?: boolean;
    /**
     * High priority loading (fetchpriority="high", eager) for above-the-fold headers.
     */
    priority?: boolean;
    className?: string;
    imgClassName?: string;
}

const SIZE_MAP: Record<'sm' | 'md' | 'lg' | 'xl', number> = {
    sm: 32,
    md: 40,
    lg: 48,
    xl: 56,
};

export const Logo: React.FC<LogoProps> = ({
    company,
    logo,
    on,
    size = 'md',
    compact = false,
    plat = false,
    priority = false,
    className = '',
    imgClassName = '',
}) => {
    const activeLogo = logo ?? company?.logo ?? null;
    const targetHeight = typeof size === 'number' ? size : (SIZE_MAP[size] ?? 40);

    // Fallback: when logo config or files are not available, display a clean accessible wordmark
    if (!activeLogo) {
        const wordmarkName = company?.name ?? 'Derco Group';
        return (
            <span
                data-testid="logo-wordmark"
                className={`inline-block font-heading font-bold tracking-tight text-current select-none ${className}`}
                style={{ fontSize: Math.max(14, Math.round(targetHeight * 0.42)) }}
            >
                {wordmarkName}
            </span>
        );
    }

    // Determine the source file based on placement tone and compact preference
    let src = on === 'dark' ? activeLogo.dark : activeLogo.light;
    if (compact && activeLogo.compact) {
        src = activeLogo.compact;
    }

    const altText = activeLogo.alt || company?.name || 'Logo';
    const aspectRatio =
        activeLogo.width > 0 && activeLogo.height > 0
            ? `${activeLogo.width} / ${activeLogo.height}`
            : 'auto';

    const imgElement = (
        <img
            src={src}
            alt={altText}
            width={activeLogo.width}
            height={activeLogo.height}
            loading={priority ? 'eager' : 'lazy'}
            fetchPriority={priority ? 'high' : 'auto'}
            decoding={priority ? 'sync' : 'async'}
            style={{
                height: `${targetHeight}px`,
                width: 'auto',
                aspectRatio,
                objectFit: 'contain',
            }}
            className={`max-w-full block select-none pointer-events-none transition-all duration-200 ${imgClassName}`}
        />
    );

    if (plat) {
        return (
            <div
                data-testid="logo-plat"
                className={`inline-flex items-center justify-center rounded-[8px] bg-white px-4 py-3 shadow-xs ${className}`}
            >
                {imgElement}
            </div>
        );
    }

    return (
        <div
            data-testid="logo-container"
            className={`inline-flex items-center shrink-0 ${className}`}
        >
            {imgElement}
        </div>
    );
};
