var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/recipe/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel
            },
            success: function(data) {
                $('#index-content').html(data)
            }
        })
    }
    fetch_data()
}


function OpenRecipe(productID, recipeType) {

    $('#selected-product').html(InnerLoader)
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/recipe/product-recipe.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                productID: productID,
                recipeType: recipeType
            },
            success: function(data) {
                $('#selected-product').html(data)
            }
        })
    }
    fetch_data()
}


function GetProductInfo(ProductID) {
    function fetch_data() {
        $.ajax({
            url: 'assets/content/transaction_module/purchase_order/product-info.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                ProductID: ProductID
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status === 'success') {
                    var unit_name = response.unit_name
                    var cost_price = response.cost_price

                    $("#order_Unit").val(unit_name)
                    $("#new_rate").val(cost_price)
                } else {
                    var result = response.message
                    OpenAlert('error', 'Error!', result)
                }
            }
        })
    }
    fetch_data()
}

function AddToRecipe() {
    var selectedProductId = $("#select_product").val();
    var selectedProductText = $("#select_product option:selected").text();
    var selectedProductID = $("#select_product option:selected").val();
    var quantity = parseFloat($("#new_quantity").val()) || 0;
    var costPrice = parseFloat($("#new_rate").val()) || 0;
    var unit = $("#order_Unit").val();

    if (selectedProductId && quantity > 0) {
        // Check if the product is already in the table
        var existingRow = $("#recipe-table tbody tr:contains('" + selectedProductText + "')");

        if (existingRow.length > 0) {
            // If the product is already in the table, update the quantity
            var existingQuantity = parseFloat(existingRow.find("td:eq(2)").text().split(' ')[0]) || 0;
            existingRow.find("td:eq(0)").text((selectedProductID));
            existingRow.find("td:eq(1)").text((selectedProductText));
            existingRow.find("td:eq(2)").text((existingQuantity + quantity));
            existingRow.find("td:eq(3)").text((unit));
            existingRow.find("td:eq(4)").text((costPrice * (existingQuantity + quantity)).toFixed(2));
        } else {
            // If the product is not in the table, append a new row with a remove button
            var newRow = $("<tr><td>" + selectedProductId + "</td><td>" + selectedProductText + "</td><td class='text-center'>" + quantity + "</td><td class='text-center'>" + unit + "</td><td class='text-end'>" + (costPrice * quantity).toFixed(2) + "</td><td class='text-center'><i onclick='RemoveRow(this)' class='fa-solid fa-trash text-danger clickable'></i></td></tr>");
            $("#recipe-table tbody").append(newRow);
        }

        // Reset the form fields after adding/updating the table
        $("#select_product").val("");
        $("#new_quantity").val("");
        $("#order_Unit").val("");

        // Additional logic if needed
    } else {
        alert("Please select a product and enter a valid quantity.");
    }
}

function RemoveRow(button) {
    // Remove the corresponding row when the remove button is clicked
    $(button).closest('tr').remove();
}

function SaveRecipe(productID, recipeType) {
    // Check if there is at least one row in the table
    if ($("#recipe-table tbody tr").length === 0) {
        // Alert the user that at least one row is required
        OpenAlert('error', 'Error!', 'Please add at least one row to the table.');
        return;
    }

    // Collect table data for submission
    var tableData = [];
    $("#recipe-table tbody tr").each(function() {
        var rowData = {
            'recipe_product_id': $(this).find("td:eq(0)").text(),
            'product': $(this).find("td:eq(1)").text(),
            'quantity': $(this).find("td:eq(2)").text(),
            'unit': $(this).find("td:eq(3)").text(),
            'amount': $(this).find("td:eq(4)").text(),
            'main_product_id': productID,
            'LoggedUser': LoggedUser
        };
        tableData.push(rowData);
    });
    console.log(tableData);

    // Send the data to a PHP script using AJAX
    $.ajax({
        type: 'POST',
        url: 'assets/content/transaction_module/recipe/request/save.php',
        data: {
            'tableData': JSON.stringify(tableData)
        },
        success: function(data) {
            var response = JSON.parse(data)
            var result = response.message
            if (response.status === 'success') {
                OpenIndex()
                OpenRecipe(productID, recipeType)
                OpenAlert('success', 'Done!', result)
            } else {
                OpenAlert('error', 'Error!', result)
            }
        },
        error: function(error) {
            console.error(error);
        }
    });
}