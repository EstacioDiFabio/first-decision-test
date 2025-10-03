<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function returnValidationErors($errors): object {

        return response()->json([
            'validationErrors' => json_decode($errors)
        ], 422);
    }

    protected function returnErrors(string $errors): object {

        return response()->json([
            'errors' => $errors
        ], 422);
    }

    protected function returnSuccess(): object {

        return response()->json([
            'message' => 'Usuário criado com sucesso!'
        ], 201);
    }

    protected function returnSuccessData($data) {

        return response()->json([
            'data' => $data
        ]);
    }
}
