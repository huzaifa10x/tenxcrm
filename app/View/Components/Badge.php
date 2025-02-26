<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    public $text;
    public $type; // 'status' or 'priority'
    
    public function __construct($text, $type = 'status')
    {
        $this->text = $text;
        $this->type = $type;
    }

    public function getClasses()
    {
        $classes = [
            'status' => [
                'Todo' => 'text-warning-600 bg-warning-100',
                'In Progress' => 'text-info-600 bg-info-100',
                'Review' => 'text-primary-600 bg-primary-100',
                'Completed' => 'text-success-600 bg-success-100',
                'Blocked' => 'text-danger-600 bg-danger-100',
            ],
            'priority' => [
                'Low' => 'text-success-600 bg-success-100',
                'Medium' => 'text-info-600 bg-info-100',
                'High' => 'text-warning-600 bg-warning-100',
                'Urgent' => 'text-danger-600 bg-danger-100',
            ]
        ];

        return $classes[$this->type][$this->text] ?? 'bg-neutral-300 text-neutral-800';
    }

    public function render()
    {
        return view('components.badge');
    }
}
