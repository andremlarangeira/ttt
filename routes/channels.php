<?php

use App\Models\Game;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('game.{gameId}', function ($user, string $gameId) {
    $game = Game::find($gameId);

    return (int) $user->id === (int) $game->player_x_id || (int) $user->id === (int) $game->player_o_id;
});
