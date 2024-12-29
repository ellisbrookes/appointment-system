<?php

namespace App\Enums;

enum AlertVariant: string
{
    case Success = 'success';
    case Danger = 'danger';
    case Warning = 'warning';

    public function tailwindClasses(): string
    {
        return match ($this) {
            self::Success => 'bg-green-100 text-green-800 border-green-300',
            self::Danger => 'bg-red-100 text-red-800 border-red-300',
            self::Warning => 'bg-yellow-100 text-yellow-800 border-yellow-300',
        };
    }
}
