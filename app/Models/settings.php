<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'mobile',
        'trn',
        'crn',
        'street_name',
        'building_number',
        'plot_identification',
        'region',
        'city',
        'postal_number',
        'egs_serial_number',
        'business_category',
        'common_name',
        'organization_unit_name',
        'organization_name',
        'country_name',
        'registered_address',
        'otp',
        'email_address',
        'invoice_type',
        'is_production',
        'token_id',
        'logo',
        'cnf','private_key',
        'public_key',
        'csr_request',
        'certificate',
        'secret','csid',
        'production_certificate',
        'production_secret',
        'production_csid',
        'company_id',
        'scander_number',
        'stage'];
    
}
