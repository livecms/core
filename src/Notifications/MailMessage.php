<?php

namespace LiveCMS\Notifications;

use Illuminate\Notifications\Messages\MailMessage as LaravelMailMessage;

class MailMessage extends LaravelMailMessage
{

    public function __construct()
    {
        $address = globalParams('email', config('livecms.emailer.from.address'));
        $name = globalParams('site_name', config('livecms.emailer.from.name'));

        $this->from = [$address, $name];
    }
}
