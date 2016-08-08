<?php

namespace App\Repositories\Store\Ingredient;


use App\Models\Store\Ingredient;

class EloquentIngredientRepository implements IngredientRepositoryContract
{

    public function getAll()
    {
        return Ingredient::all();
    }

    public function findById($id)
    {
        return Ingredient::where('id', $id)->firstOrFail();
    }

    public function create($data)
    {
        $ingredient = new Ingredient(array(
            'name' => $data['name'],
            'vendor' => $data['vendor'],
        ));
        if($ingredient->save()) {
            flash('The ingredient has been created successfully', 'success');
            return true;
        }
        flash('Something went wrong while trying to create a new ingredient', 'danger');
        return false;
    }

    public function update($id, $data)
    {
        $ingredient = Ingredient::where('id', $id);
        $ingredient->name = $data['name'];
        $ingredient->vendor = $data['vendor'];
        if($ingredient->save()) {
            flash('The ingredient has been created successfully', 'success');
            return true;
        }
        flash('Something went wrong while trying to update the ingredient', 'danger');
        return false;
    }
}