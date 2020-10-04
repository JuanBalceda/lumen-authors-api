<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthorController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return author list
     * @return JsonResponse
     */
    public function index()
    {
        $authors = Author::all();

        return $this->successResponse($authors);
    }

    /**
     * Create an instance of Author
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'gender' => 'required|max:255|in:male,female',
            'country' => 'required|max:255',
        ];

        $this->validate($request, $rules);

        $author = Author::create($request->all());

        return $this->successResponse($author, Response::HTTP_CREATED);
    }

    /**
     * Return an instance of Author
     * @param int $idAuthor
     * @return JsonResponse
     */
    public function show($idAuthor)
    {

        $author = Author::findOrFail($idAuthor);

        return $this->successResponse($author);
    }

    /**
     * Update an specific author
     * @param Request $request
     * @param int $idAuthor
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $idAuthor)
    {
        $rules = [
            'name' => 'max:255',
            'gender' => 'max:255|in:male,female',
            'country' => 'max:255',
        ];

        $this->validate($request, $rules);

        $author = Author::findOrFail($idAuthor);
        $author->fill($request->all());

        if ($author->isClean()) {
            return $this->errorResponse('at least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $author->save();

        return $this->successResponse($author);
    }

    /**
     * Delete an instance of Author
     * @param int $idAuthor
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($idAuthor)
    {
        $author = Author::findOrFail($idAuthor);

        $author->delete();

        return $this->successResponse($author);
    }
}
