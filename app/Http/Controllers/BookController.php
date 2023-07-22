<?php

namespace App\Http\Controllers;

use App\Events\BookNotificationEvent;

use App\Models\Book;
use App\Http\Requests\BookRequest;
use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;

/**
 * @OA\Schema(
 *     schema="BookRequest",
 *     required={"name", "description", "price", "idAuthor"},
 *     @OA\Property(property="name", type="string", example="Book A", description="Name of the book."),
 *     @OA\Property(property="description", type="string", example="Description of the book.", description="Description of the book."),
 *     @OA\Property(property="price", type="number", format="float", example="29.99", description="Price of the book."),
 *     @OA\Property(property="idAuthor", type="integer", example="1", description="ID of the book's author.")
 * )
 */
class BookController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Book"},
     *     path="/api/book",
     *     summary="List all books",
     *     description="Returns a list of all books in the system.",
     *     @OA\Response(response="200", description="Returns a list of books.")
     * )
     */
    public function index()
    {
        $entities = ['books' => Book::where('active', 1)->get()];
        return response()->json(["success" => true, ...$entities], 200);
    }


    /**
     * @OA\Post(
     *     tags={"Book"},
     *     path="/api/book",
     *     summary="Create a new book",
     *     description="Creates a new book with the provided data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BookRequest")
     *     ),
     *     @OA\Response(response="200", description="Returns the newly created book.")
     * )
     */
    public function store(BookRequest $request): JsonResponse
    {
        $entity = ['book' => Book::create($request->all())];

        if ($entity['book']) 
        {
            $notification = $entity['book']->getCrateNotification();
            event(new BookNotificationEvent($notification));            
            return response()->json(["success" => true, ...$entity], 200);
        }

        return response()->json(["error" => true, 'message' => ['Book not found!']], 404);
    }

    /**
     * @OA\Get(
     *     tags={"Book"},
     *     path="/api/book/{id}",
     *     summary="Show a specific book",
     *     description="Returns the details of a specific book.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Returns the book details.")
     * )
     */
    public function show($id)
    {
        $entity = ['book' => Book::with('author')->where('active', 1)->find($id)];
        if ($entity['book']) {
            return response()->json(["success" => true, ...$entity], 200);
        }

        return response()->json(["error" => true, 'message' => ['Book not found!']], 404);
    }

    /**
     * @OA\Put(
     *     tags={"Book"},
     *     path="/api/book/{id}",
     *     summary="Update a book",
     *     description="Updates an existing book with the provided data.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BookRequest")
     *     ),
     *     @OA\Response(response="200", description="Returns the updated book.")
     * )
     */
    public function update(BookRequest $request, $id)
    {
        $entity = ['book' => Book::where('active', 1)->findOrFail($id)];

        if ($entity['book']) {
            $entity['book']->fill($request->all());
            $entity['book']->save();

            $notification = $entity['book']->getUpdateNotification();
            event(new BookNotificationEvent($notification));            
            return response()->json(["success" => true, ...$entity], 200);
        }

        return response()->json(["error" => true, 'message' => ['Book not found!']], 404);
    }

    /**
     * @OA\Delete(
     *     tags={"Book"},
     *     path="/api/book/{id}",
     *     summary="Delete a book",
     *     description="Deletes a book from the system.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Book ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="204", description="Successfully deleted the book.")
     * )
     */
    public function destroy($id)
    {
        $entity = ['book' => Book::where('active', 1)->findOrFail($id)];

        if ($entity['book']) {
            $entity['book']->active = 0;
            $entity['book']->save();

            return response()->json(["success" => true], 204);
        }

        return response()->json(["error" => true, 'message' => ['Book not found!']], 404);
    }
}
