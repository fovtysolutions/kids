<?php

namespace Workdo\Hrm\Events;

use Illuminate\Queue\SerializesModels;

class UpdateLoanOption
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $request;
    public $loanoption;

    public function __construct($request, $loanoption)
    {
        $this->request = $request;
        $this->loanoption = $loanoption;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
