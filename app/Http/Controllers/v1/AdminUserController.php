<?php

namespace App\Http\Controllers\v1;

use App\Filters\v1\FuzzyFilter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AdminUserController extends Controller
{
    public function index()
    {
        $usersQuery = QueryBuilder::for(User::class)
            ->allowedIncludes(['roles', 'roles.permissions'])
            ->defaultSort('-updated_at')
            ->allowedSorts(['first_name', 'last_name', 'email', 'status'])
            ->allowedFilters([
                AllowedFilter::custom('search', new FuzzyFilter(
                    'first_name',
                    'last_name',
                    'email'
                ))
                , AllowedFilter::exact('status')
                , AllowedFilter::exact('type')
            ])
        ;
        //dump($usersQuery->toRawSql());
        return response()->json($usersQuery->paginate());
    }

    public function store(Request $request)
    {
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
    }

    public function destroy(User $user)
    {
    }
}
