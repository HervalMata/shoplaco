<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductColorRequest;
use App\Http\Resources\ProductColorResource;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductColorController extends Controller
{
    /**
     * @param Product $product
     * @return ProductColorResource
     */
    public function index(Product $product)
    {
        return new ProductColorResource($product);
    }

    /**
     * @param ProductColorRequest $request
     * @param Product $product
     * @return array|JsonResponse
     */
    public function store(ProductColorRequest $request, Product $product)
    {
        $changed = $product->colors()->sync($request->colors);
        $colorsAttachedId = $changed['attached'];
        $colors = Color::whereIn('id', $colorsAttachedId)->get();
        return $colors->count() ? response()->json(new ProductColorResource($product), 201) : [];
    }

    /**
     * @param Product $product
     * @param Color $color
     * @return JsonResponse
     */
    public function destroy(Product $product, Color $color)
    {
        $product->colors()->detach($color->id);
        return response()->json([], 204);
    }
}
