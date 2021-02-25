<?php

namespace App\Listeners;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\CheckoutAcceptance;
use App\Models\Consumable;
use App\Models\LicenseSeat;
use App\Models\Recipients\AdminRecipient;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\CheckinAccessoryNotification;
use App\Notifications\CheckinAssetNotification;
use App\Notifications\CheckinLicenseNotification;
use App\Notifications\CheckinLicenseSeatNotification;
use App\Notifications\CheckoutAccessoryNotification;
use App\Notifications\CheckoutAssetNotification;
use App\Notifications\CheckoutConsumableNotification;
use App\Notifications\CheckoutLicenseNotification;
use App\Notifications\CheckoutLicenseSeatNotification;
use Illuminate\Support\Facades\Notification;

class CheckoutableListener
{

    /**
     * Notify the user about the checked out checkoutable
     */
    public function onCheckedOut($event) {
        /**
         * When the item wasn't checked out to a user, we can't send notifications
         */
        if(! $event->checkedOutTo instanceof User) {
            return;
        }

        /**
         * Make a checkout acceptance and attach it in the notification
         */
        $acceptance = $this->getCheckoutAcceptance($event);       

        if(!$event->checkedOutTo->locale){
            Notification::locale(Setting::getSettings()->locale)->send(
                $this->getNotifiables($event), 
                $this->getCheckoutNotification($event, $acceptance)
            );
        } else {
            Notification::send(
                $this->getNotifiables($event), 
                $this->getCheckoutNotification($event, $acceptance)
            );
        }
    }

    /**
     * Notify the user about the checked in checkoutable
     */    
    public function onCheckedIn($event) {

        \Log::debug('checkin fired');

        /**
         * When the item wasn't checked out to a user, we can't send notifications
         */
        if(!$event->checkedOutTo instanceof User) {
            \Log::debug('checked out to not a user');
            return;
        }

        /**
         * Send the appropriate notification
         */


        \Log::debug('checked out to a user');
        if(!$event->checkedOutTo->locale){
            \Log::debug('Use default settings locale');
            Notification::locale(Setting::getSettings()->locale)->send(
                $this->getNotifiables($event), 
                $this->getCheckinNotification($event)
            );
        } else {
            \Log::debug('Use user locale? I do not think this works as expected yet');
            // \Log::debug(print_r($this->getNotifiables($event), true));
            Notification::send(
                $this->getNotifiables($event), 
                $this->getCheckinNotification($event)
            );
        }
    }      

    /**
     * Generates a checkout acceptance
     * @param  Event $event
     * @return mixed
     */
    private function getCheckoutAcceptance($event) {
        if (!$event->checkoutable->requireAcceptance()) {
            return null;
        }

        $acceptance = new CheckoutAcceptance;
        $acceptance->checkoutable()->associate($event->checkoutable);
        $acceptance->assignedTo()->associate($event->checkedOutTo);
        $acceptance->save();

        return $acceptance;      
    }

    /**
     * Gets the entities to be notified of the passed event
     * 
     * @param  Event $event
     * @return Collection
     */
    private function getNotifiables($event) {
        $notifiables = collect();

        /**
         * Notify the user who checked out the item
         */
        $notifiables->push($event->checkedOutTo);

        /**
         * Notify Admin users if the settings is activated
         */
        if (Setting::getSettings()->admin_cc_email != '') {
            $notifiables->push(new AdminRecipient());
        }

        return $notifiables;       
    }

    /**
     * Get the appropriate notification for the event
     * 
     * @param  CheckoutableCheckedIn $event 
     * @return Notification
     */
    private function getCheckinNotification($event) {

        // $model = get_class($event->checkoutable);



        $notificationClass = null;

        switch (get_class($event->checkoutable)) {
            case Accessory::class:
                $notificationClass = CheckinAccessoryNotification::class;
                break;
            case Asset::class:
                $notificationClass = CheckinAssetNotification::class;
                break;    
            case LicenseSeat::class:
                $notificationClass = CheckinLicenseSeatNotification::class;
                break;
        }

        \Log::debug('Notification class: '.$notificationClass);
        return new $notificationClass($event->checkoutable, $event->checkedOutTo, $event->checkedInBy, $event->note);  
    }

    /**
     * Get the appropriate notification for the event
     * 
     * @param  CheckoutableCheckedIn $event 
     * @param  CheckoutAcceptance $acceptance 
     * @return Notification
     */
    private function getCheckoutNotification($event, $acceptance) {
        $notificationClass = null;

        switch (get_class($event->checkoutable)) {
            case Accessory::class:
                $notificationClass = CheckoutAccessoryNotification::class;
                break;
            case Asset::class:
                $notificationClass = CheckoutAssetNotification::class;
                break;
            case Consumable::class:
                $notificationClass = CheckoutConsumableNotification::class;
                break;    
            case LicenseSeat::class:
                $notificationClass = CheckoutLicenseSeatNotification::class;
                break;                
        }

        return new $notificationClass($event->checkoutable, $event->checkedOutTo, $event->checkedOutBy, $acceptance, $event->note);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\CheckoutableCheckedIn',
            'App\Listeners\CheckoutableListener@onCheckedIn'
        ); 

        $events->listen(
            'App\Events\CheckoutableCheckedOut',
            'App\Listeners\CheckoutableListener@onCheckedOut'
        ); 
    }

}