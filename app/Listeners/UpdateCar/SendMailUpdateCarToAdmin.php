<?php

namespace App\Listeners\UpdateCar;

use App\Mail\ToAdmin\NewCarRegister;
use App\Mail\ToAdmin\NewCarUpdated;
use App\Models\Admin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailUpdateCarToAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $admins = Admin::select('email')->get();
        try {
            Mail::to($admins)->send(new NewCarUpdated(
                auth()->user()->company_name,
                $event->car
            ));
        }catch (\Exception $e){
            \Log::error($e);
        }
    }
}
