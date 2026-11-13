import { Link } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, Download } from 'lucide-react';
import { useLocale, useProductUrls, type ProductData } from '@/core';
import { Container, Image, Prose, Reveal } from '@/ui';

export interface ProductDetailViewProps {
    product: ProductData;
    productsPageUrl?: string | null;
    contactPageUrl?: string | null;
}

export function ProductDetailView({
    product,
    productsPageUrl,
    contactPageUrl,
}: ProductDetailViewProps) {
    const { t } = useLocale();
    const { catalogUrl, inquireUrl } = useProductUrls({
        productsPageUrl,
        contactPageUrl,
    });

    const widePhoto = product.image_url || '/images/placeholders/coal-stockpile-1280.webp';

    return (
        <section className="py-12 lg:py-16 bg-white border-b border-line">
            <Container className="space-y-12">
                <Reveal preset="fadeUp" className="space-y-6">
                    <div>
                        <Link
                            href={catalogUrl}
                            className="inline-flex min-h-[44px] items-center gap-2 text-sm font-semibold text-slate-600 hover:text-[#101F38] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:rounded-md"
                        >
                            <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                            <span>{t('products.all_products')}</span>
                        </Link>
                    </div>

                    {/* Product Photo with Radius 12 & Shadow */}
                    <div className="relative overflow-hidden rounded-xl shadow-lg">
                        <Image
                            src={widePhoto}
                            alt={product.image_alt || product.name}
                            ratio="16/9"
                            wrapperClassName="border-0 rounded-none h-full w-full"
                            className="h-[320px] sm:h-[420px] lg:h-[480px] w-full object-cover"
                        />
                        {product.category_label ? (
                            <span className="absolute top-4 right-4 rounded-full bg-[#8A6212] px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white shadow-md">
                                {product.category_label}
                            </span>
                        ) : null}
                    </div>

                    <div className="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start pt-4">
                        <div className="lg:col-span-7 space-y-4">
                            <span className="kb-eyebrow">{t('products.detail_eyebrow')}</span>
                            <h2 className="font-heading text-3xl font-bold tracking-tight text-[#101F38] sm:text-4xl">
                                {product.name}
                            </h2>
                            <p className="text-base font-medium leading-relaxed text-slate-700 sm:text-lg">
                                {product.summary}
                            </p>
                            {product.description ? (
                                <Prose className="text-slate-600">
                                    <p>{product.description}</p>
                                </Prose>
                            ) : null}
                        </div>

                        <div className="lg:col-span-5 flex flex-col gap-4 sm:flex-row lg:flex-col lg:pl-6">
                            {product.document_url ? (
                                <a
                                    href={product.document_url}
                                    download
                                    className="kb-btn-outline min-h-[44px]"
                                >
                                    <Download className="h-4 w-4" aria-hidden="true" />
                                    <span>{t('products.download_spec')}</span>
                                </a>
                            ) : null}

                            <Link
                                href={inquireUrl}
                                className="kb-btn-solid min-h-[44px]"
                            >
                                <span>{t('products.inquire_product')}</span>
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </Link>
                        </div>
                    </div>
                </Reveal>

                {/* Complete Specifications Table */}
                <Reveal
                    preset="fadeUp"
                    className="rounded-xl border border-line bg-white p-6 sm:p-8 shadow-md space-y-6"
                >
                    <div className="flex flex-wrap items-center justify-between gap-4 border-b border-line pb-4">
                        <div className="flex items-center gap-3">
                            <h3 className="font-heading text-xl font-bold text-[#101F38]">
                                {t('products.specifications')}
                            </h3>
                        </div>
                        <span className="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-[#8A6212]">
                            {t('products.highlight_badge')}
                        </span>
                    </div>

                    <div className="overflow-x-auto rounded-lg border border-line">
                        <table className="w-full border-collapse text-left text-sm">
                            <thead>
                                <tr className="border-b border-line bg-[#F8FAFC] text-xs font-bold uppercase tracking-wider text-slate-600">
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
                                    <tr key={spec.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="py-3.5 px-5 font-mono text-sm font-bold text-[#8A6212]">
                                            {spec.code || '—'}
                                        </td>
                                        <td className="py-3.5 px-5 font-medium text-slate-800">
                                            {spec.label}
                                        </td>
                                        <td className="py-3.5 px-5 font-heading text-base font-bold text-[#101F38]">
                                            {spec.value}
                                        </td>
                                        <td className="py-3.5 px-5 text-slate-500">{spec.unit || '—'}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </Reveal>
            </Container>
        </section>
    );
}
