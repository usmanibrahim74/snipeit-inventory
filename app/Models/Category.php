<?php
namespace App\Models;

use App\Http\Traits\UniqueUndeletedTrait;
use App\Models\Traits\Searchable;
use App\Presenters\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Gate;
use Watson\Validating\ValidatingTrait;

/**
 * Model for Categories. Categories are a higher-level group
 * than Asset Models, and handle things like whether or not
 * to require acceptance from the user, whether or not to
 * send a EULA to the user, etc.
 *
 * @version    v1.0
 */
class Category extends SnipeModel
{
    protected $presenter = 'App\Presenters\CategoryPresenter';
    use Presentable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'categories';
    protected $hidden = ['user_id','deleted_at'];


    protected $casts = [
        'user_id'      => 'integer',
    ];


    /**
    * Category validation rules
    */
    public $rules = array(
        'user_id' => 'numeric|nullable',
        'name'   => 'required|min:1|max:255|unique_undeleted',
        'require_acceptance'   => 'boolean',
        'use_default_eula'   => 'boolean',
        'category_type'   => 'required|in:asset,accessory,consumable,component,license',
    );

    /**
    * Whether the model should inject it's identifier to the unique
    * validation rules before attempting validation. If this property
    * is not set in the model it will default to true.
    *
    * @var boolean
    */
    protected $injectUniqueIdentifier = true;
    use ValidatingTrait;
    use UniqueUndeletedTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_type',
        'checkin_email',
        'eula_text',
        'name',
        'require_acceptance',
        'use_default_eula',
        'user_id',
    ];

    use Searchable;

    /**
     * The attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableAttributes = ['name', 'category_type'];

    /**
     * The relations and their attributes that should be included when searching the model.
     *
     * @var array
     */
    protected $searchableRelations = [];


    /**
     * Checks if category can be deleted
     *
     * @author [Dan Meltzer] [<dmeltzer.devel@gmail.com>]
     * @since [v5.0]
     * @return bool
     */
    public function isDeletable()
    {
         return (Gate::allows('delete', $this)
                && ($this->itemCount() == 0));
    }

    /**
     * Establishes the category -> accessories relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function accessories()
    {
        return $this->hasMany('\App\Models\Accessory');
    }

    /**
     * Establishes the category -> licenses relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v4.3]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function licenses()
    {
        return $this->hasMany('\App\Models\License');
    }

    /**
     * Establishes the category -> consumables relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function consumables()
    {
        return $this->hasMany('\App\Models\Consumable');
    }

    /**
     * Establishes the category -> consumables relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function components()
    {
        return $this->hasMany('\App\Models\Component');
    }

    /**
     * Get the number of items in the category
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return int
     */
    public function itemCount()
    {
        switch ($this->category_type) {
            case 'asset':
                return $this->assets()->count();
            case 'accessory':
                return $this->accessories()->count();
            case 'component':
                return $this->components()->count();
            case 'consumable':
                return $this->consumables()->count();
            case 'license':
                return $this->licenses()->count();
        }
        return '0';
    }

    /**
     * Establishes the category -> assets relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function assets()
    {
        return $this->hasManyThrough('\App\Models\Asset', '\App\Models\AssetModel', 'category_id', 'model_id');
    }

    /**
     * Establishes the category -> models relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function models()
    {
        return $this->hasMany('\App\Models\AssetModel', 'category_id');
    }

    /**
     * Checks for a category-specific EULA, and if that doesn't exist,
     * checks for a settings level EULA
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return string | null
     */
    public function getEula()
    {

        $Parsedown = new \Parsedown();

        if ($this->eula_text) {
            return $Parsedown->text(e($this->eula_text));
        } elseif ((Setting::getSettings()->default_eula_text) && ($this->use_default_eula=='1')) {
            return $Parsedown->text(e(Setting::getSettings()->default_eula_text));
        } else {
            return null;
        }

    }


    /**
     * -----------------------------------------------
     * BEGIN QUERY SCOPES
     * -----------------------------------------------
     **/

    /**
     * Query builder scope for whether or not the category requires acceptance
     *
     * @author  Vincent Sposato <vincent.sposato@gmail.com>
     *
     * @param  \Illuminate\Database\Query\Builder  $query  Query builder instance
     * @return \Illuminate\Database\Query\Builder          Modified query builder
     */

    public function scopeRequiresAcceptance($query)
    {

        return $query->where('require_acceptance', '=', true);
    }
}
