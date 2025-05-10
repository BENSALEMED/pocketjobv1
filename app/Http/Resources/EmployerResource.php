<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
{
    return [
      'id'                 => $this->id,
      'name'               => $this->name,
      'email'              => $this->email,
      'phone'              => $this->phone,
      'domaine'            => $this->domaine,
      'typeProfessionnel'  => $this->type_professionnel,
      'description'        => $this->description,
      'document'           => $this->document ? ['name' => $this->document['name']] : null,
      'adresse'            => $this->adresse,
      'qualification'      => $this->qualification ? ['name' => $this->qualification['name']] : null,
      'creator'            => $this->creator ? [
                                'firstname'=> $this->creator->firstname,
                                'lastname' => $this->creator->lastname
                              ] : null,
      'status'             => $this->status,
      'created_at'         => $this->created_at->toDateTimeString(),
      'image'              => $this->image,
    ];
}

}
