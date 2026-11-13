import { Link, usePage } from '@inertiajs/react';
import { ArrowRight, Droplets, Flame, Layers, Sparkles, type LucideIcon } from 'lucide-react';
import { useLocale, type ProductData, type ProductListBlockData, type ProductSpecData, type SharedPageProps } from '@/core';
import { Container, Image, Reveal } from '@/ui';

export interface ProductListBlockProps {
    data: ProductListBlockData;
    isFirstBlock?: boolean;
}

const specIcons: Record<string, LucideIcon> = {
    calori: Flame,
    kalori: Flame,
    cv: Flame,
    moisture: Droplets,
    tm: Droplets,
    air: Droplets,
    ash: Layers,
    abu: Layers,
    sulfur: Sparkles,
    belerang: Sparkles,
    ts: Sparkles,
};

export function ProductListBlock({ data }: ProductListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const allProducts: ProductData[] = props.products ?? [];

    const isHomePage = props.page?.key === 'home';
    const limit = isHomePage
        ? data.limit && data.limit > 0
            ? data.limit
            : 1
        : data.limit && data.limit > 0
          ? data.limit
          : allProducts.length;
    const products = allProducts.slice(0, limit);

    const productsUrl =
        props.navigation?.items?.find((i) => i.key === 'products')?.href ||
        `/${props.locale}/produk`;

    // Fallback if no products seeded
    const fallbackSpecs: ProductSpecData[] = [
        { id: 1, label: 'Kalori', value: '4.800 – 6.200 kcal/kg [PLACEHOLDER]', is_highlight: true },
        { id: 2, label: 'Total Moisture', value: '≤ 12% [PLACEHOLDER]', is_highlight: true },
        { id: 3, label: 'Ash Content', value: '≤ 8% [PLACEHOLDER]', is_highlight: true },
        { id: 4, label: 'Sulfur Content', value: '≤ 1% [PLACEHOLDER]', is_highlight: true },
    ];

    const displayProducts =
        products.length > 0
            ? products
            : [
                  {
                      id: 1,
                      slug: 'thermal-coal-gar-4200',
                      name: 'Batubara Berkualitas Tinggi',
                      summary:
                          'Kami menyediakan batubara dengan spesifikasi terbaik untuk mendukung kebutuhan energi dan industri, dengan kualitas yang terjamin dan pasokan yang stabil. [PLACEHOLDER]',
                      description:
                          'Kami menyediakan batubara dengan spesifikasi terbaik untuk mendukung kebutuhan energi dan industri, dengan kualitas yang terjamin dan pasokan yang stabil. [PLACEHOLDER]',
                      url: '/produk/thermal-coal-gar-4200',
                      image_url: '/images/placeholders/coal-stockpile-1280.webp',
                      specs: fallbackSpecs,
                  },
              ];

    return (
        <section
            data-testid="korin-bara-products"
            className="py-16 lg:py-24 bg-white border-b border-line"
        >
            <Container className="space-y-24">
                {displayProducts.map((product, pIdx) => {
                    const productImage =
                        product.image_url || '/images/placeholders/coal-stockpile-1280.webp';
                    const detailHref = product.url || '#';

                    // 4 general specs
                    const defaultSpecs = [
                        { name: 'Kalori', value: '4.800 – 6.200 kcal/kg [PLACEHOLDER]', icon: Flame },
                        { name: 'Total Moisture', value: '≤ 12% [PLACEHOLDER]', icon: Droplets },
                        { name: 'Ash Content', value: '≤ 8% [PLACEHOLDER]', icon: Layers },
                        { name: 'Sulfur Content', value: '≤ 1% [PLACEHOLDER]', icon: Sparkles },
                    ];

                    const displaySpecs =
                        product.specs && product.specs.length >= 4
                            ? product.specs.slice(0, 4).map((spec, sIdx) => {
                                  const key = spec.label.toLowerCase();
                                  const IconComponent =
                                      Object.entries(specIcons).find(([k]) => key.includes(k))?.[1] ||
                                      defaultSpecs[sIdx]?.icon ||
                                      Flame;
                                  const displayValue =
                                      spec.unit && !spec.value.includes(spec.unit)
                                          ? `${spec.value} ${spec.unit}`
                                          : spec.value;
                                  return {
                                      name: spec.label,
                                      value: displayValue,
                                      icon: IconComponent,
                                  };
                              })
                            : defaultSpecs;

                    return (
                        <div
                            key={product.id || pIdx}
                            className="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12"
                        >
                            {/* Left Column (4 cols): Eyebrow, H2, Summary, Pill Button */}
                            <Reveal preset="fadeUp" className="space-y-6 lg:col-span-4">
                                <div>
                                    <span className="kb-eyebrow">
                                        {data.eyebrow || t('products.featured_eyebrow')}
                                    </span>
                                </div>

                                <h2 className="font-heading text-3xl font-bold tracking-tight text-[#101F38] sm:text-4xl">
                                    {product.name}
                                </h2>

                                <p className="text-base leading-relaxed text-slate-600 sm:text-lg">
                                    {product.summary || product.description}
                                </p>

                                <div className="pt-2">
                                    <Link
                                        href={detailHref}
                                        className="kb-btn-solid min-h-[44px]"
                                    >
                                        <span>{t('products.view_specs')}</span>
                                        <ArrowRight className="h-4 w-4" aria-hidden="true" />
                                    </Link>
                                </div>
                            </Reveal>

                            {/* Middle Column (4 cols): Product Photo */}
                            <Reveal preset="fadeIn" className="lg:col-span-4">
                                <div className="overflow-hidden rounded-xl shadow-lg">
                                    <Image
                                        src={productImage}
                                        alt={product.name || 'Produk Batubara'}
                                        ratio="4/3"
                                        wrapperClassName="border-0 rounded-none h-full w-full"
                                        className="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                                    />
                                </div>
                            </Reveal>

                            {/* Right Column (4 cols): General Specifications Card */}
                            <Reveal preset="fadeUp" className="lg:col-span-4">
                                <div className="rounded-xl border border-line bg-white p-6 shadow-md lg:p-7">
                                    <h3 className="font-heading text-lg font-bold text-[#101F38] border-b border-line pb-4">
                                        {t('products.general_specs')}
                                    </h3>

                                    <div className="divide-y divide-line">
                                        {displaySpecs.map((spec, sIdx) => {
                                            const IconComp = spec.icon;
                                            return (
                                                <div
                                                    key={sIdx}
                                                    className="flex items-center justify-between py-3.5 first:pt-4 last:pb-0"
                                                >
                                                    <div className="flex items-center gap-3">
                                                        <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-[#8A6212]">
                                                            <IconComp className="h-4 w-4 stroke-[2]" aria-hidden="true" />
                                                        </div>
                                                        <span className="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                                            {spec.name}
                                                        </span>
                                                    </div>
                                                    <span className="font-heading text-sm font-bold text-[#101F38] text-right">
                                                        {spec.value}
                                                    </span>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            </Reveal>
                        </div>
                    );
                })}

                {isHomePage ? (
                    <Reveal preset="fadeUp" className="flex justify-center pt-4">
                        <Link
                            href={productsUrl}
                            className="kb-btn-outline min-h-[44px]"
                        >
                            <span>{t('products.view_all_products')}</span>
                            <ArrowRight className="h-4 w-4" aria-hidden="true" />
                        </Link>
                    </Reveal>
                ) : null}
            </Container>
        </section>
    );
}
