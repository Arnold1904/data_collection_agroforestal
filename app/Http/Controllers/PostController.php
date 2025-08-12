<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Services\Post\PostService;

class PostController extends Controller
{
    public function __construct(protected PostService $service)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = $this->service->getAll($request->only('status'));
        return view('posts.index', compact('posts')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.form', ['post'=> new Post()]);
}
/**
 * Store  newly created resource in storage.
 */
    public function store(CreatePostRequest $request)
    // aqui se valida que los datos vienen y se guardan en el store
    {   
        $this->service->create($request->validated());
        
        return redirect()->route('posts.index')->with('message', 'Post creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $post = $this->service->find($id);
        return view('posts.form', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, int $id)
    {
        $this->service->update($id, $request->validated());
        
        return redirect()->route('posts.index')->with('message', 'Post actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->route('posts.index')->with('message', 'Post eliminado exitosamente.');
    }
}
