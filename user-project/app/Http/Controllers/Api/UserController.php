<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    protected $userService;

    public function __construct(UserService $userService) {

        $this->userService = $userService;
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'min:3','max:50'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:20', 'same:password_confirmation'],
        ]);
        $this->validateFields($validator);

        try {
            $user = $this->userService->createUser($request->only(['name', 'email', 'password']));
        } catch (\Exception $e) {
            $validator->after(function ($validator) use ($e) {
                $validator->errors()->add(
                    'error', $e->getMessage()
                );
            });
        }
        if ($validator->fails()) {

            return $this->returnErrors($validator->errors());
        }

        return $this->returnSuccess($user);
    }

    private function validateFields(object $validator): object {
        if ($validator->fails()) {

            return $this->returnErrors($validator->errors());
        }

        return $validator;
    }

    private function returnErrors(object $errors): object {

        return response()->json([
            'errors' => $errors
        ], 422);
    }

    private function returnSuccess(object $userData): object {

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'data'    => $userData
        ], 201);
    }
}
