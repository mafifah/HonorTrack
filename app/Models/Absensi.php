<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_jadwal
 * @property string $tanggal
 * @property string $status_kehadiran
 * @property string $pokok_pembahasan
 * @property string $koordinat
 * @property string $created_at
 * @property string $updated_at
 * @property Jadwal $jadwal
 */
class Absensi extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'absensi';

    /**
     * @var array
     */
    protected $fillable = ['id_jadwal', 'tanggal', 'status_kehadiran', 'pokok_pembahasan', 'koordinat', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jadwal()
    {
        return $this->belongsTo('App\Models\Jadwal', 'id_jadwal');
    }
}
