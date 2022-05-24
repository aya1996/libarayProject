<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'tag_id',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
    
}
