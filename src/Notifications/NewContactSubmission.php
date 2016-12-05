<?php

namespace LiveCMS\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewContactSubmission extends Notification
{
    use Queueable;

    protected $sender;
    protected $submission;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender, $submission)
    {
        $this->sender = $sender;
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
                    ->replyTo($this->sender)
                    ->line(trans('livecms::notifications.new_contact_submissions.intro'));
        $mail->subject('New Contact Submission @ '.date('j F Y H:i:s'));
        $mail->viewData = ['rawText' => $this->createSubmissionEmail($this->submission)];
        $mail->viewData = ['rawTextPlain' => $this->createSubmissionEmailPlain($this->submission)];
        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function createSubmissionEmailPlain(array $submissionData = [])
        $message = '';
        foreach ($submissionData as $key => $value) {
            $message .= "$key : $value \n\n";
        }
        return $message;
    }

    public function createSubmissionEmail(array $submissionData = [])
    {
        if (count($submissionData)) {
            $message =
<<<HTML
                <table border="1" cellpadding="10" cellmargin="0" style="margin: 10px; margin: 0; border-collapse: collapse; ">
                    <tr>
                        <th>Data</th>
                        <th>Value</th>
                    </tr>
HTML;
            foreach ($submissionData as $key => $value) {
                $message .=
<<<HTML
                    <tr>
                        <th align="right" style="text-align: right;">$key</th>
                        <td>$value</td>
                    </tr>
HTML;
            }
            $message .= '</table>';
            return $message;
        }
    }
}
