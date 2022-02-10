<?php

namespace App\Http\Controllers;

use App\Http\Library\API\BaseAPILibrary;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\Response as HttpResponse;

class ProductController extends BaseAPILibrary
{
    /**
     * Product Model
     *
     * @var Product
     */
    public $model;

    /**
     * Construct
     *
     */
    public function __construct()
    {
        $this->model = new Product();
    }

    /**
     * List Products
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listProducts(Request $request)
    {
        $products = $this->applyPagination($this->model, $request);

        if (count($products)) {
            return $this->setSuccessResponse($this->model->converToProductOutput($products));
        }

        return $this->setFailureResponse('NOT_FOUND', 'Product list is empty.', true, HttpResponse::HTTP_NOT_FOUND);
    }

    /**
     * Get Single Product
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function singleProduct($id)
    {
        $product = $this->model->find($id);

        if (isset($product->id)) {
            return $this->setSuccessResponse($this->model->converToProductOutput($product), 'item');
        }

        return $this->setFailureResponse('NOT_FOUND', 'Product not found.', true, HttpResponse::HTTP_NOT_FOUND);
    }

    /**
     * Store Product
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeProduct(Request $request)
    {
        $input = $request->all();

        $this->validateCustom($input, $this->createProductRules, $this->createProductMessages);

        if ($this->model->create($input)) {
            return response()->json([
                'status'  => true,
                'message' => 'Product has been added successfully.'
            ]);
        }

        return $this->setFailureResponse('INTERNAL_SERVER_ERROR', 'Something went wrong while adding product.', true, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update Product
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProduct(Request $request, $id)
    {
        $input = $request->all();

        if (empty($input)) {
            return $this->setFailureResponse('BAD_REQUEST', 'Please add atleast one field to update.', true, HttpResponse::HTTP_BAD_REQUEST);
        }

        $product = $this->model->find($id);

        if (isset($product->id)) {
            $rules = [
                'name'        => 'unique:products,name,' . $id,
                'category_id' => 'exists:categories,id',
                'sku'         => 'unique:products,sku,' . $id,
                'price'       => 'numeric'
            ];

            $this->validateCustom($input, $rules);

            if ($product->update($input)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Product has been updated successfully.'
                ]);
            }

            return $this->setFailureResponse('INTERNAL_SERVER_ERROR', 'Something went wrong while updating product.', true, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->setFailureResponse('NOT_FOUND', 'Product not found.', true, HttpResponse::HTTP_NOT_FOUND);
    }

    /**
     * Delete Product
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProduct($id)
    {
        $product = $this->model->find($id);

        if (isset($product->id)) {
            if ($product->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Product deleted successfully.'
                ]);
            }

            return $this->setFailureResponse('INTERNAL_SERVER_ERROR', 'Unable to delete the product.', true, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->setFailureResponse('NOT_FOUND', 'Product not found.', true, HttpResponse::HTTP_NOT_FOUND);
    }
}
