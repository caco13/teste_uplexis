<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SintegraES extends Model
{
    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'sintegra';

    protected $fillable = [
        'idusuario',
        'cnpj',
        'resultado_json'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'resultado_json' => 'array'
    ];

    /**
     * A saved consult belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
