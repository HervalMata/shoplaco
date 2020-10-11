<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::with('profile')->paginate(10);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->all();
        $token = $request->token;
        $data['photo'] = $data['photo'] ?? null;
        $user = User::createCustomer($data);
        return ['token' => Auth::guard('api')->login($user)];
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(CustomerRequest $request)
    {
        $data = $request->all();
        if ($request->has('token')) {
            $token = $request->token;
        }

        if ($request->has('remove_photo')) {
            $data["photo"] = null;

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
