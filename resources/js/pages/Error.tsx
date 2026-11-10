import { useEffect, useState } from 'react';
import { Head, usePage } from '@inertiajs/react';
import { Home, LifeBuoy } from 'lucide-react';
import { useLocale, type SharedPageProps } from '@/core';
import { Button, Container, Heading, Reveal, Section } from '@/ui';
import {
    getLoadedTheme,
    preloadTheme,
    resolveTheme,
    type ThemeRegistration,
} from '@/themes/registry';

export interface ErrorPageProps {
    status?: number;
    theme?: string;
}

export default function Error({ status = 404 }: ErrorPageProps) {
    const { props } = usePage<SharedPageProps & { status?: number; theme?: string }>();
    const { locale } = useLocale();

    const errorStatus = status || props.status || 404;
    const themeKey = props.theme || props.company?.theme || 'holding';
    const [themeReg, setThemeReg] = useState<ThemeRegistration>(() => resolveTheme(themeKey));

    useEffect(() => {
        let isMounted = true;
        const loaded = getLoadedTheme(themeKey);
        if (loaded) {
            setThemeReg(loaded);
        } else {
            preloadTheme(themeKey).then((resolved) => {
                if (isMounted) setThemeReg(resolved);
            });
        }
        return () => {
            isMounted = false;
        };
    }, [themeKey]);

    const { Layout } = themeReg;

    const is404 = errorStatus === 404;
    const isEn = locale === 'en';

    const title = is404
        ? isEn
            ? 'Page Not Found — 404'
            : 'Halaman Tidak Ditemukan — 404'
        : isEn
          ? 'Internal Server Error — 500'
          : 'Terjadi Gangguan Sistem — 500';

    const headline = is404
        ? isEn
            ? 'The requested page could not be found'
            : 'Halaman yang Anda tuju tidak ditemukan'
        : isEn
          ? 'Internal server technical issue'
          : 'Sistem sedang mengalami kendala teknis';

    const message = is404
        ? isEn
            ? 'The URL may be mistyped, the page has been moved, or it is currently in unpublished draft status.'
            : 'Tautan yang Anda tuju mungkin salah ketik, telah berpindah alamat, atau berstatus draft yang belum ditayangkan.'
        : isEn
          ? 'We apologize for the inconvenience. Our technical administration team has been notified.'
          : 'Mohon maaf atas ketidaknyamanan ini. Tim teknis kami telah menerima notifikasi perbaikan.';

    const homeUrl = `/${locale}`;
    const contactUrl = isEn ? `/${locale}/contact` : `/${locale}/kontak`;

    return (
        <Layout>
            <Head title={title}>
                <meta name="robots" content="noindex, nofollow" />
            </Head>

            <Section tone="surface" className="min-h-[70vh] flex items-center justify-center py-20">
                <Container size="narrow" className="text-center">
                    <Reveal preset="fadeUp" className="space-y-6">
                        <div className="inline-flex items-center gap-2 rounded-full border border-line bg-bg px-4 py-1.5 text-xs font-mono font-bold uppercase tracking-[0.14em] text-accent-strong">
                            <span>HTTP {errorStatus}</span>
                        </div>

                        <Heading level={1} size="display" className="text-ink">
                            {headline}
                        </Heading>

                        <p className="mx-auto max-w-[54ch] text-base leading-7 text-muted">
                            {message}
                        </p>

                        <div className="flex flex-wrap items-center justify-center gap-4 pt-6">
                            <Button
                                href={homeUrl}
                                variant="solid"
                                size="lg"
                                className="text-xs font-bold uppercase tracking-[0.12em]"
                            >
                                <Home className="h-4 w-4" aria-hidden="true" />
                                <span>{isEn ? 'Back to Homepage' : 'Kembali ke Beranda'}</span>
                            </Button>

                            <Button
                                href={contactUrl}
                                variant="outline"
                                size="lg"
                                className="text-xs font-bold uppercase tracking-[0.12em]"
                            >
                                <LifeBuoy className="h-4 w-4" aria-hidden="true" />
                                <span>{isEn ? 'Contact Support' : 'Hubungi Dukungan'}</span>
                            </Button>
                        </div>
                    </Reveal>
                </Container>
            </Section>
        </Layout>
    );
}
