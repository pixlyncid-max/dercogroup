import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, Download, FileCheck2 } from 'lucide-react';
import { useLocale, type ProductData, type SharedPageProps } from '@/core';
import { Button, Container, Heading, Prose, Reveal, Section } from '@/ui';
import { GoldRule } from './GoldRule';

export interface ProductDetailViewProps {
    product: ProductData;
    productsPageUrl?: string | null;
    contactPageUrl?: string | null;
}

/**
 * Korin Berkat Product Detail View ("Editorial Index", DESIGN.md Section 5.4).
 * Displays full coal/commodity specification table, technical description, and PDF specification download.
 */
export function ProductDetailView({
    product,
    productsPageUrl,
    contactPageUrl,
}: ProductDetailViewProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();

    const highlightSpecs = product.specs.filter((spec) => spec.is_highlight).slice(0, 3);
    const otherProducts = (props.products ?? []).filter((item) => item.id !== product.id);
    const catalogUrl =
        productsPageUrl ??
        props.navigation?.items?.find((item) => item.key === 'products')?.href ??
        `/${props.locale}`;
    const inquireUrl =
        contactPageUrl ??
        props.navigation?.items?.find((item) => item.key === 'contact')?.href ??
        catalogUrl;

    return (
        <>
            {/* Editorial Split Header */}
            <Section tone="default" className="border-b border-line">
                <Container>
                    <Reveal preset="fadeUp" className="space-y-8">
                        <div>
                            <Link
                                href={catalogUrl}
                                className="inline-flex min-h-10 items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-muted transition-colors hover:text-ink"
                            >
                                <ArrowLeft
                                    className="h-4 w-4 text-accent-strong"
                                    aria-hidden="true"
                                />
                                <span>{t('products.all_products')}</span>
                            </Link>
                        </div>

                        <div className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-start">
                            <div className="lg:col-span-7 space-y-5">
                                {product.category_label ? (
                                    <p className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-strong">
                                        {product.category_label}
                                    </p>
                                ) : null}

                                <GoldRule thick className="w-16" />

                                <Heading level={1} size="h2">
                                    {product.name}
                                </Heading>

                                <p className="text-base leading-7 text-muted sm:text-lg">
                                    {product.summary}
                                </p>

                                {product.description ? (
                                    <div className="pt-2 border-t border-line">
                                        <h2 className="text-xs font-semibold uppercase tracking-[0.12em] text-ink mb-2">
                                            {t('products.overview_heading')}
                                        </h2>
                                        <Prose className="text-muted">
                                            <p>{product.description}</p>
                                        </Prose>
                                    </div>
                                ) : null}

                                <div className="flex flex-wrap items-center gap-4 pt-4">
                                    {product.document_url ? (
                                        <a
                                            href={product.document_url}
                                            download
                                            className="inline-flex min-h-12 items-center justify-center gap-2.5 rounded-[var(--radius-button)] bg-surface-dark px-6 py-3.5 text-xs font-semibold uppercase tracking-[0.12em] text-ink-on-dark transition-colors hover:bg-surface-deeper"
                                        >
                                            <Download
                                                className="h-4 w-4 text-accent-on-dark"
                                                aria-hidden="true"
                                            />
                                            <span>{t('products.download_spec')}</span>
                                        </a>
                                    ) : null}

                                    <Button
                                        href={inquireUrl}
                                        variant="outline"
                                        size="lg"
                                        className="text-xs font-semibold uppercase tracking-[0.12em]"
                                    >
                                        <span>{t('products.inquire_product')}</span>
                                        <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                    </Button>
                                </div>
                            </div>

                            {/* Right 5 Cols: Primary Grade Summary Panel in Navy */}
                            <div className="lg:col-span-5 rounded-[var(--radius-card)] bg-surface-dark p-7 text-ink-on-dark border-t-4 border-accent sm:p-8">
                                <div className="flex items-center justify-between border-b border-ink-on-dark/15 pb-4">
                                    <span className="text-xs font-semibold uppercase tracking-[0.14em] text-accent-on-dark">
                                        {t('products.highlight_badge')}
                                    </span>
                                    <FileCheck2
                                        className="h-4 w-4 text-accent-on-dark"
                                        aria-hidden="true"
                                    />
                                </div>

                                <dl className="divide-y divide-ink-on-dark/15 my-2">
                                    {highlightSpecs.map((spec) => (
                                        <div
                                            key={spec.id}
                                            className="flex items-baseline justify-between gap-4 py-4"
                                        >
                                            <dt className="flex flex-col">
                                                <span className="text-xs font-medium text-muted-on-dark">
                                                    {spec.label}
                                                </span>
                                                {spec.code ? (
                                                    <span className="text-[11px] font-semibold uppercase tracking-[0.12em] text-accent-on-dark">
                                                        {spec.code}
                                                    </span>
                                                ) : null}
                                            </dt>
                                            <dd className="font-heading text-2xl font-semibold text-ink-on-dark">
                                                {spec.value}
                                                {spec.unit ? (
                                                    <span className="ml-1.5 font-body text-xs font-normal text-muted-on-dark">
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
                </Container>
            </Section>

            {/* Full Specification Table */}
            <Section tone="surface" className="border-b border-line">
                <Container>
                    <Reveal preset="fadeUp" className="space-y-6">
                        <div className="flex flex-wrap items-end justify-between gap-4">
                            <div className="space-y-2">
                                <p className="text-xs font-semibold uppercase tracking-[0.14em] text-ink">
                                    COA / ASTM / ISO STANDARD
                                </p>
                                <Heading level={2} size="h2">
                                    {t('products.specifications')}
                                </Heading>
                            </div>
                            {product.document_url ? (
                                <a
                                    href={product.document_url}
                                    download
                                    className="inline-flex min-h-10 items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-ink hover:underline"
                                >
                                    <Download className="h-4 w-4" aria-hidden="true" />
                                    <span>{t('download_pdf')}</span>
                                </a>
                            ) : null}
                        </div>

                        <div className="overflow-x-auto rounded-[var(--radius-card)] border border-line bg-bg">
                            <table className="w-full border-collapse text-left text-sm">
                                <thead>
                                    <tr className="border-b-2 border-ink bg-surface text-xs font-semibold uppercase tracking-[0.12em] text-ink">
                                        <th scope="col" className="py-4 px-5 w-24">
                                            {t('products.code')}
                                        </th>
                                        <th scope="col" className="py-4 px-5">
                                            {t('products.parameter')}
                                        </th>
                                        <th scope="col" className="py-4 px-5">
                                            {t('products.value')}
                                        </th>
                                        <th scope="col" className="py-4 px-5">
                                            {t('products.unit')}
                                        </th>
                                        <th scope="col" className="py-4 px-5">
                                            {t('products.basis')}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-line">
                                    {product.specs.map((spec) => (
                                        <tr
                                            key={spec.id}
                                            className="transition-colors hover:bg-surface/60"
                                        >
                                            <td className="py-4 px-5 font-heading font-semibold text-accent-strong">
                                                {spec.code || '—'}
                                            </td>
                                            <td className="py-4 px-5 font-medium text-ink">
                                                {spec.label}
                                            </td>
                                            <td className="py-4 px-5 font-heading text-lg font-semibold text-ink">
                                                {spec.value}
                                            </td>
                                            <td className="py-4 px-5 text-muted">
                                                {spec.unit || '—'}
                                            </td>
                                            <td className="py-4 px-5">
                                                <span
                                                    className={`inline-flex items-center rounded-[var(--radius-button)] px-2.5 py-0.5 text-xs font-medium ${
                                                        spec.is_highlight
                                                            ? 'bg-surface-dark text-ink-on-dark'
                                                            : 'bg-surface text-muted'
                                                    }`}
                                                >
                                                    {spec.is_highlight
                                                        ? t('products.highlight_badge')
                                                        : t('products.standard_badge')}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </Reveal>

                    {/* Other Products Index */}
                    {otherProducts.length > 0 ? (
                        <Reveal preset="fadeUp" className="mt-14 space-y-4">
                            <h2 className="text-xs font-semibold uppercase tracking-[0.14em] text-ink">
                                {t('products.catalog_index')}
                            </h2>
                            <div className="divide-y divide-line border-y border-line bg-bg">
                                {otherProducts.map((item, idx) => (
                                    <Link
                                        key={item.id}
                                        href={item.url ?? catalogUrl}
                                        className="flex flex-wrap items-center justify-between gap-4 p-5 transition-transform duration-300 ease-out hover:translate-x-2"
                                    >
                                        <div className="flex items-center gap-4">
                                            <span className="font-heading text-base font-semibold text-accent-strong">
                                                {String(idx + 1).padStart(2, '0')}
                                            </span>
                                            <div>
                                                <p className="font-heading text-lg font-semibold text-ink">
                                                    {item.name}
                                                </p>
                                                <p className="text-xs text-muted">
                                                    {item.category_label}
                                                </p>
                                            </div>
                                        </div>
                                        <ArrowRight
                                            className="h-4 w-4 text-accent-strong"
                                            aria-hidden="true"
                                        />
                                    </Link>
                                ))}
                            </div>
                        </Reveal>
                    ) : null}
                </Container>
            </Section>
        </>
    );
}
