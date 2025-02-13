<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\SeoProject;
use App\Models\SocialMediaProject;
use App\Models\WebDevProject;
use App\Models\Industry;
use App\Models\Customer;
use App\Models\User;
use App\Models\PaymentMethod;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $customer = Customer::first();
        $user = User::first();
        $paymentMethod = PaymentMethod::first();
        $industry = Industry::first();

        $project = Project::create([
            'title' => $faker->sentence,
            'description' => $faker->paragraph,
            'category' => 'PLATINUM',
            'status' => 'Current',
            'industry_id' => $industry->id,
            'customer_id' => $customer->id,
            'created_by' => $user->id,
            'contract_start_date' => $faker->date(),
            'contract_term' => $faker->numberBetween(12, 36),
            'contract_value' => $faker->randomFloat(2, 1000, 50000),
            'free_months' => 1,
            'payment_method_id' => $paymentMethod->id,
            'handover_notes' => $faker->paragraph,
            'project_type' => 'SEO',
        ]);

        $project->salesManagers()->sync([User::inRandomOrder()->first()->id]);
        $project->accountManagers()->sync([User::inRandomOrder()->first()->id]);
        $project->techManagers()->sync([User::inRandomOrder()->first()->id]);

        $project->contractFiles()->create([
            'file_path' => 'path/to/dummy/contract.pdf',
            'file_name' => 'dummy_contract.pdf',
            'file_type' => 'application/pdf',
        ]);

        if ($project->project_type == 'SEO') {
            SeoProject::create([
                'project_id' => $project->id,
                'number_of_keywords' => $faker->numberBetween(5, 50),
                'milestone_1_percentage' => 30,
                'milestone_1_date' => $faker->date(),
                'milestone_2_percentage' => 40,
                'milestone_2_date' => $faker->date(),
                'milestone_3_percentage' => 30,
                'milestone_3_date' => $faker->date(),
            ]);
        } elseif ($project->project_type == 'Social Media') {
            SocialMediaProject::create([
                'project_id' => $project->id,
                'channels' => json_encode(['Facebook', 'Instagram']),
                'video_shoots_per_month' => $faker->numberBetween(1, 5),
                'static_posts' => $faker->numberBetween(5, 20),
                'reels' => $faker->numberBetween(2, 10),
            ]);
        } elseif ($project->project_type == 'Web Development') {
            WebDevProject::create([
                'project_id' => $project->id,
                'payment_type' => 'Partial',
                'remaining_payment' => $faker->randomFloat(2, 5000, 20000),
                'site_condition' => 'New Project',
            ]);
        }
    }
}
