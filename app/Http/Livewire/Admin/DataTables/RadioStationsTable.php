<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\Country;
use App\Models\Genre;
use App\Models\RadioStation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class RadioStationsTable extends DataTableComponent
{
    public array $bulkActions = [
        'deleteSelected' => 'Delete',
        'restoreSelected' => 'Restore',
    ];

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            RadioStation::whereIn('id', $this->selected)->delete();

            $this->clearSelected();
        }
    }

    public function restoreSelected()
    {
        if (count($this->selected) > 0) {
            RadioStation::whereIn('id', $this->selected)->restore();

            $this->clearSelected();
        }
    }

    public function builder(): Builder
    {
        return RadioStation::query()->select();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setFilterLayoutSlideDown()
            ->setEagerLoadAllRelationsEnabled()
            ->setDefaultSort('name', 'asc');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Has Favicon')
                ->options(['' => '-- Any --', 1 => 'Yes', 0 => 'No'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->whereNotNull('favicon');
                    } else {
                        $builder->where(function ($query) {
                            $query->orWhere('favicon', '')->orWhereNull('favicon', '');
                        });
                    }
                }),
            SelectFilter::make('Is Popular')
                ->options(['' => '-- Any --', 1 => 'Yes', 0 => 'No'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('popular', true);
                    } else {
                        $builder->where('popular', false)
                            ->orWhereNull('popular');
                    }
                }),
            SelectFilter::make('Is Deleted')
                ->options(['' => '-- Any --', 1 => 'Yes', 0 => 'No'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->onlyTrashed();
                    } else {
                        $builder->whereNull('deleted_at');
                    }
                }),
            SelectFilter::make('Is Working')
                ->options(['' => '-- Any --', 1 => 'Yes', 0 => 'No'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('is_working', true);
                    } else {
                        $builder->where('is_working', false)
                            ->orWhereNull('is_working');
                    }
                }),
            SelectFilter::make('Is Global')
                ->options(['' => '-- Any --', 1 => 'Yes', 0 => 'No'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('is_global', true);
                    } else {
                        $builder->where('is_global', false)
                            ->orWhereNull('is_global');
                    }
                }),
            SelectFilter::make('Tags')
                ->options(
                    Genre::query()
                        ->where('stationcount', '>', 150)
                        ->orderBy('stationcount', 'desc')
                        ->get()
                        ->keyBy('name')
                        ->map(fn ($tag) => $tag->name)
                        ->prepend('-- Any --', '')
                        ->toArray()
                )->filter(function (Builder $builder, $value) {
                    $builder->where('tags', 'like', '%'.$value.'%');
                }),

            SelectFilter::make('Country')
                ->options(
                    Country::query()
                        ->orderBy('name')
                        ->get()
                        ->keyBy('code')
                        ->map(fn ($country) => $country->name)
                        ->prepend('-- Any --', '')
                        ->toArray()
                )->filter(function (Builder $builder, $value) {
                    $builder->where('countrycode', $value);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            ImageColumn::make('Favicon')
                ->attributes(fn ($row) => [
                    'class' => 'h-10 w-10 rounded',
                ])
                ->location(
                    fn ($row) => $row->favicon_url
                ),

            Column::make('Name', 'name')
                ->format(
                    fn ($value, $row, Column $column) => Str::limit($value, 20)
                )
                ->searchable(function ($query, $text) {
                    return $query->orWhere('name', 'like', '%'.$text.'%');
                })
                ->sortable(),

            Column::make('Votes', 'votes')
                ->collapseOnMobile()
                ->sortable(),

            BooleanColumn::make('Working?', 'is_working')
                ->collapseOnMobile()
                ->sortable(),

            Column::make('Country', 'country')
                ->format(
                    fn ($value, $row, Column $column) => Str::limit($value, 20)
                )
                ->searchable(function ($query, $text) {
                    return $query->orWhere('country', 'like', '%'.$text.'%');
                })
                ->collapseOnMobile()
                ->sortable(),

            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons([
                    LinkColumn::make('Play')
                        ->title(fn ($row) => 'Play')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'underline text-blue-500 hover:no-underline',
                                '@click' => '$store.radioPlayer.playRadio(\''.base64_encode(collect($row)->only('name', 'url', 'url_resolved')->toJson()).'\')',
                            ];
                        }),
                    LinkColumn::make('Linked Stations')
                        ->title(fn ($row) => 'Linked Stations')
                        ->location(fn ($row) => route('admin.radio-stations.linked-stations', $row))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'underline text-blue-500 hover:no-underline',
                            ];
                        }),
                    LinkColumn::make('Edit')
                        ->title(fn ($row) => 'Edit')
                        ->location(fn ($row) => route('admin.radio-stations.edit', $row))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'underline text-blue-500 hover:no-underline',
                            ];
                        }),
                    LinkColumn::make('Edit')
                        ->title(fn ($row) => 'Edit')
                        ->location(fn ($row) => route('admin.radio-stations.edit', $row))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'underline text-blue-500 hover:no-underline',
                            ];
                        }),
                ])
                ->collapseOnMobile(),
        ];
    }
}
