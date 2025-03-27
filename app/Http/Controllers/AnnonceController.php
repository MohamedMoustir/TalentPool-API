<?php
namespace App\Http\Controllers;

use App\Services\AnnonceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    protected $annonceService;

    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    public function index()
    {
        $annonces = $this->annonceService->getAllAnnonces();
        return response()->json($annonces);
    }

    public function show($id)
    {
        $annonce = $this->annonceService->getAnnonceById($id);
        return response()->json($annonce);
    }

    public function store(Request $request)
    {
   
        $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'required|string',
            'entreprise' => 'required|string',
            'localisation' => 'required|string',
            'type_contrat' => 'required|string',
            'salaire' => 'nullable|numeric',
            'date_limite' => 'nullable|date',
        ]);
        
           $user_id = Auth::id();
        $annonce = $this->annonceService->createAnnonce($request->all(),$user_id);

        return response()->json($annonce, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'entreprise' => 'nullable|string',
            'localisation' => 'nullable|string',
            'type_contrat' => 'nullable|string',
            'salaire' => 'nullable|numeric',
            'date_limite' => 'nullable|date',
        ]);
        $annonce = $this->annonceService->updateAnnonce($id, $request->all());
        return response()->json($annonce);
    }

    public function destroy($id)
    {
        $this->annonceService->deleteAnnonce($id);
        return response()->json(['message' => 'Annonce supprimée avec succès']);
    }
    
}

