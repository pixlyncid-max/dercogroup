import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import { useLocale } from '@/core';
import { Container, Image } from '@/ui';

export interface PageBannerProps {
    title: string;
    image?: string;
    imageAlt?: string;
}

const bannerImagesByKeyword: Record<string, string> = {
    about: '/images/placeholders/aerial-mine-1280.webp',
    tentang: '/images/placeholders/aerial-mine-1280.webp',
    operasi: '/images/placeholders/pit-benches-1280.webp',
    operation: '/images/placeholders/pit-benches-1280.webp',
    produk: '/images/placeholders/stockpile-haul-1280.webp',
    product: '/images/placeholders/stockpile-haul-1280.webp',
    kontak: '/images/placeholders/jetty-barge-1280.webp',
    contact: '/images/placeholders/jetty-barge-1280.webp',
};

export function PageBanner({ title, image, imageAlt }: PageBannerProps) {
    const { locale, t } = useLocale();
    const homeHref = `/${locale}`;

    const normalizedTitle = title.toLowerCase();
    const fallbackImage =
        Object.entries(bannerImagesByKeyword).find(([key]) => normalizedTitle.includes(key))?.[1] ??
        '/images/placeholders/pit-benches-1280.webp';

    const bannerImage = image || fallbackImage;

    return (
        <section
            data-testid="korin-bara-page-banner"
            className="relative h-[180px] sm:h-[220px] lg:h-[260px] w-full overflow-hidden bg-surface-dark"
        >
            {/* Background Image */}
            <div className="absolute inset-0">
                <Image
                    src={bannerImage}
                    alt={imageAlt || title}
                    ratio="16/9"
                    priority
                    wrapperClassName="h-full w-full border-0 rounded-none"
                    className="h-full w-full object-cover object-center"
                />
                {/* Dark overlay from left to guarantee WCAG AA contrast (>= 4.5:1) */}
                <div
                    aria-hidden="true"
                    className="absolute inset-0 bg-gradient-to-r from-[#101F38]/95 via-[#101F38]/80 to-[#101F38]/30"
                />
            </div>

            {/* Content: Title & Breadcrumb */}
            <Container className="relative z-10 flex h-full flex-col justify-center">
                <h1 className="font-heading text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl">
                    {title}
                </h1>

                {/* Breadcrumb: Beranda › {Title} */}
                <nav aria-label={t('breadcrumbs.label')} className="mt-2.5 flex items-center gap-1.5 text-xs text-slate-300 sm:text-sm">
                    <Link
                        href={homeHref}
                        className="transition-colors hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                    >
                        {t('breadcrumbs.home')}
                    </Link>
                    <ChevronRight className="h-3.5 w-3.5 shrink-0 text-slate-400" aria-hidden="true" />
                    <span aria-current="page" className="font-medium text-white">
                        {title}
                    </span>
                </nav>
            </Container>
        </section>
    );
}

