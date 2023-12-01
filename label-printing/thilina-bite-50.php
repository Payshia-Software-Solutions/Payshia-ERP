<?php
$row_count = 11;
$column_count = 3;
$label = $_POST['label'];

$labelTypes = [
    ["id" => "0", "text" => "Thilina Bite 20 LKR"],
    ["id" => "1", "text" => "Thilina Bite 50 LKR"],
    ["id" => "2", "text" => "Murukku Bite 20 LKR"]
];

$batch_code = $_POST['batch_code'];
$manufacture_date = $_POST['manufacture_date'];
$expire_date = $_POST['expire_date'];


$dateTime = new DateTime($manufacture_date);
$manufacture_date = $dateTime->format('d/m/Y');


$dateTime = new DateTime($expire_date);
$expire_date = $dateTime->format('d/m/Y');

// Check if the "label_back" file was uploaded
if (isset($_FILES["label_back"]) && $_FILES["label_back"]["error"] == UPLOAD_ERR_OK) {
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["label_back"]["name"]);

    if (move_uploaded_file($_FILES["label_back"]["tmp_name"], $targetFile)) {
        // echo 'File uploaded successfully.';
    } else {
        echo 'Error uploading file.';
    }
} else {
    echo 'No file uploaded or an error occurred.';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_POST['manufacture_date'] ?> @ B<?= $batch_code ?> - <?= $labelTypes[$label]['text'] ?></title>
    <style>
        @page {
            size: A3;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        .back-page {
            width: 296mm !important;
            height: 420mm;
            border: 2px solid black;
            box-sizing: border-box;
            position: fixed;
        }

        .label-row {
            display: flex;
        }

        .label {
            border: 1px solid black;
            width: 96.8mm;
            height: 36.4mm;
            margin: 1mm 0mm 0mm 1mm;
            background-repeat: no-repeat;
            background-size: 96.8mm 36.4mm;
        }

        .batch-code {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 800;
            font-size: 8px;
            color: #fff;
            left: 17mm;
            top: 15.7mm;
            position: relative;
        }

        .manufacture-date {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 800;
            font-size: 10px;
            left: 80.5mm;
            top: 25.8mm;
            color: #fff;
            position: relative;
        }

        .expire-date {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 800;
            font-size: 10px;
            left: 80.5mm;
            color: #fff;
            top: 25.1mm;
            position: relative;
        }
    </style>
</head>

<body>
    <div class="back-page">
        <?php for ($i = 1; $i <= $row_count; $i++) { ?>
            <div class="label-row">
                <?php for ($j = 1; $j <= $column_count; $j++) { ?>
                    <div class="label" style="background-image:url(<?= $targetFile ?>)">
                        <div class="batch-code">B<?= $batch_code ?></div>
                        <div class="manufacture-date"><?= $manufacture_date ?></div>
                        <div class="expire-date"><?= $expire_date ?></div>
                    </div>
                <?php }  ?>
            </div>
        <?php } ?>


    </div>


    <script>
        window.print()
    </script>
</body>

</html>