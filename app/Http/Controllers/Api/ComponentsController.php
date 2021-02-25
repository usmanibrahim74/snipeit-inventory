<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Transformers\ComponentsTransformer;
use App\Models\Company;
use App\Models\Component;
use Illuminate\Http\Request;

class ComponentsController extends Controller
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
        $this->authorize('view', Component::class);
        $components = Company::scopeCompanyables(Component::select('components.*')
            ->with('company', 'location', 'category'));

        if ($request->filled('search')) {
            $components = $components->TextSearch($request->input('search'));
        }

        if ($request->filled('company_id')) {
            $components->where('company_id','=',$request->input('company_id'));
        }

        if ($request->filled('category_id')) {
            $components->where('category_id','=',$request->input('category_id'));
        }

        if ($request->filled('location_id')) {
            $components->where('location_id','=',$request->input('location_id'));
        }

        // Set the offset to the API call's offset, unless the offset is higher than the actual count of items in which
        // case we override with the actual count, so we should return 0 items.
        $offset = (($components) && ($request->get('offset') > $components->count())) ? $components->count() : $request->get('offset', 0);

        // Check to make sure the limit is not higher than the max allowed
        ((config('app.max_results') >= $request->input('limit')) && ($request->filled('limit'))) ? $limit = $request->input('limit') : $limit = config('app.max_results');

        $allowed_columns = ['id','name','min_amt','order_number','serial','purchase_date','purchase_cost','company','category','qty','location','image'];
        $order = $request->input('order') === 'asc' ? 'asc' : 'desc';
        $sort = in_array($request->input('sort'), $allowed_columns) ? $request->input('sort') : 'created_at';

        switch ($sort) {
            case 'category':
                $components = $components->OrderCategory($order);
                break;
            case 'location':
                $components = $components->OrderLocation($order);
                break;
            case 'company':
                $components = $components->OrderCompany($order);
                break;
            default:
                $components = $components->orderBy($sort, $order);
                break;
        }

        $total = $components->count();
        $components = $components->skip($offset)->take($limit)->get();
        return (new ComponentsTransformer)->transformComponents($components, $total);
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
        $this->authorize('create', Component::class);
        $component = new Component;
        $component->fill($request->all());

        if ($component->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $component, trans('admin/components/message.create.success')));
        }
        return response()->json(Helper::formatStandardApiResponse('error', null, $component->getErrors()));
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
        $this->authorize('view', Component::class);
        $component = Component::findOrFail($id);

        if ($component) {
            return (new ComponentsTransformer)->transformComponent($component);
        }
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
        $this->authorize('update', Component::class);
        $component = Component::findOrFail($id);
        $component->fill($request->all());

        if ($component->save()) {
            return response()->json(Helper::formatStandardApiResponse('success', $component, trans('admin/components/message.update.success')));
        }

        return response()->json(Helper::formatStandardApiResponse('error', null, $component->getErrors()));
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
        $this->authorize('delete', Component::class);
        $component = Component::findOrFail($id);
        $this->authorize('delete', $component);
        $component->delete();
        return response()->json(Helper::formatStandardApiResponse('success', null, trans('admin/components/message.delete.success')));
    }

    /**
     * Display all assets attached to a component
     *
     * @author [A. Bergamasco] [@vjandrea]
     * @since [v4.0]
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
    */
    public function getAssets(Request $request, $id)
    {
        $this->authorize('view', \App\Models\Asset::class);
        
        $component = Component::findOrFail($id);
        $assets = $component->assets();

        $offset = request('offset', 0);
        $limit = $request->input('limit', 50);
        $total = $assets->count();
        $assets = $assets->skip($offset)->take($limit)->get();
        return (new ComponentsTransformer)->transformCheckedoutComponents($assets, $total);
    }
}
