<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteProjectChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_project_id',
        'type',
        // AM Validation
        'forms_working',
        'content_validated',
        'responsiveness_checked',
        // SEO Tech
        'urls_checked',
        'meta_setup_checked',
        'tracking_setup',
        'indexing_checked',
        'page_speed_checked',
        // Ads AM
        'conversions_setup',
        'tracking_validated',
        'forms_tracking',
        'pixel_ga4_setup',
        // Lead Dev QA
        'code_quality_reviewed',
        'security_checked',
        'performance_optimized',
        'cross_browser_tested',
        // Common
        'notes',
        'checked_by',
        'checked_at',
    ];

    protected $casts = [
        'forms_working' => 'boolean',
        'content_validated' => 'boolean',
        'responsiveness_checked' => 'boolean',
        'urls_checked' => 'boolean',
        'meta_setup_checked' => 'boolean',
        'tracking_setup' => 'boolean',
        'indexing_checked' => 'boolean',
        'page_speed_checked' => 'boolean',
        'conversions_setup' => 'boolean',
        'tracking_validated' => 'boolean',
        'forms_tracking' => 'boolean',
        'pixel_ga4_setup' => 'boolean',
        'code_quality_reviewed' => 'boolean',
        'security_checked' => 'boolean',
        'performance_optimized' => 'boolean',
        'cross_browser_tested' => 'boolean',
        'checked_at' => 'datetime',
    ];

    public function websiteProject()
    {
        return $this->belongsTo(WebsiteProject::class);
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function isComplete()
    {
        switch ($this->type) {
            case 'am_validation':
                return $this->forms_working &&
                       $this->content_validated &&
                       $this->responsiveness_checked;

            case 'seo_tech':
                return $this->urls_checked &&
                       $this->meta_setup_checked &&
                       $this->tracking_setup &&
                       $this->indexing_checked &&
                       $this->page_speed_checked;

            case 'ads_am':
                return $this->conversions_setup &&
                       $this->tracking_validated &&
                       $this->forms_tracking &&
                       $this->pixel_ga4_setup;

            case 'lead_dev_qa':
                return $this->code_quality_reviewed &&
                       $this->security_checked &&
                       $this->performance_optimized &&
                       $this->cross_browser_tested;

            default:
                return false;
        }
    }

    public function getCompletionPercentage()
    {
        $fields = $this->getChecklistFields();
        $total = count($fields);
        $completed = 0;

        foreach ($fields as $field) {
            if ($this->$field) {
                $completed++;
            }
        }

        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    protected function getChecklistFields()
    {
        switch ($this->type) {
            case 'am_validation':
                return ['forms_working', 'content_validated', 'responsiveness_checked'];

            case 'seo_tech':
                return ['urls_checked', 'meta_setup_checked', 'tracking_setup', 'indexing_checked', 'page_speed_checked'];

            case 'ads_am':
                return ['conversions_setup', 'tracking_validated', 'forms_tracking', 'pixel_ga4_setup'];

            case 'lead_dev_qa':
                return ['code_quality_reviewed', 'security_checked', 'performance_optimized', 'cross_browser_tested'];

            default:
                return [];
        }
    }
}
