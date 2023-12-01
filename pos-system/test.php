<!DOCTYPE html>
<html>

<head>
    <style>
        .tab-container {
            display: flex;
        }

        .box {
            flex: 1;
            height: 100px;
            text-align: center;
            line-height: 100px;
            cursor: pointer;
        }

        #box1 {
            background-color: red;
        }

        #box2 {
            background-color: green;
        }

        #box3 {
            background-color: blue;
        }

        #box4 {
            background-color: orange;
        }

        #box5 {
            background-color: purple;
        }
    </style>
</head>

<body>
    <div class="tab-container">
        <div class="box" id="box1">Box 1</div>
        <div class="box" id="box2">Box 2</div>
        <div class="box" id="box3">Box 3</div>
        <div class="box" id="box4">Box 4</div>
        <div class="box" id="box5">Box 5</div>
    </div>

    <script>
        const boxes = document.querySelectorAll('.box');
        boxes.forEach(box => {
            box.addEventListener('click', () => {
                alert(`You clicked ${box.textContent}`);
            });
        });
    </script>
</body>

</html>