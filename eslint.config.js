import js from '@eslint/js';
import globals from 'globals';
import tseslint from 'typescript-eslint';
import reactPlugin from 'eslint-plugin-react';
import reactHooksPlugin from 'eslint-plugin-react-hooks';

export default tseslint.config(
    {
        ignores: ['node_modules/**', 'vendor/**', 'public/**', 'bootstrap/ssr/**', 'storage/**'],
    },
    js.configs.recommended,
    ...tseslint.configs.recommended,
    {
        files: ['resources/js/**/*.{ts,tsx}'],
        languageOptions: {
            ecmaVersion: 2022,
            globals: {
                ...globals.browser,
                ...globals.node,
            },
            parserOptions: {
                ecmaFeatures: {
                    jsx: true,
                },
            },
        },
        plugins: {
            react: reactPlugin,
            'react-hooks': reactHooksPlugin,
        },
        settings: {
            react: {
                version: 'detect',
            },
        },
        rules: {
            ...reactPlugin.configs.recommended.rules,
            ...reactHooksPlugin.configs.recommended.rules,
            'react/react-in-jsx-scope': 'off',
            'react/prop-types': 'off',
            '@typescript-eslint/explicit-function-return-type': 'off',
            '@typescript-eslint/no-unused-vars': [
                'error',
                { argsIgnorePattern: '^_', varsIgnorePattern: '^_' },
            ],
        },
    },
    {
        files: ['resources/js/themes/**/*.{ts,tsx}', 'resources/js/pages/**/*.{ts,tsx}'],
        rules: {
            'react/jsx-no-literals': [
                'error',
                {
                    noStrings: false,
                    allowedStrings: [
                        '/', ':', '-', '•', '|', '·', '©', '→', '←', '&', '&amp;',
                        'gar', 'nar', 'hgi', 'mm', 'kcal/kg',
                        'GAR', 'NAR', 'MT', 'km', '%', '+', '—',
                        ' ', ',', '.', '01', '02', '03', '04', '05', '06',
                        '07', '08', '09', '10', '24/7', 'PDF', 'HTTP',
                    ],
                    ignoreProps: true,
                    noAttributeStrings: false,
                },
            ],
        },
    },
);

