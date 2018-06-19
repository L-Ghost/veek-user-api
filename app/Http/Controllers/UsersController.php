<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return $this->createJsonResponse($users, 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return $this->createJsonResponse($user, 200);
        }
        return $this->doesNotExist($id);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validateUser($request);

        if (User::whereEmail($request->email)->first()) {
            return $this->createJsonResponse([
                'message' => 'You cannot insert another user with the same email'
            ], 422);
        }

        $user = User::create($request->all());
        return $this->createJsonResponse([
            'message' => 'The user has been created', 'id' => $user->id
        ], 201);
    }

    // validates data sent to server
    private function validateUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
        ]);
    }

    /**
     * default response for invalid ids
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    private function doesNotExist($id)
    {
        return $this->createJsonResponse([
            'message' => "The user with id {$id} does not exist"
        ], 404);
    }

}