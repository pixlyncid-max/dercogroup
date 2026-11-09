<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\SetLocale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Redirect root path "/" to preferred browser locale with fallback to "/id".
     */
    public function redirectRoot(Request $request): RedirectResponse
    {
        $locale = SetLocale::negotiateFromRequest($request);

        return redirect()->to('/'.$locale);
    }

    /**
     * Render the bilingual foundation home page.
     */
    public function index(): Response
    {
        return Inertia::render('Home');
    }
}
