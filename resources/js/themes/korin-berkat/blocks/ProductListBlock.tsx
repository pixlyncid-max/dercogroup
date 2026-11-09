import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Download } from 'lucide-react';
import {
    useLocale,
    type ProductData,
    type ProductListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { EditorialHeader } from '../EditorialHeader';

export interface ProductListBlockProps {
    data: ProductListBlockData;
    isFirstBlock?: boolean;
}

/**
 * Korin Berkat Product List rendered as horizontal Editorial Rows (NEVER cards) per DESIGN.md Section 5.4 & 5.5.
 * Each row displays: 01..03 index, name + category, summary, 3 inline highlighted specs (CV · TM · Ash), and right arrow.
 * Shifts +8px right on hover (`hover:translate-x-2`).
 */
export function ProductListBlock({ data, isFirstBlock = false }: ProductListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const allProducts: ProductData[] = props.products ?? [];

    const limit = data.limit && data.limit > 0 ? data.limit : allProducts.length;
    const products = allProducts.slice(0, limit);

    const productsNavHref = props.navigation?.items?.find((item) => item.key === 'products')?.href;
    const isHomePage = props.page?.key === 'home';

    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                <EditorialHeader
                    indexLabel="03"
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                    level={isFirstBlock ? 1 : 2}
                    action={
                        isHomePage && productsNavHref ? (
                            <Link
                                href={productsNavHref}
                                className="inline-flex min-h-11 items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-ink transition-colors hover:text-accent-strong"
                            >
                                <span>{t('see_details')}</span>
                                <ArrowRight
                                    className="h-4 w-4 text-accent-strong"
                                    aria-hidden="true"
                                />
                            </Link>
                        ) : null
                    }
                />

                {/* Editorial Index Header Bar */}
                <div className="mt-12 hidden border-b-2 border-ink pb-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-muted lg:grid lg:grid-cols-12 lg:gap-6">
                    <div className="lg:col-span-1">No.</div>
                    <div className="lg:col-span-5">{t('products.catalog_index')}</div>
                    <div className="lg:col-span-5">{t('products.specifications')}</div>
                    <div className="lg:col-span-1 text-right">{t('see_details')}</div>
                </div>

                {/* Editorial Rows */}
                <div className="divide-y divide-line border-t border-line lg:border-t-0">
                    {products.map((product, idx) => {
                        const highlightSpecs = product.specs
                            .filter((spec) => spec.is_highlight)
                            .slice(0, 3);
                        const inlineSpecs =
                            highlightSpecs.length > 0 ? highlightSpecs : product.specs.slice(0, 3);
                        const detailHref = product.url ?? productsNavHref ?? '#';

                        return (
                            <Reveal key={product.id} preset="fadeUp">
                                <div className="group relative grid grid-cols-1 gap-6 py-8 transition-transform duration-300 ease-out hover:translate-x-2 lg:grid-cols-12 lg:items-center lg:gap-6">
                                    {/* Index 01..03 */}
                                    <div className="lg:col-span-1 flex items-center justify-between lg:block">
                                        <span className="font-heading text-xl font-semibold text-accent-strong">
                                            {String(idx + 1).padStart(2, '0')}
                                        </span>
                                        {product.category_label ? (
                                            <span className="inline-block rounded-[var(--radius-button)] bg-surface px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.08em] text-ink lg:hidden">
                                                {product.category_label}
                                            </span>
                                        ) : null}
                                    </div>

                                    {/* Product Name, Category & Summary */}
                                    <div className="lg:col-span-5 space-y-2">
                                        {product.category_label ? (
                                            <p className="hidden text-xs font-semibold uppercase tracking-[0.1em] text-accent-strong lg:block">
                                                {product.category_label}
                                            </p>
                                        ) : null}
                                        <Heading
                                            level={isFirstBlock ? 2 : 3}
                                            size="h3"
                                            className="font-semibold tracking-tight"
                                        >
                                            <Link
                                                href={detailHref}
                                                className="focus:outline-none group-hover:text-accent-strong transition-colors"
                                            >
                                                {product.name}
                                            </Link>
                                        </Heading>
                                        <p className="text-sm leading-6 text-muted">
                                            {product.summary}
                                        </p>
                                    </div>

                                    {/* 3 Inline Specifications (CV · TM · Ash) */}
                                    <div className="lg:col-span-5">
                                        <dl className="grid grid-cols-3 gap-3 rounded-[var(--radius-card)] border border-line bg-surface p-3.5 sm:gap-4 sm:p-4">
                                            {inlineSpecs.map((spec) => (
                                                <div
                                                    key={spec.id}
                                                    className="border-l-2 border-accent pl-3 first:border-l-0 first:pl-0"
                                                >
                                                    <dt className="text-[11px] font-semibold uppercase tracking-[0.1em] text-muted">
                                                        {spec.code || spec.label}
                                                    </dt>
                                                    <dd className="mt-1 font-heading text-base font-semibold text-ink sm:text-lg">
                                                        {spec.value}
                                                        {spec.unit ? (
                                                            <span className="ml-1 font-body text-xs font-normal text-muted">
                                                                {spec.unit}
                                                            </span>
                                                        ) : null}
                                                    </dd>
                                                </div>
                                            ))}
                                        </dl>
                                    </div>

                                    {/* Action Arrow & Optional Spec PDF Download */}
                                    <div className="lg:col-span-1 flex items-center justify-between gap-3 lg:justify-end">
                                        {data.show_download_button && product.document_url ? (
                                            <a
                                                href={product.document_url}
                                                download
                                                aria-label={`${t('download_pdf')} — ${product.name}`}
                                                className="inline-flex min-h-11 min-w-11 items-center justify-center rounded-[var(--radius-button)] border border-line text-muted transition-colors hover:border-ink hover:text-ink"
                                            >
                                                <Download className="h-4 w-4" aria-hidden="true" />
                                            </a>
                                        ) : null}
                                        <Link
                                            href={detailHref}
                                            aria-label={`${t('products.view_specification')} — ${product.name}`}
                                            className="inline-flex min-h-11 min-w-11 items-center justify-center rounded-[var(--radius-button)] bg-surface-dark text-ink-on-dark transition-colors group-hover:bg-accent-strong"
                                        >
                                            <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                        </Link>
                                    </div>
                                </div>
                            </Reveal>
                        );
                    })}
                </div>
            </Container>
        </Section>
    );
}
