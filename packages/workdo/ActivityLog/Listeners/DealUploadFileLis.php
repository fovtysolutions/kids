<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Lead\Events\DealUploadFile;

class DealUploadFileLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(DealUploadFile $event)
    {
        if (module_is_active('ActivityLog')) {
            $deal = $event->deal;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'CRM';
            $activity['sub_module']     = 'Deal';
            $activity['description']    = __('New File Uploaded in deal ') . $deal->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $deal->workspace_id;
            $activity['created_by']     = $deal->created_by;
            $activity->save();
        }
    }
}
