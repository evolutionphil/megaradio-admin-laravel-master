<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\StationRequest;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class StationRequestsTable extends DataTableComponent
{
    protected $model = StationRequest::class;

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

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->collapseOnMobile()
                ->sortable(),
            Column::make('Name', 'name')
                ->searchable(function ($query, $text) {
                    return $query->orWhere(function ($q) use ($text) {
                        $q->orWhere('name', 'like', '%'.$text.'%')
                            ->orWhere('description', 'like', '%'.$text.'%')
                            ->orWhere('url', 'like', '%'.$text.'%');
                    });
                }),
            Column::make('Url', 'url')
                ->collapseOnMobile(),
            Column::make('Description', 'description')
                ->collapseOnMobile(),
            Column::make('Request Date', 'created_at')
                ->sortable(),
        ];
    }
}
