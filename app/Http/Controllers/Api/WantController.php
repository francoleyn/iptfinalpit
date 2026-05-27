<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserWant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $wants = $request->user()
            ->wants()
            ->with('skill')
            ->latest()
            ->get();

        return response()->json($wants);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        $want = $request->user()->wants()->create($validated);
        $want->load('skill');

        return response()->json($want, 201);
    }

    public function update(Request $request, UserWant $userWant): JsonResponse
    {
        $this->authorizeWant($request, $userWant);

        $validated = $request->validate([
            'skill_id' => ['sometimes', 'exists:skills,id'],
            'priority' => ['sometimes', 'in:low,medium,high'],
        ]);

        $userWant->update($validated);
        $userWant->load('skill');

        return response()->json($userWant);
    }

    public function destroy(Request $request, UserWant $userWant): JsonResponse
    {
        $this->authorizeWant($request, $userWant);

        $userWant->delete();

        return response()->json(['message' => 'Want deleted successfully.']);
    }

    private function authorizeWant(Request $request, UserWant $want): void
    {
        if ($want->user_id !== $request->user()->id) {
            abort(403, 'You can only manage your own wants.');
        }
    }
}
