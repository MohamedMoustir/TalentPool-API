<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'titre', 'description', 'entreprise', 'localisation', 'type_contrat', 'salaire', 'date_limite', 'active'];

    public function recruteur()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class); 
    }
   
    

}


