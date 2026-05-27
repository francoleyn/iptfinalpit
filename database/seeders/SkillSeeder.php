<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'JavaScript', 'category' => 'Coding'],
            ['name' => 'Python', 'category' => 'Coding'],
            ['name' => 'PHP', 'category' => 'Coding'],
            ['name' => 'React', 'category' => 'Coding'],
            ['name' => 'Laravel', 'category' => 'Coding'],
            ['name' => 'SQL', 'category' => 'Coding'],
            ['name' => 'Spanish', 'category' => 'Languages'],
            ['name' => 'French', 'category' => 'Languages'],
            ['name' => 'Japanese', 'category' => 'Languages'],
            ['name' => 'Mandarin', 'category' => 'Languages'],
            ['name' => 'German', 'category' => 'Languages'],
            ['name' => 'Guitar', 'category' => 'Music'],
            ['name' => 'Piano', 'category' => 'Music'],
            ['name' => 'Singing', 'category' => 'Music'],
            ['name' => 'Drums', 'category' => 'Music'],
            ['name' => 'Photoshop', 'category' => 'Art'],
            ['name' => 'Illustration', 'category' => 'Art'],
            ['name' => 'Video Editing', 'category' => 'Art'],
            ['name' => 'Photography', 'category' => 'Art'],
            ['name' => 'Drawing', 'category' => 'Art'],
            ['name' => 'Basketball', 'category' => 'Sports'],
            ['name' => 'Swimming', 'category' => 'Sports'],
            ['name' => 'Yoga', 'category' => 'Sports'],
            ['name' => 'Tennis', 'category' => 'Sports'],
            ['name' => 'Running', 'category' => 'Sports'],
            ['name' => 'Baking', 'category' => 'Cooking'],
            ['name' => 'Italian Cooking', 'category' => 'Cooking'],
            ['name' => 'Vegetarian Cooking', 'category' => 'Cooking'],
            ['name' => 'Grilling', 'category' => 'Cooking'],
            ['name' => 'Pastry', 'category' => 'Cooking'],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill['name']], $skill);
        }
    }
}
