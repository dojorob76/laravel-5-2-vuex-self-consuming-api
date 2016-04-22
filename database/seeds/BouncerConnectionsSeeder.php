<?php

use App\User;
use Silber\Bouncer\Bouncer;
use Illuminate\Database\Seeder;

class BouncerConnectionsSeeder extends Seeder
{

    public function __construct(Bouncer $bouncer)
    {
        $this->bouncer = $bouncer;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        DB::table('assigned_roles')->delete();
        DB::table('roles')->delete();
        DB::table('abilities')->delete();

        // Declare abilities that are not directly connected to models - arrays can be extended
        $adminAbilities = ['do-everything', 'access-admin-subdomain'];
        $memberAbilities = ['do-member-actions'];
        $anonymousAbilities = ['do-anonymous-actions', 'login'];

        // Declare the standard abilities that are connected to models
        $actions = ['create', 'view', 'edit', 'update', 'delete'];

        // Declare the Admin Role basic abilities that are connected to models
        foreach ($actions as $action) {
            $this->bouncer->allow('admin')->to($action, User::class);
            $this->bouncer->allow('admin')->to($action, \App\ApiConsumer::class);
        }
        $systemApiAccountAbilities = [
            'view-system-api-accounts',
            'edit-system-api-accounts',
            'delete-system-api-accounts'
        ];

        // Allow the admin role to manage the system API Accounts
        foreach ($systemApiAccountAbilities as $ability) {
            $this->bouncer->allow('admin')->to($ability, \App\ApiConsumer::class);
        }

        // Declare the Member Role abilities that are connected to models
        $this->bouncer->allow('member')->to('create', \App\ApiConsumer::class);

        // Declare the Anonymous Role abilities that are connected to models
        $this->bouncer->allow('anonymous')->to('create', User::class);
        $this->bouncer->allow('anonymous')->to('create', \App\ApiConsumer::class);

        // Include the abilities for each role that are not directly connected to models
        $this->bouncer->allow('admin')->to($adminAbilities);
        $this->bouncer->allow('member')->to($memberAbilities);
        $this->bouncer->allow('anonymous')->to($anonymousAbilities);

        // Assign the Admin Role to User 1
        $admin = User::find(1);
        $this->bouncer->assign('admin')->to($admin);
    }
}
