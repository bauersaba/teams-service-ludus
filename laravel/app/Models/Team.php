<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Team",
 *     required={"coach_id",  "name_club"},
 *     @OA\Property(property="coach_id", type="integer"),
 *     @OA\Property(property="popular_name", type="string"),
 *     @OA\Property(property="nickname_club", type="string"),
 *     @OA\Property(property="name_club", type="string"),
 *     @OA\Property(property="acronym_club", type="string"),
 *     @OA\Property(property="shield_club", type="string"),
 * )
 */

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'team';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['coach_id', 'popular_name', 'nickname_club', 'name_club', 'acronym_club', 'shield_club'];

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'coach_id')
            ->selectRaw('*, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age');
    }
    
}
