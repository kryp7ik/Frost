<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 9/13/16
 * Time: 6:45 PM
 */
namespace App\Repositories\Store\Transfer;

use App\Models\Store\Transfer;

interface TransferRepositoryContract
{
    /**
     * Retrieves all Transfers with an optionally specified date
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll();

    /**
     * Retrieves all Transfers that have not been received
     * @return mixed
     */
    public function getPending();

    /**
     * Retrieves a single Transfer by its id
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Creates a Transfer from form data
     * @param array $data
     * @return Transfer $transfer
     */
    public function create($data);

    /**
     * Deletes a Transfer if it has not yet been received by the other store
     * @param int $transfer_id
     * @return bool
     */
    public function delete($transfer_id);

    /**
     * Adds a single ProductInstance to the Transfer
     * @param Transfer $transfer
     * @param array $data
     * @return bool
     */
    public function addProductToTransfer(Transfer $transfer, $data);

    /**
     * Removes a ProductInstance from a Transfer
     * @param int $product_pivot_id
     */
    public function removeProductFromTransfer($product_pivot_id);

    /**
     * Sets the 'received' attribute to true
     * @param Transfer $transfer
     * @return Transfer $transfer
     */
    public function receiveTransfer(Transfer $transfer);
}