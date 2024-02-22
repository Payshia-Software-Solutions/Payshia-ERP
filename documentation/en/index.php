<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payshia ERP Documentation</title>
    <link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Link to your CSS file -->
</head>

<body>
    <header class="bg-dark">
        <div class="container text-light py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Payshia ERP Documentation</h1>
                </div>
                <div class="col-md-4 text-end">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Language
                        </button>
                        <div class="dropdown-menu" aria-labelledby="languageDropdown">
                            <a class="dropdown-item" href="#">English</a>
                            <a class="dropdown-item" href="#">Sinhala</a>
                            <a class="dropdown-item" href="#">Tamil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="py-5 mb-5">
        <div class="container">
            <div class="row">
                <nav class="col-md-3">
                    <ul class="nav flex-column nav-pills sticky-top">
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#installation">Installation</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#configuration">Configuration</a></li>
                        <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#master">Master Section</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#api">API Reference</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#troubleshooting">Troubleshooting</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#faqs">FAQs</a></li>
                    </ul>
                </nav>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div id="installation" class="tab-pane fade  ">
                            <?php include './parts/installation.php' ?>
                        </div>
                        <div id="configuration" class="tab-pane fade">
                            <h2>Configuration</h2>
                            <p>Instructions on how to configure Payshia ERP...</p>
                        </div>

                        <div id="master" class="tab-pane fade show active">
                            <?php include './parts/master.php' ?>
                        </div>
                        <!-- Add more tab panes for other sections -->
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-dark text-light py-3 ">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> Payshia ERP. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>