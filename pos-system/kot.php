<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .calculator {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 200px;
            margin: 0 auto;
        }

        .button {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .button:hover {
            background-color: #eee;
        }

        .button:active {
            transform: scale(0.95);
        }

        .input-box {
            width: 200px;
            margin: 0 auto;
        }

        .qty-input {
            border-radius: 5px;
            width: 100%;
            border: 1px solid #ccc;
            height: 50px;
            margin: auto;
            margin-bottom: 10px;
            text-align: right;
            font-size: 1.5rem;
        }

        .clear-button {
            background-color: #ff5733;
            color: white;
        }
    </style>
</head>

<body>
    <div class="input-box">
        <input type="text" id="inputBox" class="qty-input">
    </div>
    <div class="calculator">
        <div class="button" onclick="appendToInput('7')">7</div>
        <div class="button" onclick="appendToInput('8')">8</div>
        <div class="button" onclick="appendToInput('9')">9</div>
        <div class="button" onclick="appendToInput('4')">4</div>
        <div class="button" onclick="appendToInput('5')">5</div>
        <div class="button" onclick="appendToInput('6')">6</div>
        <div class="button" onclick="appendToInput('1')">1</div>
        <div class="button" onclick="appendToInput('2')">2</div>
        <div class="button" onclick="appendToInput('3')">3</div>
        <div class="button" onclick="appendToInput('0')">0</div>
        <div class="button" onclick="appendToInput('.')">.</div>
        <div class="button clear-button" onclick="clearInput()">C</div>
    </div>

    <script>
        function appendToInput(value) {
            var inputBox = document.getElementById('inputBox');
            var currentValue = inputBox.value;
            if (currentValue === '0' && value !== '.') {
                inputBox.value = value;
            } else {
                inputBox.value += value;
            }
        }

        function clearInput() {
            var inputBox = document.getElementById('inputBox');
            inputBox.value = '';
        }
    </script>
</body>

</html>