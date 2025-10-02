<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Exceptions\UserValidation;
use Exception;

class UserController extends Controller {

    protected $userService;

    public function __construct(UserService $userService) {

        $this->userService = $userService;
    }

    public function store(Request $request) {
        try {
            $user = $this->userService->createUser($request);
        } catch (UserValidation $e) {

            return $this->returnValidationErors($e->getMessage());
        } catch (Exception $e) {

            return $this->returnErrors($e->getMessage());
        }

        return $this->returnSuccess($user);
    }

    private function returnValidationErors(string $errors): object {

        return response()->json([
            'validationErrors' => $errors
        ], 422);
    }

    private function returnErrors(string $errors): object {

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
