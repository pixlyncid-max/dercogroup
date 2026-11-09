<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

trait RecordsAuditLog
{
    public static function bootRecordsAuditLog(): void
    {
        static::created(function (Model $model): void {
            AuditLog::record($model, 'created', $model->getAttributes());
        });

        static::updated(function (Model $model): void {
            AuditLog::record($model, 'updated', $model->getChanges());
        });

        static::deleted(function (Model $model): void {
            AuditLog::record($model, 'deleted');
        });
    }
}
