<?php
/**
 * Created by PhpStorm.
 * User: kryptik
 * Date: 9/12/16
 * Time: 2:05 PM
 */
namespace App\Repositories\Store\Shipment;

use App\Models\Store\Shipment;

interface ShipmentRepositoryContract
{
    /**
     * Retrieves all Shipments with an optionally specified date
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll();

    /**
     * Retrieves a single Shipment by its id
     * @param int $id
     * @return mixed
     */
    public function findById($id);

    /**
     * Creates a Shipment from form data
     * @param array $data
     * @return Shipment $shipment
     */
    public function create($data);

    /**
     * Deletes a shipment if it is not completed
     * @param int $shipment_id
     * @return bool
     */
    public function delete($shipment_id);

    /**
     * Adds a single ProductInstance to the Shipment
     * @param Shipment $shipment
     * @param array $data
     * @return bool
     */
    public function addProductToShipment(Shipment $shipment, $data);
}