import { usePage } from '@inertiajs/react';
import { ArrowRight, Check, MapPin } from 'lucide-react';
import { useLocale, type OperationData, type OperationListBlockData, type SharedPageProps } from '@/core';
import { Container, Image, Reveal } from '@/ui';

export interface OperationListBlockProps {
    data: OperationListBlockData;
    isFirstBlock?: boolean;
}

const OPERATION_GRID_IMAGES = [
    '/images/placeholders/excavator-loading-1280.webp',
    '/images/placeholders/haul-trucks-1280.webp',
    '/images/placeholders/aerial-mine-1280.webp',
    '/images/placeholders/coal-stockpile-1280.webp',
] as const;

export function OperationListBlock({ data }: OperationListBlockProps) {
    const { props } = usePage<SharedPageProps>();
    const { t } = useLocale();
    const operations: OperationData[] = props.operations ?? [];

    const eyebrow = data.eyebrow || t('operations.activity_eyebrow');
    const title = data.title || t('operations.activity_title');
    const description =
        data.description ||
        'Kami menjalankan kegiatan pertambangan dengan standar keselamatan tinggi, efisiensi operasional, serta kepedulian terhadap lingkungan. [PLACEHOLDER]';

    const checklistItems =
        operations.length > 0
            ? operations.slice(0, 3).map((op) => op.name)
            : [
                  'Penambangan Terbuka (Open Pit Mining)',
                  'Pengangkutan dan Hauling',
                  'Pengelolaan Lingkungan',
              ];

    return (
        <section
            data-testid="korin-bara-operations"
            className="py-16 lg:py-24 bg-white border-b border-line"
        >
            <Container className="space-y-20">
                {/* Split: Activity Intro (Left) & 2x2 Photo Grid (Right) */}
                <div className="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-16">
                    {/* Left Column: Intro & Checklist */}
                    <Reveal preset="fadeUp" className="space-y-6 lg:col-span-6 lg:border-r lg:border-line lg:pr-12">
                        {eyebrow ? (
                            <div>
                                <span className="kb-eyebrow">{eyebrow}</span>
                            </div>
                        ) : null}

                        <h2 className="font-heading text-3xl font-bold tracking-tight text-[#101F38] sm:text-4xl">
                            {title}
                        </h2>

                        <p className="text-base leading-relaxed text-slate-600 sm:text-lg">
                            {description}
                        </p>

                        {/* Checklist */}
                        <ul className="space-y-3 pt-2">
                            {checklistItems.map((item, idx) => (
                                <li key={idx} className="flex items-center gap-3 text-base font-semibold text-[#101F38]">
                                    <span className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#8A6212] text-white">
                                        <Check className="h-3 w-3 stroke-[3]" aria-hidden="true" />
                                    </span>
                                    <span>{item}</span>
                                </li>
                            ))}
                        </ul>

                        {/* Pill Anchor Button */}
                        <div className="pt-4">
                            <a
                                href="#daftar-operasi"
                                className="kb-btn-solid min-h-[44px]"
                            >
                                <span>{t('operations.view_details')}</span>
                                <ArrowRight className="h-4 w-4" aria-hidden="true" />
                            </a>
                        </div>
                    </Reveal>

                    {/* Right Column: 2x2 Photo Grid */}
                    <Reveal preset="fadeIn" className="lg:col-span-6">
                        <div className="grid grid-cols-2 gap-4">
                            {OPERATION_GRID_IMAGES.map((imgSrc, idx) => (
                                <div
                                    key={idx}
                                    className="overflow-hidden rounded-lg shadow-xs"
                                >
                                    <Image
                                        src={imgSrc}
                                        alt={`Aktivitas Operasi ${idx + 1}`}
                                        ratio="4/3"
                                        wrapperClassName="border-0 rounded-none h-full w-full"
                                        className="h-full w-full object-cover transition-transform duration-300 hover:scale-105"
                                    />
                                </div>
                            ))}
                        </div>
                    </Reveal>
                </div>

                {/* Operations Directory List Below */}
                {operations.length > 0 ? (
                    <div id="daftar-operasi" className="pt-8 border-t border-line scroll-mt-24 space-y-6">
                        <div className="flex flex-col gap-1">
                            <span className="kb-eyebrow">{t('operations.directory_eyebrow')}</span>
                            <h3 className="font-heading text-2xl font-bold text-[#101F38]">
                                {t('operations.directory_title')}
                            </h3>
                        </div>

                        <div className="divide-y divide-line rounded-xl border border-line bg-white shadow-xs">
                            {operations.map((op) => (
                                <div
                                    key={op.id}
                                    className="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6"
                                >
                                    <div className="space-y-1">
                                        <div className="flex items-center gap-3">
                                            <span className="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider text-[#8A6212]">
                                                {op.type}
                                            </span>
                                            <h4 className="font-heading text-lg font-bold text-[#101F38]">
                                                {op.name}
                                            </h4>
                                        </div>
                                        {op.description ? (
                                            <p className="text-sm text-slate-600">{op.description}</p>
                                        ) : null}
                                    </div>

                                    {op.latitude && op.longitude ? (
                                        <div className="flex items-center gap-2 text-xs font-mono font-medium text-slate-500 bg-slate-50 px-3 py-1.5 rounded-md self-start sm:self-auto">
                                            <MapPin className="h-3.5 w-3.5 text-[#8A6212] shrink-0" aria-hidden="true" />
                                            <span>{`${op.latitude.toFixed(4)}, ${op.longitude.toFixed(4)}`}</span>
                                        </div>
                                    ) : op.location_label ? (
                                        <div className="flex items-center gap-2 text-xs font-medium text-slate-500 bg-slate-50 px-3 py-1.5 rounded-md self-start sm:self-auto">
                                            <MapPin className="h-3.5 w-3.5 text-[#8A6212] shrink-0" aria-hidden="true" />
                                            <span>{op.location_label}</span>
                                        </div>
                                    ) : null}
                                </div>
                            ))}
                        </div>
                    </div>
                ) : null}
            </Container>
        </section>
    );
}
