<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use App\Models\Candidature;
use App\Models\User;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */

    
     
     public function boot()
     {
         Gate::define('view-candidature', function (User $user, Candidature $candidature) {
            return $user->role === 'admin' ||
                    ($user->role === 'candidat' && $user->id === $candidature->user_id) ||
                    ($user->role === 'recruteur' && $user->annonces()->where('id', $candidature->annonce_id)->exists());
         });
     
         Gate::define('delete-candidature', function (User $user, Candidature $candidature) {
             return $user->id === $candidature->user_id && $user->role === 'candidat' && $candidature->statut === 'en_attente';
         });
     
         Gate::define('update-status', function (User $user) {
             return $user->role === 'recruteur';
         });
     
         Gate::define('manage-profile', function (User $user, $profileId) {
             return $user->id === $profileId;
         });
     }
     
    
}
