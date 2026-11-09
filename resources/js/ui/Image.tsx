import type { ImgHTMLAttributes } from 'react';

export type ImageRatio = '16/9' | '4/3' | '1/1' | '3/4';

export interface ResponsiveSource {
    srcSet: string;
    type?: string;
    media?: string;
    sizes?: string;
}

export interface ImageProps extends Omit<
    ImgHTMLAttributes<HTMLImageElement>,
    'src' | 'srcSet' | 'alt'
> {
    src?: string | null;
    srcSet?: string | null;
    alt: string;
    ratio?: ImageRatio;
    sources?: ResponsiveSource[];
    priority?: boolean;
    wrapperClassName?: string;
}

const ratioClassMap: Record<ImageRatio, string> = {
    '16/9': 'aspect-[16/9]',
    '4/3': 'aspect-[4/3]',
    '1/1': 'aspect-square',
    '3/4': 'aspect-[3/4]',
};

const ratioLabelMap: Record<ImageRatio, string> = {
    '16/9': '16:9',
    '4/3': '4:3',
    '1/1': '1:1',
    '3/4': '3:4',
};

const ratioViewBoxMap: Record<ImageRatio, string> = {
    '16/9': '0 0 1600 900',
    '4/3': '0 0 1200 900',
    '1/1': '0 0 1000 1000',
    '3/4': '0 0 900 1200',
};

export function Image({
    src,
    srcSet,
    alt,
    ratio = '16/9',
    sources = [],
    priority = false,
    wrapperClassName = '',
    className = '',
    sizes = '(max-width: 768px) 100vw, 50vw',
    ...imgRest
}: ImageProps) {
    const wrapperClasses = [
        'block relative overflow-hidden rounded-[var(--radius-card)] bg-surface border border-line',
        ratioClassMap[ratio],
        wrapperClassName,
    ]
        .filter(Boolean)
        .join(' ');

    if (!src) {
        return (
            <figure className={wrapperClasses} role="img" aria-label={alt}>
                <svg
                    viewBox={ratioViewBoxMap[ratio]}
                    className="h-full w-full text-muted"
                    aria-hidden="true"
                    preserveAspectRatio="xMidYMid slice"
                >
                    <rect width="100%" height="100%" fill="currentColor" fillOpacity="0.08" />
                    <line
                        x1="0"
                        y1="0"
                        x2="100%"
                        y2="100%"
                        stroke="currentColor"
                        strokeOpacity="0.18"
                        strokeWidth="2"
                    />
                    <line
                        x1="100%"
                        y1="0"
                        x2="0"
                        y2="100%"
                        stroke="currentColor"
                        strokeOpacity="0.18"
                        strokeWidth="2"
                    />
                </svg>
                <div className="pointer-events-none absolute inset-0 flex flex-col items-center justify-center gap-1 p-4 text-center">
                    <span className="rounded-[var(--radius-button)] border border-line bg-bg/90 px-2.5 py-1 font-mono text-xs font-medium text-muted">
                        {ratioLabelMap[ratio]}
                    </span>
                </div>
            </figure>
        );
    }

    return (
        <picture className={wrapperClasses}>
            {sources.map((source, index) => (
                <source
                    key={`${source.srcSet}-${index}`}
                    srcSet={source.srcSet}
                    type={source.type}
                    media={source.media}
                    sizes={source.sizes}
                />
            ))}
            <img
                src={src}
                srcSet={srcSet ?? undefined}
                sizes={srcSet ? sizes : undefined}
                alt={alt}
                loading={priority ? 'eager' : 'lazy'}
                fetchPriority={priority ? 'high' : 'auto'}
                decoding={priority ? 'sync' : 'async'}
                className={['h-full w-full object-cover', className].filter(Boolean).join(' ')}
                {...imgRest}
            />
        </picture>
    );
}
