<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\Socials\Databases\Entities{
/**
 * App\Models\Socials\Databases\Entities\SocialEntity
 *
 * @property int $id 流水號
 * @property string|null $name 第三方姓名
 * @property string|null $email 第三方Email
 * @property int $social_type 第三方類別
 * @property string $social_type_value 第三方ID
 * @property string|null $image 第三方照片
 * @property string|null $token 第三方token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Users\Databases\Entities\UserEntity[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|SocialEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereSocialType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereSocialTypeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SocialEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SocialEntity withoutTrashed()
 */
	class SocialEntity extends \Eloquent {}
}

namespace App\Models\SymbolOperationTypes\Databases\Entities{
/**
 * App\Models\SymbolOperationTypes\Databases\Entities\SymbolOperationTypeEntity
 *
 * @property int $id 流水號
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletDetailEntity[] $wallet_details
 * @property-read int|null $wallet_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SymbolOperationTypeEntity whereUpdatedAt($value)
 */
	class SymbolOperationTypeEntity extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id 流水號
 * @property string|null $name
 * @property string|null $account
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string $password
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerifiedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models\Users\Databases\Entities{
/**
 * App\Models\Users\Databases\Entities\UserEntity
 *
 * @property int $id 流水號
 * @property string|null $name
 * @property string|null $account
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property string $password
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Socials\Databases\Entities\SocialEntity[] $socials
 * @property-read int|null $socials_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletUserEntity[] $wallet_users
 * @property-read int|null $wallet_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletEntity[] $wallets
 * @property-read int|null $wallets_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|UserEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|UserEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserEntity withoutTrashed()
 */
	class UserEntity extends \Eloquent {}
}

namespace App\Models\Wallets\Databases\Entities{
/**
 * App\Models\Wallets\Databases\Entities\WalletDetailEntity
 *
 * @property int $id 流水號
 * @property int|null $wallet_id wallet_id
 * @property int $type
 * @property int|null $payment_wallet_user_id 付款人
 * @property string $title
 * @property int $symbol_operation_type_id 加減項
 * @property int $select_all 帳本內成員全選
 * @property float $value 付款金額
 * @property int|null $created_by 創建ID
 * @property int|null $updated_by 更新ID
 * @property int|null $deleted_by 創建ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Wallets\Databases\Entities\WalletUserEntity|null $created_user
 * @property-read \App\Models\Wallets\Databases\Entities\WalletUserEntity|null $payment_user
 * @property-read \App\Models\SymbolOperationTypes\Databases\Entities\SymbolOperationTypeEntity|null $symbol_operation_types
 * @property-read \App\Models\Wallets\Databases\Entities\WalletDetailTypeEntity|null $wallet_detail_types
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletUserEntity[] $wallet_users
 * @property-read int|null $wallet_users_count
 * @property-read \App\Models\Wallets\Databases\Entities\WalletEntity|null $wallets
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|WalletDetailEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity wherePaymentWalletUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereSelectAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereSymbolOperationTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailEntity whereWalletId($value)
 * @method static \Illuminate\Database\Query\Builder|WalletDetailEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WalletDetailEntity withoutTrashed()
 */
	class WalletDetailEntity extends \Eloquent {}
}

namespace App\Models\Wallets\Databases\Entities{
/**
 * App\Models\Wallets\Databases\Entities\WalletDetailTypeEntity
 *
 * @property int $id 流水號
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletDetailEntity[] $wallet_details
 * @property-read int|null $wallet_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|WalletDetailTypeEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletDetailTypeEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|WalletDetailTypeEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WalletDetailTypeEntity withoutTrashed()
 */
	class WalletDetailTypeEntity extends \Eloquent {}
}

namespace App\Models\Wallets\Databases\Entities{
/**
 * App\Models\Wallets\Databases\Entities\WalletEntity
 *
 * @property int $id 流水號
 * @property int|null $user_id 創建ID
 * @property string|null $code 房間編號
 * @property string|null $title
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Users\Databases\Entities\UserEntity|null $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletDetailEntity[] $wallet_details
 * @property-read int|null $wallet_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletUserEntity[] $wallet_users
 * @property-read int|null $wallet_users_count
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|WalletEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletEntity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|WalletEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WalletEntity withoutTrashed()
 */
	class WalletEntity extends \Eloquent {}
}

namespace App\Models\Wallets\Databases\Entities{
/**
 * App\Models\Wallets\Databases\Entities\WalletUserEntity
 *
 * @property int $id 流水號
 * @property int $wallet_id
 * @property int|null $user_id users id
 * @property string|null $name
 * @property string|null $token
 * @property int|null $is_admin 管理員 1 yes : 0 no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletDetailEntity[] $created_wallet_details
 * @property-read int|null $created_wallet_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletDetailEntity[] $payment_wallet_details
 * @property-read int|null $payment_wallet_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallets\Databases\Entities\WalletDetailEntity[] $wallet_details
 * @property-read int|null $wallet_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|WalletUserEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WalletUserEntity whereWalletId($value)
 * @method static \Illuminate\Database\Query\Builder|WalletUserEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WalletUserEntity withoutTrashed()
 */
	class WalletUserEntity extends \Eloquent {}
}

