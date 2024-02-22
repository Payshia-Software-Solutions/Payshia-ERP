var UserLevel = document.getElementById('UserLevel').value
var LoggedUser = document.getElementById('LoggedUser').value
var company_id = document.getElementById('company_id').value
var default_location = 1;

$(document).ready(function() {
    OpenIndex()
})

function OpenIndex() {
    document.getElementById('index-content').innerHTML = InnerLoader
    ClosePopUP()

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/report-home/index.php',
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

function DayEndSaleReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/day-end-sale/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetDayEndSaleReport()
            }
        })
    }
    fetch_data()
}

function GetDayEndSaleReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/day-end-sale/day-end-sale-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}


function PrintDayEndSale() {
    var queryDate = document.getElementById('date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/day-end-sale-report?location_id=' + encodeURIComponent(location_id) + '&date_input=' + encodeURIComponent(queryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}


// Sale Summary Report
function SaleSummaryReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/sale-summary/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetDaySaleSummaryReport()
            }
        })
    }
    fetch_data()

}

function GetDaySaleSummaryReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/sale-summary/sale-summary-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintDaySaleSummaryReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/sale-summary-report?location_id=' + encodeURIComponent(location_id) + '&fromQueryDate=' + encodeURIComponent(fromQueryDate) + '&toQueryDate=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}


// Invoice Report
function InvoiceReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/invoice-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()

}

function CustomerStatement() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/customer-statement/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintCustomerStatement() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var customerId = document.getElementById('customerId').value

    if (customerId != "") {
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
        var printWindow = window.open('report-viewer/customer-statement?customerId=' + encodeURIComponent(customerId) + '&fromQueryDate=' + encodeURIComponent(fromQueryDate) + '&toQueryDate=' + encodeURIComponent(toQueryDate), '_blank');
    } else {
        alert('Please Select Customer!')
    }
    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }

}


function PrintDayInvoiceReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/invoice-report?location_id=' + encodeURIComponent(location_id) + '&fromQueryDate=' + encodeURIComponent(fromQueryDate) + '&toQueryDate=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function StockBalanceReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/stock-balance-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetStockBalanceReport()
            }
        })
    }
    fetch_data()

}

function GetStockBalanceReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/stock-balance-report/stock-balance-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}


function PrintStockBalanceReport() {
    var location_id = document.getElementById('location_id').value
    var section_id = document.getElementById('section_id').value
    var department_id = document.getElementById('department_id').value
    var category_id = document.getElementById('category_id').value
    var queryDate = document.getElementById('date-input').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/stock-balance-report?location_id=' + encodeURIComponent(location_id) + '&section_id=' + encodeURIComponent(section_id) + '&category_id=' + encodeURIComponent(category_id) + '&department_id=' + encodeURIComponent(department_id) + '&queryDate=' + encodeURIComponent(queryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function BinCardReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/bin-card/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetBinCardReport()
            }
        })
    }
    fetch_data()

}

function GetBinCardReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/bin-card/bin-card-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintBinCardReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
    var select_product = document.getElementById('select_product').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/bin-card?location_id=' + encodeURIComponent(location_id) + '&from-date-input=' + encodeURIComponent(fromQueryDate) + '&to-date-input=' + encodeURIComponent(toQueryDate) + '&select_product=' + encodeURIComponent(select_product), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function ItemWiseSale() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/item-wise-sale-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetItemWiseSaleReport()
            }
        })
    }
    fetch_data()
}

function GetItemWiseSaleReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/item-wise-sale-report/item-wise-sale-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintItemWiseSaleReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/item-wise-sale?location_id=' + encodeURIComponent(location_id) + '&from-date-input=' + encodeURIComponent(fromQueryDate) + '&to-date-input=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

function ReceiptReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/receipt-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetReceiptReport()
            }
        })
    }
    fetch_data()
}




function GetReceiptReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)

    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/receipt-report/receipt-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()


}



function PrintReceiptReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/receipt-report?location_id=' + encodeURIComponent(location_id) + '&from-date-input=' + encodeURIComponent(fromQueryDate) + '&to-date-input=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

