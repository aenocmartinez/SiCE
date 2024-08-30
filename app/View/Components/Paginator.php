<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Paginator extends Component
{
    public $paginate;
    public $route;
    public $criterio;
    public $page;

    public function __construct($paginate, $route, $criterio, $page)
    {
        $this->paginate = $paginate;
        $this->route = $route;
        $this->criterio = $criterio;
        $this->page = $page;
    }

    public function render()
    {
        return view('components.paginator');
    }
}
