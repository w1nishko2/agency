<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'client_name',
        'client_phone',
        'client_email',
        'company_name',
        'event_type',
        'event_description',
        'event_date',
        'event_time',
        'event_location',
        'duration_hours',
        'budget',
        'final_price',
        'status',
        'admin_notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'budget' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    public function model()
    {
        return $this->belongsTo(ModelProfile::class, 'model_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function confirm()
    {
        $this->update(['status' => 'confirmed']);
    }

    public function complete()
    {
        $this->update(['status' => 'completed']);
        $this->model->increment('bookings_count');
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason
        ]);
    }
}
