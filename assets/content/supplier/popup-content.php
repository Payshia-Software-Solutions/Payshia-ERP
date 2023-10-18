<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';

$ActiveStatus = 0;
$UpdateKey = $_POST['UpdateKey'];
$supplier_name = $opening_balance = $supplier_id = $contact_person = $email= $street_name= $city= $zip_code= $telephone= $fax="";
if ($UpdateKey > 0) {
    $Supplier = GetSupplier($link)[$UpdateKey];
    $supplier_id = $Supplier['supplier_id'];
    $supplier_name = $Supplier['supplier_name'];
    $opening_balance = $Supplier['opening_balance']; 
    $contact_person = $Supplier['contact_person'];
    $email = $Supplier['email'];
    $street_name = $Supplier['street_name'];
    $city = $Supplier['city'];
    $zip_code = $Supplier['zip_code'];
    $telephone = $Supplier['telephone'];
    $fax = $Supplier['fax'];
}
?>

<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-0">Supplier Information</h3>
            <p class="border-bottom pb-2">Please fill the all required fields.</p>

            <form id="supplier-form" method="post">
                <div class="row">
                    <div class="col-12 mb-2">
                        <h6 class="taxi-label">Supplier Name</h6>
                        <input type="text" class="form-control" value="<?= $supplier_name ?>" placeholder="Enter Supplier Name" id="supplier_name" name="supplier_name" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-4 mb-2">
                        <h6 class="taxi-label">Code</h6>
                        <input type="text" class="form-control" value="<?= $supplier_id ?>" placeholder="Enter Supplier code" id="supplier_code" name="supplier_code" readonly>
                    </div>
                
                    <div class="col-6 col-md-4 mb-2">
                        <h6 class="taxi-label">Opening balance</h6>
                        <input type="text" class="form-control" value="<?= $opening_balance ?>" placeholder="Enter balance" id="opening_balance" name="opening_balance" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-2">
                        <h6 class="taxi-label">Contact person</h6>
                        <input type="text" class="form-control" value="<?= $contact_person ?>" placeholder="Enter contact person Name" id="contact_person" name="contact_person" required>
                    </div>
                
                    <div class="col-6 mb-2">
                        <h6 class="taxi-label">Email</h6>
                        <input type="text" class="form-control" value="<?= $email ?>" placeholder="Enter email" id="email" name="email" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 col-md-4 mb-2">
                        <h6 class="taxi-label">Street name</h6>
                        <input type="text" class="form-control" value="<?= $street_name ?>" placeholder="Enter street name" id="street_name" name="street_name" required>
                    </div>

                    
                    <div class="col-4 col-md-4 mb-2">
                        <h6 class="taxi-label">City</h6>
                        <input type="text" class="form-control" value="<?= $city ?>" placeholder="Enter city name" id="city" name="city" required>
                    </div>
                    
                   
                    <div class="col-4 col-md-4 mb-2">
                        <h6 class="taxi-label">Zip code </h6>
                        <input type="text" class="form-control" value="<?= $zip_code ?>" placeholder="Enter zip code" id="strezip_codeet_name" name="zip_code" required> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-2">
                        <h6 class="taxi-label">Telephone</h6>
                        <input type="text" class="form-control" value="<?= $telephone ?>" placeholder="Enter telephone number" id="telephone" name="telephone" required>
                    </div>
               
                    <div class="col-6 mb-2">
                        <h6 class="taxi-label">Fax</h6>
                        <input type="text" class="form-control" value="<?= $fax ?>" placeholder="Enter fax" id="fax" name="fax" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-light" type="reset" name="BookPackageButton" id="BookPackageButton">Clear</button>
                        <button class="btn btn-dark" type="button" name="BookPackageButton" id="BookPackageButton" onclick="SaveSupplier (1, <?= $UpdateKey ?>)">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>