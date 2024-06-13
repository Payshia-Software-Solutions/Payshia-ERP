<?php
require_once('../../../include/config.php');
include '../../../include/function-update.php';
include '../../../include/lms-functions.php';

include './methods/functions.php'; //Ticket Methods

$ticketId = $_POST['ticketId'];
$ticketInfo = GetTicketsById($ticketId);

$stateCode = $ticketInfo['is_active'];
$stateArray = GetTicketStatus($stateCode);
?>


<div class="loading-popup-content">
    <div class="row">
        <div class="col-12 w-100 text-end">
            <button class="btn btn-sm btn-dark" onclick="ClosePopUP()"><i class="fa-regular fa-circle-xmark"></i></button>
        </div>
    </div>




    <form action="#" id="ticket-reply-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-8">
                        <h5 class="mb-0">Reply Details</h5>
                        <span class="badge bg-<?= $stateArray['bgColor'] ?>"><?= $stateArray['stateValue'] ?></span>
                        <h4><?= $ticketInfo['subject'] ?></h4>
                    </div>
                    <div class="col-4">
                        <div class="bg-light rounded-3 p-3 text-end my-2">
                            <button onclick="OpenTicket('<?= $ticketId ?>', 1)" class="btn btn-primary "><i class="fa-solid fa-arrow-left"></i> Back</button>
                        </div>
                    </div>
                </div>

                <p class="border-bottom pb-2"></p>
                <textarea class="form-control" rows="4" name="ticketReply" id="ticketReply"></textarea>

                <div class="text-end mt-2">
                    <button type="button" onclick="SendReply('<?= $ticketId ?>')" class="btn btn-dark"><i class="fa-solid fa-reply"></i> Send Reply</button>
                    <button type="button" onclick="SendReplyClose('<?= $ticketId ?>')" class="btn btn-dark"><i class="fa-solid fa-xmark"></i> Send Reply & Close</button>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    tinymce.remove()
    tinymce.init({
        selector: 'textarea#ticketReply',
        height: 300,
        menubar: false,
        content_css: 'assets/css/custom_editor.css',
        plugins: 'fullscreen anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'fullscreen undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        /* enable title field in the Image dialog*/
        image_title: true,
        /* enable automatic uploads of images represented by blob or data URIs*/
        automatic_uploads: true,
        /*
          URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
          images_upload_url: 'postAcceptor.php',
          here we add custom filepicker only to Image dialog
        */
        file_picker_types: 'image',
        /* and here's our custom image picker*/
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];

                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    /*
                      Note: Now we need to register the blob in TinyMCEs image blob
                      registry. In the next release this part hopefully won't be
                      necessary, as we are looking to handle it internally.
                    */
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                });
                reader.readAsDataURL(file);
            });

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });
</script>