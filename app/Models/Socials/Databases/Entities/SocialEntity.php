<?php
/**
 * @Author: Roy
 * @DateTime: 2022/6/19 下午 03:15
 */

namespace App\Models\Socials\Databases\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users\Databases\Entities\UserEntity;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialEntity extends Model
{
    use SoftDeletes;

    const Table = 'socials';
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
        'name',
        'email',
        'social_type',
        'social_type_value',
        'image',
        'token',
        'followed',
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

    public function users()
    {
        return $this->belongsToMany(UserEntity::class, 'user_social', 'social_id', 'user_id');
    }
}
