<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Ingredient;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\IngredientFormRequest;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();
        return view('backend.store.ingredients.index', compact('ingredients'));
    }

    public  function store(IngredientFormRequest $request)
    {
        $ingredient = new Ingredient(array(
            'name' => $request->get('name'),
            'vendor' => $request->get('vendor')
        ));
        $ingredient->save();
        return redirect('/admin/store/ingredients')->with('status', 'The Ingredient has been created successfully');
    }

    public function edit($id)
    {
        $ingredient = Ingredient::whereId($id)->firstOrFail();
        return view('backend.store.ingredients.edit', compact('ingredient'));
    }

    public function update($id, IngredientFormRequest $request)
    {
        $ingredient = Ingredient::whereId($id)->firstOrFail();
        $ingredient->name = $request->get('name');
        $ingredient->vendor = $request->get('vendor');
        $ingredient->save();
        return redirect('/admin/store/ingredients')->with('status', 'The Ingredient has been successfully updated');
    }
}
