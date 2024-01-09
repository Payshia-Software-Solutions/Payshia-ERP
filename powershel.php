<!DOCTYPE html>
<html>

<head>
    <title>Printer Extension</title>
    <script src="./node_modules/jsprintmanager/JSPrintManager.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>

<body>
    <script type="text/javascript">
        JSPM.JSPrintManager.auto_reconnect = true;
        JSPM.JSPrintManager.start();
        JSPM.JSPrintManager.WS.onStatusChanged = function() {
            if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open) {
                // Select the div containing your HTML content
                var invElement = document.getElementById("inv");

                // Use html2canvas to convert the content to an image
                html2canvas(invElement).then(function(canvas) {
                    // Create a blob from the image data
                    canvas.toBlob(function(blob) {
                        // Create a File object from the blob
                        var file = new File([blob], 'MyFile.jpg', {
                            type: 'image/jpeg'
                        });

                        // Create a new client print job
                        var cpj = new JSPM.ClientPrintJob();

                        // Set the client printer (in this case, 'Microsoft Print To PDF')
                        cpj.clientPrinter = new JSPM.InstalledPrinter('Microsoft Print To PDF');

                        // Add the image file to the client print job
                        cpj.files.push(new JSPM.PrintFile(file, JSPM.FileSourceType.BLOB, 'MyFile.jpg', 1));

                        // Send the print job to the client
                        cpj.sendToClient();
                    }, 'image/jpeg');
                });
            }
        };
    </script>
</body>

</html>