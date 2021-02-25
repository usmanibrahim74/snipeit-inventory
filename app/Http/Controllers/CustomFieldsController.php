<?php
namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\CustomFieldRequest;
use App\Models\CustomField;
use App\Models\CustomFieldset;
use Illuminate\Support\Facades\Auth;
use Redirect;

/**
 * This controller handles all actions related to Custom Asset Fields for
 * the Snipe-IT Asset Management application.
 *
 * @todo Improve documentation here.
 * @todo Check for raw DB queries and try to convert them to query builder statements
 * @version    v2.0
 * @author [Brady Wetherington] [<uberbrady@gmail.com>]
 */

class CustomFieldsController extends Controller
{

    /**
     * Returns a view with a listing of custom fields.
     *
     * @author [Brady Wetherington] [<uberbrady@gmail.com>]
     * @since [v1.8]
     * @return \Illuminate\Support\Facades\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', CustomField::class);

        $fieldsets = CustomFieldset::with("fields", "models")->get();
        $fields = CustomField::with("fieldset")->get();

        return view("custom_fields.index")->with("custom_fieldsets", $fieldsets)->with("custom_fields", $fields);
    }


    /**
     * Returns a view with a form to create a new custom field.
     *
     * @see CustomFieldsController::storeField()
     * @author [Brady Wetherington] [<uberbrady@gmail.com>]
     * @since [v1.8]
     * @return \Illuminate\Support\Facades\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', CustomField::class);

        return view("custom_fields.fields.edit",[
            'predefinedFormats' => Helper::predefined_formats(),
	    'customFormat' => ''
        ])->with('field', new CustomField());
    }


    /**
     * Validates and stores a new custom field.
     *
     * @see CustomFieldsController::createField()
     * @author [Brady Wetherington] [<uberbrady@gmail.com>]
     * @since [v1.8]
     * @return Redirect
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(CustomFieldRequest $request)
    {
        $this->authorize('create', CustomField::class);

        $field = new CustomField([
            "name" => $request->get("name"),
            "element" => $request->get("element"),
            "help_text" => $request->get("help_text"),
            "field_values" => $request->get("field_values"),
            "field_encrypted" => $request->get("field_encrypted", 0),
            "show_in_email" => $request->get("show_in_email", 0),
            "user_id" => Auth::id()
        ]);


        if ($request->filled("custom_format")) {
            $field->format = e($request->get("custom_format"));
        } else {
            $field->format = e($request->get("format"));
        }

        if ($field->save()) {

            return redirect()->route("fields.index")->with("success", trans('admin/custom_fields/message.field.create.success'));
        }

        return redirect()->back()->withInput()
            ->with('error', trans('admin/custom_fields/message.field.create.error'));

    }


    /**
     * Detach a custom field from a fieldset.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return Redirect
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteFieldFromFieldset($field_id, $fieldset_id)
    {
        $field = CustomField::find($field_id);

        $this->authorize('update', $field);

        if ($field->fieldset()->detach($fieldset_id)) {
            return redirect()->route('fieldsets.show', ['fieldset' => $fieldset_id])
                ->with("success", trans('admin/custom_fields/message.field.delete.success'));
        }

        return redirect()->back()->withErrors(['message' => "Field is in-use"]);
    }

    /**
     * Delete a custom field.
     *
     * @author [Brady Wetherington] [<uberbrady@gmail.com>]
     * @since [v1.8]
     * @return Redirect
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($field_id)
    {
        $field = CustomField::find($field_id);

        $this->authorize('delete', $field);

        if ($field->fieldset->count()>0) {
            return redirect()->back()->withErrors(['message' => "Field is in-use"]);
        }
        $field->delete();
        return redirect()->route("fields.index")
            ->with("success", trans('admin/custom_fields/message.field.delete.success'));
    }


    /**
     * Return a view to edit a custom field
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param  int $id
     * @since [v4.0]
     * @return \Illuminate\Support\Facades\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $field = CustomField::find($id);

        $this->authorize('update', $field);

        $customFormat = '';
        if((stripos($field->format, 'regex') === 0) && ($field->format !== CustomField::PREDEFINED_FORMATS['MAC'])) {
            $customFormat = $field->format;
        }

        return view("custom_fields.fields.edit",[
            'field'             => $field,
            'customFormat'      => $customFormat,
            'predefinedFormats' => Helper::predefined_formats()
        ]);
    }


    /**
     * Store the updated field
     *
     * @todo Allow encrypting/decrypting if encryption status changes
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param  int $id
     * @since [v4.0]
     * @return Redirect
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(CustomFieldRequest $request, $id)
    {
        $field =  CustomField::find($id);
 
        $this->authorize('update', $field);

        $field->name          = e($request->get("name"));
        $field->element       = e($request->get("element"));
        $field->field_values  = e($request->get("field_values"));
        $field->user_id       = Auth::id();
        $field->help_text     = $request->get("help_text");
        $field->show_in_email = $request->get("show_in_email", 0);

        if ($request->get('format') == 'CUSTOM REGEX') {
            $field->format = e($request->get("custom_format"));
        } else {
            $field->format = e($request->get("format"));
        }

        if ($field->save()) {
           return redirect()->route("fields.index")->with("success", trans('admin/custom_fields/message.field.update.success'));
        }

        return redirect()->back()->withInput()->with('error', trans('admin/custom_fields/message.field.update.error'));
    }



}
