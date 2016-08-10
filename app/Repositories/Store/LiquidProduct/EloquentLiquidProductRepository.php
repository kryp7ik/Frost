<?php

namespace App\Repositories\Store\LiquidProduct;

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
     * @return mixed
     */
    public function getIncompleteWhereStore($store_id)
    {
        return LiquidProduct::
                    where('store', $store_id)
                    ->where('mixed', 0)->get();
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
     * @param int $shop_order_id
     * @param int $store_id
     * @param array $data
     * @return bool
     */
    public function create($shop_order_id, $store_id, $data)
    {
        $liquidProduct = new LiquidProduct([
            'shop_order_id' => $shop_order_id,
            'store' => $store_id,
            'recipe_id' => $data['recipe'],
            'size' => $data['size'],
            'nicotine' => $data['nicotine'],
            'vg' => $data['vg'],
            'menthol' => $data['menthol'],
            'extra' => $data['extra'],
            'mixed' => false
        ]);
        return $liquidProduct->save();
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
        $liquid->delete();
        flash('The Liquid Product has been successfully deleted', 'success');
    }

}