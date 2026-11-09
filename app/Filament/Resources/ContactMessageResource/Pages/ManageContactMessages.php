<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Domain\Contact\Models\ContactMessage;
use App\Filament\Resources\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ManageContactMessages extends ManageRecords
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_csv')
                ->label('Ekspor CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (): StreamedResponse {
                    $messages = ContactMessage::query()->with('company')->latest()->get();

                    return response()->streamDownload(function () use ($messages): void {
                        $handle = fopen('php://output', 'w');
                        if ($handle === false) {
                            return;
                        }

                        fputcsv($handle, [
                            'ID',
                            'Tanggal',
                            'Perusahaan',
                            'Bahasa',
                            'Nama',
                            'Email',
                            'Telepon',
                            'Organisasi',
                            'Subjek',
                            'Pesan',
                            'Status',
                        ]);

                        foreach ($messages as $msg) {
                            fputcsv($handle, [
                                $msg->id,
                                $msg->created_at?->toDateTimeString(),
                                $msg->company?->name,
                                $msg->locale,
                                $msg->name,
                                $msg->email,
                                $msg->phone,
                                $msg->organization,
                                $msg->subject,
                                $msg->message,
                                $msg->status->value,
                            ]);
                        }

                        fclose($handle);
                    }, 'contact-messages-'.now()->format('Y-m-d').'.csv', [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                    ]);
                }),
        ];
    }
}
