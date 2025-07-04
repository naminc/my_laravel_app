<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\CartComposer;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\UserService;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\AuthService;
use App\Services\Interfaces\SettingServiceInterface;
use App\Services\SettingService;
use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Cache;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\CategoryService;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Services\Interfaces\ProductServiceInterface;
use App\Services\ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Services\Interfaces\CartServiceInterface;
use App\Services\CartService;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Services\Interfaces\CheckoutServiceInterface;
use App\Services\CheckoutService;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\Interfaces\OrderDetailRepositoryInterface;
use App\Repositories\OrderDetailRepository;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\OrderService;

class AppServiceProvider extends ServiceProvider
{

    protected $providers = [
        AuthServiceInterface::class => AuthService::class,
        UserServiceInterface::class => UserService::class,
        UserRepositoryInterface::class => UserRepository::class,
        SettingServiceInterface::class => SettingService::class,
        SettingRepositoryInterface::class => SettingRepository::class,
        CategoryServiceInterface::class => CategoryService::class,
        CategoryRepositoryInterface::class => CategoryRepository::class,
        ProductServiceInterface::class => ProductService::class,
        ProductRepositoryInterface::class => ProductRepository::class,
        CartServiceInterface::class => CartService::class,
        CartRepositoryInterface::class => CartRepository::class,
        CheckoutServiceInterface::class => CheckoutService::class,
        OrderRepositoryInterface::class => OrderRepository::class,
        OrderDetailRepositoryInterface::class => OrderDetailRepository::class,
        OrderServiceInterface::class => OrderService::class,
    ];
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->providers as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            if (Schema::hasTable('settings')) {
                $setting = Cache::rememberForever('site_setting', function () {
                    return app(SettingServiceInterface::class)->get();
                });
                View::share('setting', $setting);
            }
        } catch (\Throwable $e) {
            Log::error('[AppServiceProvider] ' . $e->getMessage());
        }
        try {
            if (Schema::hasTable('categories')) {
                $categories = app(CategoryServiceInterface::class)->getAll();
                View::share('categories', $categories);
            }
        } catch (\Throwable $e) {
            Log::error('[AppServiceProvider - All Categories] ' . $e->getMessage());
        }
        View::composer('*', CartComposer::class);
    }
}