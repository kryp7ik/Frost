<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\Ingredient\IngredientRepositoryContract;
use App\Repositories\Store\Recipe\RecipeRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\RecipeFormRequest;

class RecipeController extends Controller
{
    protected $recipes;

    protected $ingredients;

    public function __construct(RecipeRepositoryContract $recipes, IngredientRepositoryContract $ingredients)
    {
        $this->recipes = $recipes;
        $this->ingredients = $ingredients;

    }

    public function index()
    {
        $recipes = $this->recipes->getAll();
        return view('backend.store.recipes.index', compact('recipes'));
    }

    public function create()
    {

        $ingredients = $this->ingredients->getAll();
        return view('backend.store.recipes.create', compact('ingredients'));
    }

    public function store(RecipeFormRequest $request)
    {
        $this->recipes->create($request->all());
        return redirect('/admin/store/recipes');
    }

    public function show($id)
    {
        $recipe = $this->recipes->findById($id);
        $ingredients = $this->ingredients->getAll();
        return view('backend.store.recipes.show', compact('recipe', 'ingredients'));
    }

    public function edit($id)
    {
        $recipe = $this->recipes->findById($id);
        return view('backend.store.recipes.edit', compact('recipe'));
    }

    public function ajaxUpdate($id, RecipeFormRequest $request)
    {
        if($this->recipes->updateField($id, $request->get('name'), $request->get('value'))) {
            return \Response::json(array('status' => 1));
        } else {
            return \Response::json(array('status' => 0));
        }
    }

    public function update($id, Request $request)
    {
        $this->recipes->updateField($id, 'active', $request->get('active'));
        return back();
    }

    /**
     * Removes a single ingredient from a recipe
     * @param int $id The ID of the recipe being modified
     * @param int $iid The ID of the ingredient being removed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id, $iid)
    {
        $this->recipes->removeIngredient($id, $iid);
        return back();
    }

    /**
     * Adds a single ingredient to a recipe.
     * The recipe id is defined in a hidden form input 'recipe'.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $recipe = $this->recipes->findById($request->get('recipe'));
        $this->recipes->addIngredient($recipe, $request->all());
        return back();
    }
}
