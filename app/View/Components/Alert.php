<?php

namespace App\View\Components;
use Illuminate\View\Component;

class Alert extends Component
{
    public $type;
    public $message;
    public $timeout;

    public function __construct($type = 'success', $message = '', $timeout = 3000)
    {
        $this->type = $type;
        $this->message = $message;
        $this->timeout = $timeout;
    }

    public function render()
    {
        return view('components.alert');
    }
}