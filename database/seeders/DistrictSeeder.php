<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('districts')->insert([
            [
                'province_id' => 1, //central province
                'name' => 'Chibombo District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Chisamba District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Chitambo District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Kabwe District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Kapiri Mposhi District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Luano District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Mkushi District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Mumbwa District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Ngabwe District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Serenje District'
            ],
            [
                'province_id' => 1, //central province
                'name' => 'Shibuyunji District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Chililabombwe District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Chingola District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Kalulushi District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Kitwe District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Luanshya District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Lufwanyama District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Masaiti District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Mpongwe District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Mufulira District'
            ],
            [
                'province_id' => 2, //copperbelt province
                'name' => 'Ndola District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Chadiza District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Chama District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Chasefu District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Chipangali District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Chipata District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Kasenengwa District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Katete District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Lumezi District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Lundazi District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Mambwe District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Nyimba District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Petauke District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Petauke District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Petauke District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Sinda District'
            ],
            [
                'province_id' => 3, //eastern province
                'name' => 'Vubwi District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Chembe District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Chiengi District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Chifunabuli District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Chipili District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Kawambwa District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Lunga District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Mansa District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Milenge District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Mwansabombwe District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Mwense District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Nchelenge District'
            ],
            [
                'province_id' => 4, //Luapula Province
                'name' => 'Samfya District'
            ],
            [
                'province_id' => 5, //Lusaka Province
                'name' => 'Chilanga District'
            ],
            [
                'province_id' => 5, //Lusaka Province
                'name' => 'Chongwe District'
            ],
            [
                'province_id' => 5, //Lusaka Province
                'name' => 'Kafue District'
            ],
            [
                'province_id' => 5, //Lusaka Province
                'name' => 'Luangwa District'
            ],
            [
                'province_id' => 5, //Lusaka Province
                'name' => 'Lusaka District'
            ],
            [
                'province_id' => 5, //Lusaka Province
                'name' => 'Rufunsa District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => 'Chinsali District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => 'Isoka District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => 'Kanchibiya District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => 'Lavushimanda District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => 'Mafinga District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => 'Mpika District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => 'Nakonde District'
            ],
            [
                'province_id' => 6, //Muchinga Province
                'name' => "Shiwang'andu District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Chilubi District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Kaputa District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Kasama District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Lunte District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Lupososhi District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Luwingu District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Mbala District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Mporokoso District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Mpulungu District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Mungwi District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Nsama District"
            ],
            [
                'province_id' => 7, //Northern Province
                'name' => "Senga District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Chavuma District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Ikelenge District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Kabompo District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Kasempa District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Kalumbila District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Manyinga District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Mufumbwe District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Mushindamo District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Mwinilunga District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Solwezi District"
            ],
            [
                'province_id' => 8, //Northern Western Province
                'name' => "Zambezi District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Chikankata District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Chirundu District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Choma District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Gwembe District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Itezhi-Tezhi District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Kalomo District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Kazungula District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Livingstone District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Mazabuka District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Monze District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Namwala District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Pemba District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Siavonga District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Sinazongwe District"
            ],
            [
                'province_id' => 9, //SOUTHERN PROVINCE
                'name' => "Zimba District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Kalabo District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Kaoma District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Limulunga District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Luampa District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Lukulu District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Mitete District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Mongu District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Mulobezi District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Mwandi District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Nalolo District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Nkeyema District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Senanga District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Sesheke District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Shang'ombo District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Sikongo District"
            ],
            [
                'province_id' => 10, //WESTERN PROVINCE
                'name' => "Sioma District"
            ],
        ]);
    }
}
