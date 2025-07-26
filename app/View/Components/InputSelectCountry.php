<?php

namespace App\View\Components;

use App\Models\Country;
use Illuminate\View\Component;

class InputSelectCountry extends Component
{
    public mixed $selected;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($selected = null)
    {
        $this->selected = $selected;
    }

    /**
     * Determine if the given option is the currently selected option.
     *
     * @param  string  $option
     * @return bool
     */
    public function isSelected($option)
    {
        return $option === $this->selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $countries = Country::orderBy('name')->get();

        return view('components.input-select-country', compact('countries'));
    }
}
