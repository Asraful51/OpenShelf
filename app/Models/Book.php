<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'rating' => 'decimal:2',
        'views' => 'integer',
        'times_borrowed' => 'integer',
        'rating_count' => 'integer',
    ];

    public function getDisplayStatusAttribute(): string
    {
        return strtolower($this->status ?? 'available');
    }

    public function getCoverUrlAttribute(): string
    {
        if (empty($this->cover_image)) {
            return asset('images/default-book-cover.jpg');
        }

        $relative = ltrim($this->cover_image, '/');
        $publicPath = public_path($relative);

        return file_exists($publicPath) ? asset($relative) : asset('images/default-book-cover.jpg');
    }

    public function getOwnerAvatarUrlAttribute(): string
    {
        if (! empty($this->owner_avatar) && $this->owner_avatar !== 'default-avatar.jpg') {
            $relative = 'uploads/profile/' . ltrim($this->owner_avatar, '/');
            $publicPath = public_path($relative);

            if (file_exists($publicPath)) {
                return asset($relative);
            }
        }

        return asset('images/avatars/default.jpg');
    }

    public function getHallNameAttribute(): string
    {
        $halls = [
            '1' => 'Amar Ekushey Hall',
            '2' => 'Dr. Muhammad Shahidullah Hall',
            '3' => 'Fazlul Huq Muslim Hall',
            '4' => 'Salimullah Muslim Hall',
            '5' => 'Shahid Sergeant Zahurul Haq Hall',
            '6' => 'Haji Muhammad Mohsin Hall',
            '7' => 'Sir A.F. Rahman Hall',
            '8' => 'Masterda Surja Sen Hall',
            '9' => 'Kobi Jashimuddin Hall',
            '10' => 'Muktijoddha Ziaur Rahman Hall',
            '11' => 'Shaheed Sharif Osman Hadi Hall',
            '12' => 'Bijoy Ekattor Hall',
            '13' => 'Jagannath Hall',
            '14' => 'Ruqayyah Hall',
            '15' => 'Shamsun Nahar Hall',
            '16' => 'Bangladesh-Kuwait Maitree Hall',
            '17' => 'Begum Fazilatunnesa Mujib Hall',
            '18' => 'Kobi Sufiya Kamal Hall',
        ];

        return $halls[$this->hall] ?? 'N/A';
    }
}
