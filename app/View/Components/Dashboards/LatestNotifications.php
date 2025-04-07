<?php

namespace App\View\Components\Dashboards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LatestNotifications extends Component
{
    /**
     * Create a new component instance.
     */
    public $latestNotifications;
    public function __construct($latestNotifications)
    {
        $this->latestNotifications = $latestNotifications;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboards.latest-notifications');
    }
}
