<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use function Sodium\add;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage())
                ->subject(__('Възстановяване на парола'))
                ->line(__('Получавате това съобщение, защото е изпратена заявка за възстановяване на паролата към този имейл.'))
                ->line(__('Моля използвайте този код: :token', ['token' => $token]))
                ->line(__('Този код ще бъде валиден още 15 минути (до :time)',['time'=>Carbon::now()->add(15, 'minutes')->format('d.m.y H:i:s')]))
                ->line(__('Ако не сте направили вие тази заявка за възстановяване на паролата, не са нужни повече действия.'))
                ->greeting(__('Здравей, :noti!',['noti' => $notifiable->email]))
                ->salutation(__('Благодаря!'))
                ;
        });

    }
}
