<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductMaterialRequest;
use App\Http\Resources\ProductMaterialResource;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return ProductMaterialResource
     */
    public function index(Product $product)
    {
        return new ProductMaterialResource($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductMaterialRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function store(ProductMaterialRequest $request, Product $product)
    {
        $changed = $product->materials()->sync($request->materials);
        $materialAttachedId = $changed['attached'];
        $material = Material::whereIn('id', $materialAttachedId)->get();
        return $material->count() ? response()->json(new ProductMaterialResource($product), 201) : [];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Material $material
     * @return JsonResponse
     */
    public function destroy(Product $product, Material $material)
    {
        $product->materials()->detach($material->id);
        return response()->json([], 204);
    }
}
