    <!-- Preloader -->
    <div id="preloader">
        <div id="filler"></div>
    </div>

    <!-- Preloader -->
    <div id="inner-preloader-content" class="preloader-content">
        <div class=" text-center">
            <div class="card-body p-5 my-5">
                <img src="../assets/images/loader.svg" alt="">
                <p class="mb-0">Please Wait...</p>
            </div>
        </div>
    </div>

    <div id="component-preloader-content" class="preloader-content">
        <div class=" text-center">
            <div class="card-body p-5 my-5">
                <img src="../assets/images/inner-loader.svg" alt="">
            </div>
        </div>
    </div>


    <div class="loading-popup" id="loading-popup">
        <div class="loading-popup-content" id="loading-popup-content">
            <div class="row">
                <div class="col-12 w-100 text-end mb-2">
                    <button class="btn btn-sm btn-light x-button" onclick="ClosePopUP()"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
            <div id="pop-content"></div>
        </div>
    </div>

    <div id="error-log"></div>



    <div class="popup" id="notification"></div>

    <!-- Add Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="./assets/js/index-1.0.js"></script>
    <script src="./assets/js/qty-selector.js"></script>

    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>