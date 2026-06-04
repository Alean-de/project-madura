<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan lokal Indonesia agar nama produk lebih relevan

        // Definisikan kategori sesuai request
        $categories = [
            4 => [
                'name' => ['Kopi Susu Kekinian', 'Teh Botol Sosro', 'Susu UHT Full Cream', 'Air Mineral 600ml', 'Sirup Marjan Boudoin', 'Energy Drink XL', 'Soda Gembira Can'],
                'unit' => 'Botol'
            ],
            6 => [
                'name' => ['Keripik Singkong Pedas', 'Biskuit Cokelat Premium', 'Mie Instan Goreng', 'Roti Tawar Gandum', 'Kacang Atom Shanghai', 'Makaroni Goreng Pedas', 'Cokelat Batangan 60g'],
                'unit' => 'Pcs'
            ],
            7 => [
                'name' => ['Buku Tulis Isi 40', 'Pulpen Gel Hitam', 'Pensil 2B Kenko', 'Penghapus Standard', 'Kertas HVS A4 80gr', 'Stapler Mini', 'Penggaris Besi 30cm'],
                'unit' => 'Pack'
            ]
        ];

        $products = [];

        // Generate 20 data produk
        for ($i = 0; $i < 20; $i++) {
            // Ambil kategori acak dari id: 4, 6, atau 7
            $categoryId = $faker->randomElement([4, 6, 7]);
            
            // Ambil nama produk acak berdasarkan kategori agar datanya masuk akal
            $productName = $faker->randomElement($categories[$categoryId]['name']) . ' ' . $faker->colorName; 
            $unit = $categories[$categoryId]['unit'];

            // Sesuaikan rentang harga berdasarkan kategori (Harga Sekarang)
            if ($categoryId == 4) { // Minuman
                $purchasePrice = $faker->numberBetween(3000, 15000);
            } elseif ($categoryId == 6) { // Makanan
                $purchasePrice = $faker->numberBetween(5000, 25000);
            } else { // ATK/ATS
                $purchasePrice = $faker->numberBetween(2000, 35000);
            }

            // Atur harga eceran/jual selalu lebih tinggi dari harga beli (margin keuntungan 15% - 30%)
            $margin = $faker->numberBetween(15, 30) / 100;
            $sellingPrice = $purchasePrice + ($purchasePrice * $margin);

            // Bulatkan harga ke ratusan terdekat agar rapi (misal 12,345 jadi 12,300 atau 12,400)
            $purchasePrice = round($purchasePrice, -2);
            $sellingPrice = round($sellingPrice, -2);

            $products[] = [
                'user_id'        => 1,
                'product_name'   => $productName,
                'category_id'    => $categoryId,
                'supplier_id'    => 1, // Fix id = 1 sesuai request
                'unit'           => $unit,
                'purchase_price' => $purchasePrice,
                'selling_price'  => $sellingPrice,
                'initial_stock'  => $faker->numberBetween(30, 100),
                'minimum_stock'  => $faker->numberBetween(5, 20),
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
