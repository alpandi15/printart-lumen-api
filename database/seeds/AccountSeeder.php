<?php

use Illuminate\Database\Seeder;
use App\Model\Mysql\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::insert([
            [
                "type" => "ACCOUNT_RECEIVABLE",
                "description" => "Account Receivable",
                "value" => "0"
            ],[
                "type" => "ACCOUNT_SALES",
                "description" => "Account Penjualan",
                "value" => "0"
            ],[
                "type" => "ACCOUNT_SALES_TERM_DISC",
                "description" => "Account Discount",
                "value" => "0"
            ],[
                "type" => "ACCOUNT_FREIGHT",
                "description" => "Account Biaya Pengiriman",
                "value" => "0"
            ]
        ]);
    }
}
