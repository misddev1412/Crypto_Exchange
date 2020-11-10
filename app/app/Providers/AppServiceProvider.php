<?php

namespace App\Providers;

use App\Models\Core\ApplicationSetting;
use App\Override\Api\BankApi;
use App\Override\Api\BitcoinForkedApi;
use App\Override\Api\CoinpaymentsApi;
use App\Services\Core\LanguageService;
use App\Services\Logger\LaraframeLogger;
use App\Services\Orders\ProcessLimitOrderService;
use App\Services\Orders\ProcessMarketOrderService;
use App\Services\Withdrawal\WithdrawalService;
use Carbon\Carbon;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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

        Paginator::useBootstrap();

        if (env("APP_PROTOCOL", 'http') == 'https') {
            URL::forceScheme('https');
        }

        if (function_exists('bcscale')) {
            bcscale(8);
        }

        Validator::extend('hash_check', function ($attribute, $value, $parameters) {
            return $value == null ? true : Hash::check($value, $parameters[0]);
        });

        Validator::extend('digits_only', function ($attribute, $value, $parameters) {
            return $value == null ? true : ctype_digit($value);
        });

        Validator::extend('date_gt', function ($attribute, $value, $parameters, $validator) {
            $data = $this->dateComparison($attribute, $value, $parameters);

            if ($value == null) {
                return true;
            }

            $validator->addReplacer('date_gt', function ($message, $attribute, $rule, $parameters) use ($validator) {
                $replaceString = isset($validator->customAttributes[$parameters[0]]) ? $validator->customAttributes[$parameters[0]] : str_replace('_', ' ', $parameters[0]);
                return str_replace([':other'], $replaceString, $message);
            });
            return $data[0] > $data[1];
        });

        Validator::extend('date_gte', function ($attribute, $value, $parameters, $validator) {
            $data = $this->dateComparison($attribute, $value, $parameters);
            if ($value == null) {
                return true;
            }

            $validator->addReplacer('date_gte', function ($message, $attribute, $rule, $parameters) use ($validator) {
                $replaceString = isset($validator->customAttributes[$parameters[0]]) ? $validator->customAttributes[$parameters[0]] : str_replace('_', ' ', $parameters[0]);
                return str_replace([':other'], $replaceString, $message);
            });
            return $data[0] >= $data[1];
        });

        Validator::extend('date_lt', function ($attribute, $value, $parameters, $validator) {
            $data = $this->dateComparison($attribute, $value, $parameters);
            if ($value == null) {
                return true;
            }

            $validator->addReplacer('date_lt', function ($message, $attribute, $rule, $parameters) use ($validator) {
                $replaceString = isset($validator->customAttributes[$parameters[0]]) ? $validator->customAttributes[$parameters[0]] : str_replace('_', ' ', $parameters[0]);
                return str_replace([':other'], $replaceString, $message);
            });
            return $data[0] < $data[1];
        });

        Validator::extend('date_lte', function ($attribute, $value, $parameters, $validator) {
            $data = $this->dateComparison($attribute, $value, $parameters);
            if ($value == null || !$data) {
                return true;
            }

            $validator->addReplacer('date_lte', function ($message, $attribute, $rule, $parameters) use ($validator) {
                $replaceString = isset($validator->customAttributes[$parameters[0]]) ? $validator->customAttributes[$parameters[0]] : str_replace('_', ' ', $parameters[0]);
                return str_replace([':other'], $replaceString, $message);
            });
            return $data[0] <= $data[1];
        });

        Validator::extend('date_neq', function ($attribute, $value, $parameters, $validator) {
            $data = $this->dateComparison($attribute, $value, $parameters);
            if ($value == null) {
                return true;
            }

            $validator->addReplacer('date_neq', function ($message, $attribute, $rule, $parameters) use ($validator) {
                $replaceString = isset($validator->customAttributes[$parameters[0]]) ? $validator->customAttributes[$parameters[0]] : str_replace('_', ' ', $parameters[0]);
                return str_replace([':other'], $replaceString, $message);
            });
            return $data[0] != $data[1];
        });

        Validator::extend('date_eq', function ($attribute, $value, $parameters, $validator) {
            $data = $this->dateComparison($attribute, $value, $parameters);
            if ($value == null) {
                return true;
            }

            $validator->addReplacer('date_eq', function ($message, $attribute, $rule, $parameters) use ($validator) {
                $replaceString = isset($validator->customAttributes[$parameters[0]]) ? $validator->customAttributes[$parameters[0]] : str_replace('_', ' ', $parameters[0]);
                return str_replace([':other'], $replaceString, $message);
            });
            return $data[0] == $data[1];
        });

        Validator::extend('alpha_space', function ($attribute, $value) {
            if ($value == null) {
                return true;
            }
            return is_string($value) && preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('decimal_scale', function ($attribute, $value, $parameters, $validator) {
            $validator->addReplacer('decimal_scale', function ($message, $attribute, $rule, $parameters) use ($validator) {
                return str_replace([':other'], sprintf("(%s,%s)", $parameters[0], $parameters[1]), $message);
            });

            if (!is_numeric($value)) {
                return false;
            }

            if ($value < 0) {
                $value = substr($value, 1);
            }

            $parts = explode('.', $value);
            if ($parts[0] === '' || ($parts[0] != 0 && strlen(ltrim($parts[0], '0')) > $parameters[0])) {
                return false;
            }

            if (count($parts) === 2 && ($parts[1] === '' || strlen(rtrim($parts[1], '0')) > $parameters[1])) {
                return false;
            }


            return true;
        });

        Validator::extend('slug', function ($attribute, $value) {
            if ($value == null) {
                return true;
            }
            return is_string($value) && preg_match('/^[\pL\pM\pN-]+$/u', $value);
        });

        Blade::withoutComponentTags();


        Queue::looping(function () {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        });

        /*        DB::listen(function ($query) {
                    logs()->info($query->sql);
                    logs()->info($query->bindings);
                    logs()->info($query->time);
                });*/

        $this->app->singleton(LanguageService::class, function () {
            return new LanguageService(
                new Filesystem,
                $this->app['path.lang'],
                [$this->app['path.resources'], $this->app['path']]
            );
        });

        $this->app->singleton('logger', LaraframeLogger::class);

        //API Service Binding
        $this->app->bind("BitcoinForkedApi", function ($app, $parameters) {
            return new BitcoinForkedApi($parameters[0]);
        });

        $this->app->bind("CoinpaymentsApi", function ($app, $parameters) {
            return new CoinpaymentsApi($parameters[0]);
        });

        $this->app->bind("BankApi", function ($app, $parameters) {
            return new BankApi($parameters[0]);
        });

        $this->app->bind(WithdrawalService::class, function ($app, $parameters) {
            return new WithdrawalService($parameters[0]);
        });

        $this->app->bind(ProcessLimitOrderService::class, function ($app, $parameters) {
            return new ProcessLimitOrderService($parameters[0]);
        });

        $this->app->bind(ProcessMarketOrderService::class, function ($app, $parameters) {
            return new ProcessMarketOrderService($parameters[0]);
        });

        //Cache admin settings
        $this->loadApplicationSettings();
    }

    private function dateComparison($attribute, $value, $parameters)
    {
        $otherFieldValue = Request::get($parameters[0]);
        $currentInputNameParts = explode('.', $attribute);
        if (count($currentInputNameParts) > 1) {
            $otherInputPartName = explode('.', $parameters[0]);
            if (count($otherInputPartName) > 1) {
                $otherFieldValue = Request::get($otherInputPartName[0]);
                foreach ($otherInputPartName as $key => $inputValue) {
                    if ($key != 0) {
                        if ($inputValue == '*') {
                            $otherFieldValue = $otherFieldValue[$currentInputNameParts[$key]];
                        } else {
                            $otherFieldValue = $otherFieldValue[$inputValue];
                        }
                    }
                }
            }
        }

        $thisFormat = isset($parameters[1]) ? $parameters[1] : 'Y-m-d H:i:s';
        $otherFormat = isset($parameters[2]) ? $parameters[2] : $thisFormat;
        try {
            $thisValue = Carbon::createFromFormat($thisFormat, $value)->getTimestamp();
            $otherValue = Carbon::createFromFormat($otherFormat, $otherFieldValue)->getTimestamp();
            return [$thisValue, $otherValue];
        } catch (Exception $e) {
            return false;
        }
    }

    private function loadApplicationSettings()
    {
        $applicationSettings = settings();
        if (empty($applicationSettings)) {
            try {
                $applicationSettings = ApplicationSetting::pluck('value', 'slug')->toArray();
                foreach ($applicationSettings as $key => $val) {
                    if (is_json($val)) {
                        $applicationSettings[$key] = json_decode($val, true);
                    }
                }
                Cache::forever('appSettings', $applicationSettings);
            } catch (Exception $exception) {
                return false;
            }
        }
    }
}
