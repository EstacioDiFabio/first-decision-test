<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository {

    /**
     * @var User
     */
    protected $model;

    public function __construct(User $model) {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return boolean
     */
    public function create(array $data): bool {
        $data['password'] = Hash::make($data['password']);

        return $this->model->fill($data)->save();
    }

    /**
     * @return array
     */
    public function obtain(): array {

        return $this->model->all()->toArray();
    }
}
