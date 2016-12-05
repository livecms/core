<?php

use Illuminate\Database\Seeder;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            ['publicable' => true,'key' => 'site_name', 'value' => 'Live CMS'],
            ['publicable' => true,'key' => 'site_initial', 'value' => 'LC'],
            ['publicable' => true,'key' => 'site_email', 'value' => 'no.reply@livecms.dev'],
            ['publicable' => true,'key' => 'slug_admin', 'value' => '@'],
            ['publicable' => true,'key' => 'slug_article', 'value' => 'a'],
            ['publicable' => true,'key' => 'slug_staticpage', 'value' => 'p'],
            ['publicable' => true,'key' => 'mailchimp_key', 'value' => ''],
            ['publicable' => true,'key' => 'mailchimp_form_url', 'value' => ''],
            ['publicable' => true,'key' => 'google_analytic_id', 'value' => ''],
        ];

        foreach ($datas as $data) {
            $exist = DB::table('settings')->where(array_only($data, ['publicable', 'key']))->first();
            if (!$exist) {
                DB::table('settings')->insert($data);
            }
            
        }
    }
}
