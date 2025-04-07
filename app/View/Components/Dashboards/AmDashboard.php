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
    public function __construct(
        $currentPortfolio,
        $projects,
        $pendingTasks,
        $completedTasks,
        $assignedTasks
    ) {
        $this->currentPortfolio = $currentPortfolio;
        $this->projects = $projects;
        $this->pendingTasks = $pendingTasks;
        $this->completedTasks = $completedTasks;
        $this->assignedTasks = $assignedTasks;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboards.am-dashboard');
    }
}
