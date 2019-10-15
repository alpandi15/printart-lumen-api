<?php

namespace App\Model\Accurate;

use Illuminate\Database\Eloquent\Model;

class ARINV extends Model
{
    protected $connection = 'firebird';
    protected $table = 'ARINV';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'ARINVOICEID';
    // protected $dates = ['SHIPDATE','INVOICEDATE'];

    protected $fillable = [
        'PURCHASEORDERNO',
        'ARINVOICEID',
        'CUSTOMERID',
        'SALESMANID',
        'INVOICENO',
        'WAREHOUSEID',
        'INVOICEDATE',
        'INVOICEAMOUNT',
        'PAIDAMOUNT',
        'RATE',
        'TERMDISCOUNT',
        'RETURNAMOUNT',
        'OWING',
        'TERMSID',
        'GLPERIOD',
        'GLYEAR',
        'PRINTED',
        'SHIPDATE',
        'TAX1RATE',
        'TAX2RATE',
        'GLHISTID',
        'PAYMENT',
        'CASHDISCOUNT',
        'TEMPLATEID',
        'ARACCOUNT',
        'GETFROMOTHER',
        'DELIVERYORDER',
        'GETFROMSO',
        'FISCALRATE',
        'OWINGDC',
        'TAX1AMOUNT',
        'TAX2AMOUNT',
        'INCLUSIVETAX',
        'CUSTOMERISTAXABLE',
        'GETFROMDO',
        'FREIGHT',
        'RECONCILED',
        'INVFROMSR',
        'TAXDATE',
        'REPORTEDTAX1',
        'REPORTEDTAX2',
        'ROUNDEDTAX1AMOUNT',
        'ROUNDEDTAX2AMOUNT',
        'ISTAXPAYMENT',
        'TRANSACTIONID',
        'SHIPTO1',
        'SHIPTO2',
        'SHIPTO3',
        'SHIPTO4',
        'BRANCHCODEID',
        'GETFROMQUOTE',
        'TAXRETURNAMOUNT',
        'RETURNNOTAX',
        'INVAMTBEFORETAX',
        'TAXDISCPAYMENT',
        'DISCPAYMENT',
        'TAXPAIDAMOUNT',
        'BASETAXINVAMT',
        'ISOUTSTANDING',
        'OUTSTANDINGDO',
        'MAXCHEQUEDATE',
        'RATETYPE',
        'TAX1AMOUNTDP',
        'TAX2AMOUNTDP',
        'ROUNDEDTAX1DP',
        'ROUNDEDTAX2DP',
        'PPH23AMOUNT',
        'COGSAMOUNT',
        'DPAMOUNT',
        'DPTAX',
        'PROJECTAMOUNT'
    ];

    public function arinvdet(){
        return $this->hasMany(ARINVDET::class, 'ARINVOICEID', 'ARINVOICEID');
    }
    public function salesman(){
        return $this->hasOne(SALESMAN::class, 'SALESMANID', 'SALESMANID');
    }
    public function item(){
        return $this->hasOne(ITEM::class, 'ITEMNO');
    }
}
