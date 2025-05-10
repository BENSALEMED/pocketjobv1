<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    return [
        'id'                => $this->id,
        'diploma'           => $this->diploma ? asset('storage/'.$this->diploma) : null,
        'name'              => $this->name,
        'contractDuration'  => $this->contract_duration,
        'salary'            => $this->salary,
        'skills'            => $this->skills,
        'workLocation'      => $this->work_location,
        'description'       => $this->description,
        'status'            => $this->status,
        'created_at'        => $this->created_at->toDateTimeString(),
    ];
}

}
