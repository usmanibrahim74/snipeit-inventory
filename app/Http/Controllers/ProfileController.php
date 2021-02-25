<?php
namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use App\Models\Setting;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Image;
use Redirect;
use View;

/**
 * This controller handles all actions related to User Profiles for
 * the Snipe-IT Asset Management application.
 *
 * @version    v1.0
 */
class ProfileController extends Controller
{
    /**
    * Returns a view with the user's profile form for editing
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return \Illuminate\Contracts\View\View
     */
    public function getIndex()
    {
        $user = Auth::user();
        return view('account/profile', compact('user'));
    }

    /**
    * Validates and stores the user's update data.
    *
    * @author [A. Gianotto] [<snipe@snipe.net>]
    * @since [v1.0]
    * @return \Illuminate\Http\RedirectResponse
     */
    public function postIndex(ImageUploadRequest $request)
    {

        $user = Auth::user();
        $user->first_name = $request->input('first_name');
        $user->last_name  = $request->input('last_name');
        $user->website    = $request->input('website');
        $user->gravatar   = $request->input('gravatar');
        $user->phone   = $request->input('phone');



        if (!config('app.lock_passwords')) {
            $user->locale = $request->input('locale', 'en');
        }

        if ((Gate::allows('self.two_factor')) && ((Setting::getSettings()->two_factor_enabled=='1') && (!config('app.lock_passwords')))) {
            $user->two_factor_optin = $request->input('two_factor_optin', '0');
        }

        if (Gate::allows('self.edit_location')  && (!config('app.lock_passwords'))) {
            $user->location_id    = $request->input('location_id');
        }


        if ($request->input('avatar_delete') == 1) {
            $user->avatar = null;
        }


        if ($request->hasFile('avatar')) {
            $path = 'avatars';

            if(!Storage::disk('public')->exists($path)) Storage::disk('public')->makeDirectory($path, 775);

            $upload = $image = $request->file('avatar');
            $ext = $image->getClientOriginalExtension();
            $file_name = 'avatar-'.str_random(18).'.'.$ext;

            if ($image->getClientOriginalExtension()!='svg') {
                $upload =  Image::make($image->getRealPath())->resize(84, 84);
            }

            // This requires a string instead of an object, so we use ($string)
            Storage::disk('public')->put($path.'/'.$file_name, (string)$upload->encode());

            // Remove Current image if exists
            if (($user->avatar) && (Storage::disk('public')->exists($path.'/'.$user->avatar))) {
                Storage::disk('public')->delete($path.'/'.$user->avatar);
            }

            $user->avatar = $file_name;
        }



        if ($user->save()) {
            return redirect()->route('profile')->with('success', 'Account successfully updated');
        }
        return redirect()->back()->withInput()->withErrors($user->getErrors());
    }


    /**
     * Returns a page with the API token generation interface.
     *
     * We created a controller method for this because closures aren't allowed
     * in the routes file if you want to be able to cache the routes.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return View
     */
    public function api() {
        return view('account/api');
    }

    /**
     * User change email page.
     *
     * @return View
     */
    public function password()
    {
        $user = Auth::user();
        return view('account/change-password', compact('user'));
    }

    /**
     * Users change password form processing page.
     *
     * @return Redirect
     */
    public function passwordSave(Request $request)
    {

        if (config('app.lock_passwords')) {
            return redirect()->route('account.password.index')->with('error', trans('admin/users/table.lock_passwords'));
        }

        $user = Auth::user();
        if ($user->ldap_import=='1') {
            return redirect()->route('account.password.index')->with('error', trans('admin/users/message.error.password_ldap'));
        }

        $rules = array(
            'current_password'     => 'required',
            'password'         => Setting::passwordComplexityRulesSaving('store').'|confirmed',
        );

        $validator = \Validator::make($request->all(), $rules);
        $validator->after(function($validator) use ($request, $user) {

            if (!Hash::check($request->input('current_password'), $user->password)) {
                $validator->errors()->add('current_password', trans('validation.hashed_pass'));
            }

            // This checks to make sure that the user's password isn't the same as their username,
            // email address, first name or last name (see https://github.com/snipe/snipe-it/issues/8661)
            // While this is handled via SaveUserRequest form request in other places, we have to do this manually
            // here because we don't have the username, etc form fields available in the profile password change
            // form.

            // There may be a more elegant way to do this in the future.

            // First let's see if that option is enabled in the settings
            if (strpos(Setting::passwordComplexityRulesSaving('store'), 'disallow_same_pwd_as_user_fields') !== FALSE) {
                if (($request->input('password') == $user->username) ||
                    ($request->input('password') == $user->email) ||
                    ($request->input('password') == $user->first_name) ||
                    ($request->input('password') == $user->last_name))
                {
                    $validator->errors()->add('password', trans('validation.disallow_same_pwd_as_user_fields'));
                }
            }



            
        });

        if (!$validator->fails()) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return redirect()->route('account.password.index')->with('success', 'Password updated!');

        }
        return redirect()->back()->withInput()->withErrors($validator);


    }

    /**
     * Save the menu state of open/closed when the user clicks on the hamburger
     * menu.
     *
     * This URL is triggered via jquery in
     * resources/views/layouts/default.blade.php
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @return View
     */

    public function getMenuState(Request $request) {
        if ($request->input('state')=='open') {
            $request->session()->put('menu_state', 'open');
        } else {
            $request->session()->put('menu_state', 'closed');
        }
    }


}
