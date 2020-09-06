<?php

use App\Ability;
use Illuminate\Database\Seeder;

use App\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['admin'];

        foreach($roles as $role) {
            Role::create([
                'name' => $role,
                'label' => ucwords($role)
            ]);
        }

        $abilities = ['see_dashboard',
                    'manage_user','create_user','delete_user','update_user',
                    'manage_category','create_category','delete_category','update_category',
                    'manage_role','create_role','delete_role','update_role',
                    'manage_movie','create_movie','delete_movie','update_movie',
                    'delete_comment',
                    'manage_actor','create_actor','update_actor','delete_actor'];

        foreach($abilities as $ability) {
            Ability::create([
                'name' => $ability,
                'label' => ucwords(str_replace('_',' ',$ability))
            ]);
        }

        $user = Factory(App\User::class)->create([
            'name' => 'hamza elharsi',
            'email' => 'hamzaelharsi123@gmail.com'
        ]);

        $admin = Role::whereName('admin')->firstOrFail();

        $user->assignRole($admin);

        Ability::all()->each(function($ability) use($admin){
            $admin->allowTo($ability);
        });
    }
}
