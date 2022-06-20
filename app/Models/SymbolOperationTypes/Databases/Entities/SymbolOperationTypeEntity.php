<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 03:14
 */

namespace App\Models\SymbolOperationTypes\Databases\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\Databases\Entities\WalletDetailEntity;

class SymbolOperationTypeEntity extends Model
{
    const Table = 'symbol_operation_types';
    /**
     * @var string
     */
    protected $table = self::Table;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:48
     */
    public function wallet_details()
    {
        return $this->hasMany(WalletDetailEntity::class, 'symbol_operation_type_id', 'id');
    }
}
