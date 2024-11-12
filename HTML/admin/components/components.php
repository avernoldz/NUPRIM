<?php
function create_random_string($length)
{
    // Define the characters to use in the random string
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    // Generate the random string
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

function showToastr($message, $type)
{
    echo '<script>
        var alertMessage = "' . addslashes($message) . '";
        if (alertMessage) {
                toastr.' . $type . '(alertMessage);
        }
    </script>';
}
