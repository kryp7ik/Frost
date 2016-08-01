<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Ingredient;
use App\Models\Store\Recipe;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\RecipeFormRequest;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::all();
        return view('backend.store.recipes.index', compact('recipes'));
    }

    public function create()
    {

        $ingredients = Ingredient::all();
        return view('backend.store.recipes.create', compact('ingredients'));
    }

    public function store(RecipeFormRequest $request)
    {
        $recipe = new Recipe(array(
            'name' => $request->get('name'),
        ));
        $recipe->save();
        foreach ($request->get('ingredients') as $key => $value) {
            $recipe->ingredients()->attach([$value['ingredient'] => ['amount' => $value['amount']]]);
        }
        return redirect('/admin/store/recipes')->with('status', 'The Recipe has been created successfully');
    }

    public function show($id)
    {
        $recipe = Recipe::whereId($id)->firstOrFail();
        $ingredients = Ingredient::all();
        return view('backend.store.recipes.show', compact('recipe', 'ingredients'));
    }

    public function edit($id)
    {
        $recipe = Recipe::whereId($id)->firstOrFail();
        return view('backend.store.recipes.edit', compact('recipe'));
    }

    public function ajaxUpdate($id, RecipeFormRequest $request)
    {
        $name = $request->get('name');
        $value = $request->get('value');
        if($recipe = Recipe::where('id', $id)->update([$name => $value])) {
            return \Response::json(array('status' => 1));
        } else {
            return \Response::json(array('status' => 1));
        }
    }

    public function update($id, Request $request)
    {
        $recipe = Recipe::whereId($id)->firstOrFail();
        $recipe->active = ($request->get('active')) ? true : false;
        $recipe->save();
        return back();
    }

    /**
     * Removes a single ingredient from a recipe
     * @param int $id The Id of the recipe being modified
     * @param int $iid The id of the ingredient being removed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id, $iid)
    {
        $recipe = Recipe::whereId($id)->firstOrFail();
        $recipe->ingredients()->detach($iid);
        $recipe->save();
        return redirect('/admin/store/recipes/' . $recipe->id . '/show')->with('status', 'The Ingredient has been successfully removed.');
    }

    /**
     * Adds a single ingredient to a recipe.
     * The recipe id is defined in a hidden form input.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $recipe = Recipe::whereId($request->get('recipe'))->firstOrFail();
        $recipe->ingredients()->attach([$request->get('ingredient') => ['amount' => $request->get('amount')]]);
        return redirect('/admin/store/recipes/' . $recipe->id . '/show')->with('status', 'The Ingredient has been successfully added.');
    }
}
