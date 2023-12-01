<?php
$row_count = 9;
$column_count = 6;
if (!isset($_POST['label'])) {
    echo "Invalid Request";
    exit;
}
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
            size: A3 landscape;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        .back-page {
            width: 420mm;
            height: 297mm;
            border: 2px solid black;
            box-sizing: border-box;
            position: fixed;
        }

        .label-row {
            display: flex;
        }

        .label {
            border: 1px solid black;
            width: 68.1mm;
            height: 31.2mm;
            margin: 1mm 0mm 0mm 1mm;
            background-repeat: no-repeat;
            background-size: 259.19px 119.73px;
        }

        .batch-code {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 800;
            font-size: 6px;
            color: #fff;
            left: 12mm;
            top: 13.7mm;
            position: relative;
        }

        .manufacture-date {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 800;
            font-size: 7px;
            left: 56.8mm;
            top: 22.9mm;
            color: #fff;
            position: relative;
        }

        .expire-date {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 800;
            font-size: 7px;
            left: 56.8mm;
            color: #fff;
            top: 22.7mm;
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