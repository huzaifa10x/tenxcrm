<?php

namespace App\View\Components\Dashboards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContentDashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public $pendingTasksCount;
    public $completedTasksCount;
    public $pendingWords;
    public $completedWords;
    public $pendingTasks;
    public $completedTasks;
    public $assignedTasks;
    public function __construct(
        $pendingTasksCount,
        $completedTasksCount,
        $pendingWords,
        $completedWords,
        $pendingTasks,
        $completedTasks,
        $assignedTasks,
    ) {
        $this->pendingTasksCount = $pendingTasksCount;
        $this->completedTasksCount = $completedTasksCount;
        $this->pendingWords = $pendingWords;
        $this->completedWords = $completedWords;
        $this->pendingTasks = $pendingTasks;
        $this->completedTasks = $completedTasks;
        $this->assignedTasks = $assignedTasks;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboards.content-dashboard');
    }
}
