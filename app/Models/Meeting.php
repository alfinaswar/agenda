<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meetings';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the user associated with the Meeting
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getRuangan()
    {
        return $this->hasOne(MasterRuangan::class, 'id', 'Ruangan');
    }
}
