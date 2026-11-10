import { Link, usePage } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, Download } from 'lucide-react';
import { useLocale, type ProductData, type SharedPageProps } from '@/core';
import { Button, Container, Heading, Image, Prose, Reveal, Section } from '@/ui';

export interface ProductDetailViewProps {
    product: ProductData;
    productsPageUrl?: string | null;
    contactPageUrl?: string | null;
}

/**
 * Korin Bara Product Detail View ("Field Rail", DESIGN.md Section 6.4).
 * Wide documentary photograph, brass-gold corner label, and specifications inside a warm cream box.
 */
export function ProductDetailView({
    product,
    productsPageUrl,
    contactPageUrl,
}: ProductDetailViewProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();

    const catalogUrl =
        productsPageUrl ??
        props.navigation?.items?.find((item) => item.key === 'products')?.href ??
        `/${props.locale}`;
    const inquireUrl =
        contactPageUrl ??
        props.navigation?.items?.find((item) => item.key === 'contact')?.href ??
        catalogUrl;

    const widePhoto = product.image_url || '/images/placeholders/coal-stockpile-1280.webp';

    return (
        <Section tone="default" className="border-b-2 border-line">
            <Container className="space-y-12">
                <Reveal preset="fadeUp" className="space-y-6">
                    <div>
                        <Link
                            href={catalogUrl}
                            className="inline-flex min-h-10 items-center gap-2 font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink hover:text-accent-strong"
                        >
                            <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                            <span>{t('products.all_products')}</span>
                        </Link>
                    </div>

                    {/* Wide Photo with Brass Gold Corner Label */}
                    <div className="relative border-2 border-ink bg-surface-dark">
                        <Image
                            src={widePhoto}
                            alt={product.image_alt || product.name}
                            ratio="16/9"
                            wrapperClassName="border-0 rounded-none"
                        />
                        {product.category_label ? (
                            <span className="absolute top-0 right-0 bg-accent px-5 py-2.5 font-heading text-sm font-bold uppercase tracking-[0.14em] text-ink border-b-2 border-l-2 border-ink">
                                {product.category_label}
                            </span>
                        ) : null}
                    </div>

                    <div className="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start pt-4">
                        <div className="lg:col-span-7 space-y-4">
                            <Heading level={1} size="h2" className="text-ink">
                                {product.name}
                            </Heading>
                            <p className="text-base font-medium leading-7 text-ink sm:text-lg">
                                {product.summary}
                            </p>
                            {product.description ? (
                                <Prose className="text-muted">
                                    <p>{product.description}</p>
                                </Prose>
                            ) : null}
                        </div>

                        <div className="lg:col-span-5 flex flex-col gap-3 sm:flex-row lg:flex-col">
                            {product.document_url ? (
                                <a
                                    href={product.document_url}
                                    download
                                    className="inline-flex min-h-12 items-center justify-center gap-2.5 bg-accent px-6 py-3.5 font-heading text-sm font-bold uppercase tracking-[0.14em] text-ink border-2 border-ink transition-colors hover:bg-surface"
                                >
                                    <Download className="h-4 w-4" aria-hidden="true" />
                                    <span>{t('products.download_spec')}</span>
                                </a>
                            ) : null}

                            <Button
                                href={inquireUrl}
                                variant="solid"
                                size="lg"
                                className="bg-surface-dark text-ink-on-dark hover:bg-surface-deeper font-heading text-sm font-bold uppercase tracking-[0.14em]"
                            >
                                <span>{t('products.inquire_product')}</span>
                                <ArrowRight
                                    className="h-4 w-4 text-accent-on-dark"
                                    aria-hidden="true"
                                />
                            </Button>
                        </div>
                    </div>
                </Reveal>

                {/* Specifications in Warm Cream Box (DESIGN.md 6.4) */}
                <Reveal
                    preset="fadeUp"
                    className="border-2 border-ink bg-surface p-6 sm:p-8 lg:p-10 space-y-6"
                >
                    <div className="flex flex-wrap items-center justify-between gap-4 border-b-2 border-line pb-4">
                        <div className="flex items-center gap-3">
                            <span aria-hidden="true" className="h-1.5 w-10 bg-accent" />
                            <Heading level={2} size="h3" className="text-ink">
                                {t('products.specifications')}
                            </Heading>
                        </div>
                        <span className="font-heading text-xs font-bold uppercase tracking-[0.14em] text-accent-strong">
                            ASTM / ISO VERIFIED
                        </span>
                    </div>

                    <div className="overflow-x-auto bg-bg border-2 border-line">
                        <table className="w-full border-collapse text-left text-sm">
                            <thead>
                                <tr className="border-b-2 border-ink bg-surface-dark font-heading text-xs font-bold uppercase tracking-[0.14em] text-ink-on-dark">
                                    <th scope="col" className="py-3.5 px-5 w-24">
                                        {t('products.code')}
                                    </th>
                                    <th scope="col" className="py-3.5 px-5">
                                        {t('products.parameter')}
                                    </th>
                                    <th scope="col" className="py-3.5 px-5">
                                        {t('products.value')}
                                    </th>
                                    <th scope="col" className="py-3.5 px-5">
                                        {t('products.unit')}
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-line">
                                {product.specs.map((spec) => (
                                    <tr key={spec.id}>
                                        <td className="py-4 px-5 font-heading text-base font-bold text-accent-strong">
                                            {spec.code || '—'}
                                        </td>
                                        <td className="py-4 px-5 font-semibold text-ink">
                                            {spec.label}
                                        </td>
                                        <td className="py-4 px-5 font-heading text-xl font-bold text-ink">
                                            {spec.value}
                                        </td>
                                        <td className="py-4 px-5 text-muted">{spec.unit || '—'}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </Reveal>
            </Container>
        </Section>
    );
}
