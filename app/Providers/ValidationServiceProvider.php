<?php
namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Validator;

/**
 * This service provider handles a few custom validation rules.
 *
 * PHP version 5.5.9
 * @version    v3.0
 */

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Custom email array validation
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return void
     */
    public function boot()
    {

        // Email array validator
        Validator::extend('email_array', function ($attribute, $value, $parameters, $validator) {
            $value = str_replace(' ', '', $value);
            $array = explode(',', $value);

            foreach ($array as $email) { //loop over values
                $email_to_validate['alert_email'][]=$email;
            }

            $rules = array('alert_email.*'=>'email');
            $messages = array(
                'alert_email.*'=>trans('validation.email_array')
            );

            $validator = Validator::make($email_to_validate, $rules, $messages);

            return $validator->passes();

        });


        // Unique only if undeleted
        // This works around the use case where multiple deleted items have the same unique attribute.
        // (I think this is a bug in Laravel's validator?)
        Validator::extend('unique_undeleted', function ($attribute, $value, $parameters, $validator) {

            if (count($parameters)) {
                $count = DB::table($parameters[0])->select('id')->where($attribute, '=', $value)->whereNull('deleted_at')->where('id', '!=', $parameters[1])->count();
                return $count < 1;
            }

        });


        // Yo dawg. I heard you like validators.
        // This validates the custom validator regex in custom fields.
        // We're just checking that the regex won't throw an exception, not
        // that it's actually correct for what the user intended.

        Validator::extend('valid_regex', function ($attribute, $value, $parameters, $validator) {

            // Make sure it's not just an ANY format
            if ($value!='') {

                //  Check that the string starts with regex:
                if (strpos($value, 'regex:') === false) {
                    return false;
                }

                $test_string = 'My hovercraft is full of eels';

                // We have to stip out the regex: part here to check with preg_match
                $test_pattern = str_replace('regex:','', $value);

                try {
                    preg_match($test_pattern, $test_string);
                    return true;
                } catch (\Exception $e) {
                    return false;
                }

            }
            return true;

        });


        // This ONLY works for create/update user forms, since the Update Profile Password form doesn't
        // include any of these additional validator fields
        Validator::extend('disallow_same_pwd_as_user_fields', function ($attribute, $value, $parameters, $validator) {
            
            $data = $validator->getData();

            if (array_key_exists("username", $data)) {
                if ($data['username'] == $data['password']) {
                    return false;
                }
            }

            if (array_key_exists("email", $data)) {
                if ($data['email'] == $data['password']) {
                    return false;
                }
            }

            if (array_key_exists("first_name", $data)) {
                if ($data['first_name'] == $data['password']) {
                    return false;
                }
            }

            if (array_key_exists("last_name", $data)) {
                if ($data['last_name'] == $data['password']) {
                    return false;
                }
            }


           return true;


        });

        Validator::extend('letters', function ($attribute, $value, $parameters) {
            return preg_match('/\pL/', $value);
        });

        Validator::extend('numbers', function ($attribute, $value, $parameters) {
            return preg_match('/\pN/', $value);
        });

        Validator::extend('case_diff', function ($attribute, $value, $parameters) {
            return preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $value);
        });

        Validator::extend('symbols', function ($attribute, $value, $parameters) {
            return preg_match('/\p{Z}|\p{S}|\p{P}/', $value);
        });

        Validator::extend('cant_manage_self', function ($attribute, $value, $parameters, $validator) {
            // $value is the actual *value* of the thing that's being validated
            // $attribute is the name of the field that the validation is running on - probably manager_id in our case
            // $parameters are the optional parameters - an array for everything, split on commas. But we don't take any params here.
            // $validator gives us proper access to the rest of the actual data
            $data = $validator->getData();

            if(array_key_exists("id", $data)) {
                if ($value && $value == $data['id']) {
                    // if you definitely have an ID - you're saving an existing user - and your ID matches your manager's ID - fail.
                    return false;
                } else {
                    return true;
                }
            } else {
                // no 'id' key to compare against (probably because this is a new user)
                // so it automatically passes this validation
                return true;
            }
        });


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
