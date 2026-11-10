import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Download } from 'lucide-react';
import {
    useLocale,
    type ProductData,
    type ProductListBlockData,
    type SharedPageProps,
} from '@/core';
import { Container, Heading, Reveal, Section } from '@/ui';
import { FieldSectionHeader } from '../FieldSectionHeader';

export interface ProductListBlockProps {
    data: ProductListBlockData;
    isFirstBlock?: boolean;
}

/**
 * Korin Bara Product List ("Field Rail", DESIGN.md Section 6.4).
 * Home page: 3 large industrial tiles ("Apa yang kami lakukan").
 * Products page: 2-column large tiles with Brass Gold Corner Labels and specifications in a warm cream box.
 */
export function ProductListBlock({ data, isFirstBlock = false }: ProductListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const allProducts: ProductData[] = props.products ?? [];

    const limit = data.limit && data.limit > 0 ? data.limit : allProducts.length;
    const products = allProducts.slice(0, limit);

    const isHomePage = props.page?.key === 'home';
    const productsNavHref = props.navigation?.items?.find((item) => item.key === 'products')?.href;

    const gridColsClass = isHomePage ? 'grid-cols-1 lg:grid-cols-3' : 'grid-cols-1 lg:grid-cols-2';

    return (
        <Section tone="default" className="border-b-2 border-line">
            <Container>
                <FieldSectionHeader
                    eyebrow={data.eyebrow}
                    title={data.title}
                    description={data.description}
                    level={isFirstBlock ? 1 : 2}
                    action={
                        isHomePage && productsNavHref ? (
                            <Link
                                href={productsNavHref}
                                className="inline-flex min-h-11 items-center gap-2 border-2 border-ink bg-surface px-4 py-2 font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink transition-colors hover:bg-surface-dark hover:text-ink-on-dark"
                            >
                                <span>{t('see_details')}</span>
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        ) : null
                    }
                />

                <div className={`mt-12 grid gap-8 ${gridColsClass}`}>
                    {products.map((product, idx) => {
                        const highlightSpecs = product.specs
                            .filter((spec) => spec.is_highlight)
                            .slice(0, 3);
                        const inlineSpecs =
                            highlightSpecs.length > 0 ? highlightSpecs : product.specs.slice(0, 3);
                        const detailHref = product.url ?? productsNavHref ?? '#';

                        return (
                            <Reveal
                                key={product.id}
                                preset="fadeUp"
                                className="relative flex flex-col justify-between border-2 border-ink bg-bg p-7 sm:p-8"
                            >
                                {/* Brass Gold Corner Label */}
                                <div className="flex items-start justify-between gap-4 border-b-2 border-line pb-5">
                                    <span className="font-heading text-2xl font-bold text-accent-strong">
                                        {String(idx + 1).padStart(2, '0')}
                                    </span>
                                    {product.category_label ? (
                                        <span className="-mt-7 -mr-7 sm:-mt-8 sm:-mr-8 bg-accent px-4 py-2 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink border-b-2 border-l-2 border-ink">
                                            {product.category_label}
                                        </span>
                                    ) : null}
                                </div>

                                <div className="my-6 space-y-4">
                                    <Heading
                                        level={isFirstBlock ? 2 : 3}
                                        size="h3"
                                        className="text-ink"
                                    >
                                        <Link href={detailHref} className="hover:underline">
                                            {product.name}
                                        </Link>
                                    </Heading>

                                    <p className="text-sm leading-6 text-muted">
                                        {product.summary}
                                    </p>

                                    {/* Specifications in a Warm Cream Box */}
                                    <dl className="grid grid-cols-3 gap-3 border-2 border-line bg-surface p-4">
                                        {inlineSpecs.map((spec) => (
                                            <div key={spec.id} className="space-y-1">
                                                <dt className="font-heading text-xs font-bold uppercase tracking-[0.1em] text-accent-strong">
                                                    {spec.code || spec.label}
                                                </dt>
                                                <dd className="font-heading text-lg font-bold text-ink">
                                                    {spec.value}
                                                    {spec.unit ? (
                                                        <span className="ml-1 font-body text-xs font-medium text-muted">
                                                            {spec.unit}
                                                        </span>
                                                    ) : null}
                                                </dd>
                                            </div>
                                        ))}
                                    </dl>
                                </div>

                                <div className="flex flex-wrap items-center justify-between gap-3 border-t border-line pt-5">
                                    <Link
                                        href={detailHref}
                                        className="inline-flex min-h-11 items-center gap-2 bg-surface-dark px-5 py-2.5 font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink-on-dark transition-colors hover:bg-surface-deeper"
                                    >
                                        <span>{t('products.view_specification')}</span>
                                        <ArrowRight
                                            className="h-4 w-4 text-accent-on-dark"
                                            aria-hidden="true"
                                        />
                                    </Link>

                                    {data.show_download_button && product.document_url ? (
                                        <a
                                            href={product.document_url}
                                            download
                                            aria-label={`${t('download_pdf')} — ${product.name}`}
                                            className="inline-flex min-h-11 items-center gap-2 border-2 border-ink bg-surface px-4 py-2 font-heading text-xs font-bold uppercase tracking-[0.12em] text-ink hover:bg-accent"
                                        >
                                            <Download className="h-4 w-4" aria-hidden="true" />
                                            <span>PDF</span>
                                        </a>
                                    ) : null}
                                </div>
                            </Reveal>
                        );
                    })}
                </div>
            </Container>
        </Section>
    );
}
