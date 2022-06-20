<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Socials\Contracts\Constants\SocialType;
use Illuminate\Support\Facades\Schema;

class InitializationSocialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert_data = [
            [
                'id' => SocialType::SOCIAL_TYPE_ACCOUNT,
                'title' => 'account',
                'status' => 301,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_EMAIL,
                'title' => 'email',
                'status' => 301,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_CELL_PHONE,
                'title' => 'cell_phone',
                'status' => 301,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_MAC_ADDRESS,
                'title' => 'mac_address',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_GOOGLE,
                'title' => 'google',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_FACEBOOK,
                'title' => 'facebook',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_LINKED_IN,
                'title' => 'linked_in',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_TWITTER,
                'title' => 'twitter',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_LINE,
                'title' => 'line',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_LINE_AT,
                'title' => 'line_at',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_WECHAT,
                'title' => 'wechat',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_YAHOO,
                'title' => 'yahoo',
                'status' => 201,
            ],
            [
                'id' => SocialType::SOCIAL_TYPE_OTHER,
                'title' => 'other',
                'status' => 301,
            ]
        ];

        Schema::disableForeignKeyConstraints();
        AccountTypeEntity::truncate();
        AccountTypeEntity::insert($insert_data);
        Schema::enableForeignKeyConstraints();

        echo self::class . ' Complete' . PHP_EOL . PHP_EOL;

    }
}
