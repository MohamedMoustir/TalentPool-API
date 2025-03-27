<?php
namespace App\Http\Controllers;

use App\Http\Requests\CandidatureRequest;
use App\Http\Requests\StatusUpdateRequest;
use App\Mail\WelcomeEmailNotification;
use App\Services\CandidatureService;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class CandidatureController extends Controller
{
    protected $candidatureService;

    public function __construct(CandidatureService $candidatureService)
    {
        $this->candidatureService = $candidatureService;
    }
    public function index(Request $request)
    {

        if ($request->user()->role === 'admin') {
            $candidatures = $this->candidatureService->getAllCandidatures();
            return response()->json(['candidatures' => $candidatures]);
        } elseif ($request->user()->role === 'recruteur') {
            $user = Auth::user();
            $annonceIds = $user->annonces()->pluck('id');
            $candidatures = [];

            foreach ($annonceIds as $annonceId) {
                $candidatures = array_merge(
                    $candidatures,
                    $this->candidatureService->getAnnonceCandidatures($annonceId)->toArray()
                );
            }

            return response()->json(['candidatures' => $candidatures]);
        } else {
            $candidatures = $this->candidatureService->getUserCandidatures(Auth::id());
            return response()->json(['candidatures' => $candidatures]);
        }
    }
    public function show($id)
    {
        $candidature = $this->candidatureService->getCandidatureById($id);
        Gate::authorize('view-candidature', $candidature);
        $user = Auth::user();

            return response()->json(['candidature' => $candidature]);
    
    }
    public function store(CandidatureRequest $request)
    {

        if ($request->user()->role !== 'candidat') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if (!$request->hasFile('cv') || !$request->hasFile('lettre_motivation')) {
            return response()->json(['message' => 'CV et lettre de motivation requis'], 422);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $candidature = $this->candidatureService->createCandidature(
            $data,
            $request->file('cv'),
            $request->file('lettre_motivation')
        );

        return response()->json(['message' => 'Candidature créée avec succès', 'candidature' => $candidature], 201);
    }
    public function destroy($id)
{
    $candidature = $this->candidatureService->getCandidatureById($id);
    Gate::authorize('delete-candidature', $candidature);

    $this->candidatureService->deleteCandidature($id);
    return response()->json(['message' => 'Candidature retirée avec succès']);
}

    // error
    public function myCandidatures()
    {

        if (Auth::user()->role !== 'candidat') {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $candidatures = $this->candidatureService->getUserCandidatures(Auth::id());
        return response()->json(['candidatures' => $candidatures]);
    }
    public function updateStatus($id, StatusUpdateRequest $request)
{
    Gate::authorize('update-status');

    $updatedCandidature = $this->candidatureService->updateCandidatureStatus(
        $id,
        $request->validated()['statut']
    );

    Mail::to('itsmoustir@gmail.com')->send(new WelcomeEmailNotification($request->statut));

    return response()->json([
        'message' => 'Statut mis à jour avec succès',
        'candidature' => $updatedCandidature
    ]);
}

    public function getCandidaturesByStatus(Request $request)
    {
        $status = $request->query('status');

        if (!$status) {
            return response()->json(['message' => 'Le statut est requis'], 400);
        }

        $candidatures = $this->candidatureService->getCandidaturesByStatus(auth()->id(), $status);

        if ($candidatures->isEmpty()) {
            return response()->json(['message' => 'Aucune candidature trouvée pour ce statut'], 404);
        }

        return response()->json($candidatures);
    }
    public function getNotifications($id)
    {
        $candidature = $this->candidatureService->getCandidatureById($id);
        $user = Auth::user();

        if (
            $user->role === 'admin' || ($user->role === 'candidat' && $user->id === $candidature->user_id) ||
            ($user->role === 'recruteur' && $user->annonces()->where('id', $candidature->annonce_id)->exists())
        ) {
            return response()->json(['notifications' => []]);
        }

        return response()->json(['message' => 'Non autorisé'], 403);
    }
    public function getProfile(){
         
        $profile = $this->candidatureService->getProfile(Auth::id());

        return response()->json(['message' => 'profile' , 'profile'=>$profile ]);
      
    
    }

    public function updateProfile(Request $request)
    {
        Gate::authorize('manage-profile', Auth::id());
    
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:8',
        ]);
    
        $user = $this->candidatureService->updateProfile($validated);
    
        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }
    
    public function deleteProfile($id)
    {
       
    
        $this->candidatureService->deleteProfile($id);
        return response()->json(['message' => 'Profile supprimé avec succès']);
    }
    

}

