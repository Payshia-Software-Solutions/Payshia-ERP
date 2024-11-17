<?php
// Function to display assignment content based on file type
function displayAssignmentContent($file_name)
{
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $file_path = "./uploads/assignments/" . $file_name; // Update this path as needed

    switch ($file_extension) {
        case 'pdf':
            return "<embed src='$file_path' width='100%' height='600px' type='application/pdf'>";
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'webp':
        case 'gif':
            return "<img src='$file_path' alt='Assignment Image' class='w-100 rounded-4'>";
        case 'mp4':
            return "<video width='100%' controls><source src='$file_path' type='video/mp4'>Your browser does not support the video tag.</video>";
        default:
            return "<a href='$file_path' download>Download Assignment</a>";
    }
}
