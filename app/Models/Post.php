<?php

namespace App\Models;

use App\Observers\PostObserver;
use App\Observers\PostOserver;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy(PostObserver::class)]

class Post extends Model
{
    protected $fillable = ['title' , 'slug' , 'content' , 'category_id' , 'thumbnail' , 'tags' , 'published'];


    protected $casts = [
        'tags' => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_post')->withPivot('order')->orderBy('order');
    }


}
