<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputSelectCodec extends Component
{
    /**
     * @var null
     */
    public $selected;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($selected = null)
    {
        //
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
        $codecs = [
            'AAC',
            'AAC+',
            'AAC+,H.264',
            'AAC,H.264',
            'FLV',
            'MP3',
            'MP3,H.264',
            'OGG',
            'UNKNOWN',
            'UNKNOWN,H.264',
        ];

        return view('components.input-select-codec', compact('codecs'));
    }
}
