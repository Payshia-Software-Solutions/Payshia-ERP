<h1 class="fw-bold">Master කොටස</h1>
<p>මාස්ටර් කොටස සඳහා උපදෙස් පත්‍රිකාව</p>

<h4 class="border-bottom pb-2">පැති මෙනුවෙයි ඇති <span class="bg-light px-1 fw-bolder">Master</span> බොත්තම ඔබා එය විවෘත කරන්න.</h4>
<p>ඒවිට පහත ආකාරයේ මෙනුවක් ඔබට දක්වට හැකිවේ.</p>

<div class="row g-2">
    <div class="col-md-4">
        <img src="../assets/images/master-section.png" class="w-100 rounded-3">
    </div>
    <div class="col-md-8">

        <div class="bg-light p-3 rounded-3">
            <p class="fw-bold">මෙම කොටස තුල පහත ක්‍රියාකාරකම් අන්තර්ගත වේ</p>
            <ul>
                <li><a href="#product">Product (නිෂ්පාදන)</a>
                    <ul>
                        <li><a href="#add-product">Add New Product (අලුත් අයිතම එකතු කිරීම)</a></li>
                    </ul>
                </li>
                <li><a href="#location">Location (තොග පාලන ස්ථාන)</a></li>
            </ul>

        </div>

    </div>
</div>

<hr>
<section id="product">
    <h4 class="mb-0 fw-bold border-bottom pb-1">Product</h4>
    <p>When You open the Product Module you will get a page like following!</p>
    <img src="../assets/images/master/product-home.png" class="w-100 rounded-3 mb-3">

    <h5 id="add-product" class="mb-2 fw-bold border-bottom pb-1">Add New Product</h5>
    <h5>If You need to Add new Product to the System click on the <span class="badge border bg-dark text-light rounded-1 px-2 fw-bolder">+ Add New Product/Service</span> button. Then you will open a Product form and you have to fill the form with correct data to save the New Product</h5>

    <div class="border-bottom my-2"></div>

    <div class="row">
        <div class="col-md-12">
            <p class="mb-0 text-secondary">1st Part of the Product form need to filled with the Product Name configuration.</p>
            <img src="../assets/images/master/product-form-1.png" class="w-100 rounded-3 mb-3">

            <div class="bg-light p-2 rounded-2">
                <p class="mb-1">Examples</p>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Field Name</th>
                            <th>Possible Attribute Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Product Code</td>
                            <td>
                                It is read only Attribute so don't need to filled it
                            </td>
                        </tr>
                        <tr>
                            <td>Product Name</td>
                            <td>
                                This is the Product name that the system identified as Product Name!
                                <div class="text-danger">
                                    Eg:
                                    <li>Rice - Basmathi</li>
                                    <li>Fish - Toona</li>
                                    <li>Sauce - Soya</li>
                                </div>
                                <div class="fw-bold text-danger mt-2">Product Name must not exceed 255 characters</div>
                            </td>
                        </tr>

                        <tr>
                            <td>Print Name</td>
                            <td>
                                This is the Product name that the printed on any Invoice or Purchase order as Product Name!
                                <div class="text-danger">
                                    Eg:
                                    <li>Basmathi Rice</li>
                                    <li>Toona Fish</li>
                                    <li>Soya Sauce</li>
                                </div>
                                <div class="fw-bold text-danger mt-2">Print Name must not exceed 255 characters</div>
                            </td>
                        </tr>

                        <tr>
                            <td>Display Name</td>
                            <td>
                                This is the Product name that Display in the POS devices as Product Name!
                                <div class="text-danger">
                                    Eg:
                                    <li>Basmathi Rice</li>
                                    <li>Toona Fish</li>
                                    <li>Soya Sauce</li>
                                </div>
                                <div class="fw-bold text-danger mt-2">Display Name must not exceed 255 characters</div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>