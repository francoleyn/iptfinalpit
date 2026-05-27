<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Skill::query()->orderBy('category')->orderBy('name');

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where('name', 'like', "%{$search}%");
        }

        return response()->json($query->get());
    }

    public function show(Skill $skill): JsonResponse
    {
        return response()->json($skill);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:skills,name'],
            'category' => ['required', 'string', 'max:255'],
        ]);

        $skill = Skill::create($validated);

        return response()->json($skill, 201);
    }

    public function update(Request $request, Skill $skill): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', 'unique:skills,name,'.$skill->id],
            'category' => ['sometimes', 'string', 'max:255'],
        ]);

        $skill->update($validated);

        return response()->json($skill);
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully.']);
    }
}
