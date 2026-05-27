<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $offers = $request->user()
            ->offers()
            ->with('skill')
            ->latest()
            ->get();

        return response()->json($offers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'proficiency_level' => ['required', 'in:beginner,intermediate,advanced,expert'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $offer = $request->user()->offers()->create($validated);
        $offer->load('skill');

        return response()->json($offer, 201);
    }

    public function update(Request $request, UserOffer $userOffer): JsonResponse
    {
        $this->authorizeOffer($request, $userOffer);

        $validated = $request->validate([
            'skill_id' => ['sometimes', 'exists:skills,id'],
            'proficiency_level' => ['sometimes', 'in:beginner,intermediate,advanced,expert'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $userOffer->update($validated);
        $userOffer->load('skill');

        return response()->json($userOffer);
    }

    public function destroy(Request $request, UserOffer $userOffer): JsonResponse
    {
        $this->authorizeOffer($request, $userOffer);

        $userOffer->delete();

        return response()->json(['message' => 'Offer deleted successfully.']);
    }

    private function authorizeOffer(Request $request, UserOffer $offer): void
    {
        if ($offer->user_id !== $request->user()->id) {
            abort(403, 'You can only manage your own offers.');
        }
    }
}
