<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    protected $table = 'stock_opname_details';

    protected $fillable = [
        'stock_opname_id',
        'sparepart_id',
        'system_stock',
        'physical_stock',
        'discrepancy',
        'notes',
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id');
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }
}
