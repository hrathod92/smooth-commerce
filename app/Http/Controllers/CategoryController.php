<?php

namespace App\Http\Controllers;

use App\Library\API\BaseAPILibrary;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response as HttpResponse;

class CategoryController extends BaseAPILibrary
{
    /**
     * Category Model
     *
     * @var Category
     */
    public $model;

    /**
     * Construct
     *
     */
    public function __construct()
    {
        $this->model = new Category();
    }

    /**
     * List Categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listCategories(Request $request)
    {
        $categories = $this->applyPagination($this->model, $request);

        if (count($categories)) {
            return $this->setSuccessResponse($this->model->converToCategoryOutput($categories));
        }

        return $this->setFailureResponse('NOT_FOUND', 'Category list is empty.', true, HttpResponse::HTTP_NOT_FOUND);
    }
}
