<?php
namespace App\Events;

use App\Models\Candidature;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CandidatureStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $candidature;
    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }
}