<?php

namespace App\Http\Livewire\Admin\DataTables;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Storage;

class LanguagesTable extends DataTableComponent
{
    public ?string $defaultSortColumn = 'name';

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public function deleteSelected()
    {
        if (count($this->selected) > 0) {
            Language::whereIn('id', $this->selected)->where('key', '!=', 'en')->delete();

            $this->clearSelected();
        }
    }

    public function builder(): Builder
    {
        return Language::query()->select();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
                ->sortable(),

            Column::make('Key', 'key')
                ->sortable(),

            Column::make('ISO', 'iso')
                ->sortable(),

            BooleanColumn::make('Is Published', 'is_published')
                ->sortable(),

            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons($this->getActions()),
        ];
    }

    private function getActions()
    {
        $actions = [
            LinkColumn::make('Edit')
                ->title(fn ($row) => 'Edit')
                ->location(fn ($row) => route('admin.languages.edit', $row))
                ->attributes(function ($row) {
                    return [
                        'class' => 'underline text-blue-500 hover:no-underline',
                    ];
                }),

            LinkColumn::make('Generate Sitemap')
                ->title(fn ($row) => 'Generate Sitemap')
                ->location(fn ($row) => route('admin.languages.generate-sitemap', $row))
                ->attributes(function ($row) {
                    return [
                        'class' => 'underline text-blue-500 hover:no-underline',
                    ];
                }),

            LinkColumn::make('Sitemap')
                ->title(fn ($row) => 'Sitemap')
                ->location(fn ($row) => 'https://megaradio.live/sitemaps/'.$row->key.'/index.xml')
                ->attributes(function ($row) {
                    $hiddenClass = ! Storage::disk('public')->exists('sitemaps/'.$row->key.'/index.xml') ? 'hidden' : '';

                    return [
                        'target' => '_blank',
                        'class' => 'underline text-blue-500 hover:no-underline '.$hiddenClass,
                    ];
                }),
        ];

        return $actions;
    }
}
