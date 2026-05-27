<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserOffer;
use App\Models\UserWant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@skillswap.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'bio' => 'Platform administrator.',
                'location' => 'Manila',
                'is_admin' => true,
            ]
        );

        $users = [
            [
                'name' => 'Alice',
                'email' => 'alice@skillswap.test',
                'bio' => 'Designer who wants to learn music.',
                'location' => 'Manila',
                'offers' => [
                    ['skill' => 'Photoshop', 'proficiency_level' => 'advanced', 'description' => '5 years of photo retouching.'],
                ],
                'wants' => [
                    ['skill' => 'Guitar', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Bob',
                'email' => 'bob@skillswap.test',
                'bio' => 'Musician learning design tools.',
                'location' => 'Manila',
                'offers' => [
                    ['skill' => 'Guitar', 'proficiency_level' => 'intermediate', 'description' => 'Acoustic and electric guitar.'],
                ],
                'wants' => [
                    ['skill' => 'Photoshop', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Carol',
                'email' => 'carol@skillswap.test',
                'bio' => 'Developer looking for language practice.',
                'location' => 'Cebu',
                'offers' => [
                    ['skill' => 'Python', 'proficiency_level' => 'expert', 'description' => 'Backend and automation tutoring.'],
                ],
                'wants' => [
                    ['skill' => 'Spanish', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'Diego',
                'email' => 'diego@skillswap.test',
                'bio' => 'Language tutor picking up coding.',
                'location' => 'Cebu',
                'offers' => [
                    ['skill' => 'Spanish', 'proficiency_level' => 'advanced', 'description' => 'Conversational Spanish for beginners.'],
                ],
                'wants' => [
                    ['skill' => 'Python', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Emma',
                'email' => 'emma@skillswap.test',
                'bio' => 'Pianist exploring frontend development.',
                'location' => 'Manila',
                'offers' => [
                    ['skill' => 'Piano', 'proficiency_level' => 'advanced', 'description' => 'Classical and pop piano lessons.'],
                ],
                'wants' => [
                    ['skill' => 'React', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Frank',
                'email' => 'frank@skillswap.test',
                'bio' => 'Frontend dev who always wanted to play piano.',
                'location' => 'Quezon City',
                'offers' => [
                    ['skill' => 'React', 'proficiency_level' => 'expert', 'description' => 'React hooks, state, and component design.'],
                ],
                'wants' => [
                    ['skill' => 'Piano', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'Grace',
                'email' => 'grace@skillswap.test',
                'bio' => 'Yoga instructor with a sweet tooth.',
                'location' => 'Davao',
                'offers' => [
                    ['skill' => 'Yoga', 'proficiency_level' => 'advanced', 'description' => 'Vinyasa and beginner yoga sessions.'],
                ],
                'wants' => [
                    ['skill' => 'Baking', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Henry',
                'email' => 'henry@skillswap.test',
                'bio' => 'Home baker staying active through yoga.',
                'location' => 'Davao',
                'offers' => [
                    ['skill' => 'Baking', 'proficiency_level' => 'intermediate', 'description' => 'Bread, cookies, and pastry basics.'],
                ],
                'wants' => [
                    ['skill' => 'Yoga', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'Ivy',
                'email' => 'ivy@skillswap.test',
                'bio' => 'Japanese tutor and travel photography fan.',
                'location' => 'Manila',
                'offers' => [
                    ['skill' => 'Japanese', 'proficiency_level' => 'advanced', 'description' => 'JLPT prep and daily conversation.'],
                ],
                'wants' => [
                    ['skill' => 'Photography', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Jake',
                'email' => 'jake@skillswap.test',
                'bio' => 'Photographer learning Japanese for travel shoots.',
                'location' => 'Manila',
                'offers' => [
                    ['skill' => 'Photography', 'proficiency_level' => 'expert', 'description' => 'Portrait and street photography.'],
                ],
                'wants' => [
                    ['skill' => 'Japanese', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Kate',
                'email' => 'kate@skillswap.test',
                'bio' => 'Laravel developer and weekend tennis player.',
                'location' => 'Cebu',
                'offers' => [
                    ['skill' => 'Laravel', 'proficiency_level' => 'expert', 'description' => 'APIs, Eloquent, and Sanctum auth.'],
                ],
                'wants' => [
                    ['skill' => 'Tennis', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'Leo',
                'email' => 'leo@skillswap.test',
                'bio' => 'Tennis coach building web apps.',
                'location' => 'Cebu',
                'offers' => [
                    ['skill' => 'Tennis', 'proficiency_level' => 'advanced', 'description' => 'Forehand, serve, and match strategy.'],
                ],
                'wants' => [
                    ['skill' => 'Laravel', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Maria',
                'email' => 'maria@skillswap.test',
                'bio' => 'French teacher who loves the water.',
                'location' => 'Manila',
                'offers' => [
                    ['skill' => 'French', 'proficiency_level' => 'advanced', 'description' => 'French conversation and grammar.'],
                ],
                'wants' => [
                    ['skill' => 'Swimming', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Nathan',
                'email' => 'nathan@skillswap.test',
                'bio' => 'Swim instructor studying French culture.',
                'location' => 'Manila',
                'offers' => [
                    ['skill' => 'Swimming', 'proficiency_level' => 'expert', 'description' => 'Freestyle technique and water safety.'],
                ],
                'wants' => [
                    ['skill' => 'French', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'Olivia',
                'email' => 'olivia@skillswap.test',
                'bio' => 'Illustrator curious about web development.',
                'location' => 'Baguio',
                'offers' => [
                    ['skill' => 'Illustration', 'proficiency_level' => 'advanced', 'description' => 'Digital illustration and character design.'],
                ],
                'wants' => [
                    ['skill' => 'JavaScript', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Paul',
                'email' => 'paul@skillswap.test',
                'bio' => 'JS developer wanting to improve drawing skills.',
                'location' => 'Baguio',
                'offers' => [
                    ['skill' => 'JavaScript', 'proficiency_level' => 'expert', 'description' => 'Vanilla JS, DOM, and async patterns.'],
                ],
                'wants' => [
                    ['skill' => 'Illustration', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Quinn',
                'email' => 'quinn@skillswap.test',
                'bio' => 'Singer exploring video editing.',
                'location' => 'Davao',
                'offers' => [
                    ['skill' => 'Singing', 'proficiency_level' => 'intermediate', 'description' => 'Vocal warmups and performance tips.'],
                    ['skill' => 'Drums', 'proficiency_level' => 'beginner', 'description' => 'Basic drum patterns and rhythm.'],
                ],
                'wants' => [
                    ['skill' => 'Video Editing', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Rosa',
                'email' => 'rosa@skillswap.test',
                'bio' => 'Video editor who wants vocal coaching.',
                'location' => 'Davao',
                'offers' => [
                    ['skill' => 'Video Editing', 'proficiency_level' => 'advanced', 'description' => 'Premiere-style cuts and storytelling.'],
                ],
                'wants' => [
                    ['skill' => 'Singing', 'priority' => 'medium'],
                ],
            ],
            [
                'name' => 'Sam',
                'email' => 'sam@skillswap.test',
                'bio' => 'Basketball player learning to cook Italian food.',
                'location' => 'Quezon City',
                'offers' => [
                    ['skill' => 'Basketball', 'proficiency_level' => 'advanced', 'description' => 'Dribbling, shooting, and team drills.'],
                ],
                'wants' => [
                    ['skill' => 'Italian Cooking', 'priority' => 'high'],
                ],
            ],
            [
                'name' => 'Tina',
                'email' => 'tina@skillswap.test',
                'bio' => 'Italian cooking enthusiast and sports fan.',
                'location' => 'Quezon City',
                'offers' => [
                    ['skill' => 'Italian Cooking', 'proficiency_level' => 'expert', 'description' => 'Pasta, sauces, and traditional recipes.'],
                ],
                'wants' => [
                    ['skill' => 'Basketball', 'priority' => 'low'],
                ],
            ],
        ];

        foreach ($users as $data) {
            $this->seedUser($data);
        }
    }

    private function seedUser(array $data): void
    {
        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'bio' => $data['bio'],
                'location' => $data['location'],
            ]
        );

        foreach ($data['offers'] as $offer) {
            $skill = Skill::where('name', $offer['skill'])->firstOrFail();

            UserOffer::firstOrCreate(
                ['user_id' => $user->id, 'skill_id' => $skill->id],
                [
                    'proficiency_level' => $offer['proficiency_level'],
                    'description' => $offer['description'] ?? null,
                ]
            );
        }

        foreach ($data['wants'] as $want) {
            $skill = Skill::where('name', $want['skill'])->firstOrFail();

            UserWant::firstOrCreate(
                ['user_id' => $user->id, 'skill_id' => $skill->id],
                ['priority' => $want['priority']]
            );
        }
    }
}
