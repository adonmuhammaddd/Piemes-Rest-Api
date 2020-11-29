<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('whereLike', function($attributes, $searchTerms) {
            $this->where(function ($query) use ($attributes, $searchTerms) {
                $_attributes = Arr::wrap($attributes);
                $_searchTerms = Arr::wrap($searchTerms);
                foreach($_attributes as $attribute) {
                    foreach($_searchTerms as $searchTerm) {
                        $query->orWhere($attribute, 'LIKE', $searchTerm);
                    }
                }
            });

            return $this;
        });
    }
}
