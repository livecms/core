<?php

namespace LiveCMS\Notifications;

class ContactSubmissionConfirmation extends NewContactSubmission
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender, $submission, $confirmation = true)
    {
        parent::__construct($sender, $submission, false);
        $this->subject = trans('livecms::notifications.'.strtolower((new \ReflectionClass($this))->getShortName()).'.subject');
    }
}
