<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = ['title', 'description', 'long_description', 'completed'];

    // The attributes that aren't mass assignable.
    protected $guarded = [];

    // The attributes that should be cast.
    protected $casts = ['completed' => 'boolean'];

    /**
     * Get the route key for the model.
     * @return string
     */
/*    public function getRouteKeyName(): string
    {
        return 'id';
    }*/
}
