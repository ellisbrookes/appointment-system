<?php

namespace App\View\Components\Shared;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
  public $type;
  public $heading;
  public $subheading;

  /**
   * Create a new component instance.
   * @param $type
   * @param $heading
   * @param $subheading
   */

  public function __construct($type, $heading, $subheading)
  {
    $this->type = $type;
    $this->heading = $heading;
    $this->subheading = $subheading;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.shared.header');
  }
 
  public function headerVariant(): string
  {
    return match ($this->type) {
      'sidebar' => 'text-white h-screen my-4 px-20',
      'top' => 'text-white py-24',
      'cosy' => 'bg-red-100 text-red-800 border-red-300'
    };
  }
}
