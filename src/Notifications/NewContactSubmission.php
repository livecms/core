<?php

namespace LiveCMS\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;

class NewContactSubmission extends Notification
{
    use Queueable, Notifiable;

    protected $email;
    protected $sender;
    protected $submission;
    protected $replyTo;
    protected $subject;
    protected $intro;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender, $submission, $confirmation = true)
    {
        $this->sender = $sender;
        $this->submission = $submission;
        $this->replyTo = $sender;
        $this->subject = (isset($submission['subject']) ? $submission['subject'] : '').'(New Contact Submission @ '.date('j F Y H:i:s').')';
        $this->intro = trans('livecms::notifications.'.strtolower((new \ReflectionClass($this))->getShortName()).'.intro');

        if ($confirmation) {
            $this->email = $sender;
            $this->notify(new ContactSubmissionConfirmation($sender, $submission, false));
        }
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
                    ->replyTo($this->replyTo)
                    ->line($this->intro)
                    ->subject($this->subject);

        $mail->viewData = [
                'rawText' => $this->createSubmissionEmail($this->submission),
                'rawTextPlain' => $this->createSubmissionEmailPlain($this->submission)
            ];
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
    {
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
                $value = str_replace("\n", '<br>', $value);
                $message .=
<<<HTML
                    <tr>
                        <th align="right" valign="top" style="text-align: right; vertical-align: top">$key</th>
                        <td>$value</td>
                    </tr>
HTML;
            }
            $message .= '</table>';
            return $message;
        }
    }
}
