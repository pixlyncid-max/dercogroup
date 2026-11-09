import { describe, expect, it } from 'vitest';
import { setI18nContext, t, translateFromDictionary } from '@/core/i18n';
import { resolvePreset } from '@/motion/presets';

describe('i18n helpers', () => {
    const dictionary = {
        contact_us: 'Hubungi Kami',
        greeting: 'Halo, :name dari :city',
        foundation: {
            title: 'PT Derco Bara Energy',
        },
    };

    it('resolves top-level and nested translation keys', () => {
        expect(translateFromDictionary(dictionary, 'contact_us')).toBe('Hubungi Kami');
        expect(translateFromDictionary(dictionary, 'foundation.title')).toBe(
            'PT Derco Bara Energy',
        );
    });

    it('interpolates named replacements and returns key when missing', () => {
        expect(
            translateFromDictionary(dictionary, 'greeting', {
                name: 'Budi',
                city: 'Jakarta',
            }),
        ).toBe('Halo, Budi dari Jakarta');

        expect(translateFromDictionary(dictionary, 'missing.key')).toBe('missing.key');
    });

    it('synchronizes active dictionary for standalone t() calls', () => {
        setI18nContext('id', dictionary);
        expect(t('foundation.title')).toBe('PT Derco Bara Energy');
    });
});

describe('motion presets', () => {
    it('returns reduced fade preset when prefers-reduced-motion is active', () => {
        const standard = resolvePreset('fadeUp', false);
        const reduced = resolvePreset('fadeUp', true);

        expect(standard.hidden).toEqual({ opacity: 0, y: 24 });
        expect(reduced.hidden).toEqual({ opacity: 0 });
    });
});
