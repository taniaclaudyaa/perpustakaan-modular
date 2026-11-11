<?php

namespace Modules\Circulation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $fillable = ['loan_id', 'days_late', 'amount', 'paid_at'];

    protected $casts = ['paid_at' => 'datetime'];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}