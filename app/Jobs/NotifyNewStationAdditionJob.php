<?php

namespace App\Jobs;

use App\Models\RadioStation;
use App\Models\User;
use App\Notifications\NewStationAdded;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyNewStationAdditionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $radioStation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RadioStation $radioStation)
    {
        $this->radioStation = $radioStation->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app(FcmService::class)->notifyStationAdded($this->radioStation);

        $users = User::where('role', '!=', 1)->get();

        foreach ($users as $user) {
            $user->notify(new NewStationAdded($this->radioStation));
        }
    }
}
