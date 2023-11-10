<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     'name' => Str::random(10),
        //     'email' => Str::random(10).'@gmail.com',
        //     'password' => Hash::make('password'),
        // ]);
        DB::table('settings_apis')->insert([
            [
                'creator_id' => 1,
                'appType' => 'facebook',
                'appID' => '690179252628964',
                'appSecret' => '9ac7abd4768f0bcf9c92779dd406b4d0',
                'apiKey' => null,
            ],
            [
                'creator_id' => 1,
                'appType' => 'twitter',
                'appID' => 'tBoZ80ztGOfOjMOx4VOwmdG2G',
                'appSecret' => 'qQjq9BgXxPLc9TQXAtrnXHuRB2vtgSg9fljFUHq2K6ZrV2v56n',
                'apiKey' => null,
            ],
            [
                'creator_id' => 1,
                'appType' => 'youtube',
                'appID' => '400800346626-3pj9lb5923bmurej4bk6ql2v2rm29kco.apps.googleusercontent.com',
                'appSecret' => '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',
                'apiKey' => 'AIzaSyCZhW13YQV1En4FEtVET312rRwIbAj3Rp4',
            ],
        ]);

    }
}
