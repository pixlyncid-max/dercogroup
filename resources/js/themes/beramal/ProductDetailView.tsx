import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, Download, ShieldCheck } from 'lucide-react';
import { useLocale, useProductUrls, type ProductData, type SharedPageProps } from '@/core';
import { Button, Container, Heading, Image, Prose, Reveal, Section } from '@/ui';
import { WaveDivider } from './WaveDivider';

export interface ProductDetailViewProps {
    product: ProductData;
    productsPageUrl?: string | null;
    contactPageUrl?: string | null;
}

/**
 * Beramal Sukses Sejahtera Product / Service Detail View ("Calm Flow", DESIGN.md Section 7.4).
 * Rounded 24px/28px cards, capsule highlight badges, and full specification table.
 */
export function ProductDetailView({
    product,
    productsPageUrl,
    contactPageUrl,
}: ProductDetailViewProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const { catalogUrl, catalogLabel, inquireUrl } = useProductUrls({
        productsPageUrl,
        contactPageUrl,
    });

    const highlightSpecs = product.specs.filter((spec) => spec.is_highlight).slice(0, 3);
    const otherProducts = (props.products ?? []).filter((item) => item.id !== product.id);

    return (
        <>
            <Section tone="default">
                <Container>
                    <Reveal preset="fadeUp" className="space-y-8">
                        <div>
                            <Link
                                href={catalogUrl}
                                className="inline-flex min-h-11 items-center gap-2 rounded-full border border-line bg-surface px-4 py-2 text-xs font-bold text-ink transition-colors hover:bg-accent hover:text-on-accent"
                            >
                                <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                                <span>{catalogLabel}</span>
                            </Link>
                        </div>

                        <div className="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-start">
                            {/* Left 7 Cols: Service/Product Narrative & CTA Pills */}
                            <div className="lg:col-span-7 space-y-6">
                                {product.category_label ? (
                                    <div className="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-4 py-1.5 text-xs font-bold text-ink">
                                        <span
                                            aria-hidden="true"
                                            className="h-2 w-2 rounded-full bg-accent"
                                        />
                                        <span>{product.category_label}</span>
                                    </div>
                                ) : null}

                                <Heading level={1} size="h2">
                                    {product.name}
                                </Heading>

                                <p className="text-base leading-7 text-muted sm:text-lg">
                                    {product.summary}
                                </p>

                                {product.description ? (
                                    <div className="rounded-[var(--radius-card)] border border-line bg-surface p-6 space-y-2">
                                        <h2 className="text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                            {t('products.overview_heading')}
                                        </h2>
                                        <Prose className="text-muted">
                                            <p>{product.description}</p>
                                        </Prose>
                                    </div>
                                ) : null}

                                <div className="flex flex-wrap items-center gap-4 pt-2">
                                    {product.document_url ? (
                                        <a
                                            href={product.document_url}
                                            download
                                            className="inline-flex min-h-12 items-center justify-center gap-2.5 rounded-full bg-accent px-7 py-3.5 text-sm font-bold text-on-accent transition-opacity hover:opacity-90"
                                        >
                                            <Download className="h-4 w-4" aria-hidden="true" />
                                            <span>{t('products.download_spec')}</span>
                                        </a>
                                    ) : null}

                                    <Button
                                        href={inquireUrl}
                                        variant="outline"
                                        size="lg"
                                        className="rounded-full text-sm font-bold"
                                    >
                                        <span>{t('products.inquire_product')}</span>
                                        <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                    </Button>
                                </div>
                            </div>

                            {/* Right 5 Cols: Rounded Image + Highlight Capsule Panel */}
                            <div className="lg:col-span-5 space-y-6">
                                <Image
                                    src={product.image_url}
                                    alt={product.image_alt || product.name}
                                    ratio="16/9"
                                    wrapperClassName="shadow-sm"
                                />

                                <div className="rounded-[var(--radius-card)] bg-surface-dark p-7 text-ink-on-dark shadow-md space-y-5">
                                    <div className="flex items-center justify-between border-b border-ink-on-dark/15 pb-4">
                                        <span className="text-xs font-bold uppercase tracking-[0.12em] text-accent-on-dark">
                                            {t('products.highlight_badge')}
                                        </span>
                                        <ShieldCheck
                                            className="h-5 w-5 text-accent-on-dark"
                                            aria-hidden="true"
                                        />
                                    </div>

                                    <dl className="space-y-3">
                                        {highlightSpecs.map((spec) => (
                                            <div
                                                key={spec.id}
                                                className="flex items-center justify-between gap-4 rounded-full bg-surface-deeper px-5 py-3.5"
                                            >
                                                <dt className="flex flex-col">
                                                    <span className="text-xs font-medium text-muted-on-dark">
                                                        {spec.label}
                                                    </span>
                                                    {spec.code ? (
                                                        <span className="text-[11px] font-bold uppercase tracking-[0.1em] text-accent-on-dark">
                                                            {spec.code}
                                                        </span>
                                                    ) : null}
                                                </dt>
                                                <dd className="font-heading text-xl font-bold text-ink-on-dark">
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
                        </div>
                    </Reveal>
                </Container>
            </Section>

            <WaveDivider tone="surface" />

            {/* Full Specification Parameters Table */}
            <Section tone="surface">
                <Container>
                    <Reveal preset="fadeUp" className="space-y-6">
                        <div className="flex flex-wrap items-end justify-between gap-4">
                            <div className="space-y-2">
                                <span className="inline-flex items-center gap-2 rounded-full border border-line bg-bg px-4 py-1 text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                    <span
                                        aria-hidden="true"
                                        className="h-2 w-2 rounded-full bg-accent"
                                    />
                                    <span>{t('products.standard_badge')}</span>
                                </span>
                                <Heading level={2} size="h2">
                                    {t('products.specifications')}
                                </Heading>
                            </div>
                            {product.document_url ? (
                                <a
                                    href={product.document_url}
                                    download
                                    className="inline-flex min-h-11 items-center gap-2 rounded-full border border-line bg-bg px-5 py-2 text-xs font-bold text-ink transition-colors hover:bg-accent hover:text-on-accent"
                                >
                                    <Download className="h-4 w-4" aria-hidden="true" />
                                    <span>{t('download_pdf')}</span>
                                </a>
                            ) : null}
                        </div>

                        <div className="overflow-x-auto rounded-[var(--radius-card)] border border-line bg-bg shadow-sm">
                            <table className="w-full border-collapse text-left text-sm">
                                <thead>
                                    <tr className="border-b border-line bg-surface/60 text-xs font-bold uppercase tracking-[0.1em] text-ink">
                                        <th scope="col" className="py-4 px-6 w-28">
                                            {t('products.code')}
                                        </th>
                                        <th scope="col" className="py-4 px-6">
                                            {t('products.parameter')}
                                        </th>
                                        <th scope="col" className="py-4 px-6">
                                            {t('products.value')}
                                        </th>
                                        <th scope="col" className="py-4 px-6">
                                            {t('products.unit')}
                                        </th>
                                        <th scope="col" className="py-4 px-6">
                                            {t('products.basis')}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-line">
                                    {product.specs.map((spec) => (
                                        <tr
                                            key={spec.id}
                                            className="transition-colors hover:bg-surface/40"
                                        >
                                            <td className="py-4 px-6 font-heading font-bold text-accent-strong">
                                                {spec.code || '—'}
                                            </td>
                                            <td className="py-4 px-6 font-medium text-ink">
                                                {spec.label}
                                            </td>
                                            <td className="py-4 px-6 font-heading text-lg font-bold text-ink">
                                                {spec.value}
                                            </td>
                                            <td className="py-4 px-6 text-muted">
                                                {spec.unit || '—'}
                                            </td>
                                            <td className="py-4 px-6">
                                                <span
                                                    className={`inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ${
                                                        spec.is_highlight
                                                            ? 'bg-accent text-on-accent'
                                                            : 'bg-surface text-ink'
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

                    {otherProducts.length > 0 ? (
                        <Reveal preset="fadeUp" className="mt-14 space-y-5">
                            <h2 className="text-xs font-bold uppercase tracking-[0.12em] text-ink">
                                {t('products.catalog_index')}
                            </h2>
                            <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
                                {otherProducts.map((item) => (
                                    <Link
                                        key={item.id}
                                        href={item.url ?? catalogUrl}
                                        className="group flex items-center justify-between gap-4 rounded-[var(--radius-card)] border border-line bg-bg p-6 shadow-sm transition-shadow hover:shadow-md"
                                    >
                                        <div className="space-y-1">
                                            <p className="font-heading text-lg font-bold text-ink group-hover:text-accent-strong transition-colors">
                                                {item.name}
                                            </p>
                                            <p className="text-xs text-muted">
                                                {item.category_label}
                                            </p>
                                        </div>
                                        <span className="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-surface text-ink transition-colors group-hover:bg-accent group-hover:text-on-accent">
                                            <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                        </span>
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
