<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\SeoProject;
use App\Models\SocialMediaProject;
use App\Models\WebDevProject;
use App\Models\Industry;
use App\Models\User;
use App\Models\PaymentMethod;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();
        $user = User::first();
        $paymentMethod = PaymentMethod::first();
        $industry = Industry::first();

        $project = Project::create([
            'title' => 'SEO Optimization for ABC Corp',
            'description' => 'An advanced SEO strategy to improve search engine rankings and organic traffic.',
            'category' => 'PLATINUM',
            'status' => 'Current',
            'industry_id' => $industry->id,
            'company_id' => $company->id,
            'created_by' => $user->id,
            'contract_start_date' => '2024-03-01',
            'contract_term' => 24,
            'contract_value' => 15000.00,
            'free_months' => 1,
            'payment_method_id' => $paymentMethod->id,
            'handover_notes' => 'Ensure proper documentation and keyword analysis reports.',
            'project_type' => 'SEO',
        ]);

        $project->salesManagers()->sync([User::inRandomOrder()->first()->id]);
        $project->accountManagers()->sync([User::inRandomOrder()->first()->id]);
        $project->techManagers()->sync([User::inRandomOrder()->first()->id]);

        $project->contractFiles()->create([
            'file_path' => 'uploads/contracts/seo_contract.pdf',
            'file_name' => 'seo_contract.pdf',
            'file_type' => 'application/pdf',
        ]);

        if ($project->project_type == 'SEO') {
            SeoProject::create([
                'project_id' => $project->id,
                'number_of_keywords' => 25,
                'milestone_1_percentage' => 30,
                'milestone_1_date' => '2024-04-15',
                'milestone_2_percentage' => 40,
                'milestone_2_date' => '2024-07-15',
                'milestone_3_percentage' => 30,
                'milestone_3_date' => '2024-10-15',
            ]);
        } elseif ($project->project_type == 'Social Media') {
            SocialMediaProject::create([
                'project_id' => $project->id,
                'channels' => json_encode(['Facebook', 'Instagram', 'Twitter']),
                'video_shoots_per_month' => 3,
                'static_posts' => 10,
                'reels' => 5,
            ]);
        } elseif ($project->project_type == 'Web Development') {
            WebDevProject::create([
                'project_id' => $project->id,
                'payment_type' => 'Partial',
                'remaining_payment' => 7500.00,
                'site_condition' => 'New Project',
            ]);
        }
    }
}