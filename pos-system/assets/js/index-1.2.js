var UserLevel = document.getElementById("UserLevel").value;
var LoggedUser = document.getElementById("LoggedUser").value;
var company_id = document.getElementById("company_id").value;
var LocationID = document.getElementById("LocationID").value;
var LastInvoiceStatus = document.getElementById("LastInvoiceStatus").value;

let inactivityTimer;
const inactivityTimeout = 180 * 60 * 1000; // 5 minutes in milliseconds

function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(logout, inactivityTimeout);
}

function openURLInNewWindow(url) {
    // Validate and sanitize the URL before using it
    var sanitizedUrl = validateAndSanitizeUrl(url);

    // Open the URL in a new window
    window.open(sanitizedUrl, '_blank');
}

function openURL(url) {
    // Validate and sanitize the URL before using it
    var sanitizedUrl = validateAndSanitizeUrl(url);

    // Open the URL in a new window
    window.location.href = sanitizedUrl;
}

function validateAndSanitizeUrl(url) {
    // Implement your URL validation and sanitation logic here
    // Return the sanitized URL
    return url;
}

function logout() {
    // Perform logout actions, such as redirecting to a logout page or clearing the session.
    window.location.href = "logout.php"; // Redirect to the logout page
}

document.addEventListener("mousemove", resetInactivityTimer);
document.addEventListener("keydown", resetInactivityTimer);

$(document).ready(function() {
    OpenIndex();
});

// Use jQuery for event handling
$(document).on("click", playTouchSound);

function playTouchSound() {
    var audio = new Audio("./assets/audio/new-touch.mp3");
    audio.play();
}

function OpenPopup() {
    document.getElementById("loading-popup").style.display = "flex";
}

function ClosePopUP() {
    document.getElementById("loading-popup").style.display = "none";
    SetInputFocus();
}

// JavaScript to show the overlay
function showOverlay() {
    var overlay = document.querySelector(".overlay");
    overlay.style.display = "block";
}

// JavaScript to hide the overlay
function hideOverlay() {
    var overlay = document.querySelector(".overlay");
    overlay.style.display = "none";
}

// Prevent Refresh or Back Unexpectedly
// window.onbeforeunload = function () {
//   return 'Are you sure you want to leave?'
// }

// JavaScript to hide the overlay
function CloseApp() {
    window.close();
}

function PromptCloseApp() {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/close-app.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }

    fetch_data();
}

const InnerLoader = document.getElementById(
    "inner-preloader-content"
).innerHTML;
// const InnerLoader = 'Please Wait..'

function OpenIndex() {
    function fetch_data() {
        SetTable();
        $.ajax({
            url: "assets/content/home/index.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#index-content").html(data);
                OpenItemContainer(0, "not-set");
                OpenBillContainer("Table Not Set", -1, 1, 0, 0, 0, 0, 1, "Default", 0);
            },
        });
    }
    if (LastInvoiceStatus == 0) {
        document.getElementById("index-content").innerHTML = InnerLoader;
        fetch_data();
    } else {
        InvoiceFinishWindow(LastInvoiceStatus);
    }
}

function OpenItemContainer(FilterKey, FilterType) {
    document.getElementById("item-container").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/item-container.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                FilterKey: FilterKey,
                FilterType: FilterType,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#item-container").html(data);
            },
        });
    }
    fetch_data();
}

function OpenBillContainer(
    TableName,
    TableID,
    ServiceChargeStatus,
    DiscountRate,
    CloseType,
    TenderedAmount,
    invoice_number,
    CustomerID,
    stewardName,
    stewardID
) {
    document.getElementById("bill-container").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/bill.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                TableName: TableName,
                TableID: TableID,
                ServiceChargeStatus: ServiceChargeStatus,
                DiscountRate: DiscountRate,
                CloseType: CloseType,
                TenderedAmount: TenderedAmount,
                LocationID: LocationID,
                invoice_number: invoice_number,
                CustomerID: CustomerID,
                stewardName: stewardName,
                stewardID: stewardID,
            },
            success: function(data) {
                $("#bill-container").html(data);
            },
        });
    }
    fetch_data();
}

