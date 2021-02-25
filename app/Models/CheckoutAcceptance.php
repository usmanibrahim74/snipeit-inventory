<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckoutAcceptance extends Model
{

	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    	'accepted_at',
    	'declined_at',
    	'deleted_at'
    ];

    /**
     * The resource that was is out
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function checkoutable() {
    	return $this->morphTo();
    }

    /**
     * The user that the checkoutable was checked out to
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedTo() {
    	return $this->belongsTo(User::class);
    }

    /**
     * Is this checkout acceptance pending?
     * 
     * @return boolean
     */
    public function isPending() {
        return $this->accepted_at == null && $this->declined_at == null;
    }

    /**
     * Was the checkoutable checked out to this user?
     * 
     * @param  User    $user
     * @return boolean
     */
    public function isCheckedOutTo(User $user) {
        return $this->assignedTo->is($user);
    }

    /**
     * Accept the checkout acceptance
     * 
     * @param  string $signature_filename
     */
    public function accept($signature_filename) {
        $this->accepted_at        = now();
        $this->signature_filename = $signature_filename;
        $this->save();

        /**
         * Update state for the checked out item
         */
        $this->checkoutable->acceptedCheckout($this->assignedTo, $signature_filename);     
    }

    /**
     * Decline the checkout acceptance
     * 
     * @param  string $signature_filename
     */
    public function decline($signature_filename) {
        $this->declined_at        = now();
        $this->signature_filename = $signature_filename;
        $this->save();

        /**
         * Update state for the checked out item
         */
        $this->checkoutable->declinedCheckout($this->assignedTo, $signature_filename);
    }    

    /**
     * Filter checkout acceptences by the user
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  User    $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser(Builder $query, User $user) {
        return $query->where('assigned_to_id', $user->id);
    }

    /**
     * Filter to only get pending acceptances
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending(Builder $query) {
        return $query->whereNull('accepted_at')->whereNull('declined_at');
    }
}
