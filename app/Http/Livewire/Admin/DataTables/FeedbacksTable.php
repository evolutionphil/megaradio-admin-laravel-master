<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\Feedback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class FeedbacksTable extends DataTableComponent
{
    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            Feedback::whereIn('id', $this->selected)->delete();

            $this->clearSelected();
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('created_at', 'desc')
            ->setTableRowUrl(function ($row) {
                return route('admin.feedbacks.show', $row);
            });
    }

    public function builder(): Builder
    {
        return Feedback::query()->select('*');
    }

    public function columns(): array
    {
        return [
            Column::make('Type', 'type')
                ->searchable(function ($query, $text) {
                    return $query->orWhere('type', $text);
                })
                ->sortable(),

            Column::make('Email', 'email')
                ->searchable(function ($query, $text) {
                    return $query->orWhere('email', 'like', '%'.$text.'%');
                })
                ->collapseOnTablet()
                ->sortable(),

            Column::make('Message', 'message')
                ->searchable(function ($query, $text) {
                    return $query->orWhere('message', 'like', '%'.$text.'%');
                })
                ->format(fn ($value) => Str::limit($value, 30))
                ->collapseOnTablet()
                ->sortable(),

            Column::make('Submission Date', 'created_at')
                ->sortable(),

            Column::make('Station', 'station')
                ->format(
                    fn ($value, $row, Column $column) => $row->radioStation ? '<a class="text-blue-400" href="/admin/radio-stations/'.$row->radioStation->id.'/edit">Show Station</a>' : null
                )->html(),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Feedback Type')
                ->options(['' => '-- Any --', 'FEEDBACK' => 'Feedback', 'STATION_REPORT' => 'Station Report', 'CONTACT' => 'Contact'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('type', $value);
                    }
                }),
        ];
    }
}
