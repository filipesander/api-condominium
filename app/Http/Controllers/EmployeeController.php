<?php

namespace App\Http\Controllers;

use App\Exceptions\EmployeeException;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class EmployeeController extends Controller
{
    /**
     * Listagem de funcionários
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $employee = Employee::all();

        return response()->json($employee);
    }


    /**
     * Cadastra um funcionário
     * @param StoreEmployeeRequest $request
     * @throws EmployeeException
     * @return JsonResponse
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $employee = Employee::create([
                'name' => $validatedData['name'],
                'age' => $validatedData['age'],
                'salary' => $validatedData['salary'],
                'date_dismissal' => $validatedData['date_dismissal'],

            ]);

            return response()->json([
                "message" => "Funcionário cadastrado com sucesso!",
                "employee" => $employee
            ], 201);
        } catch (Throwable $e) {
            report($e);

            throw new EmployeeException(
                message: 'Não foi possível cadastrar um funcionário',
                description: $e->getMessage(),
                status: 500
            );
        }
    }

    /**
     * Retorna um funcionário específico
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $employee = Employee::whereId($id)->first();

        if (!$employee) return response()->json(["message" => "Funcionário não encontrado!"], 404);

        return response()->json($employee);
    }


    /**
     * Atualiza um funcionário
     * @param UpdateEmployeeRequest $request
     * @param string $id
     * @throws EmployeeException
     * @return JsonResponse
     */
    public function update(UpdateEmployeeRequest $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $employee = Employee::whereId($id)->first();

            if (!$employee) return response()->json(["message" => "Funcionário não encontrado!"], 404);

            $employee->update($validatedData);

            return response()->json(["message" => "Dados do Funcionário atualizado com sucesso"], 200);
        } catch (Throwable $e) {
            report($e);

            throw new EmployeeException(
                message: 'Não foi possível atualizar um os dados de um funcionário!',
                description: $e->getMessage(),
                status: 500
            );
        }
    }

    /**
     * Deleta um funcionário
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $employee = Employee::whereId($id)->first();

        if (!$employee) return response()->json(["message" => "Funcionário não encontrado!"], 404);

        $employee->delete();

        return response()->json(["message" => "Funcionário deletado com sucesso!"]);
    }
}