<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bank_branches')->insert([
            // Bank of Zambia
            [
                'bank_name_id' => 1,
                'branch_code' => '000001',
                'branch_name' => 'Lusaka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 1,
                'branch_code' => '000102',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Zambia National Commercial Bank
            [
                'bank_name_id' => 2,
                'branch_code' => '010001',
                'branch_name' => 'Head office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010002',
                'branch_name' => 'International Bank',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010003',
                'branch_name' => 'Lusaka Business centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010007',
                'branch_name' => 'Human Resources',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010010',
                'branch_name' => 'Longacres',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010016',
                'branch_name' => 'Head Office Processing Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010018',
                'branch_name' => 'Treasury',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010040',
                'branch_name' => 'Cairo Business centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010041',
                'branch_name' => 'Lusaka north end',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010050',
                'branch_name' => 'Findeco House',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010052',
                'branch_name' => 'Lusaka centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010053',
                'branch_name' => 'Lusaka kwacha',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010055',
                'branch_name' => 'Debt Recovery',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010066',
                'branch_name' => 'Lusaka Premium House',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010067',
                'branch_name' => 'Lusaka Civic Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010073',
                'branch_name' => 'Twin Palms Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010074',
                'branch_name' => 'North Mead',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010075',
                'branch_name' => 'Manda Hill',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010078',
                'branch_name' => 'Manda Hill',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010083',
                'branch_name' => 'Xapit',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010084',
                'branch_name' => 'Government Comlpex',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010085',
                'branch_name' => 'Woodlands',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010086',
                'branch_name' => 'Acacia Park Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010093',
                'branch_name' => 'Digital',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010099',
                'branch_name' => 'Waterfalls',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010142',
                'branch_name' => 'Ndola Business Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010154',
                'branch_name' => 'Ndola west',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010164',
                'branch_name' => 'Ndola Industrial',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010217',
                'branch_name' => 'Kitwe Clearing centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010245',
                'branch_name' => 'Kitwe Obote',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010256',
                'branch_name' => 'Kitwe Industrial',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010298',
                'branch_name' => 'Mukuba',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010349',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010543',
                'branch_name' => 'Mufulira',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010763',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010862',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '010946',
                'branch_name' => 'Kabwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '011044',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '011160',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '011259',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '011596',
                'branch_name' => 'Nakonde',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '011707',
                'branch_name' => 'Chinsali',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '011865',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '011948',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '012104',
                'branch_name' => 'Kawambwa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '012309',
                'branch_name' => 'Mkushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '012461',
                'branch_name' => 'Kapiri Mposhi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '012571',
                'branch_name' => 'Namwala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '012606',
                'branch_name' => 'Mfuwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '012868',
                'branch_name' => 'Siavonga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '013151',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '013372',
                'branch_name' => 'Avondale',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '013458',
                'branch_name' => 'Kafue',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '013582',
                'branch_name' => 'Chirundu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '013647',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '013757',
                'branch_name' => 'Monza',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '013869',
                'branch_name' => 'Maamba',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '014070',
                'branch_name' => 'Lundazi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '014305',
                'branch_name' => 'Petauke',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '014508',
                'branch_name' => 'Chisamba',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '014779',
                'branch_name' => 'Itezhi Tezhi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 2,
                'branch_code' => '015181',
                'branch_name' => 'Senanga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Absa Bank Zambia PLC
            [
                'bank_name_id' => 3,
                'branch_code' => '020001',
                'branch_name' => 'Head office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020002',
                'branch_name' => 'HeadOffice - Elunda',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020008',
                'branch_name' => 'Lusaka - Kamwala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020014',
                'branch_name' => 'Lusaka - Northend',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020015',
                'branch_name' => 'Lusaka-Matero',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020016',
                'branch_name' => 'Lusaka business centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020017',
                'branch_name' => 'Lusaka longacres',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020018',
                'branch_name' => 'Chilenja',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020019',
                'branch_name' => 'Lusaka industrial',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020026',
                'branch_name' => 'University of Zambia Lusaka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020033',
                'branch_name' => 'Soweto',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020036',
                'branch_name' => 'Chestone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020043',
                'branch_name' => 'Kabwata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020044',
                'branch_name' => 'Lusaka - Chawama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020049',
                'branch_name' => 'Manda hill',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020050',
                'branch_name' => 'Lusaka operation processing centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020054',
                'branch_name' => 'Kabelanga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020055',
                'branch_name' => 'Elunda Premium Banking Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020125',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020139',
                'branch_name' => 'Ndola Operations Processing Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020146',
                'branch_name' => 'Ndola - Masala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020209',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020210',
                'branch_name' => 'Kitwe Chimwemwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020241',
                'branch_name' => 'Kitwe Parklands Center',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020252',
                'branch_name' => 'Kitwe operation processing centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020303',
                'branch_name' => 'Chingola & prestige',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020453',
                'branch_name' => 'Chililabomwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020523',
                'branch_name' => 'Mufulira & prestige',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020648',
                'branch_name' => 'Kalulushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020713',
                'branch_name' => 'Luanshy',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020832',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '020906',
                'branch_name' => 'Kabwe & prestige',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '021012',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '021104',
                'branch_name' => 'Chipata,Katete & Petauke',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '021205',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '021451',
                'branch_name' => 'Mbala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '021540',
                'branch_name' => 'Nakonde',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '021845',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '021920',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '022338',
                'branch_name' => 'Mkushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '022411',
                'branch_name' => 'Kapiri Mposhi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '022531',
                'branch_name' => 'Lundazi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '022622',
                'branch_name' => 'Mfuwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '022829',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '023135',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '023407',
                'branch_name' => 'Kafue',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '023542',
                'branch_name' => 'Chirundu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '023621',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '023724',
                'branch_name' => 'Monze',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '024127',
                'branch_name' => 'Kalomo',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '024330',
                'branch_name' => 'Petauke',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '024637',
                'branch_name' => 'Chongwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '024928',
                'branch_name' => 'Katete',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '025247',
                'branch_name' => 'Chambishi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 3,
                'branch_code' => '025334',
                'branch_name' => 'Mumbwa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Citibank Zambia
            [
                'bank_name_id' => 4,
                'branch_code' => '030001',
                'branch_name' => 'Lusaka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030003',
                'branch_name' => 'Mcommerce Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030007',
                'branch_name' => 'Natsave',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030008',
                'branch_name' => 'ZNBS Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030031',
                'branch_name' => 'Permanent House',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030032',
                'branch_name' => 'Society House',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030033',
                'branch_name' => 'Soweto Agency',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030102',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030137',
                'branch_name' => 'Ndola Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030238',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030366',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030469',
                'branch_name' => 'Chililabobwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030568',
                'branch_name' => 'Mufulira',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030767',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030846',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030965',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031050',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031149',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031264',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031874',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031970',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '032487',
                'branch_name' => 'Kapiri Mposhi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '032871',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '033172',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '033673',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '035434',
                'branch_name' => 'Nyimba',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Stanbic Bank Zambia
            [
                'bank_name_id' => 5,
                'branch_code' => '040000',
                'branch_name' => 'Head office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040002',
                'branch_name' => 'Lusaka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040007',
                'branch_name' => 'Lusaka industrial',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040010',
                'branch_name' => 'Arcades',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040011',
                'branch_name' => 'Matero',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040015',
                'branch_name' => 'Mulungushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040023',
                'branch_name' => 'Soweto',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040026',
                'branch_name' => 'Kabwata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040027',
                'branch_name' => 'Private Banking',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040029',
                'branch_name' => 'Kabulonga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040030',
                'branch_name' => 'Woodlands',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040039',
                'branch_name' => 'Waterfall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040093',
                'branch_name' => 'Cosmopolitan Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040094',
                'branch_name' => 'East Park Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040103',
                'branch_name' => 'Ndola Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040104',
                'branch_name' => 'Arcades',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040105',
                'branch_name' => 'Ndola South',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040195',
                'branch_name' => 'Kafubu Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040206',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040224',
                'branch_name' => 'Chisokone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040296',
                'branch_name' => 'Mukuba Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040309',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040514',
                'branch_name' => 'Mufulira',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040922',
                'branch_name' => 'Kabwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '041017',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '041116',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '041218',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '042308',
                'branch_name' => 'Mkushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '042812',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '043419',
                'branch_name' => 'Kafue',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '043613',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '044821',
                'branch_name' => 'Lumwana',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '045225',
                'branch_name' => 'Chambishi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Standard Chartered Bank
            [
                'bank_name_id' => 6,
                'branch_code' => '060002',
                'branch_name' => 'Customer Service Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060011',
                'branch_name' => 'Financial control',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060014',
                'branch_name' => 'Kabulonga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060015',
                'branch_name' => 'Cross Roads',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060017',
                'branch_name' => 'Lusaka main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060021',
                'branch_name' => 'Levy Park Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060030',
                'branch_name' => 'Manda Hill',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060043',
                'branch_name' => 'North end',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060120',
                'branch_name' => 'Jacaranda Mall Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060171',
                'branch_name' => 'Buteko',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060228',
                'branch_name' => 'Zambia Way',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060336',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060444',
                'branch_name' => 'Chililiabombwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060732',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060813',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '061018',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '061237',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '062816',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '063148',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '063619',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Indo Zambia Bank
            [
                'bank_name_id' => 7,
                'branch_code' => '090000',
                'branch_name' => 'Head office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 7,
                'branch_code' => '090001',
                'branch_name' => 'Lusaka Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 7,
                'branch_code' => '090003',
                'branch_name' => 'Chilanga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '030965',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031050',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031149',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031264',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031874',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '031970',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '032487',
                'branch_name' => 'Kapiri Mposhi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '032871',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '033172',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '033673',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 4,
                'branch_code' => '035434',
                'branch_name' => 'Nyimba',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Stanbic Bank Zambia
            [
                'bank_name_id' => 5,
                'branch_code' => '040000',
                'branch_name' => 'Head office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040002',
                'branch_name' => 'Lusaka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040007',
                'branch_name' => 'Lusaka industrial',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040010',
                'branch_name' => 'Arcades',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040011',
                'branch_name' => 'Matero',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040015',
                'branch_name' => 'Mulungushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040023',
                'branch_name' => 'Soweto',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040026',
                'branch_name' => 'Kabwata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040027',
                'branch_name' => 'Private Banking',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040029',
                'branch_name' => 'Kabulonga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040030',
                'branch_name' => 'Woodlands',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040039',
                'branch_name' => 'Waterfall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040093',
                'branch_name' => 'Cosmopolitan Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040094',
                'branch_name' => 'East Park Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040103',
                'branch_name' => 'Ndola Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040104',
                'branch_name' => 'Arcades',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040105',
                'branch_name' => 'Ndola South',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040195',
                'branch_name' => 'Kafubu Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040206',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040224',
                'branch_name' => 'Chisokone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040296',
                'branch_name' => 'Mukuba Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040309',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040514',
                'branch_name' => 'Mufulira',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '040922',
                'branch_name' => 'Kabwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '041017',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '041116',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '041218',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '042308',
                'branch_name' => 'Mkushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '042812',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '043419',
                'branch_name' => 'Kafue',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '043613',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '044821',
                'branch_name' => 'Lumwana',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 5,
                'branch_code' => '045225',
                'branch_name' => 'Chambishi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Standard Chartered Bank
            [
                'bank_name_id' => 6,
                'branch_code' => '060002',
                'branch_name' => 'Customer Service Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060011',
                'branch_name' => 'Financial control',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060014',
                'branch_name' => 'Kabulonga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060015',
                'branch_name' => 'Cross Roads',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060017',
                'branch_name' => 'Lusaka main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060021',
                'branch_name' => 'Levy Park Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060030',
                'branch_name' => 'Manda Hill',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060043',
                'branch_name' => 'North end',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060120',
                'branch_name' => 'Jacaranda Mall Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060171',
                'branch_name' => 'Buteko',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060228',
                'branch_name' => 'Zambia Way',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060336',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060444',
                'branch_name' => 'Chililiabombwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060732',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '060813',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '061018',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '061237',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '062816',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '063148',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 6,
                'branch_code' => '063619',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Indo Zambia Bank
            [
                'bank_name_id' => 7,
                'branch_code' => '090000',
                'branch_name' => 'Head office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 7,
                'branch_code' => '090001',
                'branch_name' => 'Lusaka Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 7,
                'branch_code' => '090003',
                'branch_name' => 'Chilanga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 12,
                'branch_code' => '190001',
                'branch_name' => 'Lusaka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 12,
                'branch_code' => '190202',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // BancABC
            [
                'bank_name_id' => 13,
                'branch_code' => '200000',
                'branch_name' => 'Head office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200001',
                'branch_name' => 'Lusaka Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200006',
                'branch_name' => 'Longacres',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200007',
                'branch_name' => 'Kamwala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200009',
                'branch_name' => 'Arcades',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200032',
                'branch_name' => 'Down Town',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200033',
                'branch_name' => 'Industrial',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200036',
                'branch_name' => 'Nyumba Yanga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200044',
                'branch_name' => 'Pyramid Plaza',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200046',
                'branch_name' => 'Arcades',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200050',
                'branch_name' => 'East Park Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200052',
                'branch_name' => 'UTH',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200053',
                'branch_name' => 'Tenga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200054',
                'branch_name' => 'Livonia',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200102',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200149',
                'branch_name' => 'Kafubu Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200203',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200251',
                'branch_name' => 'Mukuba Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200315',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200434',
                'branch_name' => 'Kasumbalesa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200443',
                'branch_name' => 'Chililabombwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200513',
                'branch_name' => 'Mufulira',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200731',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200804',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '200925',
                'branch_name' => 'Kabwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201023',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201108',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201116',
                'branch_name' => 'Katete',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201205',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201247',
                'branch_name' => 'Sinazeze',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201329',
                'branch_name' => 'Mpulungu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201421',
                'branch_name' => 'Mbala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201520',
                'branch_name' => 'Nakonde',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201627',
                'branch_name' => 'Isoka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201728',
                'branch_name' => 'Chinsali',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201826',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '201942',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '202012',
                'branch_name' => 'Samfya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '202211',
                'branch_name' => 'Serenje',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '202541',
                'branch_name' => 'Lundazi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '202718',
                'branch_name' => 'Mwinilunga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '202822',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '202919',
                'branch_name' => 'Kabompo',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '203056',
                'branch_name' => 'Zambezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '203145',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '203217',
                'branch_name' => 'Sesheke',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '203514',
                'branch_name' => 'Chirundu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '203724',
                'branch_name' => 'Monze',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '204110',
                'branch_name' => 'Kalomo',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '204430',
                'branch_name' => 'Kaoma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '204638',
                'branch_name' => 'Chongwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 13,
                'branch_code' => '205339',
                'branch_name' => 'Mumbwa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // AB Bank
            [
                'bank_name_id' => 14,
                'branch_code' => '210000',
                'branch_name' => 'Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210001',
                'branch_name' => 'Cairo Main Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210002',
                'branch_name' => 'Chilenje',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210003',
                'branch_name' => 'Matero',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210004',
                'branch_name' => 'Kalingalinga Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210005',
                'branch_name' => 'Chelston',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210006',
                'branch_name' => 'Garden Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210108',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 14,
                'branch_code' => '210207',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // First National Bank
            [
                'bank_name_id' => 15,
                'branch_code' => '260001',
                'branch_name' => 'Commercial Suite',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260002',
                'branch_name' => 'Industrial Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260004',
                'branch_name' => 'FNB Operation Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260005',
                'branch_name' => 'Headoffice',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260006',
                'branch_name' => 'Electronic Banking Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260011',
                'branch_name' => 'Treasury',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260014',
                'branch_name' => 'Manda Hill',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260015',
                'branch_name' => 'Vechicle and Asset Finance',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260016',
                'branch_name' => 'Makeni Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260020',
                'branch_name' => 'Home Loan',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260025',
                'branch_name' => 'Branchless Banking',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260027',
                'branch_name' => 'Electronic Wallet',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260029',
                'branch_name' => 'CIB Corporate',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260031',
                'branch_name' => 'POS-Visa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260032',
                'branch_name' => 'POS- MasterCard',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260033',
                'branch_name' => 'POS-FNB',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260036',
                'branch_name' => 'Government and Public Sector',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260039',
                'branch_name' => 'Premier Banking',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260040',
                'branch_name' => 'Agriculture Centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260042',
                'branch_name' => 'Corporate Investment Banking',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260046',
                'branch_name' => 'Chilenje',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260048',
                'branch_name' => 'Cash centre',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260049',
                'branch_name' => 'PHI Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260050',
                'branch_name' => 'Cairo',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260061',
                'branch_name' => 'FNB Zambia Lusaka Private Suite',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260072',
                'branch_name' => 'Kabulonga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260103',
                'branch_name' => 'Ndola Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260118',
                'branch_name' => 'Jacaranda Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260212',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260243',
                'branch_name' => 'Mukuba Mall',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260247',
                'branch_name' => 'Kitwe Industrial',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260261',
                'branch_name' => 'FNB Kitwe Private Suite',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260322',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260544',
                'branch_name' => 'Mufulira Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260741',
                'branch_name' => 'Luanshya Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '260937',
                'branch_name' => 'Kabwe Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '261061',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '261121',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '261238',
                'branch_name' => 'Choma Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '262319',
                'branch_name' => 'Mkushi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '262823',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '262827',
                'branch_name' => 'Kalumbila Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 15,
                'branch_code' => '263613',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // First Capital Bank
            [
                'bank_name_id' => 16,
                'branch_code' => '280000',
                'branch_name' => 'Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 16,
                'branch_code' => '280001',
                'branch_name' => 'Industrial Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 16,
                'branch_code' => '280002',
                'branch_name' => 'Cairo Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 16,
                'branch_code' => '280003',
                'branch_name' => 'Lusaka Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 16,
                'branch_code' => '280004',
                'branch_name' => 'Makeni Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 16,
                'branch_code' => '280006',
                'branch_name' => 'Kamwala Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 16,
                'branch_code' => '280105',
                'branch_name' => 'Ndola Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 16,
                'branch_code' => '280207',
                'branch_name' => 'Kitwe Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // First Alliance Bank
            [
                'bank_name_id' => 17,
                'branch_code' => '340001',
                'branch_name' => 'Lusaka Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 17,
                'branch_code' => '340005',
                'branch_name' => 'Lusaka Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 17,
                'branch_code' => '340006',
                'branch_name' => 'Industrial Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 17,
                'branch_code' => '340007',
                'branch_name' => 'East Park Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 17,
                'branch_code' => '340103',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 17,
                'branch_code' => '340204',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Access Bank
            [
                'bank_name_id' => 18,
                'branch_code' => '350000',
                'branch_name' => 'Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350001',
                'branch_name' => 'Cairo Road',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350002',
                'branch_name' => 'Longacres',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350003',
                'branch_name' => 'Acacia',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350006',
                'branch_name' => 'Makeni',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350008',
                'branch_name' => 'Lusaka Square',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350012',
                'branch_name' => 'Garden',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350013',
                'branch_name' => 'Kalingalinga',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350018',
                'branch_name' => 'Tazara',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350104',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350205',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350310',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350409',
                'branch_name' => 'Chililabombwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '350814',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '351416',
                'branch_name' => 'Mbala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '351611',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '351915',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '352807',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 18,
                'branch_code' => '352817',
                'branch_name' => 'Mufumbwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Ecobank
            [
                'bank_name_id' => 19,
                'branch_code' => '360001',
                'branch_name' => 'Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360002',
                'branch_name' => 'Thabo Mbeki',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360003',
                'branch_name' => 'Cairo Road',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360004',
                'branch_name' => 'Woodlands',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360007',
                'branch_name' => 'Industrial Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360010',
                'branch_name' => 'Lumumba Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360109',
                'branch_name' => 'Ndola Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360205',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '360208',
                'branch_name' => 'Copperbelt University',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '363611',
                'branch_name' => 'Mazabuka Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 19,
                'branch_code' => '365506',
                'branch_name' => 'Chibombo',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // United Bank for Africa
            [
                'bank_name_id' => 20,
                'branch_code' => '370001',
                'branch_name' => 'Head Office Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 20,
                'branch_code' => '370002',
                'branch_name' => 'Kamwala',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 20,
                'branch_code' => '370003',
                'branch_name' => 'Cairo',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 20,
                'branch_code' => '370006',
                'branch_name' => 'Lewanika Branch',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 20,
                'branch_code' => '370099',
                'branch_name' => 'Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 20,
                'branch_code' => '370105',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 20,
                'branch_code' => '370204',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Zambia National Building Society
            [
                'bank_name_id' => 21,
                'branch_code' => '510019',
                'branch_name' => 'Banking Society Business Park',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510031',
                'branch_name' => 'Society House',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510032',
                'branch_name' => 'Permanent House',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510040',
                'branch_name' => 'Soweto',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510042',
                'branch_name' => 'Cosmopolitan',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510162',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510264',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510366',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510468',
                'branch_name' => 'Chililabombwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510565',
                'branch_name' => 'Mufulira',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510767',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510869',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '510963',
                'branch_name' => 'Kabwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '511037',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '511136',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '511235',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '511872',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '511970',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '512439',
                'branch_name' => 'Kapiri Mposhi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '512871',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '513134',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '513638',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 21,
                'branch_code' => '515441',
                'branch_name' => 'Nyimba',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // Bayport Financial Services
            [
                'bank_name_id' => 22,
                'branch_code' => '550001',
                'branch_name' => 'Lusaka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550003',
                'branch_name' => 'Heroes',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550004',
                'branch_name' => 'UTH',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550099',
                'branch_name' => 'Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550105',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550202',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550307',
                'branch_name' => 'Chingola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550309',
                'branch_name' => 'Konkola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550508',
                'branch_name' => 'Mufulira',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550706',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550817',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '550911',
                'branch_name' => 'Kabwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '551019',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '551112',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '551815',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '551913',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '552810',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '553114',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '553416',
                'branch_name' => 'Kafue',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 22,
                'branch_code' => '553618',
                'branch_name' => 'Mazabuka',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],

            // National Savings and Credit Bank
            [
                'bank_name_id' => 23,
                'branch_code' => '580001',
                'branch_name' => 'Head Office',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580002',
                'branch_name' => 'Credit Center',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580008',
                'branch_name' => 'Cosmopolitan',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580009',
                'branch_name' => 'Chilenje',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580010',
                'branch_name' => 'Lusaka Main',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580011',
                'branch_name' => 'Matero',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580012',
                'branch_name' => 'Northend',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580130',
                'branch_name' => 'Ndola',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580220',
                'branch_name' => 'Chimwemwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580224',
                'branch_name' => 'Kitwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580725',
                'branch_name' => 'Luanshya',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580822',
                'branch_name' => 'Kasama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '580906',
                'branch_name' => 'Kabwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '581007',
                'branch_name' => 'Livingstone',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '581104',
                'branch_name' => 'Chipata',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '581219',
                'branch_name' => 'Choma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '581739',
                'branch_name' => 'Chinsali',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '581837',
                'branch_name' => 'Mpika',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '581927',
                'branch_name' => 'Mansa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '582831',
                'branch_name' => 'Solwezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '582836',
                'branch_name' => 'Lumwana',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '583032',
                'branch_name' => 'Zambezi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '583113',
                'branch_name' => 'Mongu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '584315',
                'branch_name' => 'Petauke',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '584605',
                'branch_name' => 'Chongwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '584817',
                'branch_name' => 'Kalabo',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '585116',
                'branch_name' => 'Lukulu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '585214',
                'branch_name' => 'Mumbwa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '585435',
                'branch_name' => 'Lufwanyama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '585634',
                'branch_name' => 'Mpongwe',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '585903',
                'branch_name' => 'Chama',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '586338',
                'branch_name' => 'Mwense',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '586529',
                'branch_name' => 'Nchelenge',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '586721',
                'branch_name' => 'Kaputa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '586926',
                'branch_name' => 'Luwingu',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '587033',
                'branch_name' => 'Chilubi',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '587128',
                'branch_name' => 'Mporokoso',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '587223',
                'branch_name' => 'Kasempa',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '587518',
                'branch_name' => 'Kazungula',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ],
            [
                'bank_name_id' => 23,
                'branch_code' => '587740',
                'branch_name' => 'Chavuma',
                'closure_date' => '2040-12-12',
                'status' => 'Open'
            ]


        ]);
    }
}
