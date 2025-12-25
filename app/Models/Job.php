<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\JobStatus;
use App\Enums\PaymentStatus;

class Job extends Model
{
    use SoftDeletes;

    protected $table = 'cargo_jobs';

    protected $fillable = [
        'job_reference',
        'client_id',
        'status',
        'payment_status',
        'completed_at',
    ];

    protected $casts = [
        'status' => JobStatus::class,
        'payment_status' => PaymentStatus::class,
        'completed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /* ==========================
     | Relationships
     ========================== */

    public function transports(): HasMany
    {
        return $this->hasMany(JobTransport::class);
    }

    public function costLines(): HasMany
    {
        return $this->hasMany(JobCostLine::class);
    }

    public function revenueLines(): HasMany
    {
        return $this->hasMany(JobRevenueLine::class);
    }

    public function adjustmentLines(): HasMany
    {
        return $this->hasMany(JobAdjustmentLine::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(JobPayment::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(JobNote::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
