<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\UpdateLeave;

class UpdateLeaveLis
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
    public function handle(UpdateLeave $event)
    {
        if (module_is_active('ActivityLog')) {
            $leave = $event->request;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'HRM';
            $activity['sub_module']     = 'Leave Management';
            $activity['description']    = __('Leave Updated of employee id ') . Employee::employeeIdFormat($leave->employee_id) . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $leave->workspace;
            $activity['created_by']     = $leave->created_by;
            $activity->save();
        }
    }
}
