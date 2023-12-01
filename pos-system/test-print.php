<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebUSB Printer Control</title>
</head>

<body>
    <button id="connectButton">Connect to Printer</button>
    <button id="printButton" disabled>Print</button>

    <script>
        document.getElementById("connectButton").addEventListener("click", async () => {
            try {
                const filters = [
                    // Define filters for compatible USB devices (check your printer's details)
                ];
                const device = await navigator.usb.requestDevice({
                    filters
                });


                await device.open();
                await device.selectConfiguration(1);
                await device.claimInterface(0);

                alert(device)
                // Store the connected device for future use
                window.connectedPrinter = device;

                // Enable the "Print" button
                document.getElementById("printButton").removeAttribute("disabled");
            } catch (error) {
                console.error("Error connecting to the printer:", error);
            }
        });

        document.getElementById("printButton").addEventListener("click", function() {
            const htmlContent = `
                <h1>Print This Page</h1>
                <p>This is the HTML content of the page that you want to print.</p>
            `;
            printHTML(htmlContent);
        });

        async function printHTML(htmlContent) {
            const connectedPrinter = window.connectedPrinter;
            if (connectedPrinter) {
                try {
                    // Convert HTML content to bytes (adjust encoding as needed)
                    const encoder = new TextEncoder();
                    const htmlBytes = encoder.encode(htmlContent);

                    // Send the HTML content to the printer
                    await connectedPrinter.transferOut(1, htmlBytes);
                } catch (error) {
                    console.error("Error printing:", error);
                }
            }
        }
    </script>
</body>

</html>