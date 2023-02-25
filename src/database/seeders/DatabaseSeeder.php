<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $customer_role = Role::firstOrNew(['name' => 'customer']);
        $customer_role->name = 'customer';
        $customer_role->guard_name = 'web';
        $customer_role->save();


          //create a default merchant role
          $merchant_role = Role::firstOrNew(['name' => 'merchant']);
          $merchant_role->name = 'merchant';
          $merchant_role->guard_name = 'web';
          $merchant_role->save();
  
          //create a default sub merchant role
          $sub_merchant_role = Role::firstOrNew(['name' => 'sub_merchant']);
          $sub_merchant_role->name = 'sub_merchant';
          $sub_merchant_role->guard_name = 'web';
          $sub_merchant_role->save();

        //create a default admin role
        $admin_role = Role::firstOrNew(['name' => 'admin']);
        $admin_role->name = 'admin';
        $admin_role->guard_name = 'web';
        $admin_role->save();

        //create a default super admin role
        $super_admin_role = Role::firstOrNew(['name' => 'super_admin']);
        $super_admin_role->name = 'super_admin';
        $super_admin_role->guard_name = 'web';
        $super_admin_role->save();

        $user = User::create([
            'user_id'=> $this->generateUserId("CUSTOMER", "2348166219698"),
            'first_name' => 'Vanguard',
            'last_name' => 'Vanguard',
            'phone_number' => '2348166219698',
            'email' => 'admin@example.com',
            'transaction_pin' => '1234',
            'status' => "active"
        ]);

        $user->assignRole('customer');
       
    }

    public function generateUserId($user_type, $phone)
    {
        return (date('Y') % 2019).($user_type == 'CUSTOMER' ? 0 : 1).substr($phone, -4).substr(date('U'), -2);
    }
}
