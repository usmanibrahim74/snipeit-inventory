<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\LicenseSeatsTransformer;
use App\Http\Transformers\LicensesTransformer;
use App\Http\Transformers\SelectlistTransformer;
use App\Models\Company;
use App\Models\License;
use App\Models\LicenseSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LicensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', License::class);
        $licenses = Company::scopeCompanyables(License::with('company', 'manufacturer', 'freeSeats', 'supplier','category')->withCount('freeSeats as free_seats_count'));


        if ($request->filled('company_id')) {
            $licenses->where('company_id','=',$request->input('company_id'));
        }

        if ($request->filled('name')) {
            $licenses->where('licenses.name','=',$request->input('name'));
        }

        if ($request->filled('product_key')) {
            $licenses->where('licenses.serial','=',$request->input('product_key'));
        }

        if ($request->filled('order_number')) {
            $licenses->where('order_number','=',$request->input('order_number'));
        }

        if ($request->filled('purchase_order')) {
            $licenses->where('purchase_order','=',$request->input('purchase_order'));
        }

        if ($request->filled('license_name')) {
            $licenses->where('license_name','=',$request->input('license_name'));
        }

        if ($request->filled('license_email')) {
            $licenses->where('license_email','=',$request->input('license_email'));
        }

        if ($request->filled('manufacturer_id')) {
            $licenses->where('manufacturer_id','=',$request->input('manufacturer_id'));
        }

        if ($request->filled('supplier_id')) {
            $licenses->where('supplier_id','=',$request->input('supplier_id'));
        }

        if ($request->filled('category_id')) {
            $licenses->where('category_id','=',$request->input('category_id'));
        }

        if ($request->filled('depreciation_id')) {
            $licenses->where('depreciation_id','=',$request->input('depreciation_id'));
        }

        if ($request->filled('supplier_id')) {
            $licenses->where('supplier_id','=',$request->input('supplier_id'));
        }


        if ($request->filled('search')) {
            $licenses = $licenses->TextSearch($request->input('search'));
        }


        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = (($licenses) && ($request->get('offset') > $licenses->count())) ? $licenses->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';


        switch ($request->input('sort')) {
                case 'manufacturer':
                    $licenses = $licenses->leftJoin('manufacturers', 'licenses.manufacturer_id', '=', 'manufacturers.id')->orderBy('manufacturers.name', $order);
                break;
            case 'supplier':
                $licenses = $licenses->leftJoin('suppliers', 'licenses.supplier_id', '=', 'suppliers.id')->orderBy('suppliers.name', $order);
                break;
            case 'category':
                $licenses = $licenses->leftJoin('categories', 'licenses.category_id', '=', 'categories.id')->orderBy('categories.name', $order);
                break;
            case 'company':
                $licenses = $licenses->leftJoin('companies', 'licenses.company_id', '=', 'companies.id')->orderBy('companies.name', $order);
                break;
            default:
                $allowed_columns = ['id','name','purchase_cost','expiration_date','purchase_order','order_number','notes','purchase_date','serial','company','category','license_name','license_email','free_seats_count','seats'];
                $sort = in_array($request->input('sort'), $allowed_columns) ? e($request->input('sort')) : 'created_at';
                $licenses = $licenses->orderBy($sort, $order);
                break;
        }



        $total = $licenses->count();

        $licenses = $licenses->skip($offset)->take($limit)->get();
        return (new LicensesTransformer)->transformLicenses($licenses, $total);

    }




    /**
     * Store a newly created resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create', License::class);
        $license = new License;
        $license->fill($request->all());

        if($license->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $license, trans('admin/licenses/message.create.success')));
        }
        return response()->json(Helper::formatStandardApiResponse('error', null, $license->getErrors()));
    }

    /**
     * Display the specified resource.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', License::class);
        $license = License::withCount('freeSeats')->findOrFail($id);
        $license = $license->load('assignedusers', 'licenseSeats.user', 'licenseSeats.asset');
        return (new LicensesTransformer)->transformLicense($license);
    }


    /**
     * Update the specified resource in storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->authorize('update', License::class);

        $license = License::findOrFail($id);
        $license->fill($request->all());

        if ($license->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $license, trans('admin/licenses/message.update.success')));
        }

        return Helper::formatStandardApiResponse('error', null, $license->getErrors());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.0]
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $license = License::findOrFail($id);
        $this->authorize('delete', $license);

        if($license->assigned_seats_count == 0) {
            // Delete the license and the associated license seats
            DB::table('license_seats')
                ->where('id', $license->id)
                ->update(array('assigned_to' => null,'asset_id' => null));

            $licenseSeats = $license->licenseseats();
            $licenseSeats->delete();
            $license->delete();

            // Redirect to the licenses management page
            return response()->json(Helper::formatStandardApiResponse('success', null,  trans('admin/licenses/message.delete.success')));
        }
        return response()->json(Helper::formatStandardApiResponse('error', null, trans('admin/licenses/message.assoc_users')));
    }

    /**
     * Get license seat listing
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v1.0]
     * @param int $licenseId
     * @return \Illuminate\Contracts\View\View
     */
    public function seats(Request $request, $licenseId)
    {

        if ($license = License::find($licenseId)) {

            $this->authorize('view', $license);

            $seats = LicenseSeat::with('license', 'user', 'asset', 'user.department')
                ->where('license_seats.license_id', $licenseId);

            $order = $request->input('order') === 'asc' ? 'asc' : 'desc';

            if ($request->input('sort')=='department') {
                $seats->OrderDepartments($order);
            } else {
                $seats->orderBy('id', $order);
            }

            $offset = (($seats) && (request('offset') > $seats->count())) ? 0 : request('offset', 0);
            $limit = request('limit', 50);
            
            $total = $seats->count();

            $seats = $seats->skip($offset)->take($limit)->get();

            if ($seats) {
                return (new LicenseSeatsTransformer)->transformLicenseSeats($seats, $total);
            }

        }

        return response()->json(Helper::formatStandardApiResponse('error', null, trans('admin/licenses/message.does_not_exist')), 200);

    }

    
    /**
     * Gets a paginated collection for the select2 menus
     *
     * @see \App\Http\Transformers\SelectlistTransformer
     */
    public function selectlist(Request $request)
    {

        $licenses = License::select([
            'licenses.id',
            'licenses.name'
        ]);

        if ($request->filled('search')) {
            $licenses = $licenses->where('licenses.name', 'LIKE', '%'.$request->get('search').'%');
        }

        $licenses = $licenses->orderBy('name', 'ASC')->paginate(50);


        return (new SelectlistTransformer)->transformSelectlist($licenses);
    }


}
