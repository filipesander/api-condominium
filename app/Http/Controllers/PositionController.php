<?php

namespace App\Http\Controllers;

use App\Exceptions\PositionException;
use App\Http\Requests\Position\StoreUpdatePositionRequest;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Throwable;

class PositionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/positions",
     *     summary="Listar todos os cargos",
     *     description="Retorna uma lista de cargos paginados",
     *     tags={"Positions"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cargos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="string", example="1"),
     *                     @OA\Property(property="title", type="string", example="Manager"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-08T12:34:56Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-08T12:34:56Z")
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Lista de cargos retornada com sucesso")
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
        $positions = Position::paginate();
        return response()->json([
            "message" => "Lista de cargos retornada com sucesso",
            "data" => $positions
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/positions",
     *     summary="Criar um novo cargo",
     *     description="Cria um cargo e retorna os detalhes do novo cargo",
     *     tags={"Positions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Manager")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cargo criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cargo criado com sucesso!"),
     *             @OA\Property(property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="string", example="1"),
     *                 @OA\Property(property="title", type="string", example="Manager"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-08T12:34:56Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-08T12:34:56Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Já existe um cargo com esse nome!"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar um cargo"
     *     )
     * )
     */
    public function store(StoreUpdatePositionRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $positionExists = Position::whereTitle($validatedData['title'])->first();

            if ($positionExists) {
                return response()->json(["message" => "Já existe um cargo com esse nome!"], 400);
            }

            $position = Position::create([
                "title" => $validatedData['title']
            ]);

            return response()->json([
                "message" => "Cargo criado com sucesso!",
                "data" => $position
            ], 201);
        } catch (Throwable $e) {
            report($e);

            throw new PositionException(
                message: "Erro ao criar um cargo",
                description: $e->getMessage(),
                status: 500
            );
        }
    }

    /**
     * @OA\Get(
     *     path="/api/positions/{id}",
     *     summary="Exibir detalhes de um cargo",
     *     description="Retorna os detalhes de um cargo específico",
     *     tags={"Positions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cargo",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo retornado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string", example="1"),
     *             @OA\Property(property="title", type="string", example="Manager"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-08T12:34:56Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-08T12:34:56Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cargo não encontrado"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $position = Position::whereId($id)->first();

        if (empty($position)) {
            return response()->json(["message" => "Esse cargo não foi encontrado!"], 404);
        }

        return response()->json($position, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/positions/{id}",
     *     summary="Atualizar um cargo",
     *     description="Atualiza as informações de um cargo específico",
     *     tags={"Positions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cargo",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Manager")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cargo atualizado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cargo não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao atualizar um cargo"
     *     )
     * )
     */
    public function update(StoreUpdatePositionRequest $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $position = Position::whereId($id)->first();

            if (empty($position)) {
                return response()->json(["message" => "Esse cargo não foi encontrado!"], 404);
            }

            $position->update([
                "title" => $validatedData['title']
            ]);

            return response()->json([
                "message" => "Cargo atualizado com sucesso!"
            ], 200);
        } catch (Throwable $e) {
            report($e);

            throw new PositionException(
                message: "Erro ao atualizar um cargo",
                description: $e->getMessage(),
                status: 500
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/positions/{id}",
     *     summary="Deletar um cargo",
     *     description="Deleta um cargo específico",
     *     tags={"Positions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do cargo",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cargo deletado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Cargo deletado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cargo não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao deletar o cargo"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $position = Position::whereId($id)->first();

            if (empty($position)) {
                return response()->json(["message" => "Esse cargo não foi encontrado!"], 404);
            }

            $position->delete();

            return response()->json([
                "message" => "Cargo deletado com sucesso!"
            ], 200);
        } catch (Throwable $e) {
            report($e);

            throw new PositionException(
                message: "Erro ao deletar o cargo",
                description: $e->getMessage(),
                status: 500
            );
        }
    }
}