<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\Ingredient\IngredientRepositoryContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\IngredientFormRequest;

class IngredientController extends Controller
{
    /**
     * @var IngredientRepositoryContract
     */
    protected $ingredients;

    /**
     * IngredientController constructor.
     * @param IngredientRepositoryContract $ingredients
     */
    public function __construct(IngredientRepositoryContract $ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $ingredients = $this->ingredients->getAll();
        return view('backend.store.ingredients.index', compact('ingredients'));
    }

    /**
     * @param IngredientFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public  function store(IngredientFormRequest $request)
    {
        $this->ingredients->create($request->all());
        return redirect('/admin/store/ingredients');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $ingredient = $this->ingredients->findById($id);
        return view('backend.store.ingredients.edit', compact('ingredient'));
    }

    /**
     * @param int $id
     * @param IngredientFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, IngredientFormRequest $request)
    {
        $this->ingredients->update($id, $request->all());
        return redirect('/admin/store/ingredients');
    }
}
