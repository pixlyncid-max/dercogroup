<?php

declare(strict_types=1);

namespace App\Domain\Contact\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use App\Domain\Contact\Enums\ContactMessageStatus;
use App\Domain\Contact\Mail\ContactMessageReceivedMail;
use App\Domain\Contact\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class SubmitContactMessage
{
    /**
     * Persist a contact message and queue an email notification unless trapped by the honeypot field.
     *
     * @param  array{
     *     name: string,
     *     email: string,
     *     phone?: string|null,
     *     organization?: string|null,
     *     subject: string,
     *     message: string,
     *     website?: string|null
     * }  $data
     */
    public function execute(
        Company $company,
        array $data,
        string $locale = 'id',
        ?string $ipAddress = null,
    ): ?ContactMessage {
        $honeypot = trim((string) ($data['website'] ?? ''));
        if ($honeypot !== '') {
            return null;
        }

        $contactMessage = ContactMessage::query()->create([
            'company_id' => $company->id,
            'locale' => $locale,
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'phone' => isset($data['phone']) && trim((string) $data['phone']) !== '' ? trim((string) $data['phone']) : null,
            'organization' => isset($data['organization']) && trim((string) $data['organization']) !== '' ? trim((string) $data['organization']) : null,
            'subject' => trim($data['subject']),
            'message' => trim($data['message']),
            'status' => ContactMessageStatus::New,
            'ip_address' => $ipAddress,
        ]);

        $recipient = $this->resolveRecipientEmail($company);
        if ($recipient !== '') {
            Mail::to($recipient)->queue(new ContactMessageReceivedMail($contactMessage, $company));
        }

        return $contactMessage;
    }

    private function resolveRecipientEmail(Company $company): string
    {
        $companyEmail = $company->contacts()
            ->whereNotNull('notify_email')
            ->value('notify_email');

        if (is_string($companyEmail) && trim($companyEmail) !== '') {
            return trim($companyEmail);
        }

        $defaultSetting = Setting::getValue('contact_default_email');
        if (is_array($defaultSetting) && is_string($defaultSetting['email'] ?? null) && trim($defaultSetting['email']) !== '') {
            return trim($defaultSetting['email']);
        }

        return 'corsec@dercobara.co.id';
    }
}