function OpenQtySelector(ProductID, ItemPrice, ItemDiscount, CurrentStock) {
    var TableID = document.getElementById("set-table").getAttribute("data-id");

    function fetch_data() {
        OpenPopup();
        document.getElementById("pop-content").innerHTML = InnerLoader;
        $.ajax({
            url: "assets/content/home/item-qty-selector.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                ProductID: ProductID,
                ItemPrice: ItemPrice,
                ItemDiscount: ItemDiscount,
                CurrentStock: CurrentStock,
                LocationID: LocationID
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }
    if (TableID < "") {
        showNotification("Please Set Table");
        SetTable();
    } else {
        fetch_data();
    }
}

function appendToInput(value) {
    var inputBox = document.getElementById("qty-input");
    var currentValue = inputBox.value;
    if (currentValue === "0" && value !== ".") {
        inputBox.value = value;
    } else {
        inputBox.value += value;
    }
}

function clearInput() {
    var inputBox = document.getElementById("qty-input");
    inputBox.value = "";
}

function AddToCart(ProductID) {
    var TableID = document.getElementById("set-table").getAttribute("data-id");
    var TableName = document.getElementById("set-table").value;
    var CustomerID = document
        .getElementById("customer-id")
        .getAttribute("data-id");

    var stewardID = document.getElementById("set-steward").getAttribute("data-id");
    var stewardName = document.getElementById("set-steward").value;
    var ItemPrice = document.getElementById("item-price").value;
    var ItemDiscount = document.getElementById("item-discount").value;
    var ItemQty = document.getElementById("qty-input").value;

    var discount_rate = document.getElementById("discount_rate").value;
    var chargeStatus = document.getElementById("charge_status").value;
    var close_type = document.getElementById("close_type").value;
    var tendered_amount = document.getElementById("tendered_amount").value;
    var invoice_number = document.getElementById("invoice_number").value;

    function fetch_data() {
        document.getElementById("pop-content").innerHTML = InnerLoader;
        $.ajax({
            url: "assets/content/home/tasks/add-to-cart.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                ProductID: ProductID,
                ItemPrice: ItemPrice,
                ItemDiscount: ItemDiscount,
                CustomerID: CustomerID,
                ItemQty: ItemQty,
                TableID: TableID,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var result = response.message;
                    OpenBillContainer(
                        TableName,
                        TableID,
                        chargeStatus,
                        discount_rate,
                        close_type,
                        tendered_amount,
                        invoice_number,
                        CustomerID,
                        stewardName,
                        stewardID
                    );
                    ClosePopUP();
                } else {
                    var result = response.message;
                }
                showNotification(result);
            },
        });
    }
    if (ItemQty > 0) {
        fetch_data();
    } else {
        showNotification("Please Add Quantity");
    }
}

function showNotification(message) {
    var notification = document.getElementById("notification");
    notification.textContent = message;
    notification.style.display = "block";

    // Hide the notification after 3 seconds (3000 milliseconds)
    setTimeout(function() {
        notification.style.display = "none";
    }, 3000);
}

function RemoveFromCart(ProductID) {
    var TableID = document.getElementById("set-table").getAttribute("data-id");
    var TableName = document.getElementById("set-table").value;

    var CustomerID = document
        .getElementById("customer-id")
        .getAttribute("data-id");

    document.getElementById("pop-content").innerHTML = InnerLoader;

    var stewardID = document.getElementById("set-steward").getAttribute("data-id");
    var stewardName = document.getElementById("set-steward").value;
    var discount_rate = document.getElementById("discount_rate").value;
    var chargeStatus = document.getElementById("charge_status").value;
    var close_type = document.getElementById("close_type").value;
    var tendered_amount = document.getElementById("tendered_amount").value;
    var invoice_number = document.getElementById("invoice_number").value;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/tasks/remove-from-cart.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                ProductID: ProductID,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var result = response.message;
                    OpenBillContainer(
                        TableName,
                        TableID,
                        chargeStatus,
                        discount_rate,
                        close_type,
                        tendered_amount,
                        invoice_number,
                        CustomerID,
                        stewardName,
                        stewardID
                    );
                } else {
                    var result = response.message;
                }
                showNotification(result);

                ClosePopUP()
            },
        });
    }
    fetch_data();
}

function SetTable() {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/set-table.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }

    fetch_data();
}

