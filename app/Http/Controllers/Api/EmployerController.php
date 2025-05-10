<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Http\Resources\EmployerResource;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function index()
    {
        return EmployerResource::collection(
            Employer::with('creator')->get()
        );
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:employers,email',
            'phone'              => 'required|string|max:50',
            'domaine'            => 'nullable|string|max:255',
            'typeProfessionnel'  => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'document.name'      => 'nullable|string',
            'adresse'            => 'nullable|string|max:255',
            'qualification.name' => 'nullable|string',
            'status'             => 'nullable|string|max:50',
            'image'              => 'nullable|string|max:255',
        ]);

        $data = [
            'name'               => $v['name'],
            'email'              => $v['email'],
            'phone'              => $v['phone'],
            'domaine'            => $v['domaine'] ?? null,
            'type_professionnel' => $v['typeProfessionnel'] ?? null,
            'description'        => $v['description'] ?? null,
            'document'           => isset($v['document']) 
                                   ? ['name' => $v['document']['name']] 
                                   : null,
            'adresse'            => $v['adresse'] ?? null,
            'qualification'      => isset($v['qualification']) 
                                   ? ['name' => $v['qualification']['name']] 
                                   : null,
            'status'             => $v['status'] ?? null,
            'image'              => $v['image'] ?? null,
            'created_by'         => auth()->id(),
        ];

        $employer = Employer::create($data);

        return new EmployerResource($employer->load('creator'));
    }

    public function show($id)
    {
        return new EmployerResource(
            Employer::with('creator')->findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $employer = Employer::findOrFail($id);

        $v = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:employers,email,' . $id,
            'phone'              => 'required|string|max:50',
            'domaine'            => 'nullable|string|max:255',
            'typeProfessionnel'  => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'document.name'      => 'nullable|string',
            'adresse'            => 'nullable|string|max:255',
            'qualification.name' => 'nullable|string',
            'status'             => 'nullable|string|max:50',
            'image'              => 'nullable|string|max:255',
        ]);

        $data = [
            'name'               => $v['name'],
            'email'              => $v['email'],
            'phone'              => $v['phone'],
            'domaine'            => $v['domaine'] ?? null,
            'type_professionnel' => $v['typeProfessionnel'] ?? null,
            'description'        => $v['description'] ?? null,
            'document'           => isset($v['document']) 
                                   ? ['name' => $v['document']['name']] 
                                   : null,
            'adresse'            => $v['adresse'] ?? null,
            'qualification'      => isset($v['qualification']) 
                                   ? ['name' => $v['qualification']['name']] 
                                   : null,
            'status'             => $v['status'] ?? null,
            'image'              => $v['image'] ?? null,
        ];

        $employer->update($data);

        return new EmployerResource($employer->load('creator'));
    }

    public function destroy($id)
    {
        Employer::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted'], 204);
    }
}
