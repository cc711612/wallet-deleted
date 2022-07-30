<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 03:19
 */

namespace App\Models\Wallets\Databases\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SymbolOperationTypes\Databases\Entities\SymbolOperationTypeEntity;

class WalletDetailEntity extends Model
{
    use SoftDeletes;

    const Table = 'wallet_details';
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
        'wallet_id',
        'type',
        'payment_wallet_user_id',
        'title',
        'symbol_operation_type_id',
        'value',
        'select_all',
        'checkout_by',
        'created_by',
        'updated_by',
        'deleted_by',
        'checkout_at',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:43
     */
    public function wallets()
    {
        return $this->belongsTo(WalletEntity::class, 'wallet_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:45
     */
    public function wallet_users()
    {
        return $this->belongsToMany(WalletUserEntity::class, 'wallet_detail_wallet_user', 'wallet_detail_id',
            'wallet_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:46
     */
    public function wallet_detail_types()
    {
        return $this->belongsTo(WalletDetailTypeEntity::class, 'type', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:46
     */
    public function symbol_operation_types()
    {
        return $this->belongsTo(SymbolOperationTypeEntity::class, 'symbol_operation_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:50
     */
    public function payment_user()
    {
        return $this->belongsTo(WalletUserEntity::class, 'payment_wallet_user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:52
     */
    public function created_user()
    {
        return $this->belongsTo(WalletUserEntity::class, 'created_by', 'id');
    }
}
