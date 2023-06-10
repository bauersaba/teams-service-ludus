<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class TeamController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/team",
     *     operationId="getTeams",
     *     tags={"Teams"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Get list of teams",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Team")
     *         ),
     *     ),
     * )
    */
    public function index()
    {
        $teams = Team::with('coach')->get();
        return response()->json($teams);
    }

    /**
     * @OA\Get(
     *     path="/api/team/{id}",
     *     operationId="getTeamById",
     *     tags={"Teams"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Find team by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of team to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Team"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Team not found",
     *     ),
     * )
    */
    public function show($id)
    {
        try {
            $team = Team::with('coach')->withTrashed()->findOrFail($id);
            return response()->json($team);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Esse time não foi encontrado: ' . $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/team",
     *     operationId="createTeam",
     *     tags={"Teams"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Create a new team",
     *     @OA\RequestBody(
     *         description="Team object to be created",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Team"),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Team created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Team"),
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
            'coach_id' => 'required',
            'name_club' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag()], 422);
        }

        try {
            $team = Team::create($request->all());
            return response()->json($team, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao inserir esse time: ' . $e->getMessage()], 422);
        }
    }

    /**
    * @OA\Put(
    *     path="/api/team/{id}",
    *     operationId="updateTeam",
    *     tags={"Teams"},
    *     security={{ "bearerAuth": {} }},
    *     summary="Update an existing team",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID of team to update",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\RequestBody(
    *         description="Team object to be updated",
    *         required=true,
    *         @OA\JsonContent(ref="#/components/schemas/Team"),
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Team updated successfully",
    *         @OA\JsonContent(ref="#/components/schemas/Team"),
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Team not found",
    *     ),
    * )
    */
    public function update(Request $request, $id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->fill($request->all());
            $team->save();
            return response()->json($team, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao atualizar esse time: ' . $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/team/{id}",
     *     operationId="deleteTeam",
     *     tags={"Teams"},
     *     security={{ "bearerAuth": {} }},
     *     summary="Delete a team",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of team to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Team deleted successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Team not found",
     *     ),
     * )
    */
    public function destroy($id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao deletar esse time: ' . $e->getMessage()], 404);
        }
    }
}
