<script>
    async function validatePayment(paymentId) {
        const response = await fetch('https://inspirelk.payshia.com/payment-api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'payment_id=' + encodeURIComponent(paymentId),
        });

        if (!response.ok) {
            console.error('Error:', response.statusText);
            return;
        }

        const data = await response.json();
        console.log(data)
    }

    // Example usage
    validatePayment('payment_id_1');
</script>