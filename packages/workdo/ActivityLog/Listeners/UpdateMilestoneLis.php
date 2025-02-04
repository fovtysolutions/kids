<?php

namespace Modules\ActivityLog\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Modules\ActivityLog\Entities\AllActivityLog;
use Modules\Taskly\Entities\Project;
use Modules\Taskly\Events\UpdateMilestone;

class UpdateMilestoneLis
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
    public function handle(UpdateMilestone $event)
    {
        if (module_is_active('ActivityLog')) {
            $milestone = $event->milestone;
            $project = Project::find($milestone->project_id);

            $activity                   = new AllActivityLog();
            $activity['module']         = 'Projects';
            $activity['sub_module']     = 'Milestone';
            $activity['description']    = __('Milestone ') . $milestone->title . __(' updated in project ') . $project->name . __(' by the ');
            $activity['user_id']        =  Auth::user()->id;
            $activity['url']            = '';
            $activity['workspace']      = $project->workspace;
            $activity['created_by']     = $project->created_by;
            $activity->save();
        }
    }
}
