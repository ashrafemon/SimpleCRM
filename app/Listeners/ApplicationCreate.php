<?php
namespace App\Listeners;

use App\Events\LeadMaintainerConvert;
use App\Models\Application;
use App\Models\Lead;

class ApplicationCreate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LeadMaintainerConvert $event): void
    {
        Application::query()->create([
            'name'    => $event->appName ?? Lead::query()->where('id', $event->leadMaintainer->lead_id)->first()->name,
            'lead_id' => $event->leadMaintainer->lead_id,
            'user_id' => $event->leadMaintainer->user_id,
        ]);
    }
}
