<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'category_id',
        'publisher_id',
        'published_year',
        'cover_image'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'borrowings')
            ->withPivot('id', 'borrowed_at', 'returned_at')
            ->withTimestamps();
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function getCoverImageUrlAttribute()
     {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }
        return asset('images/default-book-cover.jpg'); // Imagem padrão caso não exista
    }

    protected static function booted()
    {
        static::deleted(function ($book) {
            // Deleta a imagem quando o livro é deletado
            if ($book->cover_image) {
                Storage::delete($book->cover_image);
            }
        });
    }
}
