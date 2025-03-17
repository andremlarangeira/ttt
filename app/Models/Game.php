<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasUuids;

    protected $fillable = [
        'is_active',
        'current_player',
        'player_x_id',
        'player_o_id',
        'board',
        'moves_x',
        'moves_o',
    ];


    protected function board(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => json_decode($value),
        );
    }


    protected function movesX(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => json_decode($value),
        );
    }


    protected function movesO(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => json_decode($value),
        );
    }


    protected $casts = [
        'is_active' => 'boolean'
    ];
}
