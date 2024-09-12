<?php

namespace App\Repository;

interface ArticleRepository
{
    public function all();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function findByLibelle($libelle);
    public function findByEtat($etat);
    public function findByFilters(array $filters);
    public function softDelete($id);
    public function restore($id);
    public function forceDelete($id);
    
}