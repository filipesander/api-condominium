<?php

namespace App\Http\Controllers;

use App\Exceptions\PositionException;
use App\Http\Requests\Position\StoreUpdatePositionRequest;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PositionController extends Controller
{
    /**
     * Lista todos os Cargos
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $positions = Position::paginate();

        return response()->json($positions, 200);
    }

    /**
     * Cria um Cargo
     * @param StoreUpdatePositionRequest $request
     * @throws PositionException
     * @return JsonResponse
     */
    public function store(StoreUpdatePositionRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $positionExists = Position::whereTitle($validatedData['title'])->first();

            if ($positionExists) return response()->json(["message" => "Já existe um cargo com esse nome!"], 400);

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
     * Exibe um Cargo
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $position = Position::whereId($id)->first();

        if (empty($position)) return response()->json(["message" => "Esse cargo não foi encontrado!"], 404);

        return response()->json($position, 200);
    }

    /**
     * Atualiza um Cargo
     * @param StoreUpdatePositionRequest $request
     * @param string $id
     * @throws PositionException
     * @return JsonResponse
     */
    public function update(StoreUpdatePositionRequest $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $position = Position::whereId($id)->first();

            if (empty($position)) return response()->json(["message" => "Esse cargo não foi encontrado!"], 404);

            $position->update($validatedData);

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
     * Deleta um Cargo
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $position = Position::whereId($id)->first();

        if (empty($position)) return response()->json(["message" => "Esse cargo não foi encontrado!"], 404);

        $position->delete();
        return response()->json(["message" => "Cargo deletado com sucesso!"], 200);
    }
}