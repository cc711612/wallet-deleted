<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 03:21
 */

namespace App\Models\Wallets\Databases\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class WalletDetailTypeEntity extends Model
{
    use SoftDeletes;

    const Table = 'wallet_detail_types';
    /**
     * @var string
     */
    protected $table = self::Table;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:48
     */
    public function wallet_details()
    {
        return $this->hasMany(WalletDetailEntity::class, 'type', 'id');
    }
}
