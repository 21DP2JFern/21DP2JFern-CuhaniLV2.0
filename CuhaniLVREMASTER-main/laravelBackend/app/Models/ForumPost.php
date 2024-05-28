<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    protected $fillable = [
        'ierakstaID',
        'nosaukums',
        'saturs',
        'autors',
        'date',

    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);

    }


}
