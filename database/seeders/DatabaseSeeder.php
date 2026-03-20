<?php

namespace Database\Seeders;

use App\Models\FnbBusiness;
use App\Models\FnbBusinessUser;
use App\Models\FnbOutlet;
use App\Models\FnbOutletStaff;
use App\Models\FnbProduct;
use App\Models\FnbProductCategory;
use App\Models\FnbProductVariant;
use App\Models\FnbSaleMode;
use App\Models\FnbTable;
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

        FnbOutlet::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'name' => 'Outlet 1 - KOPETA Tbk.',
            'phone' => '021-12345678',
            'email' => 'kopeta1@ventro.id',
            'address' => 'Jl. Merdeka No. 123, Jakarta',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
        ]);

        FnbOutletStaff::create([
            'fnb_outlet_id' => FnbOutlet::first()->id,
            'fnb_business_user_id' => FnbBusinessUser::first()->id,
        ]);

        $outlet2 = FnbOutlet::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'name' => 'Outlet 2 - KOPETA Tbk.',
            'phone' => '021-12345678',
            'email' => 'kopeta2@ventro.id',
            'address' => 'Jl. Merdeka No. 123, Jakarta',
            'latitude' => '-6.200000',
            'longitude' => '106.816666',
        ]);

        FnbOutletStaff::create([
            'fnb_outlet_id' => $outlet2->id,
            'fnb_business_user_id' => FnbBusinessUser::first()->id,
        ]);

        FnbSaleMode::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'name' => 'Dine-in',
            'description' => 'Mode penjualan untuk pelanggan yang menikmati makanan dan minuman di tempat.',
        ]);

        FnbSaleMode::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'name' => 'Takeaway',
            'description' => 'Mode penjualan untuk pelanggan yang memesan makanan dan minuman untuk dibawa pulang.',
        ]);



        FnbProductCategory::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'name' => 'Coffee Specialties',
            'description' => 'Kategori ini mencakup berbagai jenis kopi spesial yang disajikan dengan metode penyeduhan yang unik dan bahan berkualitas tinggi, cocok untuk para pecinta kopi yang mencari pengalaman rasa yang berbeda.',
        ]);

        FnbTable::create([
            'fnb_outlet_id' => FnbOutlet::first()->id,
            'name' => 'Meja 1',
            'location' => 'Lantai 1',
            'capacity' => 4,
        ]);

        FnbTable::create([
            'fnb_outlet_id' => FnbOutlet::first()->id,
            'name' => 'Meja 2',
            'location' => 'Lantai 2',
            'capacity' => 2,
        ]);

        FnbProductCategory::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'name' => 'Pastry & Desserts',
            'description' => 'Kategori ini menawarkan berbagai pilihan pastry dan dessert lezat yang dibuat dengan bahan-bahan segar dan resep khas, sempurna untuk menemani kopi spesial Anda atau sebagai hidangan penutup yang memanjakan lidah.',
        ]);

        FnbProduct::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'fnb_product_category_id' => FnbProductCategory::firstWhere('name', 'Coffee Specialties')->id,
            'name' => 'Espresso Con Panna',
            'description' => 'Espresso Con Panna adalah minuman kopi yang terdiri dari espresso yang disajikan dengan krim kocok di atasnya, memberikan kombinasi rasa kuat dari kopi dan kelembutan dari krim, cocok untuk dinikmati sebagai hidangan penutup atau sebagai teman santai di sore hari.',
        ]);

        FnbProductVariant::create([
            'fnb_product_id' => FnbProduct::firstWhere('name', 'Espresso Con Panna')->id,
            'name' => 'Regular',
            'price' => 25000,
        ]);

        FnbProduct::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'fnb_product_category_id' => FnbProductCategory::firstWhere('name', 'Coffee Specialties')->id,
            'name' => 'Coffee Latte',
            'description' => 'Coffee Latte adalah minuman kopi yang terdiri dari espresso yang dicampur dengan susu panas, menghasilkan rasa kopi yang lembut dan creamy, cocok untuk dinikmati kapan saja, baik di pagi hari sebagai teman sarapan atau di sore hari untuk bersantai.',
        ]);

        FnbProductVariant::create([
            'fnb_product_id' => FnbProduct::firstWhere('name', 'Coffee Latte')->id,
            'name' => 'Regular',
            'price' => 30000,
        ]);

        FnbProductVariant::create([
            'fnb_product_id' => FnbProduct::firstWhere('name', 'Coffee Latte')->id,
            'name' => 'Large',
            'price' => 35000,
        ]);

        FnbProduct::create([
            'fnb_business_id' => FnbBusiness::first()->id,
            'fnb_product_category_id' => FnbProductCategory::firstWhere('name', 'Pastry & Desserts')->id,
            'name' => 'Chocolate Croissant',
            'description' => 'Chocolate Croissant adalah pastry yang terbuat dari adonan croissant yang renyah dan lembut, diisi dengan cokelat lezat di dalamnya, sempurna untuk dinikmati sebagai camilan manis atau sebagai teman kopi spesial Anda.',
        ]);

        FnbProductVariant::create([
            'fnb_product_id' => FnbProduct::firstWhere('name', 'Chocolate Croissant')->id,
            'name' => 'Regular',
            'price' => 20000,
        ]);
    }
}
