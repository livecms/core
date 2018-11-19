<?php

namespace LiveCMS\Auth\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\InteractsWithTime;

class VerifyEmail extends Notification
{
    use InteractsWithTime;
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage)
            ->subject(Lang::getFromJson('Verify Email Address'))
            ->line(Lang::getFromJson('Please click the button below to verify your email address.'))
            ->action(
                Lang::getFromJson('Verify Email Address'),
                $this->verificationUrl($notifiable)
            )
            ->line(Lang::getFromJson('If you did not create an account, no further action is required.'));
    }

    /**
     * Create a temporary signed route URL for a named route.
     *
     * @param  string  $name
     * @param  \DateTimeInterface|int  $expiration
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string
     */
    protected function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
    {
        if (method_exists(URL::class, 'temporarySignedRoute')) {
            return URL::temporarySignedRoute($name, $expiration, $parameters, $absolute);
        }
        $parameters = URL::formatParameters($parameters);
        if ($expiration) {
            $parameters = $parameters + ['expires' => $this->availableAt($expiration)];
        }
        ksort($parameters);
        $key = config('app.key');
        return URL::route($name, $parameters + [
            'signature' => hash_hmac('sha256', URL::route($name, $parameters), $key),
        ], $absolute);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return $this->temporarySignedRoute(
                LC_BaseRoute().'.verification.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]
            );
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}