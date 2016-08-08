<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\Ingredient\IngredientRepositoryContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\IngredientFormRequest;

class IngredientController extends Controller
{
    protected $ingredients;

    public function __construct(IngredientRepositoryContract $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    public function index()
    {
        $ingredients = $this->ingredients->getAll();
        return view('backend.store.ingredients.index', compact('ingredients'));
    }

    public  function store(IngredientFormRequest $request)
    {
        $this->ingredients->create($request->all());
        return redirect('/admin/store/ingredients');
    }

    public function edit($id)
    {
        $ingredient = $this->ingredients->findById($id);
        return view('backend.store.ingredients.edit', compact('ingredient'));
    }

    public function update($id, IngredientFormRequest $request)
    {
        $this->ingredients->update($id, $request->all());
        return redirect('/admin/store/ingredients');
    }
}
