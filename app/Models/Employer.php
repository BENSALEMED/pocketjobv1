<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = [
      'name','email','phone','domaine','type_professionnel',
      'description','document','adresse','qualification',
      'status','image','created_by'
    ];

    protected $casts = [
      'document'      => 'array',
      'qualification' => 'array',
      'created_at'    => 'datetime',
      'updated_at'    => 'datetime',
    ];

    // Creator relation for TS “creator” field
    public function creator()
    {
      return $this->belongsTo(User::class, 'created_by')
                  ->select(['id','firstname','lastname']);
    }
}
