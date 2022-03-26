<?php
/**
 * The required_fields_array contauins the list of all required fields
 * The output array contains all errors
 */
function check_empty_fields($required_fields_array){
    //Create an array to hold error messages
    $form_errors = array();

    foreach($required_fields_array as $name_of_field){
        if(!isset($_POST[$name_of_field]) || $_POST[$name_of_field] == NULL){
            $form_errors[] = $name_of_field . " is a required field";
        }
    }

    return $form_errors;
}

/**
 * The fields_to_check_length is an array containing the name of fields
 * for which we want to check min required length e.g array('username' => 4, 'email' => 12)
 * The output array contains all errors
 */
function check_min_length($fields_to_check_length){
    //Create an array to hold error messages
    $form_errors = array();

    foreach($fields_to_check_length as $name_of_field => $minimum_length_required){
        if(strlen(trim($_POST[$name_of_field])) < $minimum_length_required && $_POST[$name_of_field] != NULL){
            $form_errors[] = $name_of_field . " is too short, must be {$minimum_length_required} characters long";
        }
    }
    return $form_errors;
}

/**
 * The data holds a key/value pair array where key is the name of the form control
 * in this case 'email' and value is the input entered by the user
 * The output array contains email error
 */
function check_email($data){
    //Create an array to hold error messages
    $form_errors = array();
    $key = 'email';

    //Inspect if the key email exist in data array
    if(array_key_exists($key, $data)){

        //Inspect if the email field has a value
        if($_POST[$key] != null){

            // Drop all illegal characters from the email
            $key = filter_var($key, FILTER_SANITIZE_EMAIL);

            //Inspect if the input is a valid email address
            if(filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false){
                $form_errors[] = $key . " is not a valid email address";
            }
        }
    }
    return $form_errors;
}

/**
 * The form_errors_array is the array holding all
 * errors which we want to loop through
 * The output list contains all error messages
 */
function show_errors($form_errors_array){
    $errors = "<p><ul style='color: red;'>";

    //loop through the array of error and show all items in a list
    foreach($form_errors_array as $the_error){
        $errors .= "<li> {$the_error} </li>";
    }
    $errors .= "</ul></p>";
    return $errors;
}
