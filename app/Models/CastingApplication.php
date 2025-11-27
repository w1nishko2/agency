<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CastingApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'gender',
        'birth_date',
        'city',
        'phone',
        'email',
        'telegram',
        'instagram',
        'height',
        'weight',
        'bust',
        'waist',
        'hips',
        'shoe_size',
        'clothing_size',
        'eye_color',
        'hair_color',
        'skin_tone',
        'has_experience',
        'experience_description',
        'has_modeling_school',
        'school_name',
        'languages',
        'special_skills',
        'photo_portrait',
        'photo_full_body',
        'photo_profile',
        'photo_additional_1',
        'photo_additional_2',
        'about',
        'motivation',
        'categories_interest',
        'status',
        'admin_notes',
        'terms_accepted',
        'personal_data_accepted',
        'photo_usage_accepted',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'languages' => 'array',
        'special_skills' => 'array',
        'categories_interest' => 'array',
        'has_experience' => 'boolean',
        'has_modeling_school' => 'boolean',
        'terms_accepted' => 'boolean',
        'personal_data_accepted' => 'boolean',
        'photo_usage_accepted' => 'boolean',
    ];

    protected $appends = ['full_name', 'age'];

    public function getFullNameAttribute()
    {
        $name = "{$this->first_name} {$this->last_name}";
        if ($this->patronymic) {
            $name .= " {$this->patronymic}";
        }
        return $name;
    }

    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        return \Carbon\Carbon::parse($this->birth_date)->age;
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $reason
        ]);
    }
}
