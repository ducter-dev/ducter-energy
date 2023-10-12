<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyNomination extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nominacion_mensual';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unidadNeg',
        'anio',
        'mes',
        'nominacion',
    ];

     /**
     * Get the comments for the blog post.
     */
    public function days()
    {
        return $this->hasMany(Nomination::class,'unidadNeg','unidadNeg');
    }
}
