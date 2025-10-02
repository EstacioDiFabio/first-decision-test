<?php

namespace App\Http\Controllers\Web;

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
     *
     */
    public function create() {

        return view('user');
    }

    /**
     * @param Request $request
     */
    public function store(Request $request) {
        try {
            $this->userService->createUser($request);

            return redirect()->route('create')
                ->with('success', 'Usuário cadastrado com sucesso!');
        } catch (UserValidation $e) {
            return back()->withErrors($e->getErrors())->withInput();
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
