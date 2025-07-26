<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class GenresTable extends DataTableComponent
{
    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            Genre::whereIn('id', $this->selected)->delete();

            $this->clearSelected();
        }
    }

    public function builder(): Builder
    {
        return Genre::query()->orderByDesc('total_stations')->select('*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Is Discoverable')
                ->options(['' => '-- Any --', 1 => 'Yes', 0 => 'No'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('is_discoverable', true);
                    } else {
                        $builder->where('is_discoverable', false)
                            ->orWhereNull('is_discoverable');
                    }
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->searchable(function ($query, $text) {
                    return $query->where('name', 'like', '%'.$text.'%');
                })
                ->sortable(),

            BooleanColumn::make('Discoverable', 'is_discoverable')
                ->sortable(),

            LinkColumn::make('Action')
                ->title(fn ($row) => 'Edit')
                ->location(fn ($row) => route('admin.genres.edit', $row)),
        ];
    }
}
