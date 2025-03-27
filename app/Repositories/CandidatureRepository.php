<?php
namespace App\Repositories;

use App\Models\Candidature;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CandidatureRepository
{
    protected $model;

    public function __construct(Candidature $candidature)
    {
        $this->model = $candidature;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $candidature = $this->findById($id);
        $candidature->update($data);
        return $candidature;
    }

    public function delete($id)
    {
        $candidature = $this->findById($id);
        return $candidature->delete();
    }

    public function getByUser($userId)
    {
   
      
        return $this->model->where('user_id', $userId)->get();
        
    }

    public function getByAnnonce($annonceId)
    {
        return $this->model->where('annonce_id', $annonceId)->get();
    }

    public function updateStatus($id, $status)
    {
        $candidature = $this->findById($id);
        $candidature->statut = $status;
        $candidature->save();
        return $candidature;
    }

    public function getByStatus(int $userId, string $status)
    {
        
        return Candidature::where('user_id', $userId)
                          ->where('statut', $status)
                          ->get();
    }

    public function getProfileByidf($id){
        return User::findOrFail($id);
    }
    public function updateProfile(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return tap($user)->update($data);
    }
    
    public function deleteProfile($id){
       $user = User::findOrFail($id);
        return $user->delete();

    }

}
