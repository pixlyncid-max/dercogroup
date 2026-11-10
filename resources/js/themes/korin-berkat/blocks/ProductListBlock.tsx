import { usePage } from '@inertiajs/react';
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
 * Korin Berkat Product List (DESIGN.md Section 5.4 & Revision 2 B2/B4).
 * - On Home: section title is visually hidden (<h2 className="sr-only">), no download buttons or arrow links.
 * - On Products Page: editorial 2-column layout displaying FULL technical specification tables directly.
 */
export function ProductListBlock({ data, isFirstBlock = false }: ProductListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const allProducts: ProductData[] = props.products ?? [];

    const isHomePage = props.page?.key === 'home';
    const limit = isHomePage && data.limit && data.limit > 0 ? data.limit : allProducts.length;
    const products = allProducts.slice(0, limit);

    return (
        <Section tone="default" className="border-b border-line">
            <Container>
                {isHomePage ? (
                    <h2 className="sr-only">{data.title}</h2>
                ) : (
                    <EditorialHeader
                        indexLabel="01"
                        eyebrow={data.eyebrow}
                        title={data.title}
                        description={data.description}
                        level={isFirstBlock ? 1 : 2}
                    />
                )}

                {isHomePage ? (
                    /* Home Page: Editorial product summary strip with no arrows or download buttons */
                    <div className="divide-y divide-line border-y border-line">
                        {products.map((product, idx) => {
                            const highlightSpecs = product.specs
                                .filter((spec) => spec.is_highlight)
                                .slice(0, 3);
                            const inlineSpecs =
                                highlightSpecs.length > 0
                                    ? highlightSpecs
                                    : product.specs.slice(0, 3);

                            return (
                                <Reveal key={product.id} preset="fadeUp">
                                    <div className="grid grid-cols-1 gap-6 py-8 lg:grid-cols-12 lg:items-center lg:gap-8">
                                        {/* Index 01..03 */}
                                        <div className="lg:col-span-1">
                                            <span className="font-heading text-xl font-semibold text-accent-strong">
                                                {String(idx + 1).padStart(2, '0')}
                                            </span>
                                        </div>

                                        {/* Product Name, Category & Summary */}
                                        <div className="lg:col-span-6 space-y-2">
                                            {product.category_label ? (
                                                <span className="inline-block rounded-[var(--radius-button)] bg-surface px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-[0.1em] text-accent-strong">
                                                    {product.category_label}
                                                </span>
                                            ) : null}
                                            <Heading
                                                level={3}
                                                size="h3"
                                                className="font-heading text-lg font-semibold tracking-tight text-ink"
                                            >
                                                {product.name}
                                            </Heading>
                                            <p className="text-sm leading-relaxed text-muted">
                                                {product.summary}
                                            </p>
                                        </div>

                                        {/* 3 Inline Highlight Specs */}
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
                                                        <dd className="mt-1 font-heading text-base font-semibold text-ink sm:text-lg tabular-nums">
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
                                    </div>
                                </Reveal>
                            );
                        })}
                    </div>
                ) : (
                    /* Products Page: 2-column Editorial Layout with FULL specification table */
                    <div className="mt-12 divide-y divide-line border-y border-line">
                        {products.map((product) => (
                            <Reveal key={product.id} preset="fadeUp">
                                <div className="grid grid-cols-1 gap-8 py-10 lg:grid-cols-12 lg:gap-12 lg:py-12">
                                    {/* Left Column (4/12): Name, Category & Summary */}
                                    <div className="lg:col-span-4 space-y-3">
                                        {product.category_label ? (
                                            <span className="inline-block rounded-[var(--radius-button)] bg-surface px-3 py-1 text-xs font-semibold uppercase tracking-[0.1em] text-accent-strong">
                                                {product.category_label}
                                            </span>
                                        ) : null}
                                        <Heading
                                            level={2}
                                            size="h3"
                                            className="font-heading text-xl lg:text-2xl font-semibold tracking-tight text-ink"
                                        >
                                            {product.name}
                                        </Heading>
                                        <p className="text-sm leading-relaxed text-muted">
                                            {product.summary}
                                        </p>
                                    </div>

                                    {/* Right Column (8/12): FULL Specifications Table */}
                                    <div className="lg:col-span-8">
                                        <div className="border-b-2 border-ink pb-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-muted flex justify-between">
                                            <span>{t('products.specifications')}</span>
                                            <span>{t('products.parameter_value')}</span>
                                        </div>
                                        <div className="divide-y divide-line border-b border-line">
                                            {product.specs.map((spec) => (
                                                <div
                                                    key={spec.id}
                                                    className="flex items-center justify-between py-3 text-sm"
                                                >
                                                    <span className="font-medium text-muted">
                                                        {spec.label || spec.code}
                                                    </span>
                                                    <span className="font-heading font-semibold text-ink tabular-nums">
                                                        {spec.value}
                                                        {spec.unit ? (
                                                            <span className="ml-1 font-body text-xs font-normal text-muted">
                                                                {spec.unit}
                                                            </span>
                                                        ) : null}
                                                    </span>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </Reveal>
                        ))}
                    </div>
                )}
            </Container>
        </Section>
    );
}
