import { Head, usePage } from '@inertiajs/react';
import {
    useSeo,
    type ContactFormBlockData,
    type ContactInfoBlockData,
    type PageBlockData,
    type SharedPageProps,
} from '@/core';
import { resolveTheme } from '@/themes/registry';

export default function ThemedPage() {
    const { props } = usePage<SharedPageProps>();
    const { title, description, canonicalUrl, alternateUrls, isPreview } = useSeo();

    const themeKey = props.company?.theme ?? 'holding';
    const theme = resolveTheme(themeKey);
    const { Layout, blocks: blockComponents, FallbackBlock, ContactSplit } = theme;
    const blocks: PageBlockData[] = props.blocks ?? [];

    const hasHeroBlock = blocks.some((b) => b.type === 'hero');
    const contactInfoBlock = blocks.find((b) => b.type === 'contact_info');
    const contactFormBlock = blocks.find((b) => b.type === 'contact_form');
    const combineContactBlocks = Boolean(ContactSplit && contactInfoBlock && contactFormBlock);

    const renderedContactSplit = { done: false };

    return (
        <Layout>
            <Head title={title}>
                {description ? <meta name="description" content={description} /> : null}
                <link rel="canonical" href={canonicalUrl} />
                {Object.entries(alternateUrls).map(([lang, href]) =>
                    href ? <link key={lang} rel="alternate" hrefLang={lang} href={href} /> : null,
                )}
                {isPreview ? <meta name="robots" content="noindex, nofollow" /> : null}
            </Head>

            {blocks.map((block) => {
                if (
                    combineContactBlocks &&
                    ContactSplit &&
                    (block.type === 'contact_info' || block.type === 'contact_form')
                ) {
                    if (renderedContactSplit.done) {
                        return null;
                    }
                    renderedContactSplit.done = true;

                    return (
                        <ContactSplit
                            key={`contact-split-${block.id}`}
                            infoData={contactInfoBlock!.data as unknown as ContactInfoBlockData}
                            formData={contactFormBlock!.data as unknown as ContactFormBlockData}
                            hasHeroH1={hasHeroBlock}
                        />
                    );
                }

                const BlockComponent = blockComponents[block.type];
                if (BlockComponent) {
                    return <BlockComponent key={block.id} data={block.data} />;
                }

                return <FallbackBlock key={block.id} data={block.data} />;
            })}
        </Layout>
    );
}
