<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     title="Documentação API - Teams",
 *     version="1.0.0",
 *     description="Microservice Teams",
 * )
 */

class CoachController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/coach",
     *     operationId="getCoaches",
     *     tags={"Coaches"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Get list of coaches",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Coach")
     *         ),
     *     ),
     * )
    */
    public function index()
    {
        $coaches = Coach::selectRaw('*, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age')->get();
        return response()->json($coaches);
    }

    /**
     * @OA\Get(
     *     path="/api/coach/{id}",
     *     operationId="getCoachById",
     *     tags={"Coaches"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Find coach by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of coach to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Coach"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coach not found",
     *     ),
     * )
    */
    public function show($id)
    {
        $coach = Coach::withTrashed()->selectRaw('*, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age')->findOrFail($id);
        return response()->json($coach);
    }

    /**
     * @OA\Post(
     *     path="/api/coach",
     *     operationId="createCoach",
     *     tags={"Coaches"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Create a new coach",
     *     @OA\RequestBody(
     *         description="Coach object to be created",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Coach"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Coach created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Coach"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable entity",
     *     ),
     * )
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:60',
            'dob' => 'nullable|date',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag()], 422);
        }

        try {
            $coach = Coach::create($request->all());
            return response()->json($coach, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao criar o coach: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/coach/{id}",
     *     operationId="updateCoach",
     *     tags={"Coaches"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Update an existing coach",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of coach to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Coach object to be updated",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Coach"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coach updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Coach"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coach not found",
     *     ),
     * )
    */
    public function update(Request $request, $id)
    {
        try {
            $coach = Coach::findOrFail($id);
            $coach->fill($request->all());
            $coach->save();
            return response()->json($coach, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao atualizar o coach: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/coach/{id}",
     *     operationId="deleteCoach",
     *     tags={"Coaches"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Delete a coach",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of coach to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Coach marked as deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Coach not found",
     *     ),
     * )
    */
    public function destroy($id)
    {
        try {
            $coach = Coach::findOrFail($id);
            $coach->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao deletar esse coach: ' . $e->getMessage()], 500);
        }
    }
}