function SetTableValue(TableID, TableName) {
    document.getElementById("set-table").setAttribute("data-id", TableID);
    document.getElementById("set-table").value = TableName;

    var CustomerID = document
        .getElementById("customer-id")
        .getAttribute("data-id");
    // Check if the table ID is 0 (Take Away)
    if (TableID <= 0) {
        $("#charge_status").val(TableID);
    } else {
        $("#charge_status").val(1);
    }


    var stewardID = document.getElementById("set-steward").getAttribute("data-id");
    var stewardName = document.getElementById("set-steward").value;
    var discount_rate = document.getElementById("discount_rate").value;
    var chargeStatus = document.getElementById("charge_status").value;
    var close_type = document.getElementById("close_type").value;
    var tendered_amount = document.getElementById("tendered_amount").value;
    var invoice_number = document.getElementById("invoice_number").value;

    OpenBillContainer(
        TableName,
        TableID,
        chargeStatus,
        discount_rate,
        close_type,
        tendered_amount,
        invoice_number,
        CustomerID,
        stewardName,
        stewardID
    );
    SetSteward();

    SetInputFocus();
}

function SetSteward() {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/set-steward.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }

    fetch_data();
}

function SetStewardValue(stewardID, stewardName) {
    document.getElementById("set-steward").setAttribute("data-id", stewardID);
    document.getElementById("set-steward").value = stewardName;


    var TableID = document.getElementById("set-table").getAttribute("data-id");
    var TableName = document.getElementById("set-table").value;

    var CustomerID = document
        .getElementById("customer-id")
        .getAttribute("data-id");

    var discount_rate = document.getElementById("discount_rate").value;
    var chargeStatus = document.getElementById("charge_status").value;
    var close_type = document.getElementById("close_type").value;
    var tendered_amount = document.getElementById("tendered_amount").value;
    var invoice_number = document.getElementById("invoice_number").value;

    OpenBillContainer(
        TableName,
        TableID,
        chargeStatus,
        discount_rate,
        close_type,
        tendered_amount,
        invoice_number,
        CustomerID,
        stewardName,
        stewardID
    );
    ClosePopUP();

    SetInputFocus();
}

function SetInputFocus() {
    var inputBox = document.getElementById("search-key");
    inputBox.focus();
}

function ProcessInvoice(InvoiceNumber, InvoiceStatus, PrinterStatus) {
    var PrintType = PrinterStatus;
    var reprintStatus = 0
    var TableID = document.getElementById("set-table").getAttribute("data-id");
    var stewardID = document.getElementById("set-steward").getAttribute("data-id");
    var CustomerID = document
        .getElementById("customer-id")
        .getAttribute("data-id");
    var GrandTotal = document.getElementById("grand_total").value;

    var form = document.getElementById("inv_form");

    if (form.checkValidity()) {
        var formData = new FormData(form);
        formData.append("LoggedUser", LoggedUser);
        formData.append("UserLevel", UserLevel);
        formData.append("company_id", company_id);
        formData.append("location_id", LocationID);
        formData.append("InvoiceNumber", InvoiceNumber);
        formData.append("InvoiceStatus", InvoiceStatus);
        formData.append("TableID", TableID);
        formData.append("CustomerID", CustomerID);
        formData.append("stewardID", stewardID);

        function fetch_data() {
            showOverlay();
            $.ajax({
                url: "assets/content/home/tasks/process-invoice.php",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status === "success") {
                        var result = response.message;
                        var insert_invoice_number = response.invoice_number;
                        if (InvoiceNumber != 0) {
                            reprintStatus = 1;
                        }
                        if (InvoiceStatus == "2") {
                            reprintStatus = 0;
                            PrintInvoice(insert_invoice_number, PrintType, LocationID, reprintStatus);
                        } else if (InvoiceStatus == "1") {
                            PrintKOT(insert_invoice_number, PrintType, LocationID, reprintStatus);
                        }
                    } else {
                        var result = response.message;
                    }
                    showNotification(result);
                    hideOverlay();
                    InvoiceFinishWindow(insert_invoice_number);
                },
            });
        }
        if (GrandTotal > 0) {
            fetch_data();
        } else {
            showNotification("Please add Items");
        }
    } else {
        result = "Please Filled out All * marked Fields.";
        showNotification(result);
    }
}

