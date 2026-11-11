<?php

namespace Modules\Shared\ValueObjects;

use Carbon\Carbon;
use InvalidArgumentException;

class Denda
{
    public const TARIF_PER_HARI = 1000;

    private int $daysLate;
    private float $amount;

    public function __construct(int $daysLate, int $tarifPerHari = self::TARIF_PER_HARI)
    {
        if ($daysLate < 0) {
            throw new InvalidArgumentException('Hari keterlambatan tidak boleh negatif');
        }

        $this->daysLate = $daysLate;
        $this->amount = $daysLate * $tarifPerHari;
    }

    public static function hitung(Carbon $dueAt, Carbon $returnedAt, int $tarifPerHari = self::TARIF_PER_HARI): self
    {
        $days = $returnedAt->greaterThan($dueAt)
            ? (int) $dueAt->diffInDays($returnedAt)
            : 0;

        return new self($days, $tarifPerHari);
    }

    public function getDaysLate(): int
    {
        return $this->daysLate;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function __toString(): string
    {
        return number_format($this->amount, 0, ',', '.') . ' IDR';
    }
}