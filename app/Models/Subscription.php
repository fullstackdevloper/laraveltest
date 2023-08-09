<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'plan_id', 'start_date', 'end_date', 'subscription_id', 'amount', 'transaction_id'];


    /**
     * Check if the membership is currently active.
     * @return bool
     */
    public function isActive()
    {
        return $this->end_date >= now();
    }

    /**
     * Define the relationship to the Plan model.
     * @return BelongsTo
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

}
