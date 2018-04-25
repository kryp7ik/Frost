<?php

namespace App\Repositories\Store\ProductInstance;

use App\Models\Store\ProductInstance;

class EloquentProductInstanceRepository implements ProductInstanceRepositoryContract
{

    /**
     * Retrieves all active Product Instances from all stores
     *
     * @return mixed
     */
    public function getAllActive()
    {
        $instances = ProductInstance::where('active', 1)->get();
        return $instances;
    }

    /**
     * Retrieves all active Product Instances that belong to the designated store
     *
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
     *
     * @param array $instances
     * @return array
     */
    private function sortInstances($instances)
    {
        $sortedInstances = array();
        foreach ($instances as $instance){
            $sortedInstances[config('store.product_categories')[$instance->product->category]][] =
                array('instance_id' => $instance->id, 'name' => $instance->product->name, 'stock' => $instance->stock);
        }
        return $sortedInstances;
    }

    /**
     * Find an Instance by its ID or fail
     *
     * @param int $id
     * @return ProductInstance|boolean
     */
    public function findById($id)
    {
        return ProductInstance::where('id', $id)->firstOrFail();
    }

    /**
     * Find another Instance that falls under the same Product as the provided instance and belongs to the designated store
     *
     * @param ProductInstance $instance
     * @param int $store
     * @return ProductInstance $relatedInstance
     */
    public function findRelatedForStore(ProductInstance $instance, $store)
    {
        $relatedInstance = ProductInstance::where([
            ['product_id', '=', $instance->product->id],
            ['store', '=', $store],
            ['active', '=', 1]
        ])->first();
        return $relatedInstance;
    }

    /**
     * Returns all products where the stock is lower than redline
     * Optionally filter by a specific store default returns all.
     *
     * @param int $store
     * @return mixed
     */
    public function getBelowRedline($store = 0)
    {
        $products = ProductInstance::whereRaw('stock < redline');
        if ($store > 0) $products->where('store', $store);
        $products->where('active', 1);
        return $products->get();
    }

    /**
     * Creates and saves a new ProductInstance
     *
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
     * Update an Instance
     *
     * @param int $id
     * @param array $data
     * @return ProductInstance
     */
    public function update($id, $data)
    {
        $instance = $this->findById($id);
        $instance->price = (isset($data['price'])) ? number_format($data['price'], 2) : $instance->price;
        $instance->stock = (isset($data['stock'])) ? $data['stock'] : $instance->stock;
        $instance->redline = (isset($data['redline'])) ? $data['redline'] : $instance->redline;
        $instance->active = (isset($data['active'])) ? true : false;
        if($instance->save()) {
            flash('The product instance has been updated successfully', 'success');
            return $instance;
        }
        flash('Something went wrong while trying to update the product instance', 'danger');
        return $instance;
    }

    /**
     * Adjust the stock of a ProductInstance by a positive or negative value or if $replace is true completely change the stock
     *
     * @param ProductInstance $product
     * @param int $adjustment
     * @param bool $replace
     */
    public function adjustStock(ProductInstance $product, $adjustment, $replace = false)
    {
        if ($replace) {
            $product->stock = $adjustment;
        } else {
            $product->stock += $adjustment;
        }
        $product->save();
    }

}