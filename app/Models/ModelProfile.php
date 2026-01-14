<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'models';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'age',
        'city',
        'height',
        'weight',
        'bust',
        'waist',
        'hips',
        'eye_color',
        'hair_color',
        'appearance_type',
        'skin_color',
        'shoe_size',
        'clothing_size',
        'clothing_size_numeric',
        'bio',
        'languages',
        'skills',
        'telegram',
        'vk',
        'facebook',
        'main_photo',
        'portfolio_photos',
        'video_url',
        'has_snaps',
        'has_video_presentation',
        'has_video_walk',
        'has_passport',
        'has_professional_experience',
        'has_tattoos',
        'has_piercings',
        'categories',
        'status',
        'is_featured',
        'experience_years',
        'experience_description',
        'has_modeling_school',
    ];

    protected $casts = [
        'languages' => 'array',
        'skills' => 'array',
        'portfolio_photos' => 'array',
        'categories' => 'array',
        'is_featured' => 'boolean',
        'has_modeling_school' => 'boolean',
        'has_snaps' => 'boolean',
        'has_video_presentation' => 'boolean',
        'has_video_walk' => 'boolean',
        'has_passport' => 'boolean',
        'has_professional_experience' => 'boolean',
        'has_tattoos' => 'boolean',
        'has_piercings' => 'boolean',
    ];

    protected $appends = ['full_name', 'measurements', 'photos'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'model_id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getMeasurementsAttribute()
    {
        if ($this->bust && $this->waist && $this->hips) {
            return "{$this->bust}-{$this->waist}-{$this->hips}";
        }
        return null;
    }

    public function getPhotosAttribute()
    {
        $photos = [];
        
        // Сначала главное фото
        if ($this->main_photo) {
            $photos[] = $this->main_photo;
        }
        
        // Затем фото портфолио
        if ($this->portfolio_photos && is_array($this->portfolio_photos)) {
            $photos = array_merge($photos, $this->portfolio_photos);
        }
        
        return $photos;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->whereJsonContains('categories', $category);
    }
}
