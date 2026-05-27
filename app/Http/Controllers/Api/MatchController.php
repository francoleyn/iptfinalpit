<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function perfect(Request $request): JsonResponse
    {
        $user = $request->user();
        $myOfferedSkillIds = $user->offers()->pluck('skill_id');
        $myWantedSkillIds = $user->wants()->pluck('skill_id');

        if ($myOfferedSkillIds->isEmpty() || $myWantedSkillIds->isEmpty()) {
            return response()->json([]);
        }

        $matches = User::query()
            ->where('id', '!=', $user->id)
            ->whereHas('wants', fn ($q) => $q->whereIn('skill_id', $myOfferedSkillIds))
            ->whereHas('offers', fn ($q) => $q->whereIn('skill_id', $myWantedSkillIds))
            ->with(['offers.skill', 'wants.skill'])
            ->get()
            ->map(fn (User $match) => $this->formatMatch($match, 'perfect', $user));

        return response()->json($matches);
    }

    public function partial(Request $request): JsonResponse
    {
        $user = $request->user();
        $myOfferedSkillIds = $user->offers()->pluck('skill_id');
        $myWantedSkillIds = $user->wants()->pluck('skill_id');

        if ($myOfferedSkillIds->isEmpty() && $myWantedSkillIds->isEmpty()) {
            return response()->json([]);
        }

        $matches = User::query()
            ->where('id', '!=', $user->id)
            ->where(function ($query) use ($myOfferedSkillIds, $myWantedSkillIds) {
                if ($myOfferedSkillIds->isNotEmpty()) {
                    $query->where(function ($q) use ($myOfferedSkillIds, $myWantedSkillIds) {
                        $q->whereHas('wants', fn ($q) => $q->whereIn('skill_id', $myOfferedSkillIds));

                        if ($myWantedSkillIds->isNotEmpty()) {
                            $q->whereDoesntHave('offers', fn ($q) => $q->whereIn('skill_id', $myWantedSkillIds));
                        }
                    });
                }

                if ($myWantedSkillIds->isNotEmpty()) {
                    $method = $myOfferedSkillIds->isNotEmpty() ? 'orWhere' : 'where';
                    $query->{$method}(function ($q) use ($myOfferedSkillIds, $myWantedSkillIds) {
                        $q->whereHas('offers', fn ($q) => $q->whereIn('skill_id', $myWantedSkillIds));

                        if ($myOfferedSkillIds->isNotEmpty()) {
                            $q->whereDoesntHave('wants', fn ($q) => $q->whereIn('skill_id', $myOfferedSkillIds));
                        }
                    });
                }
            })
            ->with(['offers.skill', 'wants.skill'])
            ->get()
            ->map(fn (User $match) => $this->formatMatch($match, 'partial', $user));

        return response()->json($matches);
    }

    private function formatMatch(User $match, string $matchType, User $user): array
    {
        $suggestedSwap = null;

        if ($matchType === 'perfect') {
            $user->loadMissing(['offers.skill', 'wants.skill']);

            foreach ($user->offers as $myOffer) {
                foreach ($user->wants as $myWant) {
                    $theyWantMyOffer = $match->wants->contains('skill_id', $myOffer->skill_id);
                    $theyOfferWhatIWant = $match->offers->contains('skill_id', $myWant->skill_id);

                    if ($theyWantMyOffer && $theyOfferWhatIWant) {
                        $theirOffer = $match->offers->firstWhere('skill_id', $myWant->skill_id);

                        $suggestedSwap = [
                            'offered_skill_id' => $myOffer->skill_id,
                            'offered_skill_name' => $myOffer->skill->name,
                            'requested_skill_id' => $theirOffer->skill_id,
                            'requested_skill_name' => $theirOffer->skill->name,
                        ];
                        break 2;
                    }
                }
            }
        }

        return [
            'id' => $match->id,
            'name' => $match->name,
            'bio' => $match->bio,
            'location' => $match->location,
            'average_rating' => $match->averageRating(),
            'match_type' => $matchType,
            'offers' => $match->offers,
            'wants' => $match->wants,
            'suggested_swap' => $suggestedSwap,
        ];
    }
}
