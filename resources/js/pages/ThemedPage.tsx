import { Head, usePage } from '@inertiajs/react';
import { AnimatePresence, motion, useReducedMotion } from 'motion/react';
import {
    useSeo,
    type ContactFormBlockData,
    type ContactInfoBlockData,
    type PageBlockData,
    type SharedPageProps,
} from '@/core';
import { resolvePreset } from '@/motion/presets';
import { resolveTheme } from '@/themes/registry';

export default function ThemedPage() {
    const { props } = usePage<SharedPageProps>();
    const { title, description, canonicalUrl, alternateUrls, isPreview, jsonLd } = useSeo();
    const prefersReducedMotion = Boolean(useReducedMotion());

    const themeKey = props.company?.theme ?? 'holding';
    const theme = resolveTheme(themeKey);
    const { Layout, blocks: blockComponents, FallbackBlock, ContactSplit, ProductDetail } = theme;
    const blocks: PageBlockData[] = props.blocks ?? [];
    const product = props.product ?? null;

    const hasHeroBlock = blocks.some((b) => b.type === 'hero');
    const contactInfoBlock = blocks.find((b) => b.type === 'contact_info');
    const contactFormBlock = blocks.find((b) => b.type === 'contact_form');
    const combineContactBlocks = Boolean(ContactSplit && contactInfoBlock && contactFormBlock);

    const renderedContactSplit = { done: false };
    const themeTransitionVariants = resolvePreset('themeFade', prefersReducedMotion);

    return (
        <>
            <Head title={title}>
                {description ? <meta name="description" content={description} /> : null}
                <link rel="canonical" href={canonicalUrl} />
                {Object.entries(alternateUrls).map(([lang, href]) =>
                    href ? <link key={lang} rel="alternate" hrefLang={lang} href={href} /> : null,
                )}
                {isPreview ? <meta name="robots" content="noindex, nofollow" /> : null}
                <script type="application/ld+json">{JSON.stringify(jsonLd)}</script>
            </Head>

            <AnimatePresence initial={false} mode="wait">
                <motion.div
                    key={themeKey}
                    initial={prefersReducedMotion ? 'visible' : 'hidden'}
                    animate="visible"
                    exit="hidden"
                    variants={themeTransitionVariants}
                    style={prefersReducedMotion ? { opacity: 1, transform: 'none' } : undefined}
                >
                    <Layout>
                        {product && ProductDetail ? (
                            <ProductDetail
                                product={product}
                                productsPageUrl={props.productsPageUrl}
                                contactPageUrl={props.contactPageUrl}
                            />
                        ) : (
                            blocks.map((block, idx) => {
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
                                            infoData={
                                                contactInfoBlock!
                                                    .data as unknown as ContactInfoBlockData
                                            }
                                            formData={
                                                contactFormBlock!
                                                    .data as unknown as ContactFormBlockData
                                            }
                                            hasHeroH1={hasHeroBlock}
                                        />
                                    );
                                }

                                const isFirstBlock = idx === 0 && !hasHeroBlock;
                                const BlockComponent = blockComponents[block.type];
                                if (BlockComponent) {
                                    return (
                                        <BlockComponent
                                            key={block.id}
                                            data={block.data}
                                            isFirstBlock={isFirstBlock}
                                        />
                                    );
                                }

                                return <FallbackBlock key={block.id} data={block.data} />;
                            })
                        )}
                    </Layout>
                </motion.div>
            </AnimatePresence>
        </>
    );
}
