<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebsiteProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'platform',
        'developer_assigned',
        'lead_developer',
        'has_current_seo',
        'has_current_google_ads',
        'target_go_live_date',
        'design_status',
        'figma_link',
        'design_approval_screenshot',
        'design_completed_at',
        'design_approved_at',
        'development_status',
        'staging_link',
        'completion_agreement',
        'development_completed_at',
        'development_approved_at',
        'lead_dev_qa_approved',
        'lead_dev_qa_approved_at',
        'lead_dev_qa_approved_by',
        'am_validation_approved',
        'am_validation_approved_at',
        'am_validation_approved_by',
        'seo_tech_approved',
        'seo_tech_approved_at',
        'seo_tech_approved_by',
        'ads_am_approved',
        'ads_am_approved_at',
        'ads_am_approved_by',
        'approved_for_go_live',
        'go_live_date',
        'notes',
    ];

    protected $casts = [
        'has_current_seo' => 'boolean',
        'has_current_google_ads' => 'boolean',
        'target_go_live_date' => 'date',
        'design_completed_at' => 'datetime',
        'design_approved_at' => 'datetime',
        'development_completed_at' => 'datetime',
        'development_approved_at' => 'datetime',
        'lead_dev_qa_approved' => 'boolean',
        'lead_dev_qa_approved_at' => 'datetime',
        'am_validation_approved' => 'boolean',
        'am_validation_approved_at' => 'datetime',
        'seo_tech_approved' => 'boolean',
        'seo_tech_approved_at' => 'datetime',
        'ads_am_approved' => 'boolean',
        'ads_am_approved_at' => 'datetime',
        'approved_for_go_live' => 'boolean',
        'go_live_date' => 'datetime',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_assigned');
    }

    public function leadDeveloper()
    {
        return $this->belongsTo(User::class, 'lead_developer');
    }

    public function leadDevQAApprovedBy()
    {
        return $this->belongsTo(User::class, 'lead_dev_qa_approved_by');
    }

    public function amValidationApprovedBy()
    {
        return $this->belongsTo(User::class, 'am_validation_approved_by');
    }

    public function seoTechApprovedBy()
    {
        return $this->belongsTo(User::class, 'seo_tech_approved_by');
    }

    public function adsAmApprovedBy()
    {
        return $this->belongsTo(User::class, 'ads_am_approved_by');
    }

    public function checklists()
    {
        return $this->hasMany(WebsiteProjectChecklist::class);
    }

    public function history()
    {
        return $this->hasMany(WebsiteProjectHistory::class)->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopePlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    public function scopeDesignStatus($query, $status)
    {
        return $query->where('design_status', $status);
    }

    public function scopeDevelopmentStatus($query, $status)
    {
        return $query->where('development_status', $status);
    }

    public function scopeReadyForGoLive($query)
    {
        return $query->where('approved_for_go_live', true)
                     ->whereNull('go_live_date');
    }

    public function scopeAssignedToDeveloper($query, $developerId)
    {
        return $query->where('developer_assigned', $developerId);
    }

    // Methods
    public function canGoLive()
    {
        $baseChecks = $this->lead_dev_qa_approved && $this->am_validation_approved;

        if ($this->has_current_seo || $this->has_current_google_ads) {
            $seoCheck = $this->has_current_seo ? $this->seo_tech_approved : true;
            $adsCheck = $this->has_current_google_ads ? $this->ads_am_approved : true;

            return $baseChecks && $seoCheck && $adsCheck;
        }

        return $baseChecks;
    }

    public function updateDesignStatus($status, $additionalData = [])
    {
        $this->design_status = $status;

        if ($status === 'Completed') {
            $this->design_completed_at = now();
        } elseif ($status === 'Approved') {
            $this->design_approved_at = now();

            // Notify developer to start development
            if ($this->developer) {
                // Send notification to developer
            }
        }

        if (isset($additionalData['figma_link'])) {
            $this->figma_link = $additionalData['figma_link'];
        }

        if (isset($additionalData['design_approval_screenshot'])) {
            $this->design_approval_screenshot = $additionalData['design_approval_screenshot'];
        }

        $this->save();

        // Log history
        $this->logHistory('Design status changed to ' . $status);
    }

    public function updateDevelopmentStatus($status, $additionalData = [])
    {
        $oldStatus = $this->development_status;
        $this->development_status = $status;

        if ($status === 'In Progress' && isset($additionalData['staging_link'])) {
            $this->staging_link = $additionalData['staging_link'];
        }

        if ($status === 'Completed') {
            $this->development_completed_at = now();

            if (isset($additionalData['completion_agreement'])) {
                $this->completion_agreement = $additionalData['completion_agreement'];
            }
        }

        if ($status === 'Delivered') {
            $this->development_approved_at = now();
        }

        $this->save();

        // Log history
        $this->logHistory("Development status changed from {$oldStatus} to {$status}");
    }

    public function approveLeadDevQA($userId)
    {
        $this->lead_dev_qa_approved = true;
        $this->lead_dev_qa_approved_at = now();
        $this->lead_dev_qa_approved_by = $userId;
        $this->save();

        $this->logHistory('Lead Developer QA approved');
        $this->checkGoLiveStatus();
    }

    public function approveAMValidation($userId)
    {
        $this->am_validation_approved = true;
        $this->am_validation_approved_at = now();
        $this->am_validation_approved_by = $userId;
        $this->save();

        $this->logHistory('AM Validation approved');
        $this->checkGoLiveStatus();
    }

    public function approveSEOTech($userId)
    {
        $this->seo_tech_approved = true;
        $this->seo_tech_approved_at = now();
        $this->seo_tech_approved_by = $userId;
        $this->save();

        $this->logHistory('SEO Tech approved');
        $this->checkGoLiveStatus();
    }

    public function approveAdsAM($userId)
    {
        $this->ads_am_approved = true;
        $this->ads_am_approved_at = now();
        $this->ads_am_approved_by = $userId;
        $this->save();

        $this->logHistory('Ads AM approved');
        $this->checkGoLiveStatus();
    }

    public function checkGoLiveStatus()
    {
        if ($this->canGoLive() && !$this->approved_for_go_live) {
            $this->approved_for_go_live = true;
            $this->save();

            $this->logHistory('Project approved for Go-Live');

            // Notify relevant users
            $this->notifyReadyForGoLive();
        }
    }

    public function markAsLive()
    {
        $this->go_live_date = now();
        $this->development_status = 'Delivered';
        $this->save();

        $this->logHistory('Project went live');
    }

    public function logHistory($action)
    {
        WebsiteProjectHistory::create([
            'website_project_id' => $this->id,
            'user_id' => auth()->id(),
            'action' => $action,
        ]);
    }

    protected function notifyReadyForGoLive()
    {
        // Notify AM, Lead Dev, and assigned developer
        $users = collect([
            $this->project->accountManagers->first(),
            $this->leadDeveloper,
            $this->developer
        ])->filter()->unique('id');

        foreach ($users as $user) {
            // Send notification
        }
    }

    public function getDaysUntilGoLive()
    {
        if (!$this->target_go_live_date) {
            return null;
        }

        return now()->diffInDays($this->target_go_live_date, false);
    }

    public function isOverdue()
    {
        if (!$this->target_go_live_date || $this->go_live_date) {
            return false;
        }

        return now()->gt($this->target_go_live_date);
    }
}
