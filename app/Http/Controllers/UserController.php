<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use CRUDTrait;

    public function __construct()
    {
        $this->eloquentModel = User::class;
    }

    protected function byIdWithDetails(int $id)
    {
        return $this->getQueryBuilder()
            ->with(['roles'])
            ->findOrFail($id);
    }

    public function show(Request $request)
    {
        return $this->byIdWithDetails($request->user);
    }
}
