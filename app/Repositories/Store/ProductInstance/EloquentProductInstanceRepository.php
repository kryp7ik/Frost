<?php

namespace App\Repositories\Store\ProductInstance;

use App\Models\Store\ProductInstance;

class EloquentProductInstanceRepository implements ProductInstanceRepositoryContract
{

    /**
     * Retrieves all active Product Instances that belong to the designated store
     * @param int $store The id of the store belonging to the current user
     * @param bool $sorted If true returns a sorted array by category for usage in optgroups [$category][]['instance_id' => $id, 'name' => $instance->product->name]
     * @return array
     */
    public function getActiveWhereStore($store, $sorted = false)
    {
        $productInstances = ProductInstance::
            where('store', $store)
            ->where('active', 1)->get();
        if ($sorted) {
            return $this->sortInstances($productInstances);
        } else {
            return $productInstances;
        }
    }

    /**
     * Sorts ProductInstances into an array by category for usage in optgroups [$category][]['instance_id' => $id, 'name' => $instance->product->name]
     * @param array $instances
     * @return array
     */
    private function sortInstances($instances)
    {
        $sortedInstances = array();
        foreach ($instances as $instance){
            $sortedInstances[$instance->product->categoriesArray[$instance->product->category]][] = array('instance_id' => $instance->id, 'name' => $instance->product->name);
        }
        return $sortedInstances;

    }

    /**
     * @param int $id
     * @return ProductInstance|boolean
     */
    public function findById($id)
    {
        return ProductInstance::where('id', $id)->firstOrFail();
    }

    /**
     * @param int $product_id
     * @param int $store_id
     * @param array $data
     * @return ProductInstance|bool
     */
    public function create($product_id, $store_id, $data)
    {
        $instance = new ProductInstance(array(
            'price' => number_format($data['price'], 2),
            'stock' => $data['stock'],
            'redline' => $data['redline'],
            'store' => $store_id,
            'active' => true,
            'product_id' => $product_id,
        ));
        if($instance->save()) {
            flash('The product instance has been created successfully', 'success');
            return $instance;
        }
        flash('Something went wrong while trying to create a new product instance', 'danger');
        return false;
    }

    /**
     * @param int $id
     * @param array $data
     * @return ProductInstance
     */
    public function update($id, $data)
    {
        $instance = ProductInstance::where('id', $id)->firstOrFail();
        $instance->price = number_format($data['price'], 2);
        $instance->stock = $data['stock'];
        $instance->redline = $data['redline'];
        $instance->active = (isset($data['active'])) ? true : false;
        if($instance->save()) {
            flash('The product instance has been updated successfully', 'success');
            return $instance;
        }
        flash('Something went wrong while trying to update the product instance', 'danger');
        return $instance;
    }

}