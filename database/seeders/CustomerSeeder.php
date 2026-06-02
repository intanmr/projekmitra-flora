<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'nama_customer' => 'Andi',
            'email' => 'andi@example.com',
            'no_hp' => '081111111111',
            'alamat' => 'Jember',
            'aktif' => true
        ]);

        Customer::create([
            'nama_customer' => 'Siti',
            'email' => 'siti@example.com',
            'no_hp' => '082222222222',
            'alamat' => 'Bondowoso',
            'aktif' => true
        ]);

        Customer::create([
            'nama_customer' => 'Farid',
            'email' => 'farid@example.com',
            'no_hp' => '083333333333',
            'alamat' => 'Lumajang',
            'aktif' => true
        ]);

        Customer::create([
            'nama_customer' => 'Intan',
            'email' => 'intan@example.com',
            'no_hp' => '084444444444',
            'alamat' => 'Jember',
            'aktif' => true
        ]);
    }
}