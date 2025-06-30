<?php
// Set CORS headers for every response
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Handle OPTIONS requests (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

ini_set('memory_limit', '256M');

// Report all PHP errors
error_reporting(E_ALL);

// Display errors in the browser (for development)
ini_set('display_errors', 1);

// Transactions Route files 
$AddonProductLinkRoutes = require './routes/AddonProductLinkRoutes.php';
$CityRoutes = require './routes/CityRoutes.php';
$CompanyRoutes = require './routes/CompanyRoutes.php';
$CountryRoutes = require './routes/CountryRoutes.php';
$CustomerAreaRoutes = require './routes/CustomerAreaRoutes.php';
$CustomerRegions = require './routes/CustomerRegionRoutes.php';
$CustomerRouteRoutes = require './routes/CustomerRouteRoutes.php';
$DeliveryPartnerRoutes = require './routes/DeliveryPartnerRoutes.php';
$DistrictRoutes = require './routes/DistrictRoutes.php';
$EmployeeAccountLinkRoutes = require './routes/EmployeeAccountLinkRoutes.php';
$EmployeeDepartmentRoutes = require './routes/EmployeeDepartmentRoutes.php';
$EmployeeDetailRoutes = require './routes/EmployeeDetailRoutes.php';
$EmployeePositionRoutes = require './routes/EmployeePositionRoutes.php';
$EmployeeSalaryKeyRoutes = require './routes/EmployeeSalaryKeyRoutes.php';
$EmployeeSalaryTemplateRoutes = require './routes/EmployeeSalaryTemplateRoutes.php';
$EmployeeTemplatesKeyRoutes = require './routes/EmployeeTemplatesKeyRoutes.php';
$EmployeeWinpharmaPaymentsRoutes = require './routes/EmployeeWinpharmaPaymentsRoutes.php';
$EmployeeWorklocationsRoutes = require './routes/EmployeeWorklocationsRoutes.php';
$FinanceChartOfAccounts = require './routes/FinanceChartOfAccounts.php';
$FinanceTransactionsRoutes = require './routes/FinanceTransactionsRoutes.php';
$IconsPackRoutes = require './routes/IconsPackRoutes.php';
$MasterCategoriesRoutes = require './routes/MasterCategoriesRoutes.php';
$MasterCustomerRoutes = require './routes/MasterCustomerRoutes.php';
$MasterDepartmentRoutes = require './routes/MasterDepartmentRoutes.php';
$MasterLocationRoutes = require './routes/MasterLocationRoutes.php';
$MasterProductRoutes = require './routes/MasterProductRoutes.php';
$MasterSectionRoutes = require './routes/MasterSectionRoutes.php';
$MasterSupplierRoutes = require './routes/MasterSupplierRoutes.php';
$MasterTableRoutes = require './routes/MasterTableRoutes.php';
$MasterUnitRoutes = require './routes/MasterUnitRoutes.php';
$PageTableRoutes = require './routes/PageTableRoutes.php';
$PaymentTypesRoutes = require './routes/PaymentTypesRoutes.php';
$SettingDefaultValuesRoutes = require './routes/SettingDefaultValuesRoutes.php';
$SettingLocationRoutes = require './routes/SettingLocationRoutes.php';
$TempGoodReceiveNoteRoutes = require './routes/TempGoodReceiveNoteRoutes.php';
$TempOrderRoutes = require './routes/TempOrderRoutes.php';
$TempPurchaseOrderRoutes = require './routes/TempPurchaseOrderRoutes.php';
$TempTransferNoteRoutes = require './routes/TempTransferNoteRoutes.php';
$TransactionCancellationRoutes = require './routes/TransactionCancellationRoutes.php';
$TransactionExpensesRoutes = require './routes/TransactionExpensesRoutes.php';
$TransactionExpensesTypesRoutes = require './routes/TransactionExpensesTypesRoutes.php';
$TransactionGoodReceiveNoteRoutes = require './routes/TransactionGoodReceiveNoteRoutes.php';
$TransactionGoodReceiveNoteItemsRoutes = require './routes/TransactionGoodReceiveNoteItemsRoutes.php';
$TransactionInvoiceRoutes = require './routes/TransactionInvoiceRoutes.php';
$TransactionInvoiceItemRoutes = require './routes/TransactionInvoiceItemRoutes.php';
$TransactionProductionRoutes = require './routes/TransactionProductionRoutes.php';
$TransactionProductionItemRoutes = require './routes/TransactionProductionItemRoutes.php';
$TransactionPurchaseOrderRoutes = require './routes/TransactionPurchaseOrderRoutes.php';
$TransactionPurchaseOrderItemsRoutes = require './routes/TransactionPurchaseOrderItemsRoutes.php';
$TransactionQuotationRoutes = require './routes/TransactionQuotationRoutes.php';
// Combine all routes
$routes = array_merge(
    $AddonProductLinkRoutes,
    $CityRoutes,
    $CompanyRoutes,
    $CountryRoutes,
    $CustomerAreaRoutes,
    $CustomerRegions,
    $CustomerRouteRoutes,
    $DeliveryPartnerRoutes,
    $DistrictRoutes,
    $EmployeeAccountLinkRoutes,
    $EmployeeDepartmentRoutes,
    $EmployeeDetailRoutes,
    $EmployeePositionRoutes,
    $EmployeeSalaryKeyRoutes,
    $EmployeeSalaryTemplateRoutes,
    $EmployeeTemplatesKeyRoutes,
    $EmployeeWinpharmaPaymentsRoutes,
    $EmployeeWorklocationsRoutes,
    $FinanceChartOfAccounts,
    $FinanceTransactionsRoutes,
    $IconsPackRoutes,
    $MasterCategoriesRoutes,
    $MasterCustomerRoutes,
    $MasterDepartmentRoutes,
    $MasterLocationRoutes,
    $MasterProductRoutes,
    $MasterSectionRoutes,
    $MasterSupplierRoutes,
    $MasterTableRoutes,
    $MasterUnitRoutes,
    $PageTableRoutes,
    $PaymentTypesRoutes,
    $SettingDefaultValuesRoutes,
    $SettingLocationRoutes,
    $TempGoodReceiveNoteRoutes,
    $TempOrderRoutes,
    $TempPurchaseOrderRoutes,
    $TempTransferNoteRoutes,
    $TransactionCancellationRoutes,
    $TransactionExpensesRoutes,
    $TransactionExpensesTypesRoutes,
    $TransactionGoodReceiveNoteRoutes,
    $TransactionGoodReceiveNoteItemsRoutes,
    $TransactionInvoiceRoutes,
    $TransactionInvoiceItemRoutes,
    $TransactionProductionRoutes,
    $TransactionProductionItemRoutes,
    $TransactionPurchaseOrderRoutes,
    $TransactionPurchaseOrderItemsRoutes,
    $TransactionQuotationRoutes
);

// Define the home route with trailing slash
$routes['GET /'] = function () {
    // Serve the index.html file
    readfile('./views/index.html');
};

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);  // Get only the path, not query parameters

