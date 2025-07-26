<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\Ads;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Storage;

class AdsTable extends DataTableComponent
{
    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            Ads::whereIn('id', $this->selected)->delete();

            $this->clearSelected();
        }
    }

    public function builder(): Builder
    {
        return Ads::query()->orderBy('name')->select('*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [

            ImageColumn::make('Image')
                ->attributes(fn () => [
                    'class' => 'h-10 w-10 rounded',
                ])
                ->location(function ($row) {
                    if (empty($row->image)) {
                        return null;
                    }

                    return str_contains($row->image, 'http') ? $row->image : Storage::url($row->image);
                }),

            Column::make('Name', 'name')
                ->searchable(function ($query, $text) {
                    return $query->where('name', 'like', '%'.$text.'%');
                })
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable(),
        ];
    }
}
