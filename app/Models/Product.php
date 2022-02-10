<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'sku',
        'price'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    /**
     * Convert To Product Output
     *
     * @param mixed $collection
     * @return array
     */
    public function converToProductOutput($collection = [])
    {
        if ($collection instanceof Collection) {
            $collection = $collection->map(function ($product, $key) {
                $product = [
                    'id'       => $product->id,
                    "name"     => $product->name,
                    "category" => $product->category->name,
                    "sku"      => $product->sku,
                    "price"    => $product->price
                ];

                return $product;
            });

            return $collection;
        } else {
            return [
                'id'       => $collection->id,
                "name"     => $collection->name,
                "category" => $collection->category->name,
                "sku"      => $collection->sku,
                "price"    => $collection->price
            ];
        }
    }
}
