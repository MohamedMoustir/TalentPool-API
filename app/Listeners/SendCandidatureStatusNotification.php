<?php
namespace App\Listeners;

use App\Events\CandidatureStatusUpdated;
use App\Mail\CandidatureStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendCandidatureStatusNotification implements ShouldQueue
{
    public function handle(CandidatureStatusUpdated $event)
    {
        $candidature = $event->candidature;
        $user = $candidature->user;
        
        // Mail::to($user->email)->send(new CandidatureStatusChanged($candidature));
    }
}