<?php

namespace App\Http\Controllers;

use App\Events\GameUpdateEvent;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GameController extends Controller
{
    public function show(Game $game)
    {
        if (! $game) {
            return redirect('/');
        }

        if (Auth::id() !== $game->player_x_id && Auth::id() !== $game->player_o_id) {
            return redirect('/');
        }

        $user = User::find(Auth::id());

        return Inertia::render('Games', [
            'game' => $game,
            'user' => $user,
        ]);
    }

    public function create()
    {
        $game = Game::create([
            'player_x_id' => Auth::id(),
            'current_player' => 'X',
            'board' => '["", "", "", "", "", "", "", "", ""]',
            'moves_x' => '[]',
            'moves_o' => '[]',
        ]);

        return redirect("/games/{$game->id}");
    }

    public function update(Game $game, Request $request)
    {
        Log::info($request->all());

        $validated = $request->validate([
            'board' => 'array|nullable',
            'current_player' => 'string|nullable',
            'moves_x' => 'array|nullable',
            'moves_o' => 'array|nullable',
            'is_active' => 'boolean|nullable',
        ]);

        Log::info('validado');

        if (! $game) {
            return redirect('/');
        }

        if (empty($validated)) {
            if (Auth::id() === $game->player_x_id || Auth::id() === $game->player_o_id) {
                return redirect("/games/{$game->id}");
            }

            if ($game->player_x_id && $game->player_o_id) {
                return redirect('/');
            }

            $game->update([
                'player_o_id' => Auth::id(),
            ]);
        } else {
            Log::info('gravando');

            $validated['board'] = json_encode($validated['board']);
            $game->update($validated);

            Log::info('gravado');
        }

        GameUpdateEvent::dispatch($game);
        Log::info('disparado');

        return redirect("/games/{$game->id}");
    }
}
