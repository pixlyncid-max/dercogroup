import { useLocale } from '@/core/i18n';

export interface LanguageSwitchProps {
    className?: string;
}

export function LanguageSwitch({ className = '' }: LanguageSwitchProps) {
    const { locale, locales, switchUrl, t } = useLocale();

    return (
        <nav
            aria-label={t('language')}
            className={['inline-flex items-center gap-1 text-xs font-semibold uppercase', className]
                .filter(Boolean)
                .join(' ')}
        >
            {locales.map((code, index) => {
                const isActive = code === locale;

                return (
                    <span key={code} className="inline-flex items-center gap-1">
                        {index > 0 ? (
                            <span aria-hidden="true" className="opacity-40">
                                |
                            </span>
                        ) : null}
                        <a
                            href={switchUrl(code)}
                            lang={code}
                            aria-current={isActive ? 'true' : undefined}
                            className={[
                                'inline-flex min-h-[44px] min-w-[36px] items-center justify-center px-2 transition-opacity focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2',
                                isActive
                                    ? 'text-accent-on-dark underline decoration-accent decoration-2 underline-offset-4'
                                    : 'opacity-75 hover:opacity-100',
                            ].join(' ')}
                        >
                            {code.toUpperCase()}
                        </a>
                    </span>
                );
            })}
        </nav>
    );
}
