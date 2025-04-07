<?php

namespace App\View\Components\Dashboards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TaskCounts extends Component
{
    /**
     * Create a new component instance.
     */

    public $taskPercentages;
    public $pendingTasksCountGlobal;
    public $completedTasksCountGlobal;
    public $createdTasksCountGlobal;
    public $collaboratorTasksCountGlobal;
    public function __construct(
        $taskPercentages,
        $pendingTasksCountGlobal,
        $completedTasksCountGlobal,
        $createdTasksCountGlobal,
        $collaboratorTasksCountGlobal,
    ) {
        $this->taskPercentages = $taskPercentages;
        $this->pendingTasksCountGlobal = $pendingTasksCountGlobal;
        $this->completedTasksCountGlobal = $completedTasksCountGlobal;
        $this->createdTasksCountGlobal = $createdTasksCountGlobal;
        $this->collaboratorTasksCountGlobal = $collaboratorTasksCountGlobal;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboards.task-counts');
    }
}
