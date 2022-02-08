<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    /**
     * List Products
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listProducts(Request $request)
    {
        $input = $request->all();

        $rules = [
            'per_page' => 'numeric',
            'page'     => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {

            $messages = $validator->messages();

            return response()->json([
                'status'  => false,
                'message' => 'Validation errors occurred',
                'errors'  => $messages
            ]);
        }

        $perPage = $request->get('per_page', 50);
        $page    = $request->get('page', 0);
        $offset  = 0;
        $take    = $perPage ? $perPage : 50;
        $page    = (int) $page;

        if ($page && $page > 1) {
            $offset = ($take * $page) - $take;
        }

        $products = Product::offset($offset)->limit($take)->get();

        if (count($products)) {
            return response()->json([
                'status' => true,
                'items'   => $this->converToProductOutput($products)
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Product list is empty.'
        ]);
    }

    /**
     * List Categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listCategories(Request $request)
    {
        $input = $request->all();

        $rules = [
            'per_page' => 'numeric',
            'page'     => 'numeric'
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {

            $messages = $validator->messages();

            return response()->json([
                'status'  => false,
                'message' => 'Validation errors occurred',
                'errors'  => $messages
            ]);
        }

        $perPage = $request->get('per_page', 50);
        $page    = $request->get('page', 0);
        $offset  = 0;
        $take    = $perPage ? $perPage : 50;
        $page    = (int) $page;

        if ($page && $page > 1) {
            $offset = ($take * $page) - $take;
        }

        $categories = Category::offset($offset)->limit($take)->get();

        if (count($categories)) {
            return response()->json([
                'status' => true,
                'items'   => $this->converToCategoryOutput($categories)
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Category list is empty.'
        ]);
    }

    /**
     * Get Single Product
     *
     * @param $id
     * @return JsonResponse
     */
    public function singleProduct($id)
    {
        $product = Product::find($id);

        if (isset($product->id)) {
            return response()->json([
                'status' => true,
                'item'   => $this->converToProductOutput($product)
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Product not found.'
        ]);
    }

    /**
     * Store Product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeProduct(Request $request)
    {
        $input = $request->all();

        $rules = [
            'name'        => 'required|unique:products',
            'category_id' => 'required|exists:categories,id',
            'sku'         => 'required|unique:products',
            'price'       => 'required|numeric'
        ];

        $messages = [
            'name.required'        => 'Please enter a name.',
            'category_id.required' => 'Please enter a category.',
            'sku.required'         => 'Please enter a SKU.',
            'price.required'       => 'Please enter a Price.'
        ];

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {

            $messages = $validator->messages();

            return response()->json([
                'status'  => false,
                'message' => 'Validation errors occurred',
                'errors'  => $messages
            ]);
        }

        if (Product::create($input)) {

            return response()->json([
                'status'  => true,
                'message' => 'Product has been added successfully.'
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong while adding product.'
        ]);
    }

    /**
     * Update Product
     *
     * @param Request $request
     * @param $id
     *
     * @return JsonResponse
     */
    public function updateProduct(Request $request, $id)
    {
        $input   = $request->all();

        if (empty($input)) {
            return response()->json([
                'status'  => false,
                'message' => 'Please add atleast one field to update.'
            ]);
        }

        $product = Product::find($id);

        if (isset($product->id)) {
            $rules = [
                'name'        => 'unique:products,name,' . $id,
                'category_id' => 'exists:categories,id',
                'sku'         => 'unique:products,sku,' . $id,
                'price'       => 'numeric'
            ];

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {

                $messages = $validator->messages();

                return response()->json([
                    'status'  => false,
                    'message' => 'Validation errors occurred',
                    'errors'  => $messages
                ]);
            }

            if ($product->update($input)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Product has been updated successfully.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong while updating product.'
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Product not found.'
        ]);
    }

    /**
     * Delete Product
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (isset($product->id)) {
            if ($product->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Product deleted successfully.'
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Unable to delete the product.'
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Product not found.'
        ]);
    }

    /**
     * Convert To Product Output
     *
     * @param mixed $collection
     * @return JsonResponse
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

    /**
     * Convert To Category Output
     *
     * @param mixed $collection
     * @return JsonResponse
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
