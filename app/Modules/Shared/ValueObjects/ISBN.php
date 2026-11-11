<?php

namespace Modules\Shared\ValueObjects;

use InvalidArgumentException;

class ISBN
{
    private string $value;

    public function __construct(string $value)
    {
        $clean = str_replace(['-', ' '], '', $value);

        if (!preg_match('/^\d{13}$/', $clean) || !self::isChecksumValid($clean)) {
            throw new InvalidArgumentException("Format ISBN tidak valid: {$value}");
        }

        $this->value = $clean;
    }

    private static function isChecksumValid(string $isbn): bool
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $isbn[$i] * ($i % 2 === 0 ? 1 : 3);
        }

        return ((10 - ($sum % 10)) % 10) === (int) $isbn[12];
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(ISBN $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}