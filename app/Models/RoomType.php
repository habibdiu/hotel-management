<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomType extends Model
{
    use HasFactory;
    protected $tabel = 'room_types';
    protected $guarded = [];
    public $timestamps = true;

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
