<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'request_date' => 'datetime',
        'expected_return_date' => 'datetime',
    ];

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
