<?php
require_once './models/DeliveryPartner.php';

class DeliveryPartnerController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new DeliveryPartner($pdo);
    }

    public function getAllRecords()
    {
        $records = $this->model->getAllPartners();
        echo json_encode($records);
    }

    public function getRecordById($id)
    {
        $record = $this->model->getPartnerById($id);
        if ($record) {
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Delivery partner not found']);
        }
    }

    public function createRecord()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset(
            $data['partner_name'],
            $data['phone_1_country_code'],
            $data['phone_1_suffix'],
            $data['address_l1'],
            $data['address_l2'],
            $data['city'],
            $data['district'],
            $data['contact_person'],
            $data['created_by'],
            $data['created_at']
        )
        ) {

            $this->model->createPartner($data);
            http_response_code(201);
            echo json_encode(['message' => 'Delivery partner created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function updateRecord($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            $data && isset(
            $data['partner_name'],
            $data['phone_1_country_code'],
            $data['phone_1_suffix'],
            $data['address_l1'],
            $data['address_l2'],
            $data['city'],
            $data['district'],
            $data['contact_person'],
            $data['created_by']
        )
        ) {

            $this->model->updatePartner($id, $data);
            echo json_encode(['message' => 'Delivery partner updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
        }
    }

    public function deleteRecord($id)
    {
        $this->model->deletePartner($id);
        echo json_encode(['message' => 'Delivery partner deleted successfully']);
    }
}
?>