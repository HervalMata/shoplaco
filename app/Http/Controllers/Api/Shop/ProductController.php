<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $products = Product::with('category', 'colors', 'materials')->where('active', '=', true)
            ->where('stock', '>', 0)->get();
        return ProductResource::collection($products);
    }

    /**
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product)
    {
        $category_id = $product->categories()->id;
        $category = Category::find($category_id);
        $this->indexByRecommended($category);
        return new ProductResource($product);
    }

    /**
     * @param Category $category
     * @return AnonymousResourceCollection
     */
    public function indexByRecommended(Category $category)
    {
        $products = Product::where('active', true)
            ->where('stock', '>', 0)
            ->where('category_id', $category->id)
            ->take(5)
            ->get();
        return ProductResource::collection($products);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function indexByFeatured()
    {
        $products = Product::with('category', 'colors', 'materials')->where('active', '=', true)
            ->where('stock', '>', 0)->where('featured', '=', true)
            ->take(5)->get();
        return ProductResource::collection($products);
    }

    /**
     * @param Category $category
     * @return AnonymousResourceCollection
     */
    public function indexByCategory(Category $category)
    {
        $products = Product::where('active', true)
            ->where('stock', '>', 0)
            ->where('category_id', $category->id)
            ->get();
        return ProductResource::collection($products);
    }
}