function SetPayment() {
    var GrandTotal = document.getElementById("grand_total").value;
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        OpenPopup();
        $.ajax({
            url: "assets/content/home/set-payment.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
                GrandTotal: GrandTotal,
            },
            success: function(data) {
                $("#pop-content").html(data);
                var paymentValueInput = document.getElementById("payment-value");
                paymentValueInput.focus();
                paymentValueInput.select();
            },
        });
    }
    if (GrandTotal > 0) {
        fetch_data();
    } else {
        showNotification("Please add Items");
    }
}

function SetDiscount() {
    var total = document.getElementById("total").value;
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        OpenPopup();
        $.ajax({
            url: "assets/content/home/add-discount.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
                total: total,
            },
            success: function(data) {
                $("#pop-content").html(data);
                var paymentValueInput = document.getElementById("payment-value");
                paymentValueInput.focus();
                paymentValueInput.select();
            },
        });
    }
    if (total > 0) {
        fetch_data();
    } else {
        showNotification("Please add Items");
    }
}

function GetHoldInvoices(LocationID) {
    OpenPopup();
    $("#pop-content").html(InnerLoader);
    // document.getElementById("bill-container").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/hold-invoice-list.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                // $("#bill-container").html(data);
                $("#pop-content").html(data);
            },
        });
    }
    fetch_data();
}

function TakeHoldToCart(
    TableName,
    TableID,
    ServiceChargeStatus,
    DiscountRate,
    CloseType,
    TenderedAmount,
    invoice_number,
    CustomerID,
    stewardName,
    stewardID
) {
    document.getElementById("bill-container").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/tasks/take-hold-to-cart.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                invoice_number: invoice_number,
                LocationID: LocationID,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var result = response.message;
                    OpenBillContainer(
                        TableName,
                        TableID,
                        ServiceChargeStatus,
                        DiscountRate,
                        CloseType,
                        TenderedAmount,
                        invoice_number,
                        CustomerID,
                        stewardName,
                        stewardID
                    );

                    ClosePopUP();
                } else {
                    var result = response.message;
                }

                showNotification(result);
            },
        });
    }
    fetch_data();
}

function DefaultPrintInvoice(InvoiceNumber, PrinterName) {
    // Replace 'bill-print.php' with the actual URL of your PHP script
    var windowWidth = 600 // Adjust this width as needed
    var windowHeight = 800 // Adjust this height as needed

    // Calculate the center position based on screen dimensions
    var screenWidth = window.screen.availWidth
    var screenHeight = window.screen.availHeight
    var left = (screenWidth - windowWidth) / 2
    var top = (screenHeight - windowHeight) / 2

    // Replace 'bill-print.php' with the actual URL of your PHP script
    var printWindow = window.open('invoice-print.php?PrinterName=' + PrinterName + '&invoice_number=' + InvoiceNumber, '_blank', 'width=' + windowWidth + ',height=' + windowHeight + ',left=' + left + ',top=' + top)

    // Check if the window was opened successfully
    if (printWindow) {
        // Add an event listener to print once the content is loaded
        printWindow.addEventListener('load', function() {
            printWindow.document.location.href = 'invoice-print.php?PrinterName=' + PrinterName + '&invoice_number=' + InvoiceNumber
            printWindow.print()
        })
    } else {
        alert('The popup window was blocked. Please allow pop-ups for this site.')
    }
}

function PrintInvoice(InvoiceNumber, PrinterName, locationID, reprintStatus) {
    // Replace 'kot-print.php' with the actual URL of your PHP script
    var url =
        "invoice-print.php?invoice_number=" +
        InvoiceNumber +
        "&PrinterName=" +
        PrinterName + "&locationID=" +
        locationID + '&reprintStatus=' + reprintStatus;

    // Navigate to the KOT URL in the same window
    if (PrinterName != 'default') {
        window.location.href = url;
    } else {
        window.open(url, '_blank');
    }
}

