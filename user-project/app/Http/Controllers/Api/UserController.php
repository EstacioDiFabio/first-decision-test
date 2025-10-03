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

    /**
     * @param Request $requeset
     */
    public function store(Request $request) {
        try {
            $this->userService->createUser($request);
        } catch (UserValidation $e) {

            return $this->returnValidationErors($e->getErrors());
        } catch (Exception $e) {

            return $this->returnErrors($e->getMessage());
        }

        return $this->returnSuccess();
    }

    /**
     * @return object
     */
    public function obtain(): object {
        try {
            $users = $this->userService->obtainUsers();
        } catch (Exception $e) {

            return $this->returnErrors($e->getMessage());
        }

        return $this->returnSuccessData($users);
    }
}
