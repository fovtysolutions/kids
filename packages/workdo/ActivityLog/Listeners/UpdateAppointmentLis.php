<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Appointment\Events\UpdateAppointment;

class UpdateAppointmentLis
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
    public function handle(UpdateAppointment $event)
    {
        if (module_is_active('ActivityLog')) {
            $appointment = $event->appointment;

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Appointment';
            $activity['sub_module']     = 'Appointments';
            $activity['description']    = __('Appointment Updated by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $appointment['workspace'];
            $activity['created_by']     = $appointment['created_by'];
            $activity->save();
        }
    }
}
