<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialRequest;
use App\Http\Resources\MaterialResource;
use App\Models\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $materials = Material::paginate(5);
        return MaterialResource::collection($materials);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MaterialRequest $request
     * @return MaterialResource
     */
    public function store(MaterialRequest $request)
    {
        $material = Material::create($request->all());
        $material->refresh();
        return new MaterialResource($material);
    }

    /**
     * Display the specified resource.
     *
     * @param Material $material
     * @return MaterialResource
     */
    public function show(Material $material)
    {
        return new MaterialResource($material);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MaterialRequest $request
     * @param Material $material
     * @return MaterialResource
     */
    public function update(MaterialRequest $request, Material $material)
    {
        $material->fill($request->all());
        $material->save();
        return new MaterialResource($material);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Material $material
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Material $material)
    {
        $material->delete();
        return response()->json([], 204);
    }

}