function DefaultPrintKOT(InvoiceNumber, PrinterName) {
    console.log('print_invoice');
    // Replace 'bill-print.php' with the actual URL of your PHP script
    var windowWidth = 600 // Adjust this width as needed
    var windowHeight = 800 // Adjust this height as needed

    // Calculate the center position based on screen dimensions
    var screenWidth = window.screen.availWidth
    var screenHeight = window.screen.availHeight
    var left = (screenWidth - windowWidth) / 2
    var top = (screenHeight - windowHeight) / 2

    // Replace 'bill-print.php' with the actual URL of your PHP script
    var printWindow = window.open('kot-print.php?PrinterName=' + PrinterName + '&invoice_number=' + InvoiceNumber, '_blank', 'width=' + windowWidth + ',height=' + windowHeight + ',left=' + left + ',top=' + top)

    // Check if the window was opened successfully
    if (printWindow) {
        // Add an event listener to print once the content is loaded
        printWindow.addEventListener('load', function() {
            printWindow.document.location.href = 'kot-print.php?PrinterName=' + PrinterName + '&invoice_number=' + InvoiceNumber
            printWindow.print()
        })
    } else {
        alert('The popup window was blocked. Please allow pop-ups for this site.')
    }
}

// function PrintKOT(InvoiceNumber) {
//     console.log('print_invoice')
//     // Replace 'kot-print.php' with the actual URL of your PHP script
//     var newTabUrl = 'kot-print.php?invoice_number=' + InvoiceNumber;
//     window.open(newTabUrl);
// }

function PrintKOT(InvoiceNumber, PrinterName, locationID, reprintStatus) {
    // Replace 'kot-print.php' with the actual URL of your PHP script
    var url =
        "kot-print.php?invoice_number=" +
        InvoiceNumber +
        "&PrinterName=" +
        PrinterName + "&locationID=" +
        locationID + "&reprintStatus=" + reprintStatus;


    // Navigate to the KOT URL in the same window
    if (PrinterName != 'default') {
        window.location.href = url;
    } else {
        window.open(url, '_blank');
    }
}

// function PrintKOT(InvoiceNumber, PrinterName) {
//     var url = './kot-print.php?invoice_number=' + InvoiceNumber
//     var config = qz.configs.create(PrinterName, { rasterize: "false" });
//     var data = [{
//         type: 'pixel',
//         format: 'html',
//         flavor: 'file', // or 'plain' if the data is raw HTML
//         data: url,
//         options: {
//             pageWidth: 3
//         }
//     }];
//     qz.print(config, data).catch(function(e) {
//         console.error(e);
//     });
// }

// function PrintInvoice(InvoiceNumber, PrinterName) {
//     var url = './invoice-print.php?invoice_number=' + InvoiceNumber
//     var config = qz.configs.create(PrinterName, { rasterize: "false" });
//     var data = [{
//         type: 'pixel',
//         format: 'html',
//         flavor: 'file', // or 'plain' if the data is raw HTML
//         data: url,
//         options: {
//             pageWidth: 3
//         }
//     }];
//     qz.print(config, data).catch(function(e) {
//         console.error(e);
//     });
// }

// function PrintPaymentReceipt(RecNumber) {
//     // Replace 'bill-print.php' with the actual URL of your PHP script
//     var windowWidth = 600 // Adjust this width as needed
//     var windowHeight = 800 // Adjust this height as needed

//     // Calculate the center position based on screen dimensions
//     var screenWidth = window.screen.availWidth
//     var screenHeight = window.screen.availHeight
//     var left = (screenWidth - windowWidth) / 2
//     var top = (screenHeight - windowHeight) / 2

//     // Replace 'bill-print.php' with the actual URL of your PHP script
//     var printWindow = window.open('receipt-print.php?rec_number=' + RecNumber, '_blank', 'width=' + windowWidth + ',height=' + windowHeight + ',left=' + left + ',top=' + top)

//     // Check if the window was opened successfully
//     if (printWindow) {
//         // Add an event listener to print once the content is loaded
//         printWindow.addEventListener('load', function() {
//             printWindow.document.location.href = 'receipt-print.php?rec_number=' + RecNumber
//             printWindow.print()
//         })
//     } else {
//         alert('The popup window was blocked. Please allow pop-ups for this site.')
//     }
// }

function PrintPaymentReceipt(RecNumber, InvoiceNumber, PrinterName, locationID) {
    // Replace 'kot-print.php' with the actual URL of your PHP script
    var url =
        "receipt-print.php?invoice_number=" +
        InvoiceNumber +
        "&PrinterName=" +
        PrinterName +
        "&rec_number=" +
        RecNumber + "&locationID=" +
        locationID;

    // Navigate to the KOT URL in the same window
    if (PrinterName != 'default') {
        window.location.href = url;
    } else {
        window.open(url, '_blank');
    }
}

