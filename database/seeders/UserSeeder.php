<?php
namespace Database\Seeders;
use App\Models\Kia;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserGroupRole;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        User::query()->truncate();
        UserGroupRole::query()->truncate();
        UserRole::query()->truncate();
        UserGroup::query()->truncate();

        // enable this if, you're using Postgres
        // DB::statement('ALTER SEQUENCE users_id_seq RESTART 1');
        // DB::statement('ALTER SEQUENCE user_groups_roles_id_seq RESTART 1');
        // DB::statement('ALTER SEQUENCE user_groups_id_seq RESTART 1');
        // DB::statement('ALTER SEQUENCE user_roles_id_seq RESTART 1');

        $userGroups = [
            'Admin',
            'Bunda',
            'Bidan',
            'FasKes'
        ];

        $userRoles = [
            'General',
        ];

        $userGroupRoles = [
            [1],
            [1],
            [1],
            [1],
        ];

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@a.a',
                'password' => Hash::make('password'),
                'user_group_id' => 1
            ],
            [
                'name' => 'Bunda',
                'email' => 'bunda@a.a',
                'password' => Hash::make('password'),
                'user_group_id' => 2
            ],
            [
                'name' => 'Bidan',
                'email' => 'bidan@a.a',
                'password' => Hash::make('password'),
                'user_group_id' => 3
            ],
            [
                'name' => 'FasKes',
                'email' => 'faskes@a.a',
                'password' => Hash::make('password'),
                'user_group_id' => 4
            ]
        ];

        foreach($userGroups as $ug) {
            UserGroup::create([
                'name' => $ug
            ]);
        }

        foreach($userRoles as $ur) {
            UserRole::create([
                'name' => $ur
            ]);
        }

        for($i = 1; $i <= count($userGroups); $i ++) {
            foreach($userGroupRoles[$i - 1] as $ur) {
                UserGroupRole::create([
                    'user_group_id' => $i,
                    'user_role_id' => $ur
                ]);
            }
        }

        foreach($users as $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => $u['password'],
                'user_group_id' => $u['user_group_id']
            ]);

            /*Kia::create([
                'us er_id' => $user->id
            ]);*/
            echo 'user : ' . $user->id . PHP_EOL;
        }
    }
}
