<?php

namespace App\Livewire\Layouts;
use App\Models\Menu;
use Livewire\Component;

class Sidebar extends Component
{
    public $menus;

    public function mount()
    {
        $this->menus = Menu::with([
            'children' => function ($query) {
                $query->active()->orderBy('position', 'asc');
            },
        ])
            ->active()
            ->whereNull('parent_id')
            ->orderBy('position', 'asc')
            ->get();
    }

    public function render()
    {
        return view('partials.sidebar');
    }
}
