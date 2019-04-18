<?php

namespace Modules\Pos\Listeners;

use App\Events\AdminMenuCreated;

class AdminMenu
{

    /**
     * Handle the event.
     *
     * @param  AdminMenuCreated $event
     * @return void
     */
    public function handle(AdminMenuCreated $event)
    {
        $user = auth()->user();

        // Settings
        if ($user->can(['read-settings-settings', 'read-settings-categories', 'read-settings-currencies', 'read-settings-taxes'])) {

            $event->menu->add([
                'url' => 'pos',
                'title' => trans('pos::general.title'),
                'icon' => 'fa fa-desktop',
                'order' => 2,
            ]);

        }
    }
}
