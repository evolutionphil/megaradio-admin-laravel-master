<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\StationSubmission;
use MongoDB\Laravel\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Storage;

class StationSubmissionsTable extends DataTableComponent
{
    protected $model = StationSubmission::class;

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            $this->model::whereIn('id', $this->selected)->delete();

            $this->clearSelected();
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return $this->model::query()->select('*');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options(['' => '-- Any --', 'pending' => 'Pending', 'approved' => 'Approved', 'denied' => 'Denied'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('type', $value);
                    }
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
                    fn ($row) => str_contains($row->logo, 'http') ? $row->logo : Storage::url($row->logo)
                ),

            Column::make('Name', 'name')
                ->sortable(),

            Column::make('Status', 'status')
                ->format(function ($value, $row, Column $column) {
                    if (is_null($row->status) || $row->status === 'pending') {
                        return '<span class="inline-flex rounded-full bg-orange-100 px-2 text-xs font-semibold leading-5 text-orange-800">Pending</span>';
                    }

                    if ($row->status === 'approved') {
                        return '<span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">Approved</span>';
                    }

                    if ($row->status === 'denied') {
                        return '<span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">Denied</span>';
                    }
                })->html(),

            Column::make('Genre', 'genre')
                ->sortable(),

            Column::make('Location', 'country')
                ->format(
                    fn ($value, $row, Column $column) => collect([$row->country, $row->state])->join(', ')
                )->html(),

            Column::make('Website', 'website')
                ->format(
                    fn ($value, $row, Column $column) => $row ? '<a class="text-blue-400" target="_blank" href="'.$row->website.'">Open</a>' : null
                )->html(),

            Column::make('Stream URL', 'stream_url')
                ->format(
                    fn ($value, $row, Column $column) => $row ? '<a class="text-blue-400" target="_blank" href="'.$row->stream_url.'">Open</a>' : null
                )->html(),

            Column::make('Submitted at', 'created_at')
                ->sortable(),

            Column::make('Actions', 'actions')
                ->format(
                    fn ($value, $row, Column $column) => $row->status === 'pending' ? '<a class="text-blue-400" href="'.route('admin.station-submissions.approve', $row->id).'">Approve</a>' : null
                )->html(),
        ];
    }
}
