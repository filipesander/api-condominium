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
     * @OA\Get(
     *     path="/api/owners",
     *     summary="Listar todos os moradores",
     *     description="Retorna uma lista de moradores paginados",
     *     tags={"Owners"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de moradores retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="string", example="1"),
     *                     @OA\Property(property="name", type="string", example="João Silva"),
     *                     @OA\Property(property="cpf", type="string", example="12345678900"),
     *                     @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *                     @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *                     @OA\Property(property="tower", type="string", example="A"),
     *                     @OA\Property(property="apartment_number", type="string", example="101"),
     *                     @OA\Property(property="garage", type="string", example="1"),
     *                     @OA\Property(property="rented", type="boolean", example=true),
     *                     @OA\Property(property="paid", type="boolean", example=true)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Lista de moradores retornada com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $owner = Owner::paginate();
        return response()->json($owner);
    }

    /**
     * @OA\Post(
     *     path="/api/owners",
     *     summary="Cadastrar um morador",
     *     description="Cadastra um morador no sistema",
     *     tags={"Owners"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "cpf", "birth_date", "email", "tower", "apartment_number", "garage", "rented", "paid"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="cpf", type="string", example="12345678900"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *             @OA\Property(property="tower", type="string", example="A"),
     *             @OA\Property(property="apartment_number", type="string", example="101"),
     *             @OA\Property(property="garage", type="string", example="1"),
     *             @OA\Property(property="rented", type="boolean", example=true),
     *             @OA\Property(property="paid", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Morador cadastrado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Morador cadastrado com sucesso!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="name", type="string", example="João Silva"),
     *                 @OA\Property(property="cpf", type="string", example="12345678900"),
     *                 @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *                 @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *                 @OA\Property(property="tower", type="string", example="A"),
     *                 @OA\Property(property="apartment_number", type="string", example="101"),
     *                 @OA\Property(property="garage", type="string", example="1"),
     *                 @OA\Property(property="rented", type="boolean", example=true),
     *                 @OA\Property(property="paid", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="CPF inválido"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao cadastrar morador"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/owners/{id}",
     *     summary="Exibir um morador",
     *     description="Retorna os detalhes de um morador específico",
     *     tags={"Owners"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do morador",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Morador retornado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", example="1"),
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="cpf", type="string", example="12345678900"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *             @OA\Property(property="tower", type="string", example="A"),
     *             @OA\Property(property="apartment_number", type="string", example="101"),
     *             @OA\Property(property="garage", type="string", example="1"),
     *             @OA\Property(property="rented", type="boolean", example=true),
     *             @OA\Property(property="paid", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Morador não encontrado"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $owner = Owner::whereId($id)->first();

        if (empty($owner)) return response()->json(["message" => "Morador não encontrado!"], 404);

        return response()->json($owner);
    }

    /**
     * @OA\Put(
     *     path="/api/owners/{id}",
     *     summary="Atualizar dados de um morador",
     *     description="Atualiza os dados de um morador específico",
     *     tags={"Owners"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do morador",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "cpf", "birth_date", "email", "tower", "apartment_number", "garage", "rented", "paid"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="cpf", type="string", example="12345678900"),
     *             @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="email", type="string", example="joao@exemplo.com"),
     *             @OA\Property(property="tower", type="string", example="A"),
     *             @OA\Property(property="apartment_number", type="string", example="101"),
     *             @OA\Property(property="garage", type="string", example="1"),
     *             @OA\Property(property="rented", type="boolean", example=true),
     *             @OA\Property(property="paid", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Morador atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Dados do Morador atualizados com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Morador não encontrado"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="CPF inválido"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar morador"
     *     )
     * )
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

            return response()->json(["message" => "Dados do Morador atualizado com sucesso"], 200);
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
     * @OA\Delete(
     *     path="/api/owners/{id}",
     *     summary="Excluir um morador",
     *     description="Exclui um morador do sistema",
     *     tags={"Owners"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do morador",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Morador deletado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Morador deletado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Morador não encontrado"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $owner = Owner::whereId($id)->first();

        if (empty($owner)) return response()->json(["message" => "Morador não encontrado!"], 404);

        $owner->delete();

        return response()->json(["message" => "Morador deletado com sucesso!"]);
    }
}
