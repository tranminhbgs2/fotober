<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_time = \Illuminate\Support\Carbon::now();

        // Tạo group
        \App\Models\Group::updateOrCreate(
            ['code' => \App\Helpers\Constants::ACCOUNT_TYPE_CUSTOMER],
            [
                'name' => \App\Helpers\Constants::ACCOUNT_TYPE_CUSTOMER,
                'description' => 'Nhóm Khách hàng',
                'is_active' => 1
            ]
        );

        \App\Models\Group::updateOrCreate(
            ['code' => \App\Helpers\Constants::ACCOUNT_TYPE_SALE],
            [
                'name' => \App\Helpers\Constants::ACCOUNT_TYPE_SALE,
                'description' => 'Nhóm Sale',
                'is_active' => 1
            ]
        );

        \App\Models\Group::updateOrCreate(
            ['code' => \App\Helpers\Constants::ACCOUNT_TYPE_ADMIN],
            [
                'name' => \App\Helpers\Constants::ACCOUNT_TYPE_ADMIN,
                'description' => 'Nhóm Admin',
                'is_active' => 1
            ]
        );

        \App\Models\Group::updateOrCreate(
            ['code' => \App\Helpers\Constants::ACCOUNT_TYPE_EDITOR],
            [
                'name' => \App\Helpers\Constants::ACCOUNT_TYPE_EDITOR,
                'description' => 'Nhóm Editor',
                'is_active' => 1
            ]
        );

        \App\Models\Group::updateOrCreate(
            ['code' => \App\Helpers\Constants::ACCOUNT_TYPE_QAQC],
            [
                'name' => \App\Helpers\Constants::ACCOUNT_TYPE_QAQC,
                'description' => 'Nhóm QA/QC',
                'is_active' => 1
            ]
        );

        \App\Models\Group::updateOrCreate(
            ['code' => \App\Helpers\Constants::ACCOUNT_TYPE_SUPER_ADMIN],
            [
                'name' => \App\Helpers\Constants::ACCOUNT_TYPE_SUPER_ADMIN,
                'description' => 'Nhóm QA/QC',
                'is_active' => 1
            ]
        );

        // Tạo user
        \App\Models\User::updateOrCreate(
            [
                'username' => 'customer',
            ],
            [
                'group_id' => 1,
                'account_type' => \App\Helpers\Constants::ACCOUNT_TYPE_CUSTOMER,
                'password' => \Illuminate\Support\Facades\Hash::make('123456a@A'),
                'fullname' => 'Johny Đặng',
                'birthday' => '1991-09-11',
                'address' => 'Hà Nội, Việt Nam',
                'avatar' => null,
                'email' => 'customer@gmail.com',
                'phone' => null,
                'gender' => 1,
                'website' => null,
                'notes' => null,
                'is_admin' => 0,
                'total_order' => 0,
                'status' => \App\Helpers\Constants::USER_STATUS_ACTIVE,
                'manager_by' => null,
                'created_at' => $current_time,
                'updated_at' => $current_time
            ]
        );

        \App\Models\User::updateOrCreate(
            [
                'username' => 'sale',
            ],
            [
                'group_id' => 2,
                'account_type' => \App\Helpers\Constants::ACCOUNT_TYPE_SALE,
                'password' => \Illuminate\Support\Facades\Hash::make('123456a@A'),
                'fullname' => 'Sale Đặng',
                'birthday' => '1991-09-11',
                'address' => 'Hà Nội, Việt Nam',
                'avatar' => null,
                'email' => 'sale@gmail.com',
                'phone' => null,
                'gender' => 1,
                'website' => null,
                'notes' => null,
                'is_admin' => 0,
                'total_order' => 0,
                'status' => \App\Helpers\Constants::USER_STATUS_ACTIVE,
                'manager_by' => null,
                'created_at' => $current_time,
                'updated_at' => $current_time
            ]
        );

        \App\Models\User::updateOrCreate(
            [
                'username' => 'admin',
            ],
            [
                'group_id' => 3,
                'account_type' => \App\Helpers\Constants::ACCOUNT_TYPE_ADMIN,
                'password' => \Illuminate\Support\Facades\Hash::make('123456a@A'),
                'fullname' => 'Admin Đặng',
                'birthday' => '1991-09-11',
                'address' => 'Hà Nội, Việt Nam',
                'avatar' => null,
                'email' => 'admin@gmail.com',
                'phone' => null,
                'gender' => 1,
                'website' => null,
                'notes' => null,
                'is_admin' => 0,
                'total_order' => 0,
                'status' => \App\Helpers\Constants::USER_STATUS_ACTIVE,
                'manager_by' => null,
                'created_at' => $current_time,
                'updated_at' => $current_time
            ]
        );

        \App\Models\User::updateOrCreate(
            [
                'username' => 'editor',
            ],
            [
                'group_id' => 4,
                'account_type' => \App\Helpers\Constants::ACCOUNT_TYPE_EDITOR,
                'password' => \Illuminate\Support\Facades\Hash::make('123456a@A'),
                'fullname' => 'Editor Đặng',
                'birthday' => '1991-09-11',
                'address' => 'Hà Nội, Việt Nam',
                'avatar' => null,
                'email' => 'editor@gmail.com',
                'phone' => null,
                'gender' => 1,
                'website' => null,
                'notes' => null,
                'is_admin' => 0,
                'total_order' => 0,
                'status' => \App\Helpers\Constants::USER_STATUS_ACTIVE,
                'manager_by' => null,
                'created_at' => $current_time,
                'updated_at' => $current_time
            ]
        );

        \App\Models\User::updateOrCreate(
            [
                'username' => 'qaqc',
            ],
            [
                'group_id' => 5,
                'account_type' => \App\Helpers\Constants::ACCOUNT_TYPE_QAQC,
                'password' => \Illuminate\Support\Facades\Hash::make('123456a@A'),
                'fullname' => 'QAQC Đặng',
                'birthday' => '1991-09-11',
                'address' => 'Hà Nội, Việt Nam',
                'avatar' => null,
                'email' => 'qaqc@gmail.com',
                'phone' => null,
                'gender' => 1,
                'website' => null,
                'notes' => null,
                'is_admin' => 0,
                'total_order' => 0,
                'status' => \App\Helpers\Constants::USER_STATUS_ACTIVE,
                'manager_by' => null,
                'created_at' => $current_time,
                'updated_at' => $current_time
            ]
        );

        \App\Models\User::updateOrCreate(
            [
                'username' => 'superadmin',
            ],
            [
                'group_id' => 6,
                'account_type' => \App\Helpers\Constants::ACCOUNT_TYPE_SUPER_ADMIN,
                'password' => \Illuminate\Support\Facades\Hash::make('123456a@A'),
                'fullname' => 'SuperAdmin Đặng',
                'birthday' => '1991-09-11',
                'address' => 'Hà Nội, Việt Nam',
                'avatar' => null,
                'email' => 'superadmin@gmail.com',
                'phone' => null,
                'gender' => 1,
                'website' => null,
                'notes' => null,
                'is_admin' => 0,
                'total_order' => 0,
                'status' => \App\Helpers\Constants::USER_STATUS_ACTIVE,
                'manager_by' => null,
                'created_at' => $current_time,
                'updated_at' => $current_time
            ]
        );
    }
}
