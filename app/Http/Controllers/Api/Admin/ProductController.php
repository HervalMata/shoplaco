<?php

namespace App\Http\Controllers\Api\Admin;

use App\Common\OnlyTrashed;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    use OnlyTrashed;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        /** @var ProductFilter $filter */
        $filter = app(ProductFilter::class);
        $query = Product::query()->with('categories', 'colors', 'materials');
        $query = $this->onlyTrashedIfRequested($request, $query);
        /** @var Builder $filterQuery */
        $filterQuery = $query->filtered($filter);
        $products = $filter->hasFilterParamter() ? $filterQuery->get() : $filterQuery->paginate(10);
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return ProductResource
     * @throws \Exception
     */
    public function store(ProductRequest $request)
    {
        $product = Product::createWithPhoto($request->all());
        $product->refresh();
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->updateWithPhoto($request->all());
        $product->save();
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([], 204);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     * @throws \Exception
     */
    public function restore(Product $product)
    {
        $product->restore();
        return response()->json([], 204);
    }
}
