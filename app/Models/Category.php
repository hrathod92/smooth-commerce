<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Convert To Category Output
     *
     * @param mixed $collection
     * @return mixed
     */
    public function converToCategoryOutput($collection = [])
    {
        if ($collection instanceof Collection) {
            $collection = $collection->map(function ($category, $key) {
                $category = [
                    'id'   => $category->id,
                    "name" => $category->name
                ];

                return $category;
            });

            return $collection;
        } else {
            return [
                'id'   => $collection->id,
                "name" => $collection->name
            ];
        }
    }
}
