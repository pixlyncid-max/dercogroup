<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Actions\PageResolver;
use App\Http\Middleware\SetLocale;
use Illuminate\Http\Response;

/**
 * Generates dynamic XML sitemap containing exclusively published companies, pages, and products (FR-VIS-2 & FR-SEO-3).
 */
class SitemapController extends Controller
{
    public function __construct(
        private readonly PageResolver $pageResolver,
    ) {}

    public function index(): Response
    {
        $companies = Company::query()
            ->published()
            ->with([
                'pages' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order'),
                'products' => fn ($query) => $query->where('is_published', true)->orderBy('sort_order'),
            ])
            ->orderBy('sort_order')
            ->get();

        $entries = [];

        foreach ($companies as $company) {
            foreach ($company->pages as $page) {
                $page->setRelation('company', $company);
                $alternates = $this->pageResolver->alternateUrls($company, $page, false);

                foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
                    $entries[] = [
                        'loc' => url($this->pageResolver->pageUrl($company, $page, $locale, false)),
                        'lastmod' => $page->updated_at?->toAtomString(),
                        'alternates' => array_map(fn (string $path): string => url($path), $alternates),
                    ];
                }
            }

            if (! $company->isHolding()) {
                foreach ($company->products as $product) {
                    $product->setRelation('company', $company);
                    $alternates = $this->pageResolver->productAlternateUrls($company, $product, false);

                    foreach (SetLocale::SUPPORTED_LOCALES as $locale) {
                        $entries[] = [
                            'loc' => url($this->pageResolver->productUrl($company, $product, $locale, false)),
                            'lastmod' => $product->updated_at?->toAtomString(),
                            'alternates' => array_map(fn (string $path): string => url($path), $alternates),
                        ];
                    }
                }
            }
        }

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xhtml=\"http://www.w3.org/1999/xhtml\">\n";

        foreach ($entries as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($entry['loc'], ENT_XML1)."</loc>\n";
            if (! empty($entry['lastmod'])) {
                $xml .= '    <lastmod>'.htmlspecialchars($entry['lastmod'], ENT_XML1)."</lastmod>\n";
            }
            foreach ($entry['alternates'] as $lang => $href) {
                $xml .= '    <xhtml:link rel="alternate" hreflang="'.htmlspecialchars($lang, ENT_XML1).'" href="'.htmlspecialchars($href, ENT_XML1)."\" />\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
