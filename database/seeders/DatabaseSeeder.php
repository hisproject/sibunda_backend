<?php

namespace Database\Seeders;

use App\Models\AnakParamBbPb;
use App\Models\CovidQuestionnaire;
use App\Models\TipsDanInfo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(OauthClientsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WilayahSeeder::class);
        $this->call(ParameterSeeder::class);
        $this->call(PerkembanganQuestionnaireSeeder::class);
        $this->call(DummyUserTokenSeeder::class);
        $this->call(AnakParamBbPbSeeder::class);
        $this->call(AnakParamBbUsiaSeeder::class);
        $this->call(AnakParamImtSeeder::class);
        $this->call(AnakParamKmsSeeder::class);
        $this->call(AnakParamLingkarKepalaSeeder::class);
        $this->call(AnakParamPbUsiaSeeder::class);
        $this->call(AnakParamPerkembanganSeeder::class);
        $this->call(CovidQuestionnaireSeeder::class);
        $this->call(NotificationTemplateSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(TipDanInfoSeeder::class);
        $this->call(FinalDummySeeder::class);
    }
}
