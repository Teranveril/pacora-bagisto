<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Product\Models\ProductFlatProxy;
use Webkul\Customer\Contracts\CompareItem as CompareItemContract;

class CompareItem extends Model implements CompareItemContract
{   
    /**
     * Guarded
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'compare_items';

    /**
     * The customer that belong to the compare product.
     *
     * @return void
     */
    public function customer()
    {
        return $this->belongsTo(CustomerProxy::modelClass(), 'customer_id');
    }

    /**
     * The product that belong to the compare product.
     *
     * @return void
     */
    public function product() {
        return $this->belongsTo(ProductFlatProxy::modelClass(), 'product_id', 'product_id');
    }
}