// Ensure URI always has a trailing slash
if (substr($uri, -1) !== '/') {
    // $uri .= '/';
}

// Determine if the application is running on localhost
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Adjust URI if needed (only on localhost)
    $uri = str_replace('Payshia-ERP/server/', '', $uri);
} else {
    // Adjust URI if needed (if using a subdirectory)
    $uri = $uri;
}

// Set the header for JSON responses, except for HTML pages
if ($uri !== '/') {
    header('Content-Type: application/json');
}

// Debugging
error_log("Method: $method");
error_log("URI: $uri");

// Define a generic regex pattern for routes with placeholders like {id}, {username}, etc.
$routeRegexPattern = "#\{[a-zA-Z0-9_]+\}#"; // Matches anything inside {}

// Route matching
foreach ($routes as $route => $handler) {
    list($routeMethod, $routeUri) = explode(' ', $route, 2);

    // Replace all placeholders like {id}, {username}, etc. with a generic regex that matches alphanumeric strings
    $routeRegex = preg_replace($routeRegexPattern, '([a-zA-Z0-9_\-]+)', $routeUri);
    $routeRegex = "#^" . rtrim($routeRegex, '/') . "/?$#";

    error_log("Checking route: $routeRegex");

    // Check if the route matches the request
    if ($method === $routeMethod && preg_match($routeRegex, $uri, $matches)) {
        array_shift($matches); // Remove the full match
        error_log("Route matched: $route");

        // Call the route handler with dynamic parameters
        call_user_func_array($handler, $matches);
        exit;
    }
}

// Default 404 response
header("HTTP/1.1 404 Not Found");
echo json_encode(['error' => 'Route not found']);
