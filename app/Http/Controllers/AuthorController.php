<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\AuthorRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="AuthorRequest",
 *     required={"name", "last_name", "email"},
 *     @OA\Property(property="name", type="string", example="John", description="Author's name."),
 *     @OA\Property(property="last_name", type="string", example="Doe", description="Author's last name."),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com", description="Author's email address.")
 * ) 
 */
class AuthorController extends Controller
{    
    /**
     * @OA\Get(
     *     tags={"Author"},
     *     path="/api/author",
     *     summary="List all active authors",
     *     description="Returns a list of all active authors in the system.",
     *     @OA\Response(response="200", description="Returns a list of active authors.")
     * )
     */
    public function index()
    {
        $entities = ['authors' => Author::where('active', 1)->get()];
        return response()->json(["success" => true, ...$entities], 200);
    }
    
    /**
     * @OA\Post(
     *     tags={"Author"},
     *     path="/api/author",
     *     summary="Create a new author",
     *     description="Creates a new author with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthorRequest")
     *     ),
     *     @OA\Response(response="200", description="Returns the newly created author.")
     * )
     */
    public function store(AuthorRequest $request): JsonResponse
    {
        $entity = ['author' => Author::create($request->all())];

        if ($entity['author']) {
            return response()->json(["success" => true, ...$entity], 200);
        }
        
        return response()->json(["error" => true, 'message' => ['Author not found!']], 404);
    }

    /**
     * @OA\Get(
     *     tags={"Author"},
     *     path="/api/author/{id}",
     *     summary="Show a specific author",
     *     description="Returns the details of a specific author.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Author ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Returns the author details.")
     * )
     * @OA\Tag(name="Author")
     */
    public function show($id)
    {
        $entity = ['author' => Author::where('active', 1)->find($id)];
        if ($entity['author']) {
            return response()->json(["success" => true, ...$entity], 200);
        }
        
        return response()->json(["error" => true, 'message' => ['Author not found!']], 404);
    }
    
    /**
     * @OA\Put(
     *     tags={"Author"},
     *     path="/api/author/{id}",
     *     summary="Update an author",
     *     description="Updates an existing author with the provided data.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Author ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AuthorRequest")
     *     ),
     *     @OA\Response(response="200", description="Returns the updated author.")
     * )
     * @OA\Tag(name="Author")
     */
    public function update(AuthorRequest $request, $id)
    {
        $entity = ['author' => Author::where('active', 1)->findOrFail($id)];

        if ($entity['author']) {
            $entity['author']->fill($request->all());
            $entity['author']->save();
    
            return response()->json(["success" => true, ...$entity], 200);
        }

        return response()->json(["error" => true, 'message' => ['Author not found!']], 404);
    }

    /**
     * @OA\Delete(
     *     tags={"Author"},
     *     path="/api/author/{id}",
     *     summary="Delete an author",
     *     description="Deletes an author from the system.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Author ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Successfully deleted the author.")
     * )
     * @OA\Tag(name="Author")
     */
    public function destroy($id)
    {
        $entity = ['author' => Author::where('active', 1)->findOrFail($id)];
    
        if ($entity['author']) {
            $entity['author']->active = 0;
            $entity['author']->save();
    
            return response()->json(["success" => true], 204);
        }
    
        return response()->json(["error" => true, 'message' => ['Author not found!']], 404);
    }
}
