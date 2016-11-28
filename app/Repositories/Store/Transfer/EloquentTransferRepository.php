<?php

namespace App\Repositories\Store\Transfer;

use App\Models\Store\Transfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EloquentTransferRepository implements TransferRepositoryContract
{

    /**
     * Retrieves all Transfers with an optionally specified date
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        $transfers = Transfer::orderBy('id', 'desc')->paginate(10);
        return $transfers;
    }

    /**
     * Retrieves all Transfers that have not been received
     * @return mixed
     */
    public function getPending()
    {
        $transfers = Transfer::where('received', 0)->get();
        return $transfers;
    }

    /**
     * Retrieves a single Transfer by its id
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        $transfer = Transfer::where('id', $id)->firstOrFail();
        return $transfer;
    }

    /**
     * Creates a Transfer from form data
     * @param array $data
     * @return Transfer $transfer
     */
    public function create($data)
    {
        $transfer = new Transfer([
            'from_store' => Auth::user()->store,
            'to_store' => $data['to']
        ]);
        $transfer->save();
        foreach ($data['products'] as $productData) {
            $this->addProductToTransfer($transfer, $productData);
        }
        flash('A Transfer has been successfully created', 'success');
        return $transfer;
    }

    /**
     * Deletes a Transfer if it has not yet been received by the other store
     * @param int $transfer_id
     * @return bool
     */
    public function delete($transfer_id)
    {
        $transfer = $this->findById($transfer_id);
        if ($transfer->received) {
            flash('You can\'t delete a Transfer that has been completed', 'danger');
            return false;
        } else {
            $transfer->delete();
            flash('The Transfer has been deleted successfully', 'success');
        }
    }

    /**
     * Adds a single ProductInstance to the Transfer
     * @param Transfer $transfer
     * @param array $data
     * @return bool
     */
    public function addProductToTransfer(Transfer $transfer, $data)
    {
        if ($data['quantity'] == 0 ) return false;
        $transfer->productInstances()->attach([$data['instance'] => ['quantity' => $data['quantity']]]);
        $transfer->save();
        flash('A Product Instance has been added to the Transfer', 'success');
    }

    /**
     * Removes a ProductInstance from a Transfer
     * @param int $product_pivot_id
     */
    public function removeProductFromTransfer($product_pivot_id)
    {
        DB::table('instance_transfer')->where('id', $product_pivot_id)->delete();
        flash('A Product has been successfully removed from the Transfer', 'success');
    }

    /**
     * Sets the 'received' attribute to true
     * @param Transfer $transfer
     * @return Transfer $transfer
     */
    public function receiveTransfer(Transfer $transfer)
    {
        $transfer->received = true;
        $transfer->save();
        flash('The Product Transfer has been received and the inventory updated', 'success');
        return $transfer;
    }
}