<?php

namespace App\View\Components\shared;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextInput extends Component
{
    public $id;
    public $type;
    public $name;
    public $value;
    
    /**
     * Create a new component instance.
     * @param $id
     * @param $type
     * @param $name
     * @param $value
     */
    public function __construct($id, $type, $name, $value = false)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared.text-input');
    }
}
