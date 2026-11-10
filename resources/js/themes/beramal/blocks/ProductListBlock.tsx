import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Download } from 'lucide-react';
import {
    useLocale,
    type ProductData,
    type ProductListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Image, Reveal, Section, ThemeIcon } from '@/ui';
import { CalmHeader } from '../CalmHeader';

export interface ProductListBlockProps {
    data: ProductListBlockData;
    isFirstBlock?: boolean;
}

const SERVICE_ICONS: readonly string[] = ['layers', 'compass', 'shield-check', 'anchor'] as const;

/**
 * Beramal Sukses Sejahtera Service / Product Cards ("Calm Flow", DESIGN.md Section 7.4).
 * 3-column icon-photo cards with 24px card radius and 28px image radius.
 * Dynamically reflects the CMS-editable page title when rendered as the primary Products page block.
 */
export function ProductListBlock({ data, isFirstBlock = false }: ProductListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const allProducts: ProductData[] = props.products ?? [];

    const limit = data.limit && data.limit > 0 ? data.limit : allProducts.length;
    const products = allProducts.slice(0, limit);

    const productsNavItem = props.navigation?.items?.find((item) => item.key === 'products');
    const productsNavHref = productsNavItem?.href;
    const isHomePage = props.page?.key === 'home';

    const cmsPageTitle = props.page?.title;
    const resolvedTitle =
        isFirstBlock && cmsPageTitle
            ? data.title && !data.title.toLowerCase().includes(cmsPageTitle.toLowerCase())
                ? `${cmsPageTitle} — ${data.title}`
                : data.title || cmsPageTitle
            : data.title;

    return (
        <Section tone="default">
            <Container>
                {isHomePage ? (
                    <h2 className="sr-only">{resolvedTitle}</h2>
                ) : (
                    <CalmHeader
                        eyebrow={isFirstBlock && cmsPageTitle ? cmsPageTitle : data.eyebrow}
                        title={resolvedTitle}
                        description={data.description}
                        level={isFirstBlock ? 1 : 2}
                    />
                )}

                {/* 3-Column Service Icon + Rounded Photo Cards */}
                <div
                    className={`${
                        isHomePage ? '' : 'mt-12 '
                    }grid grid-cols-1 gap-7 md:grid-cols-2 lg:grid-cols-3`}
                >
                    {products.map((product, idx) => {
                        const highlightSpecs = product.specs
                            .filter((spec) => spec.is_highlight)
                            .slice(0, 3);
                        const inlineSpecs =
                            highlightSpecs.length > 0 ? highlightSpecs : product.specs.slice(0, 3);
                        const detailHref = product.url ?? productsNavHref ?? '#';
                        const iconName = SERVICE_ICONS[idx % SERVICE_ICONS.length];

                        return (
                            <Reveal key={product.id} preset="fadeUp" className="h-full">
                                <article className="group flex h-full flex-col justify-between rounded-[var(--radius-card)] border border-line bg-bg p-6 shadow-sm transition-shadow duration-300 hover:shadow-md sm:p-7">
                                    <div className="space-y-5">
                                        {/* Top Rounded 28px Photo + Service Icon Pill */}
                                        <div className="relative">
                                            <Image
                                                src={product.image_url}
                                                alt={product.image_alt || product.name}
                                                ratio="16/9"
                                            />
                                            <span className="absolute left-3 top-3 inline-flex h-11 w-11 items-center justify-center rounded-full bg-surface-dark text-accent-on-dark shadow-sm">
                                                <ThemeIcon name={iconName} className="h-5 w-5" />
                                            </span>
                                        </div>

                                        {product.category_label ? (
                                            <span className="inline-block rounded-full bg-surface px-3.5 py-1 text-xs font-bold text-ink">
                                                {product.category_label}
                                            </span>
                                        ) : null}

                                        <Heading
                                            level={isFirstBlock ? 2 : 3}
                                            size="h3"
                                            className="font-bold"
                                        >
                                            <Link
                                                href={detailHref}
                                                className="transition-colors group-hover:text-accent-strong focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent"
                                            >
                                                {product.name}
                                            </Link>
                                        </Heading>

                                        <p className="text-sm leading-6 text-muted">
                                            {product.summary}
                                        </p>

                                        {/* Key Specification Capsules */}
                                        {inlineSpecs.length > 0 ? (
                                            <dl className="grid grid-cols-3 gap-2 pt-1">
                                                {inlineSpecs.map((spec) => (
                                                    <div
                                                        key={spec.id}
                                                        className="rounded-2xl bg-surface px-3 py-2.5"
                                                    >
                                                        <dt className="truncate text-[11px] font-bold uppercase tracking-[0.06em] text-muted">
                                                            {spec.code || spec.label}
                                                        </dt>
                                                        <dd className="mt-0.5 font-heading text-sm font-bold text-ink">
                                                            {spec.value}
                                                            {spec.unit ? (
                                                                <span className="ml-0.5 block text-[11px] font-normal text-muted">
                                                                    {spec.unit}
                                                                </span>
                                                            ) : null}
                                                        </dd>
                                                    </div>
                                                ))}
                                            </dl>
                                        ) : null}
                                    </div>

                                    {/* Bottom Pill Action Row */}
                                    <div className="mt-6 flex items-center justify-between gap-3 border-t border-line pt-5">
                                        <Link
                                            href={detailHref}
                                            className="inline-flex min-h-11 items-center gap-2 rounded-full bg-surface px-5 py-2 text-xs font-bold text-ink transition-colors group-hover:bg-accent group-hover:text-on-accent"
                                        >
                                            <span>{t('products.view_specification')}</span>
                                            {!isHomePage ? (
                                                <ArrowRight
                                                    className="h-4 w-4"
                                                    aria-hidden="true"
                                                />
                                            ) : null}
                                        </Link>

                                        {!isHomePage &&
                                        data.show_download_button &&
                                        product.document_url ? (
                                            <a
                                                href={product.document_url}
                                                download
                                                aria-label={`${t('download_pdf')} — ${product.name}`}
                                                className="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full border border-line text-ink transition-colors hover:bg-surface"
                                            >
                                                <Download className="h-4 w-4" aria-hidden="true" />
                                            </a>
                                        ) : null}
                                    </div>
                                </article>
                            </Reveal>
                        );
                    })}
                </div>
            </Container>
        </Section>
    );
}
