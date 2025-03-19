<?php

namespace App\Repositories;

use App\Models\Annonce;

class AnnonceRepository
{
    public function all()
    {
        return Annonce::all();
    }

    public function find($id)
    {
        return Annonce::findOrFail($id);
    }

    public function create(array $data)
    {
        return Annonce::create($data);
    }

    public function update($id, array $data)
    {
        $annonce = $this->find($id);
        $annonce->update($data);
        return $annonce;
    }

    public function delete($id)
    {
        $annonce = $this->find($id);
        $annonce->delete();
        return $annonce;
    }
}
