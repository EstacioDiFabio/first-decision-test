<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\UserValidation;

class UserService {

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     */
    public function createUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'min:3','max:50'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'max:20', 'same:password_confirmation'],
        ]);
        if ($validator->fails()) {

            throw new UserValidation($validator->errors());
        }

        return $this->userRepository->create($request->only(['name', 'email', 'password']));
    }
}
