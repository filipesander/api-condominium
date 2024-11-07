<?php

namespace App\Http\Controllers;

use App\Exceptions\OwnerException;
use App\Helpers\FormatHelper;
use App\Http\Requests\Owner\StoreOwnerRequest;
use App\Http\Requests\Owner\UpdateOwnerRequest;
use App\Models\Owner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class OwnerController extends Controller
{
    /**
     * Lista todos os Moradores
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $owner = Owner::paginate();

        return response()->json($owner);
    }

    /**
     * Cadastro Morador
     * @param StoreOwnerRequest $request
     * @throws OwnerException
     * @return JsonResponse
     */
    public function store(StoreOwnerRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            if (FormatHelper::isValidCpf($validatedData['cpf']) == false) {
                return response()->json(["message" => "CPF informado inválido!"], 400);
            }

            $owner = Owner::create([
                'name' => $validatedData['name'],
                'cpf' => $validatedData['cpf'],
                'number' => $validatedData['number'],
                'birth_date' => $validatedData['birth_date'],
                'email' => $validatedData['email'],
                'tower' => $validatedData['tower'],
                'apartment_number' => $validatedData['apartment_number'],
                'garage' => $validatedData['garage'],
                'rented' => $validatedData['rented'],
                'paid' => $validatedData['paid'],
            ]);

            return response()->json([
                'message' => 'Morador cadastrado com sucesso!',
                'data' => $owner,
            ], 201);
        } catch (Throwable $e) {
            report($e);

            throw new OwnerException(
                message: 'Não foi possível cadastrar um morador',
                description: $e->getMessage(),
                status: 500
            );
        }
    }

    /**
     * Exibe um Morador
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $owner = Owner::whereId($id)->first();

        if (empty($owner)) return response()->json(["message" => "Morador não encontrado!"], 404);

        return response()->json($owner);
    }

    /**
     * Atualiza dados de um Morador
     * @param UpdateOwnerRequest $request
     * @param string $id
     * @throws OwnerException
     * @return JsonResponse
     */
    public function update(UpdateOwnerRequest $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $owner = Owner::whereId($id)->first();

            if (empty($owner)) return response()->json(["message" => "Morador não encontrado!"], 404);

            if (FormatHelper::isValidCpf($validatedData['cpf']) == false) {
                return response()->json(["message" => "CPF informado inválido!"], 400);
            }

            $owner->update($validatedData);

            return response()->json(["message" => "Dados do Funcionário atualizado com sucesso"], 200);
        } catch (Throwable $e) {
            report($e);

            throw new OwnerException(
                message: "Não foi possível atualizar o morador",
                description: $e->getMessage(),
                status: 500
            );
        }
    }

    /**
     * Exclui Morador
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $owner = Owner::whereId($id)->first();

        if (empty($owner)) return response()->json(["message" => "Morador não encontrado!"], 404);

        $owner->delete();

        return response()->json(["message" => "Morador deletado com sucesso!"]);
    }
}