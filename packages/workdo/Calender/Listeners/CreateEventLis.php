<?php

namespace Modules\Calender\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Calender\Entities\GoogleCalender;
use Modules\Hrm\Events\CreateEvent;

class CreateEventLis
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
    public function handle(CreateEvent $event)
    {
        // Google Calender
        if($event->request->get('synchronize_type')  == 'google_calender')
        {
            $type ='event';
            \Modules\Calender\Entities\CalenderUtility::addCalendarData($event->event , $type);
        }
    }
}
