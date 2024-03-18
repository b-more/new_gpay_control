<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'country_id',
        'business_type_id',
        'business_category_id',
        'province_id',
        'district_id',
        'business_name',
        'business_email',
        'business_logo',
        'business_address_line_1',
        'business_phone_number',
        'business_bank_account_number',
        'business_bank_account_name',
        'business_bank_account_branch_name',
        'account_number',
        'business_bank_account_branch_code',
        'business_bank_account_sort_code',
        'business_bank_account_swift_code',
        'callback_url',
        'is_active',
        'is_deleted',
        'business_bank_name',
        'collection_commission_id',
        'disbursement_commission_id',
        'business_tpin',
        'business_reg_number',
        'payment_checkout',
        'certificate_of_incorporation',
        'tax_clearance',
        'supporting_documents',
        'nrcs',
        'pacra_certificate',
        'approved_at',
        'director_nrc',
        'director_details',
        'pacra_printout'
    ];

    protected $casts = [
        'tax_clearance'=> 'array',
        'supporting_documents'=> 'array',
        'certificate_of_incorporation' => 'array',
        'director_nrc' => 'array',
        'director_details' => 'array',
        'pacra_printout' => 'array',
        'profile_pic' => 'array'
    ];

    public function clients()
    {
        return $this->hasMany(Business::class,'user_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function BusinessCategory()
    {
        return $this->belongsTo(BusinessCategory::class);
    }
    public function BusinessType()
    {
        return $this->belongsTo(BusinessType::class);
    }



    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
