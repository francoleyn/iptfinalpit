<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SwapRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SwapRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $swaps = SwapRequest::query()
            ->where('requester_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['requester', 'receiver', 'offeredSkill', 'requestedSkill'])
            ->latest()
            ->get();

        return response()->json($swaps);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id', 'not_in:'.$request->user()->id],
            'offered_skill_id' => ['required', 'exists:skills,id'],
            'requested_skill_id' => ['required', 'exists:skills,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        $user->offers()->where('skill_id', $validated['offered_skill_id'])->firstOrFail();

        $receiver = User::findOrFail($validated['receiver_id']);
        $receiver->offers()->where('skill_id', $validated['requested_skill_id'])->firstOrFail();
        $receiver->wants()->where('skill_id', $validated['offered_skill_id'])->firstOrFail();

        $swap = SwapRequest::create([
            ...$validated,
            'requester_id' => $user->id,
            'status' => 'pending',
        ]);

        $swap->load(['requester', 'receiver', 'offeredSkill', 'requestedSkill']);

        return response()->json($swap, 201);
    }

    public function accept(Request $request, SwapRequest $swapRequest): JsonResponse
    {
        $this->authorizeReceiver($request, $swapRequest);

        if ($swapRequest->status !== 'pending') {
            return response()->json(['message' => 'Only pending swap requests can be accepted.'], 422);
        }

        $swapRequest->update(['status' => 'accepted']);
        $swapRequest->load(['requester', 'receiver', 'offeredSkill', 'requestedSkill']);

        return response()->json($swapRequest);
    }

    public function reject(Request $request, SwapRequest $swapRequest): JsonResponse
    {
        $this->authorizeReceiver($request, $swapRequest);

        if ($swapRequest->status !== 'pending') {
            return response()->json(['message' => 'Only pending swap requests can be rejected.'], 422);
        }

        $swapRequest->update(['status' => 'rejected']);
        $swapRequest->load(['requester', 'receiver', 'offeredSkill', 'requestedSkill']);

        return response()->json($swapRequest);
    }

    public function complete(Request $request, SwapRequest $swapRequest): JsonResponse
    {
        if (! in_array($request->user()->id, [$swapRequest->requester_id, $swapRequest->receiver_id], true)) {
            abort(403, 'You are not part of this swap request.');
        }

        if ($swapRequest->status !== 'accepted') {
            return response()->json(['message' => 'Only accepted swap requests can be completed.'], 422);
        }

        $swapRequest->update(['status' => 'completed']);
        $swapRequest->load(['requester', 'receiver', 'offeredSkill', 'requestedSkill']);

        return response()->json($swapRequest);
    }

    public function destroy(Request $request, SwapRequest $swapRequest): JsonResponse
    {
        if ($swapRequest->requester_id !== $request->user()->id) {
            abort(403, 'Only the requester can delete a swap request.');
        }

        if ($swapRequest->status !== 'pending') {
            return response()->json(['message' => 'Only pending swap requests can be deleted.'], 422);
        }

        $swapRequest->delete();

        return response()->json(['message' => 'Swap request deleted successfully.']);
    }

    private function authorizeReceiver(Request $request, SwapRequest $swapRequest): void
    {
        if ($swapRequest->receiver_id !== $request->user()->id) {
            abort(403, 'Only the receiver can perform this action.');
        }
    }
}
