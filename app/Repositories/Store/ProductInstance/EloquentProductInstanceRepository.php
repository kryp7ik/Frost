<?php

namespace App\Repositories\Store\ProductInstance;

use App\Models\Store\ProductInstance;

class EloquentProductInstanceRepository implements ProductInstanceRepositoryContract
{

    /**
     * Retrieves all active Product Instances that belong to the designated store
     * @param int $store The id of the store belonging to the current user
     * @param bool $sorted If true returns a sorted array by category for usage in optgroups
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
            $sortedInstances[$instance->product->categoriesArray[$instance->product->category]][] =
                array('instance_id' => $instance->id, 'name' => $instance->product->name);
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
     * Returns all products where the stock is equal to or lower than redline
     * Optionally filter by a specific store default returns all.
     * @param int $store
     * @return mixed
     */
    public function getBelowRedline($store = 0)
    {
        $products = ProductInstance::where('stock', '<=', 'redline');
        if ($store > 0) $products->where('store', $store);
        return $products->get();
    }

    /**
     * @param int $store_id
     * @param array $data
     * @return ProductInstance|bool
     */
    public function create($store_id, $data)
    {
        $instance = new ProductInstance(array(
            'price' => number_format($data['price'], 2),
            'stock' => $data['stock'],
            'redline' => $data['redline'],
            'store' => $store_id,
            'active' => true,
            'product_id' => $data['product'],
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
        $instance = $this->findById($id);
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

    /**
     * Adjust the stock quantity of a ProductInstance by a positive or negative value
     * @param ProductInstance $product
     * @param int $adjustment
     */
    public function updateStock(ProductInstance $product, $adjustment)
    {
        $product->stock += $adjustment;
        $product->save();
    }

}