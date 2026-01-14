<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'journalist_name',
        'image',
        'category_id',
        'slug',
        'user_id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
      public function comments()
    {
            return $this->hasMany(Comment::class, 'new_id');
    }
}
