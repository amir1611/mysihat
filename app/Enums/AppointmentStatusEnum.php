<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AppointmentStatusEnum: string implements HasLabel, HasIcon, HasColor
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PENDING => 'edit',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::CANCELLED => 'danger',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'secondary',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::CANCELLED => 'danger',
        };
    }
}
