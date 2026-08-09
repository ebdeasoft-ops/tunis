<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialIssueItem extends Model
{
    use HasFactory;
    protected $table = 'material_issue_items';

    protected $fillable = [
        'issue_header_id',
        'raw_material_id',
        'issued_quantity',
        'unit_cost',
        'total_cost',
        'notes',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(products::class, 'raw_material_id');
    }
    public function issueHeader()
    {
        return $this->belongsTo(MaterialIssueHeader::class, 'issue_header_id');
    }
}
