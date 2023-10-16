<?php

namespace App\Core\Invoice\Domain\Status;

enum InvoiceStatus: string
{
    case NEW = 'new';
    case PAID = 'paid';
    case CANCELED = 'canceled';

    /**
     * Custom method to check if a string matches one of the enum values.
     *
     * @param string $value
     * @return bool
     */
    public static function isMatching(string $value): bool
    {
        return in_array($value, [
            self::NEW->value,
            self::PAID->value,
            self::CANCELED->value,
        ]);
    }
}