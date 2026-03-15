<?php

namespace Database\Seeders;

use App\Models\FnbBusiness;
use App\Models\FnbBusinessLicense;
use App\Models\FnbBusinessUser;
use App\Models\license;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory(10)->create();

    User::factory()->create([

      'username' => 'fajri_chan',
      'name' => 'Fajri Rinaldi Chan',
      'email' => 'fajri@gariskode.com',
      'phone' => '089613390766',
      'role' => 'admin',
    ]);

    license::create([
      'name' => 'UMKM Support',
      'description' => 'License untuk bisnis UMKM dengan fitur dasar, cocok untuk bisnis kecil yang baru memulai.',
      'max_transactions_per_day' => 200,
      'max_outlets' => 2,
      'max_users' => 5,
      'price' => 0.00,
    ]);

    license::create([
      'name' => 'UMKM Pro',
      'description' => 'License untuk bisnis UMKM yang berkembang, dengan fitur tambahan dan batas transaksi yang lebih tinggi.',
      'max_transactions_per_day' => 800,
      'max_outlets' => 5,
      'max_users' => 20,
      'price' => 49000,
    ]);

    license::create([
      'name' => 'Enterprise Business',
      'description' => 'License untuk bisnis besar dengan kebutuhan tinggi, termasuk fitur premium dan dukungan prioritas.',
      'max_transactions_per_day' => 5000,
      'max_outlets' => 20,
      'max_users' => 100,
      'price' => 199000,
    ]);

    FnbBusiness::create(
      [
        'name' => 'KOPETA Tbk.',
        'slug' => 'kopeta-tbk',
        'domain' => 'kopeta.ventro.id',
        'description' => 'KOPETA Tbk. adalah perusahaan yang bergerak di bidang makanan dan minuman, menyediakan berbagai produk berkualitas tinggi untuk pelanggan di seluruh Indonesia.',
        'license_id' => license::first()->id,
      ]
    );

    FnbBusinessUser::create([
      'fnb_business_id' => FnbBusiness::first()->id,
      'user_id' => User::where('email', 'fajri@gariskode.com')->first()->id,
    ]);

  }
}
