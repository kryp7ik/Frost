<?php

namespace App\Repositories\Store\LiquidProduct;

use App\Events\LiquidProductCreated;
use App\Events\LiquidProductDeleted;
use App\Models\Store\LiquidProduct;

class EloquentLiquidProductRepository implements LiquidProductRepositoryContract
{

    public function getAll()
    {
        return LiquidProduct::all();
    }

    /**
     * Returns all LiquidProducts that belong to the designated store and have not been mixed (completed)
     * @param int $store_id
     * @param bool $mutate If true sort results in a new array to use on touch screen
     * @return mixed
     */
    public function getIncompleteWhereStore($store_id, $mutate = false)
    {
        $liquids = LiquidProduct::
        where('store', $store_id)
            ->where('mixed', 0)->get();
        if($mutate) {
            $sorted = [];
            foreach ($liquids as $liquid) {
                $sorted[] = [
                    'id' => $liquid->id,
                    'recipe_id' => $liquid->recipe->id,
                    'recipe' => $liquid->recipe->name,
                    'store' => $liquid->store,
                    'size' => $liquid->size,
                    'shop_order_id' => $liquid->shopOrder->id,
                    'nicotine' => $liquid->nicotine,
                    'extra' => $liquid->extra,
                    'menthol' => $liquid->menthol,
                    'vg' => $liquid->vg,
                ];
            }
            return $sorted;
        } else {
            return $liquids;
        }
    }
    /**
     * @param int $id
     * @return LiquidProduct|boolean
     */
    public function findById($id)
    {
        return LiquidProduct::where('id', $id)->firstOrFail();
    }

    /**
     * Saves a new LiquidProduct to the database.
     * Returns the model on success or false on fail
     * @param int $shop_order_id
     * @param int $store_id
     * @param array $data
     * @return LiquidProduct|bool
     */
    public function create($shop_order_id, $store_id, $data)
    {
        if ($data['recipe'] == 0) return false;
        $liquidProduct = new LiquidProduct([
            'shop_order_id' => $shop_order_id,
            'store' => $store_id,
            'recipe_id' => $data['recipe'],
            'size' => $data['size'],
            'nicotine' => $data['nicotine'],
            'vg' => $data['vg'],
            'menthol' => $data['menthol'],
            'extra' => (isset($data['extra'])) ? true : false,
            'mixed' => false
        ]);
        if ($liquidProduct->save()) {
            event(new LiquidProductCreated($liquidProduct));
            return $liquidProduct;
        } else return false;

    }

    /**
     * @param int $id
     * @param string $field_name
     * @param mixed $value
     * @return mixed
     */
    public function updateField($id, $field_name, $value)
    {
        if($liquid = LiquidProduct::where('id', $id)->update([$field_name => $value])) {
            flash('The Liquid Product has been updated successfully', 'success');
            return $liquid;
        }
        flash('Something went wrong while trying to update the Liquid Product', 'danger');
        return $liquid;
    }

    /**
     * @param int $id
     */
    public function delete($id) {
        $liquid = $this->findById($id);
        if ($liquid) {
            event(new LiquidProductDeleted($liquid));
            $liquid->delete();
            flash('The Liquid Product has been successfully deleted', 'success');
        }
    }

}