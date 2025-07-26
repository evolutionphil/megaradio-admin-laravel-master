<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\LinkedStation;
use App\Models\RadioStation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MongoDB\BSON\ObjectId;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class SuggestedStations extends DataTableComponent
{
    public RadioStation $station;

    public array $bulkActions = [
        'attach' => 'Link with this station',
    ];

    protected string $tableName = 'suggested_stations';

    protected $model = RadioStation::class;

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            RadioStation::whereIn('id', $this->selected)->delete();

            $this->clearSelected();

            $this->emit('refreshDatatable');
        }
    }

    public function restoreSelected()
    {
        if (count($this->selected) > 0) {
            RadioStation::whereIn('id', $this->selected)->restore();

            $this->clearSelected();

            $this->emit('refreshDatatable');
        }
    }

    public function attach(): void
    {
        $selected = $this->selected;

        $skipped = [];

        foreach ($selected as $id) {
            $exists = LinkedStation::where('child_station_id', new ObjectId($id))->exists();

            if ($exists) {
                unset($selected[$id]);
                $skipped[] = $id;

                continue;
            }

            LinkedStation::firstOrCreate([
                'parent_station_id' => $this->station->id,
                'child_station_id' => $id,
            ]);
        }

        $this->station->linkedStations()
            ->attach($selected);

        if (! empty($skipped)) {
            $this->emit('notify', [
                'variant' => 'warning',
                'message' => 'Skipped '.count($skipped).' already linked stations. ',
            ]);
        }

        $this->clearSelected();

        $this->emit('refreshDatatable');
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
        Log::info('Calling builder()'.$this->search);

        $query = RadioStation::query();

        $query->whereNot('id', $this->station->id);

        Log::info('$table->getSearch(): '.$this->getSearch());

        if (! $this->hasSearch()) {
            $query->where('$text', ['$search' => $this->station->name])
                ->orderBy('score', ['$meta' => 'textScore']);
        }

        $query
            ->orderBy('votes', 'desc')
            ->select(['id', 'name', 'votes', 'is_working', 'slug', 'country', 'favicon', 'deleted_at']);

        Log::info('----');

        return $query;
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

            Column::make('Slug', 'slug')
                ->format(
                    fn ($value) => Str::limit($value, 20)
                )
                ->collapseOnMobile()
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
