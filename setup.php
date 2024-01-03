<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unique Identifier</title>
</head>

<body>
    <script>
        // Initialize the agent at application startup.
        const fpPromise = import('https://openfpcdn.io/fingerprintjs/v4')
            .then(FingerprintJS => FingerprintJS.load())

        // Get the visitor identifier when you need it.
        fpPromise
            .then(fp => fp.get())
            .then(result => {
                // This is the visitor identifier:
                const visitorId = result.visitorId
                console.log(visitorId)
            })
    </script>

    <script>
        function setUniqueIdentifier() {
            var userId = getCookie("user_id");

            if (!userId) {
                // Generate a unique identifier (you might use a library for this)
                userId = generateUniqueIdentifier();

                // Set the cookie to store the unique identifier
                document.cookie = "user_id=" + userId + "; expires=Sun, 1 Jan 2023 00:00:00 UTC; path=/";
            }

            console.log("User ID: " + userId);
        }

        function generateUniqueIdentifier() {
            // Implement your logic to generate a unique identifier
            // You might consider using a library or a combination of timestamp and random values
            return "unique_id_" + Date.now() + "_" + Math.random().toString(36).substring(2, 15);
        }

        function getCookie(name) {
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return match[2];
        }
    </script>
</body>

</html>