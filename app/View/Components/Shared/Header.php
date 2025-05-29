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
      'sidebar' => 'text-white h-full md:w-7xl py-24 md:py-0',
      'top' => 'text-white py-24 w-full',
      'cosy' => 'text-white py-12 w-full',
    };
  }
}
