<?php

namespace App\View\Components\shared;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputLabel extends Component
{
    public $for;
    public $value;

    /**
     * Create a new component instance.
     * @param $for
     * @param $value
     */
    public function __construct($for, $value)
    {
        $this->for = $for;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared.input-label');
    }
}
