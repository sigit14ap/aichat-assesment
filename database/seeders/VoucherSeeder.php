<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(1, 1000) as $row){
            $status = true;

            $number = '';

            while($status){
                $number = 'VOC-'.$this->generateAlphaNumeric(25);
                $check = Voucher::where('voucher', '=', $number)->first();

                if(!$check){
                    $status = false;
                }
            }

            Voucher::create([
                'voucher'   =>  $number
            ]);
        }
    }

    public function generateAlphaNumeric($length)
    {
        $token = '';
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        return $token;
	}
}
