<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $colors = Color::paginate(5);
        return ColorResource::collection($colors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ColorRequest $request
     * @return ColorResource
     */
    public function store(ColorRequest $request)
    {
        $color = Color::create($request->all());
        $color->refresh();
        return new ColorResource($color);
    }

    /**
     * Display the specified resource.
     *
     * @param Color $color
     * @return ColorResource
     */
    public function show(Color $color)
    {
        return new ColorResource($color);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ColorRequest $request
     * @param Color $color
     * @return ColorResource
     */
    public function update(ColorRequest $request, Color $color)
    {
        $color->fill($request->all());
        $color->save();
        return new ColorResource($color);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Color $color
     * @return Response
     * @throws \Exception
     */
    public function destroy(Color $color)
    {
        $color->delete();
        return response()->json([], 204);
    }
}
