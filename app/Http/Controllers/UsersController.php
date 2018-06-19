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

    public function update(Request $request, $id)
    {
        $this->validateUser($request);

        $user = User::find($id);
        if ($user) {
            if ($this->duplicatedEmail($user, $request->email)) {
                return $this->createJsonResponse([
                    'message' => 'There is another user already using this email'
                ], 422);
            }
            $user->update($request->all());
            return $this->createJsonResponse([
                'message' => 'The user has been updated', 'id' => $id
            ], 200);
        }
        return $this->doesNotExist($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return $this->createJsonResponse([
                'message' => 'The user has been deleted', 'id' => $id
            ], 200);
        }
        return $this->doesNotExist($id);
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
     * checks if another user is using the same email
     * @param User $user
     * @param $email
     * @return bool
     */
    private function duplicatedEmail(User $user, $email)
    {
        $user2 = User::whereEmail($email)->first();
        if ($user2) {
            if ($user->id == $user2->id) {
                return false;
            }
            return true; // there is another user
        }
        return false;
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