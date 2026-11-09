<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Company\Actions\ResolveCompanyAction;
use App\Domain\Company\Models\Company;
use App\Domain\Contact\Actions\SubmitContactMessage;
use App\Http\Requests\SubmitContactMessageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;

class ContactMessageController extends Controller
{
    public function __construct(
        private readonly ResolveCompanyAction $resolveCompanyAction,
        private readonly SubmitContactMessage $submitContactMessage,
    ) {}

    public function store(SubmitContactMessageRequest $request): RedirectResponse
    {
        /** @var Company|null $company */
        $company = $request->attributes->get('company');

        if (! $company instanceof Company) {
            $allowDraftPreview = (bool) $request->attributes->get('allowDraftPreview', false);
            $company = $this->resolveCompanyAction->execute(null, $allowDraftPreview);
        }

        /** @var array{name: string, email: string, phone?: string|null, organization?: string|null, subject: string, message: string, website?: string|null} $validated */
        $validated = $request->validated();

        $this->submitContactMessage->execute(
            $company,
            $validated,
            App::getLocale(),
            $request->ip(),
        );

        return back()->with('contactSuccess', true);
    }
}
