<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class CandidateController extends Controller
{
    /**
     * Return a list of all candidates.
     */
    public function index(): JsonResponse
    {
        // Fetch users having the 'candidate' role (adjust the role name if yours is 'condidat' in French)
        $candidates = User::role('candidate')
        ->select([
            'id',
            'name',
            'email',
            'diplome',
            'telephone',
            'status',
            'date_inscription'
        ])
        ->get();

    return response()->json($candidates);
}
}
