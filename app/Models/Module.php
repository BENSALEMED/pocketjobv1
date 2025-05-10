<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Module extends Model
{
    // If you didn't use the default "modules" table name, uncomment & set:
    // protected $table = 'modules';

    protected $fillable = [
        'diploma',
        'name',
        'contract_duration',
        'salary',
        'skills',
        'work_location',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'salary'      => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * The diploma (PDF) associated with this module.
     */
    public function diploma(): BelongsTo
    {
        return $this->belongsTo(Diploma::class, 'diploma_id');
    }

    /**
     * The user who created this module.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
     * The job type for this module (if you have a JobType model/table).
     */
    public function jobType(): BelongsTo
    {
        return $this->belongsTo(JobType::class, 'job_type');
    }
}
