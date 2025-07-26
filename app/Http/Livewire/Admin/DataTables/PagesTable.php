<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\Page;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PagesTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Page::query()->select(['name', 'content']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setSearchDisabled()
            ->setTableRowUrl(function ($row) {
                return route('admin.pages.edit', $row);
            });
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')->sortable(),
        ];
    }
}
