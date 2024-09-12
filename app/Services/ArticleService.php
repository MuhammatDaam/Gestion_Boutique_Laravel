<?php

namespace App\Services;

interface ArticleService
{
    public function all();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
    public function findByLibelle($libelle);
    public function findByEtat($etat);
    public function filter(array $filters);
    public function softDelete($id);
    public function restore($id);
    public function forceDelete($id);
    public function updateMultiple(array $articles);
}