<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 03:07
 */

namespace App\Models\Users\Databases\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Wallets\Databases\Entities\WalletEntity;
use App\Models\Socials\Databases\Entities\SocialEntity;

class UserEntity extends Model
{
    use SoftDeletes;

    const Table = 'users';
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
        'name',
        'account',
        'image',
        'password',
        'token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:38
     */
    public function wallets()
    {
        return $this->hasMany(WalletEntity::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author: Roy
     * @DateTime: 2022/6/19 下午 03:39
     */
    public function socials()
    {
        return $this->belongsToMany(SocialEntity::class, 'user_social', 'user_id', 'social_id');
    }
}
