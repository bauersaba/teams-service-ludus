<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @OA\Schema(
 *     schema="Coach",
 *     required={"name"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="dob", type="date"),
 * )
 */

class Coach extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'coach';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = ['name', 'dob'];

    public function teams()
    {
        return $this->hasMany(Team::class, 'coach_id');
    }
}
