<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $data = $request->all();
        if ($request->has('remove_photo')) {
            $data['photo'] = null;
        }
        $user = Auth::guard('api')->user();
        $user->updateWithProfile($data);
        $resource = new UserResource($user);
        return [
            'user' => $resource->toArray($request),
            'token' => Auth::guard('api')->login($user)
        ];
    }

}
