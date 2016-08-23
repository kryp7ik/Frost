<?php

namespace App\Repositories\Store\Product;

use App\Models\Store\Product;

class EloquentProductRepository implements ProductRepositoryContract
{

    public function getAll()
    {
        return Product::all();
    }

    public function findById($id)
    {
        return Product::where('id', $id)->firstOrFail();
    }

    public function create($data)
    {
        $product = new Product($data);
        if($product->save()) {
            flash('The product has been created successfully', 'success');
            return $product;
        }
        flash('Something went wrong while trying to create a new product', 'danger');
        return false;
    }

    public function update($id, $data)
    {
        $product = Product::whereId($id)->firstOrFail();
        $product->name = $data['name'];
        $product->cost = $data['cost'];
        $product->sku = $data['sku'];
        $product->category = $data['category'];
        if($product->save()) {
            flash('The product has been updated successfully', 'success');
            return $product;
        }
        flash('Something went wrong while trying to update the product', 'danger');
        return $product;
    }

    public function updateField($id, $fieldName, $value)
    {
        if($product = Product::where('id', $id)->update([$fieldName => $value])){
            flash('The products ' . ucfirst($fieldName) . ' has been updated', 'success');
            return true;
        }
        flash('Something went wrong while trying to update the products' . ucfirst($fieldName), 'danger');
        return false;
    }
}