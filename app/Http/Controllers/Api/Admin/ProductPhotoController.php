<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductPhotoRequest;
use App\Http\Resources\ProductPhotoCollection;
use App\Http\Resources\ProductPhotoResource;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\JsonResponse;

class ProductPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ProductPhotoCollection
     */
    public function index(Product $product)
    {
        return new ProductPhotoCollection($product->photos, $product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductPhotoRequest $request
     * @param Product $product
     * @return void
     */
    public function store(ProductPhotoRequest $request, Product $product)
    {
        ProductPhoto::createWithPhotoFiles($product->id, $request->photos);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @param ProductPhoto $photo
     * @return ProductPhotoResource
     */
    public function show(Product $product, ProductPhoto $photo)
    {
        $this->assertProductPhoto($product, $photo);
        return new ProductPhotoResource($photo);
    }

    private function assertProductPhoto(Product $product, ProductPhoto $photo)
    {
        if ($photo->product_id != $product->id) {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductPhotoRequest $request
     * @param Product $product
     * @param ProductPhoto $photo
     * @return ProductPhotoResource
     */
    public function update(ProductPhotoRequest $request, Product $product, ProductPhoto $photo)
    {
        $this->assertProductPhoto($product, $photo);
        $photo = $photo->updateWithPhoto($request->photo);
        return new ProductPhotoResource($photo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param ProductPhoto $photo
     * @return JsonResponse
     */
    public function destroy(Product $product, ProductPhoto $photo)
    {
        $this->assertProductPhoto($product, $photo);
        $photo->deleteWithPhoto();
        return response()->json([], 204);
    }
}
