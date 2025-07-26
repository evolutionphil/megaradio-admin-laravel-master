<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            User::whereIn('id', $this->selected)->delete();

            $this->clearSelected();
        }
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function ($row) {
                return route('admin.users.show', $row);
            });
    }

    public function builder(): Builder
    {
        return User::query()->select('*');
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Role')
                ->options(['' => '-- Any --', 1 => 'Admin', 2 => 'User'])
                ->filter(function (Builder $builder, $value) {
                    if ($value) {
                        $builder->where('role', (int) $value);
                    }
                }),
            SelectFilter::make('Login Method')
                ->options(['' => '-- Any --', 'email' => 'Email', 'facebook' => 'Facebook', 'google' => 'Google'])
                ->filter(function (Builder $builder, $value) {
                    if ($value != '') {
                        if ($value == 'email') {
                            $builder->whereNull('social_provider');
                        } else {
                            $builder->where('social_provider', $value);
                        }
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
                    'loading' => 'lazy',
                ])
                ->location(
                    fn ($row) => $row->avatar
                ),
            Column::make('Name', 'name')
                ->searchable(function ($query, $text) {
                    return $query->orWhere(function ($q) use ($text) {
                        $q->orWhere('name', 'like', '%'.$text.'%')
                            ->orWhere('email', $text);
                    });
                })
                ->sortable(),
            Column::make('Role', 'role')
                ->format(
                    function ($value, $row, Column $column) {
                        if ($value == 1) {
                            return '<span class="badge bg-green-400">Admin</span>';
                        }

                        return '<span class="badge bg-gray-400">User</span>';
                    }
                )
                ->html()
                ->sortable(),

            Column::make('Email', 'email')
                ->sortable(),

            LinkColumn::make('Actions')
                ->title(fn ($row) => 'View')
                ->location(fn ($row) => route('admin.users.show', $row)),
        ];
    }
}
