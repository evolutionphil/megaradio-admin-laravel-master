<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\LinkedStation;
use App\Models\RadioStation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use MongoDB\BSON\ObjectId;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class LinkedStations extends DataTableComponent
{
    public string $tableName = 'linked_stations';

    public RadioStation $station;

    public array $bulkActions = [
        'detach' => 'Detach from this station',
    ];

    protected $model = RadioStation::class;

    public function detach(): void
    {
        foreach ($this->selected as $id) {
            LinkedStation::where([
                'parent_station_id' => new ObjectId($this->station->id),
                'child_station_id' => new ObjectId($id),
            ])->delete();
        }

        $this->station->linkedStations()->detach($this->selected);
    }

    public function deleteSelected(): void
    {
        if (count($this->selected) > 0) {
            RadioStation::whereIn('id', $this->selected)->delete();

            $this->clearSelected();
        }
    }

    public function restoreSelected(): void
    {
        if (count($this->selected) > 0) {
            RadioStation::whereIn('id', $this->selected)->restore();

            $this->clearSelected();
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setFilterLayoutSlideDown()
            ->setEagerLoadAllRelationsEnabled();

        $this->setSearchDebounce(500);
    }

    public function builder(): Builder
    {
        return $this->station->linkedStations()
            ->orderBy('name')
            ->select(['id', 'name', 'votes', 'is_working', 'country', 'url', 'url_resolved', 'favicon', 'deleted_at'])
            ->getQuery();
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

            BooleanColumn::make('Deleted?', 'deleted_at')
                ->yesNo()
                ->format(
                    fn ($value, $row, Column $column) => isset($value)
                )
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
