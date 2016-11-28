<?php

namespace App\Repositories\Store\Shipment;

use App\Models\Store\Shipment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EloquentShipmentRepository implements ShipmentRepositoryContract
{

    /**
     * Retrieves all Shipments with an optionally specified date
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        $shipments = Shipment::orderBy('id', 'desc')->paginate(10);
        return $shipments;
    }

    /**
     * Retrieves a single Shipment by its id
     * @param int $id
     * @return mixed
     */
    public function findById($id)
    {
        $shipment = Shipment::where('id', $id)->firstOrFail();
        return $shipment;
    }

    /**
     * Creates a Shipment from form data
     * @param array $data
     * @return Shipment $shipment
     */
    public function create($data)
    {
        $shipment = new Shipment([
            'store' => Auth::user()->store
        ]);
        $shipment->save();
        foreach ($data['products'] as $productData) {
            $this->addProductToShipment($shipment, $productData);
        }
        flash('A Shipment has been successfully created', 'success');
        return $shipment;
    }

    /**
     * Deletes a shipment if it is not completed
     * @param int $shipment_id
     * @return bool
     */
    public function delete($shipment_id)
    {
        $shipment = $this->findById($shipment_id);
        if ($shipment->complete) {
            flash('You can\'t delete a Shipment that has been completed', 'danger');
            return false;
        } else {
            $shipment->delete();
            flash('The shipment has been deleted successfully', 'success');
        }
    }

    /**
     * Adds a single ProductInstance to the Shipment
     * @param Shipment $shipment
     * @param array $data
     * @return bool
     */
    public function addProductToShipment(Shipment $shipment, $data)
    {
        if ($data['quantity'] == 0 ) return false;
        $shipment->productInstances()->attach([$data['instance'] => ['quantity' => $data['quantity']]]);
        $shipment->save();
        flash('A Product Instance has been added to the shipment', 'success');
    }
}
