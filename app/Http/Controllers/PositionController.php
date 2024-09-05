<?php

namespace App\Http\Controllers;

use App\Exceptions\PositionException;
use App\Http\Requests\StoreUpdatePositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;
use Throwable;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::all(['id', 'title']);

        return response()->json($positions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdatePositionRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $positionExists = Position::whereTitle($validatedData['title'])->first();

            if ($positionExists) return response()->json(["message" => "Já existe um cargo com esse nome!"], 400);

            Position::create([
                "title" => $validatedData['title']
            ]);

            return response()->json([
                "message" => "Cargo criado com sucesso"
            ], 201);
        } catch (Throwable $e) {
            report($e);

            throw new PositionException(
                message: "Erro ao criar um cargo",
                description: $e->getMessage(),
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $position = Position::whereId($id)->first();

        if (!$position) return response()->json(["message" => "Esse cargo não foi encontrado!"], 400);


        return response()->json($position);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdatePositionRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            $position = Position::whereId($id)->first();

            if (!$position) return response()->json(["message" => "Esse cargo não foi encontrado!"], 400);

            $position->update($validatedData);

            return response()->json([
                "message" => "Cargo atualizado com sucesso"
            ], 200);
        } catch (Throwable $e) {
            report($e);

            throw new PositionException(
                message: "Erro ao atualizar um cargo",
                description: $e->getMessage()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = Position::whereId($id)->first();

        if (!$position) return response()->json(["message" => "Esse cargo não foi encontrado!"], 400);

        $position->delete();

        return response()->json(["message" => "Cargo deletado com sucesso!"]);
    }
}