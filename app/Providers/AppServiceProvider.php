<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Assignment;
use App\Models\Location;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Tests\Integration\Database\EloquentHasManyThroughTest\Category;
use Lcobucci\JWT\Claim\Validatable;
use Symfony\Component\Console\Input\Input;

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
        //Check whether User older than 18
        Validator::extend('checkAge', function ($attribute, $value, $parameters, $validator) {
            return Carbon::parse($value)->age > 18;
        });
        Validator::replacer('checkAge', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.check_age', 'validation.check_age', 'User have to be older than 18');
        });

        //Check whether JoinedDate is saturday or sunday
        Validator::extend('checkDate', function ($attribute, $value, $parameters, $validator) {
            return Carbon::parse($value)->dayOfWeek != 0 && Carbon::parse($value)->dayOfWeek != 6 ;
        });
        Validator::replacer('checkDate', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.check_date', 'validation.check_date', 'Joined date cannot be sunday or saturday');
        });

        //Check whether Location is existed
        Validator::extend('checkExisted', function ($attribute, $value, $parameters, $validator) {
            $Location = Location::find($value);
            return $Location != null;
        });
        Validator::replacer('checkExisted', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.checkExisted', 'validation.checkExisted', 'Location is not existed');
        });

        //Check whether Category is existed
        Validator::extend('checkCateExisted', function ($attribute, $value, $parameters, $validator) {
            $Category = AssetCategory::find($value);
            return $Category != null;
        });
        Validator::replacer('checkCateExisted', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.checkCateExisted', 'validation.checkCateExisted', 'one of CategoryIDs is not existed');
        });

        //Check whether Assignment is existed
        Validator::extend('checkAssignExisted', function ($attribute, $value, $parameters, $validator) {
            $Assignment = Assignment::find($value);
            return $Assignment != null;
        });
        Validator::replacer('checkAssignExisted', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.checkAssignExisted', 'validation.checkAssignExisted', 'AssignmentID is not existed');
        });
        //Check whether AssetCode is existed
        Validator::extend('checkAssetCodeExisted', function ($attribute, $value, $parameters, $validator) {
            if($value != null)
            {
                $check = Asset::query()
                    ->where('asset_code',$value)
                    ->where('id', '!=' , '7')
                    ->first();
                if($check == null)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        });
        Validator::replacer('checkAssetCodeExisted', function ($message, $attribute, $rule, $parameters) {
            return str_replace('validation.checkAssetCodeExisted', 'validation.checkAssetCodeExisted', 'Asset Code is existed');
        });
    }
}
