<?php
namespace App\Services;

use App\Models\Annonce;
use function PHPUnit\Framework\returnSelf;

class AnnonceService
{
    public function getAllAnnonces()
    {
        return Annonce::all();
    }

    public function getAnnonceById($id)
    {
        return Annonce::findOrFail($id);
    }

    public function createAnnonce(array $data,$user_id)
    {
        $data['user_id'] = $user_id;
        $Annonce = Annonce::create($data);
        return response()->json([
        'data'=>$Annonce
        ]);
    }
    public function updateAnnonce($id, array $data)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->update($data);
        return $annonce;
    }

    public function deleteAnnonce($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->delete();
    }

    
}
