<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $guarded = [];
    public $timestams = true;

    public function room_type()
    {
        return $this->belongsTo(RoomType::class);
    }
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

}
