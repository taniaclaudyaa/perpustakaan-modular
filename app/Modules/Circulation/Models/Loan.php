<?php

namespace Modules\Circulation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id', 'loan_number', 'borrowed_at',
        'due_at', 'returned_at', 'status', 'total_fine',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
    ];
    // Tidak ada relasi ke Member: lintas context, hanya simpan member_id
    public function details(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }
}