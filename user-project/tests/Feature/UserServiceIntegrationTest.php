<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\UniqueConstraintViolationException;

class UserServiceIntegrationTest extends TestCase {

    use RefreshDatabase;

    protected $userService;

    /**
     * @return void
    */
    protected function setUp(): void {
        parent::setUp();
        $this->userService = app(UserService::class);
    }

    /**
     * @return array
     */
    private function userData(): array {

        return [
            'name' => 'Usuario Novo',
            'email' => 'novo-usuario@example.com',
        ];
    }

    /**
     * @return array
     */
    private function passwordData(): array {

        return [
            'password' => '123456',
            'password_confirmation' => '123456'
        ];
    }

    /**
     * Valida a presença dos dados no retorno do método
     *
     * @return void
     */
    public function testCreateUserPersistsDatabase(): void {
        $data = array_merge($this->userData(), $this->passwordData());
        $request = new Request($data);
        $this->userService->createUser($request);

        /* asserts */
        $this->assertDatabaseHas('users', $this->userData());
    }

     /**
     * Valida a ausência de dados sensíveis
     *
     * @return void
     */
    public function testCreateUserPersistsDatabaseMissingSensiveData(): void {
        $data = array_merge($this->userData(), $this->passwordData());
        $request = new Request($data);
        $this->userService->createUser($request);

        /* asserts */
        $this->assertDatabaseMissing('users', ['password', 'password_confirmation']);
    }

    /**
     * Valida a geração de erros através da Exception UserValidation
     *
     * @return void
     */
    public function testCreateUserFails(): void {
        $request = new Request($this->userData());

        /* assert */
        $this->expectException(\App\Exceptions\UserValidation::class);
        $this->userService->createUser($request);
    }

    /**
     * Valida a geração de erros através da Exception UniqueConstraintViolationException
     *
     * @return void
     */
    public function testCreateUserFailsDuplicatedData(): void {
        $data = array_merge($this->userData(), $this->passwordData());
        $request = new Request($data);
        $this->userService->createUser($request);

        /* assert */
        $this->expectException(UniqueConstraintViolationException::class);
        $this->userService->createUser($request);
    }
}
