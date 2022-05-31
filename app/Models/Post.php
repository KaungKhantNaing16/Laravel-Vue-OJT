<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCan;

class Post extends Model
{
    use HasFactory;
    use HasCan;

    protected $fillable = [
        'title',
        'content'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'can'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:d F Y, H:i:s',
    ];

    /**
     * To show updated time reable format
     */
    public function getUpdatedAtAttribute($value)
    {
        return now()->parse($value)->timezone(config('app.timezone'))->diffforHumans();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['serarch'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' .$search. '%')
                    ->orWhere('content', 'like', '%' .$search. '%');
            });
        });
    }    
}
