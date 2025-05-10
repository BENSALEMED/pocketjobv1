<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Http\Resources\ModuleResource;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /** POST /module (list & filter) */
    public function index(Request $request)
    {
        $q = Module::with('diploma');

        if ($request->filled('filter')) {
            $term = "%{$request->filter}%";
            $q->where(fn($qb) =>
                  $qb->where('name','like',$term)
                     ->orWhere('skills','like',$term)
                     ->orWhere('description','like',$term)
            );
        }
        if ($request->filled('diploma_id')) {
            $q->where('diploma_id',$request->diploma_id);
        }

        if ((int)$request->paginate === 1) {
            $p = $q->paginate(
              $request->per_page ?? 10,
              ['*'],
              'page',
              $request->page ?? 1
            );
            return response()->json([
              'result'    => 'success',
              'data'      => ModuleResource::collection($p->items()),
              'paginator' => [
                'current_page'=> $p->currentPage(),
                'last_page'   => $p->lastPage(),
                'per_page'    => $p->perPage(),
                'total'       => $p->total(),
              ]
            ], 200);
        }

        $all = $q->get();
        return response()->json([
          'result' => 'success',
          'data'   => ModuleResource::collection($all),
        ], 200);
    }

    /** POST /module/save */
    public function store(Request $request)
{
    $v = $request->validate([
        'diploma'           => 'nullable|file|mimes:pdf|max:2048',
        'name'              => 'required|string|max:255',
        'contractDuration'  => 'required|string|max:100',
        'salary'            => 'required|integer',
        'skills'            => 'required|string',
        'workLocation'      => 'required|string|max:255',
        'description'       => 'nullable|string',
        'status'            => 'required|string|in:ACTIVE,PENDING,DISABLED',
    ]);

    $data = [
        'name'              => $v['name'],
        'contract_duration' => $v['contractDuration'],
        'salary'            => $v['salary'],
        'skills'            => $v['skills'],
        'work_location'     => $v['workLocation'],
        'description'       => $v['description'] ?? null,
        'status'            => $v['status'],
        'created_by'        => auth()->id(),
    ];

    if ($request->hasFile('diploma')) {
        // stores in storage/app/public/diplomas and returns path
        $data['diploma'] = $request->file('diploma')->store('diplomas', 'public');
    }

    $module = Module::create($data);

    return response()->json([
        'result' => 'success',
        'data'   => new ModuleResource($module->load('creator'))
    ], 201);
}

public function update(Request $request, $id)
{
    $module = Module::findOrFail($id);

    $v = $request->validate([
        'diploma'           => 'nullable|file|mimes:pdf|max:2048',
        'name'              => 'required|string|max:255',
        'contractDuration'  => 'required|string|max:100',
        'salary'            => 'required|integer',
        'skills'            => 'required|string',
        'workLocation'      => 'required|string|max:255',
        'description'       => 'nullable|string',
        'status'            => 'required|string|in:ACTIVE,PENDING,DISABLED',
    ]);

    $data = [
        'name'              => $v['name'],
        'contract_duration' => $v['contractDuration'],
        'salary'            => $v['salary'],
        'skills'            => $v['skills'],
        'work_location'     => $v['workLocation'],
        'description'       => $v['description'] ?? null,
        'status'            => $v['status'],
    ];

    if ($request->hasFile('diploma')) {
        $data['diploma'] = $request->file('diploma')->store('diplomas', 'public');
    }

    $module->update($data);

    return response()->json([
        'result' => 'success',
        'data'   => new ModuleResource($module->load('creator'))
    ], 200);
}


    /** DELETE /module/{id} */
    public function destroy($id)
    {
        Module::findOrFail($id)->delete();
        return response()->json(['result'=>'success'], 204);
    }

    /** POST /module/change_status/{id} */
    public function changeStatus(Request $request, $id)
    {
        $m = Module::findOrFail($id);
        $v = $request->validate([
          'status' => 'required|string|in:ACTIVE,PENDING,DISABLED'
        ]);
        $m->status = $v['status'];
        $m->save();

        return response()->json(['result'=>'success'], 200);
    }
}