async function PrintReceiptReportNew() {
    var fromQueryDate = document.getElementById('from-date-input').value;
    var toQueryDate = document.getElementById('to-date-input').value;
    var location_id = document.getElementById('location_id').value;

    // Generate a secure random key and IV
    var key = await generateRandomKey();
    var iv = await generateRandomIV();

    // Encrypt the data using AES-GCM
    var encryptedData = await encryptData({ location_id, fromQueryDate, toQueryDate }, key, iv);
    console.log(encryptedData)

    // Assuming you have the encryptedData, key, and iv
    var decryptedData = await decryptData(encryptedData, key, iv);

    console.log('Decrypted Data:', decryptedData);

    // Open a new tab with the encrypted query parameters
    var printWindow = window.open('report-viewer/receipt-report?encryptedData=' + encodeURIComponent(encryptedData) + '&key=' + encodeURIComponent(key) + '&iv=' + encodeURIComponent(iv), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}

async function encryptData(data, key, iv) {
    try {
        // Convert the key and IV to Uint8Arrays
        var keyArray = new TextEncoder().encode(key);
        var ivArray = new TextEncoder().encode(iv);

        // Ensure the key is the correct length (256 bits)
        keyArray = keyArray.slice(0, 32);

        // Import the key
        var importedKey = await crypto.subtle.importKey(
            'raw',
            keyArray, { name: 'AES-GCM', length: 256 },
            false, ['encrypt']
        );

        // Convert the data to a JSON string
        var jsonString = JSON.stringify(data);

        // Convert the JSON string to an ArrayBuffer
        var dataBuffer = new TextEncoder().encode(jsonString);

        // Encrypt the data using AES-GCM
        var encryptedDataBuffer = await crypto.subtle.encrypt({ name: 'AES-GCM', iv: ivArray },
            importedKey,
            dataBuffer
        );

        // Convert the encrypted data to a Base64 string
        return btoa(String.fromCharCode.apply(null, new Uint8Array(encryptedDataBuffer)));
    } catch (error) {
        console.error('Error encrypting data:', error);
        throw error;
    }
}



async function generateRandomKey() {
    try {
        const key = await crypto.subtle.generateKey({ name: 'AES-GCM', length: 256 },
            true, ['encrypt', 'decrypt']
        );

        // Export the key as a CryptoKey object
        const keyData = await crypto.subtle.exportKey('raw', key);

        // Convert the key data to a hex string or use it as needed
        const keyHex = Array.from(new Uint8Array(keyData)).map(byte => byte.toString(16).padStart(2, '0')).join('');

        console.log('Generated Key Length:', keyData.byteLength * 8);

        return keyHex;
    } catch (error) {
        console.error('Error generating key:', error);
        throw error;
    }
}



async function generateRandomIV() {
    const iv = crypto.getRandomValues(new Uint8Array(12));

    return btoa(String.fromCharCode.apply(null, iv));
}

function hexStringToUint8Array(hexString) {
    return new Uint8Array(hexString.match(/.{1,2}/g).map(byte => parseInt(byte, 16)));
}


// Decrypt function
// Decrypt function
async function decryptData(encryptedData, key, iv) {
    try {
        // Convert the key and IV to Uint8Arrays
        var keyArray = hexStringToUint8Array(key);
        var ivArray = new TextEncoder().encode(iv);

        // Import the key
        var importedKey = await crypto.subtle.importKey(
            'raw',
            keyArray, { name: 'AES-GCM' },
            false, ['encrypt', 'decrypt']
        );

        // Convert the encrypted data from Base64 to ArrayBuffer
        var encryptedDataBuffer = new Uint8Array(atob(encryptedData).split('').map(char => char.charCodeAt(0))).buffer;

        // Decrypt the data using AES-GCM
        var decryptedDataBuffer = await crypto.subtle.decrypt({ name: 'AES-GCM', iv: ivArray },
            importedKey,
            encryptedDataBuffer
        );

        // Convert the decrypted data ArrayBuffer to a JSON string
        var decryptedDataString = new TextDecoder().decode(decryptedDataBuffer);

        // Log decrypted data string
        console.log('Decrypted Data String:', decryptedDataString);

        // Parse the JSON string to an object
        var decryptedData = JSON.parse(decryptedDataString);

        return decryptedData;
    } catch (error) {
        console.error('Error decrypting data:', error);

        // Log additional information
        console.log('Encrypted Data:', encryptedData);
        console.log('Key:', key);
        console.log('IV:', iv);

        throw error; // Throw the original error for more details
    }
}



function ChargeReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/charge-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetChargeReport()
            }
        })
    }
    fetch_data()
}

function GetChargeReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/charge-report/charge-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}

function PrintChargeReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/charge-report?location_id=' + encodeURIComponent(location_id) + '&from-date-input=' + encodeURIComponent(fromQueryDate) + '&to-date-input=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}


// Sale Summary Report
function CreditSaleReport() {
    document.getElementById('report-index').innerHTML = InnerLoader

    function fetch_data() {
        $.ajax({
            url: 'assets/content/reporting_module/credit-sale-report/index.php',
            method: 'POST',
            data: {
                LoggedUser: LoggedUser,
                UserLevel: UserLevel,
                default_location: default_location
            },
            success: function(data) {
                $('#report-index').html(data)
                GetCreditSaleReport()
            }
        })
    }
    fetch_data()

}

function GetCreditSaleReport() {
    document.getElementById('report-view').innerHTML = InnerLoader
    var form = document.getElementById('report-form')
    var formData = new FormData(form)
    formData.append('LoggedUser', LoggedUser)
    formData.append('UserLevel', UserLevel)
    formData.append('company_id', company_id)
    formData.append('default_location', default_location)

    function fetch_data() {
        showOverlay()
        $.ajax({
            url: 'assets/content/reporting_module/credit-sale-report/credit-sale-summary-report.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#report-view').html(data)
                hideOverlay()
            }
        })
    }
    fetch_data()
}


function PrintCreditSaleReport() {
    var fromQueryDate = document.getElementById('from-date-input').value
    var toQueryDate = document.getElementById('to-date-input').value
    var location_id = document.getElementById('location_id').value
        // Open a new tab with the printPage.html and pass the po_number as a query parameter
    var printWindow = window.open('report-viewer/credit-sale-summary-report?location_id=' + encodeURIComponent(location_id) + '&fromQueryDate=' + encodeURIComponent(fromQueryDate) + '&toQueryDate=' + encodeURIComponent(toQueryDate), '_blank');

    // Focus on the new tab
    if (printWindow) {
        printWindow.focus();
    }
}