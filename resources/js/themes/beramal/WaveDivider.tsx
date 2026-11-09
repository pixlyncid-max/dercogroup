export interface WaveDividerProps {
    flip?: boolean;
    tone?: 'surface' | 'bg' | 'dark';
    className?: string;
}

const toneClassMap: Record<NonNullable<WaveDividerProps['tone']>, string> = {
    surface: 'text-surface',
    bg: 'text-bg',
    dark: 'text-surface-dark',
};

/**
 * Calm Flow SVG wave divider between sections (DESIGN.md Section 7.3).
 * Uses theme token classes exclusively (zero hardcoded hex values).
 */
export function WaveDivider({ flip = false, tone = 'surface', className = '' }: WaveDividerProps) {
    return (
        <div
            aria-hidden="true"
            className={[
                'pointer-events-none w-full overflow-hidden leading-none select-none',
                toneClassMap[tone],
                flip ? 'rotate-180' : '',
                className,
            ]
                .filter(Boolean)
                .join(' ')}
        >
            <svg
                viewBox="0 0 1440 56"
                fill="none"
                preserveAspectRatio="none"
                className="block h-8 w-full sm:h-11 lg:h-14"
            >
                <path
                    d="M0 24C240 56 480 56 720 28C960 0 1200 0 1440 28V56H0V24Z"
                    fill="currentColor"
                />
            </svg>
        </div>
    );
}
