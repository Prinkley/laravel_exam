<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Models\Thing;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Задание 16: Gate для проверки доступа к изменению вещи
        // Может изменять: владелец вещи ИЛИ пользователь, на которого вещь назначена
        Gate::define('edit-thing', function ($user, Thing $thing) {
            $isOwner = $user->id === $thing->master_id;
            
            $isAssigned = $thing->usages()
                ->where('user_id', $user->id)
                ->exists();
            
            return $isOwner || $isAssigned;
        });

        Gate::define('delete-thing', function ($user, Thing $thing) {
            return $user->id === $thing->master_id;
        });

        // Задание 20: Blade директива для выделения вещей текущего пользователя
        Blade::if('userThing', function ($thing) {
            return auth()->check() && (auth()->id() === $thing->master_id);
        });

        // Альтернативная директива для выделения всех связанных вещей (владельцу или назначенному)
        Blade::if('myThing', function ($thing) {
            if (!auth()->check()) {
                return false;
            }
            
            $isOwner = auth()->id() === $thing->master_id;
            $isAssigned = $thing->usages()
                ->where('user_id', auth()->id())
                ->exists();
            
            return $isOwner || $isAssigned;
        });

        // Задание 21: Blade директива для выделения активной вкладки
        Blade::if('activeRoute', function ($routeName) {
            return Route::currentRouteName() === $routeName || 
                   str_contains(Route::currentRouteName(), $routeName);
        });

        // Blade компонент для стилизации активной ссылки в навбаре
        Blade::directive('activeTabClass', function ($routeName) {
            return "<?php echo Route::currentRouteName() === {$routeName} || str_contains(Route::currentRouteName(), {$routeName}) ? 'active' : ''; ?>";
        });
    }
}
