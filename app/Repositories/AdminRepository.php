<?php
namespace App\Repositories;

use App\Models\Annonce;
use App\Models\Candidature;
use App\Models\User;

class adminRepository
{
    public function getStatsByRecruteur($recruteurId)
    {
        return [
            'total_utilisateurs' => User::count(),
            'total_annonces' => Annonce::where('user_id', $recruteurId)->count(),
            'total_candidatures' => Annonce::whereHas('candidatures', function ($query) use ($recruteurId) {
                $query->where('user_id', $recruteurId);
            })->count(),
        ];
    }
    public function getGlobalStats()
    {
        return [
            'total_utilisateurs' => User::count(),
            'total_annonces' => Annonce::count(),
            'total_candidatures' => Candidature::count(),
        ];
    }
}