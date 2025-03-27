<?php

namespace App\Services;

use App\Events\CandidatureStatusUpdated;
use App\Models\Candidature;
use App\Repositories\CandidatureRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidatureService
{
    protected $candidatureRepository;

    public function __construct(CandidatureRepository $candidatureRepository)
    {
        $this->candidatureRepository = $candidatureRepository;
    }

    public function getAllCandidatures()
    {
        return $this->candidatureRepository->all();
    }

    public function getCandidatureById($id)
    {
        return $this->candidatureRepository->findById($id);
    }

    public function createCandidature(array $data, $cvFile, $lettreFile)
    {
      
        $cvPath = $cvFile->store('candidatures/cv', 'public');
        $lettrePath = $lettreFile->store('candidatures/lettres', 'public');
        
        $data['cv_path'] = $cvPath;
        $data['lettre_motivation_path'] = $lettrePath;
        
        return $this->candidatureRepository->create($data);
    }

    public function updateCandidature($id, array $data)
    {
        return $this->candidatureRepository->update($id, $data);
    }

    public function deleteCandidature($id)
    {
        $candidature = $this->candidatureRepository->findById($id);
        
        // Delete files
        if (Storage::disk('public')->exists($candidature->cv_path)) {
            Storage::disk('public')->delete($candidature->cv_path);
        }
        
        if (Storage::disk('public')->exists($candidature->lettre_motivation_path)) {
            Storage::disk('public')->delete($candidature->lettre_motivation_path);
        }
        
        return $this->candidatureRepository->delete($id);
    }

    public function getUserCandidatures($userId)
    {
    
        return $this->candidatureRepository->getByUser($userId);
    }

    public function getAnnonceCandidatures($annonceId)
    {
        return $this->candidatureRepository->getByAnnonce($annonceId);
    }

    public function updateCandidatureStatus($id, $status)
    {
        $candidature = $this->candidatureRepository->updateStatus($id, $status);
        
        event(new CandidatureStatusUpdated($candidature));
        
        return $candidature;
    }

    public function getCandidaturesByStatus(int $userId, string $status)
    {
        return $this->candidatureRepository->getByStatus($userId, $status);
    }

    public function getProfile($id){
     return $this->candidatureRepository->getProfileByidf($id);
    }

    public function deleteProfile($id){
    return $this->candidatureRepository->deleteProfile($id);
    }
    public function updateProfile(array $data)
    {
        $user = Auth::user();
        return $this->candidatureRepository->updateProfile($user, $data);
    }

}
