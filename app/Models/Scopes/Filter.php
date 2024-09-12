<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Filter implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (request()->has('comptes')) {
            $comptes = request()->input('comptes');
            if ($comptes === 'oui') {
                $builder->whereNotNull('user_id');
            } elseif ($comptes === 'non') {
                $builder->whereNull('user_id');
            }
        }

        if (request()->has('active')) {
            $active = request()->input('active');
            $builder->whereHas('user', function ($query) use ($active) {
                $query->where('active', $active === 'oui' ? 1 : 0);
            });
        }

        if (request()->has('surname')) {
            $surname = request()->input('surname');
            $builder->where('surname', 'LIKE', "%$surname%");
        }
    }
}