function InvoiceFinishWindow(InvoiceNumber) {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/finish-window.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                InvoiceNumber: InvoiceNumber,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }
    fetch_data();
}

function GetTodaySales() {
    OpenPopup();
    $("#pop-content").html(InnerLoader);

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/today-invoices.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }
    fetch_data();
}

function SelectCustomer() {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/customer-list.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }

    fetch_data();
}

function SelectCustomerValue(CustomerID, CustomerName) {
    document.getElementById("customer-id").setAttribute("data-id", CustomerID);
    document.getElementById("customer-id").value = CustomerName;
    ClosePopUP();
}

function AddCustomer() {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/add-customer.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }

    fetch_data();
}

function SaveCustomer() {
    var customer_first_name = document.getElementById(
        "customer_first_name"
    ).value;
    var customer_last_name = document.getElementById("customer_last_name").value;
    var phone_number = document.getElementById("phone_number").value;
    var email_address = document.getElementById("email_address").value;
    var address_line1 = document.getElementById("address_line1").value;
    var address_line2 = document.getElementById("address_line2").value;
    var city_id = document.getElementById("city_id").value;

    function fetch_data() {
        $.ajax({
            url: "../assets/content/customer/save.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                location_id: LocationID,
                customer_first_name: customer_first_name,
                customer_last_name: customer_last_name,
                phone_number: phone_number,
                email_address: email_address,
                address_line1: address_line1,
                address_line2: address_line2,
                city_id: city_id,
                credit_days: 0,
                credit_limit: 0,
                opening_balance: 0,
                customer_id: 0,
                is_active: 1,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var result = response.message;
                } else {
                    var result = response.message;
                }
                ClosePopUP();
                showNotification(result);
            },
        });
    }

    if (
        customer_first_name != "" &&
        customer_last_name != "" &&
        phone_number != "" &&
        city_id != ""
    ) {
        fetch_data();
    } else {
        showNotification("Please filled out * Marked Fields");
    }
}

function ValidateStockToCart(recipeType, LocationID, ProductID) {
    var CartQty = document.getElementById('qty-input').value

    function fetch_data() {
        showNotification("Checking stock availability");
        $.ajax({
            url: "assets/content/home/tasks/get-current-stock.php",
            method: "POST",
            data: {
                LocationID: LocationID,
                ProductID: ProductID
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var stock_balance = response.stock_balance;
                    if (stock_balance >= CartQty || recipeType == '1') {
                        AddToCart(ProductID)
                    } else {
                        showNotification("Insufficient Stock to Proceed");
                    }
                }
            },
        });
    }
    fetch_data();
}

function OpenSetting() {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/setting.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }

    fetch_data();
}


function UpdateSetting(settingKey, settingValue) {
    // document.getElementById("pop-content").innerHTML = InnerLoader;

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/tasks/set-setting.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
                settingKey: settingKey,
                settingValue: settingValue,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var message = response.message
                    OpenSetting()
                    OpenIndex()
                    showNotification(message);
                } else {
                    showNotification("Something went Wrong!");
                }
            },
        });
    }

    fetch_data();
}

function OpenRemoval(productID, invoiceNumber) {
    OpenPopup();
    document.getElementById("pop-content").innerHTML = InnerLoader;

    var stewardID = document.getElementById("set-steward").getAttribute("data-id");

    function fetch_data() {
        $.ajax({
            url: "assets/content/home/item-removal.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                LocationID: LocationID,
                productID: productID,
                invoiceNumber: invoiceNumber,
                stewardID: stewardID,
            },
            success: function(data) {
                $("#pop-content").html(data);
            },
        });
    }

    fetch_data();
}

function SetRemovalNotice(refKey, reason, userID, productID) {
    function fetch_data() {
        $.ajax({
            url: "assets/content/home/tasks/save-removal-notice.php",
            method: "POST",
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                company_id: company_id,
                refKey: refKey,
                reason: reason,
                userID: userID,
                productID: productID,
                LocationID: LocationID,
            },
            success: function(data) {
                var response = JSON.parse(data);
                if (response.status === "success") {
                    var message = response.message
                    RemoveFromCart(productID)
                    showNotification(message);
                } else {
                    showNotification("Something went Wrong!");
                }
            },
        });
    }
    if (reason != "") {
        fetch_data();
    } else {
        showNotification("Please specify the reason to remove!");
    }

}