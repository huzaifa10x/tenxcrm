<?php

namespace App\View\Components\Dashboards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AmDashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public $currentPortfolio;
    public $projects;
    public $pendingTasks;
    public $completedTasks;
    public $assignedTasks;
    public $statusCounts;
    public $typeCounts;
    public $newSell;
    public $newSellProjects;
    public $monthlyStats;
    public $monthlyData;
    public $totalRenewals;
    public function __construct(
        $currentPortfolio,
        $projects,
        $pendingTasks,
        $completedTasks,
        $assignedTasks,
        $statusCounts,
        $typeCounts,
        $newSell,
        $newSellProjects,
        $monthlyStats,
        $monthlyData,
        $totalRenewals,
    ) {
        $this->currentPortfolio = $currentPortfolio;
        $this->projects = $projects;
        $this->pendingTasks = $pendingTasks;
        $this->completedTasks = $completedTasks;
        $this->assignedTasks = $assignedTasks;
        $this->statusCounts = $statusCounts;
        $this->typeCounts = $typeCounts;
        $this->newSell = $newSell;
        $this->newSellProjects = $newSellProjects;
        $this->monthlyStats = $monthlyStats;
        $this->monthlyData = $monthlyData;
        $this->totalRenewals = $totalRenewals;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboards.am-dashboard');
    }
}
