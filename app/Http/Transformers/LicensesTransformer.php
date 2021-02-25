<?php
namespace App\Http\Transformers;

use App\Helpers\Helper;
use App\Models\License;
use Gate;
use Illuminate\Database\Eloquent\Collection;

class LicensesTransformer
{

    public function transformLicenses (Collection $licenses, $total)
    {
        $array = array();
        foreach ($licenses as $license) {
            $array[] = self::transformLicense($license);
        }
        return (new DatatablesTransformer)->transformDatatables($array, $total);
    }

    public function transformLicense (License $license)
    {
        $array = [
            'id' => (int) $license->id,
            'name' => e($license->name),
            'company' => ($license->company) ? ['id' => (int) $license->company->id,'name'=> e($license->company->name)] : null,
            'manufacturer' =>  ($license->manufacturer) ? ['id' => (int) $license->manufacturer->id,'name'=> e($license->manufacturer->name)] : null,
            'product_key' => (Gate::allows('viewKeys', License::class)) ? e($license->serial) : '------------',
            'order_number' => e($license->order_number),
            'purchase_order' => e($license->purchase_order),
            'purchase_date' => Helper::getFormattedDateObject($license->purchase_date, 'date'),
            'purchase_cost' => e($license->purchase_cost),
            'notes' => e($license->notes),
            'expiration_date' => Helper::getFormattedDateObject($license->expiration_date, 'date'),
            'seats' => (int) $license->seats,
            'free_seats_count' => (int) $license->free_seats_count,
            'license_name' =>  e($license->license_name),
            'license_email' => e($license->license_email),
            'reassignable' => ($license->reassignable == 1) ? true : false,
            'maintained' => ($license->maintained == 1) ? true : false,
            'supplier' =>  ($license->supplier) ? ['id' => (int)  $license->supplier->id,'name'=> e($license->supplier->name)] : null,
            'category' =>  ($license->category) ? ['id' => (int)  $license->category->id,'name'=> e($license->category->name)] : null,
            'created_at' => Helper::getFormattedDateObject($license->created_at, 'datetime'),
            'updated_at' => Helper::getFormattedDateObject($license->updated_at, 'datetime'),
            'user_can_checkout' => (bool) ($license->free_seats_count > 0),
        ];

        $permissions_array['available_actions'] = [
            'checkout' => Gate::allows('checkout', License::class),
            'checkin' => Gate::allows('checkin', License::class),
            'clone' => Gate::allows('create', License::class),
            'update' => Gate::allows('update', License::class),
            'delete' => Gate::allows('delete', License::class),
        ];

        $array += $permissions_array;

        return $array;
    }

    public function transformAssetsDatatable($licenses) {
        return (new DatatablesTransformer)->transformDatatables($licenses);
    }



}
