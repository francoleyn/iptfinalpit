<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    public function show(User $user): JsonResponse
    {
        $user->load(['offers.skill', 'wants.skill']);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'bio' => $user->bio,
            'location' => $user->location,
            'average_rating' => $user->averageRating(),
            'offers' => $user->offers,
            'wants' => $user->wants,
        ]);
    }
}
