<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
  public $type;

  /**
   * Create a new component instance.
   * @param $type
   */
  public function __construct($type)
  {
    $this->type = $type;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.shared.alert');
  }

  public function alertVariant(): string
  {
    return match ($this->type) {
      'success' => 'bg-green-100 text-green-800 border-green-300',
      'danger' => 'bg-red-100 text-red-800 border-red-300',
      'warning' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
      'info' => 'bg-blue-100 text-blue-800 border-blue-300',
      default => 'bg-gray-100 text-gray-800 border-gray-300',
    };
  }
}
