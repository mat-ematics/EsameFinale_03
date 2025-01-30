<?php
/* Included Tools */
require_once('inclusioni/strumenti.php');
use assets\strumenti;

/* Regex for Usernames and Passwords for User Creation */
$regex_username = "/^[a-zA-Z_]{6,32}$/";
$regex_password = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z0-9@$!%*?&]{8,}$/";

$regex_category_name = "/^[a-zA-Z]{3,32}$/";

$regex_work_name = "/^[a-zA-Z\s]{3,50}$/";
$regex_work_description = "/^[a-zA-Z0-9.,!'\"\-\s]{10,500}$/";
$regex_work_languages = "/^[a-zA-Z][a-zA-Z0-9#\+\-]{0,29}$/";

$flag_response = [
    'status' => 200,
    'message' => ''
];
$form_submitted = "";

/* Check if a Form was Submitted */
if (isset($_POST) && !empty($_POST)) {
    $form_submitted = $_POST['button_submit'];

    /* Try Catch for Errors */
    try {
        $flag_response = handleFormSubmission($_POST);
    } catch (Exception $e) {
        $flag_response = [
            'status' => 500,
            'message' => $e->getMessage()
        ];
    }

    // Output the response
    // echo strumenti::stampaArray($flag_response); // Echo momentarily the response
    // exit;
}

/* Main Form Handler */
function handleFormSubmission($postData) {
    global $connection, $form_submitted, $regex_username, $regex_password, $regex_category_name, $regex_work_name, $regex_work_description, $regex_work_languages;

    switch ($form_submitted) {
        case 'user_create':
            return handleUserCreate($postData, $connection, $regex_username, $regex_password);

        case 'user_edit':
            return handleUserEdit($postData, $connection, $regex_username, $regex_password);

        case 'user_delete':
            return handleUserDelete($postData, $connection);

        case 'category_create':
            return handleCategoryCreate($postData, $connection, $regex_category_name);

        case 'category_edit':
            return handleCategoryEdit($postData, $connection, $regex_category_name);

        case 'category_delete':
            return handleCategoryDelete($postData, $connection);

        case 'work_create':
            return handleWorkCreate($postData, $connection, $regex_work_name, $regex_work_description, $regex_work_languages);

        case 'work_edit':
            return handleWorkEdit($postData, $connection, $regex_work_name, $regex_work_description, $regex_work_languages);
            break;
        
        case 'work_delete':
            return handleWorkDelete($postData, $connection);
            break;

        default:
            throw new Exception("Invalid form submission.");
    }
}

/* User Creation Handler */
function handleUserCreate($data, $connection, $regexUsername, $regexPassword) {
    $username = trim($data['username']);
    $password = trim($data['password']);
    $repeatPassword = trim($data['repeat_password']);

    $flagValid = strumenti::validateCredentials($username, $password, $regexUsername, $regexPassword, $repeatPassword);
    if (!$flagValid) {
        throw new Exception("Invalid Credentials", 1);
    } 

    if (strumenti::check_credentials($connection, $username, $password, CHECK_REGISTER)) {
        return strumenti::createResponse(406, 'User already exists.');
    }

    $create_result = strumenti::create_account($connection, $username, $password);
    if ($create_result === true) {
        return strumenti::createResponse(201, 'Account successfully created.');
    }

    throw new Exception('Failed to create account.');
}

/* User Editing Handler */
function handleUserEdit($data, $connection, $regexUsername, $regexPassword) {
    $username = trim($data['username']);
    $password = trim($data['password']);
    $repeatPassword = trim($data['repeat_password']);
    $idUser = $data['selected_user'] ?? null;
    
    if (!isset($data['selected_user'])) {
        return strumenti::createResponse(406, 'No user selected.');
    }

    if ($username !== '' || $password !== '') {
        $flagValid = strumenti::validateCredentials($username, $password, $regexUsername, $regexPassword, $repeatPassword, true);
        if (!$flagValid) {
            throw new Exception("Invalid Credentials", 1);
        } 
    }

    $existingUser = strumenti::check_credentials($connection, $username, $password, CHECK_REGISTER);

    if ($existingUser && $existingUser != $idUser) {
        return strumenti::createResponse(406, 'Another user already exists with the same username.');
    }

    $edit_result = strumenti::edit_user($connection, $idUser, $username, $password);
    if ($edit_result === true) {
        return strumenti::createResponse(201, 'Account successfully modified.');
    }

    throw new Exception('Failed to edit user.');
}

/* User Deletion Handler */
function handleUserDelete($data, $connection) {
    $idUser = $data['selected_user'] ?? null;

    if (!isset($data['selected_user'])) {
        return strumenti::createResponse(406, 'No user selected.');
    }

    $delete_result = strumenti::delete_user($connection, $idUser);
    if ($delete_result === true) {
        return strumenti::createResponse(201, 'User successfully deleted.');
    }

    throw new Exception('Failed to delete user.');
}

function handleCategoryCreate($data, $connection, $regexCategoryName) {
    $categoryName = trim($data['category_name']);

    if (!preg_match($regexCategoryName, $categoryName)) {
        return strumenti::createResponse(400, 'Invalid category name.');
    }

    if (strumenti::check_category($connection, $categoryName)) {
        return strumenti::createResponse(406, 'Category already exists.');
    }

    $create_result = strumenti::create_category($connection, $categoryName);
    if ($create_result === true) {
        return strumenti::createResponse(201, 'Category successfully created.');
    }

    throw new Exception('Failed to create category.');
}

/* Category Editing Handler */
function handleCategoryEdit($data, $connection, $regexCategoryName) {
    $categoryName = trim($data['category_name']);
    $idCategory = $data['selected_category'] ?? null;

    if (!isset($data['selected_category'])) {
        return strumenti::createResponse(406, 'No category selected.');
    }

    if (!preg_match($regexCategoryName, $categoryName)) {
        return strumenti::createResponse(400, 'Invalid category name.');
    }

    $existingCategory = strumenti::check_category($connection, $categoryName, true);
    if ($existingCategory && $existingCategory != $idCategory) {
        return strumenti::createResponse(406, 'Another category already exists with the same name.');
    }

    $edit_result = strumenti::edit_category($connection, $idCategory, $categoryName);
    if ($edit_result === true) {
        return strumenti::createResponse(201, 'Category successfully modified.');
    }

    throw new Exception('Failed to edit category.');
}

/* Category Deletion Handler */
function handleCategoryDelete($data, $connection) {
    $idCategory = $data['selected_category'] ?? null;

    if (!isset($data['selected_category'])) {
        return strumenti::createResponse(406, 'No category selected.');
    }

    $delete_result = strumenti::delete_category($connection, $idCategory);
    if ($delete_result === true) {
        return strumenti::createResponse(201, 'Category successfully deleted.');
    }

    throw new Exception('Failed to delete category.');
}

function handleWorkCreate($data, $connection, $regexWorkName, $regexWorkDesc, $regexWorkLangs) {
    $work_name = trim($data['work_name']);
    $work_date = $data['work_date'];
    $work_languages = $data['work_languages'];
    $work_description = trim($data['work_description']);
    $work_category_id = $data['work_category'];
    
    //The Image is sent through the $_FILES superglobal instead of $_POST
    $work_image = $_FILES['work_image'];

    if (!preg_match($regexWorkName, $work_name)) {
        return strumenti::createResponse(400, 'Invalid Work Name.');
    } 
    
    if (!strumenti::validate_date($work_date)) {
        return strumenti::createResponse(400, 'Invalid Work Date.');
    }

    if (!preg_match($regexWorkDesc, $work_description)) {
        return strumenti::createResponse(400, 'Invalid Work Description.');
    }

    if (!strumenti::validate_array_text($work_languages, $regexWorkLangs)) {
        return strumenti::createResponse(400, 'Invalid Work Languages.');
    }

    $image_response = strumenti::uploadImage($work_image);
    if ($image_response['error_flag']) {
        return strumenti::createResponse(400, $image_response['error_message']);
    }

    $image_full_path = $image_response['full_path'];
    
    $create_result = strumenti::create_work($connection, $work_category_id, $work_name, $work_description, $work_date, $image_full_path, $work_languages);
    if ($create_result === true) {
        return strumenti::createResponse(201, 'Work successfully created.');
    }

    /* Delete image on failure */
    strumenti::deleteImage($image_full_path);

    throw new Exception('Failed to create work.');
}

function handleWorkEdit($data, $connection, $regexWorkName, $regexWorkDesc, $regexWorkLangs) {

    // strumenti::stampaArray($data);
    // strumenti::stampaArray($_FILES);
    // strumenti::stampaArray(strumenti::get_single_work($connection, $work_id));
    // exit;
    
    $work_id = $data['work_select'] ?? null;
    $work_name = trim($data['work_name']);
    $work_date = $data['work_date'];
    $work_languages = $data['work_languages'] ?? null;
    $work_description = trim($data['work_description']);
    $work_category_id = $data['work_category'];
    if ($work_category_id === 'null') {
        $work_category_id = '';
    }
    /* Response Declaration */
    $image_response = [];
    /* Old Image Path */
    $old_image = strumenti::get_single_work($connection, $work_id)['image_path'];

    if (!isset($data['work_select'])) {
        return strumenti::createResponse(406, 'No category selected.');
    }
    
    //The Image is sent through the $_FILES superglobal instead of $_POST
    $work_image = $_FILES['work_image'];

    if ($work_image['error'] === 4 && implode('', [$work_name, $work_date, $work_languages, $work_description, $work_category_id]) === '') {
        return strumenti::createResponse(400, 'All Inputs are empty.');
    }

    if ($work_name !== '' && !preg_match($regexWorkName, $work_name)) {
        return strumenti::createResponse(400, 'Invalid Work Name.');
    } 

    /* Check if Workn Name already Exists */
    $check_result = strumenti::check_work($connection, $work_name, true);

    if ($check_result !== false && $check_result != $work_id) {
        return strumenti::createResponse(406, 'Work Name already Exists.');
    }
    
    if ($work_date !== '' && !strumenti::validate_date($work_date)) {
        return strumenti::createResponse(400, 'Invalid Work Date.');
    }

    if ($work_description !== '' && !preg_match($regexWorkDesc, $work_description)) {
        return strumenti::createResponse(400, 'Invalid Work Description.');
    }

    if (!empty($work_languages) && !strumenti::validate_array_text($work_languages, $regexWorkLangs)) {
        return strumenti::createResponse(400, 'Invalid Work Languages.');
    }

    if ($work_image['error'] !== 4) {
        $image_response = strumenti::uploadImage($work_image);
        if ($image_response['error_flag']) {
            return strumenti::createResponse(400, $image_response['error_message']);
        }
    }

    /* Check for Null/Empty values */
    $image_full_path = $image_response['full_path'] ?? ''; //Check if a new image is present
    $work_category_id = is_numeric($work_category_id) ? (int) $work_category_id : null; //Change to Integer or Null the category id

    /* Edit Work */
    $edit_result = strumenti::edit_work($connection, $work_id, $work_category_id, $work_name, $work_description, $work_date, $image_full_path, $work_languages);
    // echo $edit_result;

    if ($edit_result === true) {
        $prev_image_del = strumenti::deleteImage($old_image);
        if ($prev_image_del['error_flag']) {
            return strumenti::createResponse(500, "Couldn't delete previous image");
        }
        return strumenti::createResponse(201, 'Work successfully modified.');
    }

    /* Delete Image on Error */
    strumenti::deleteImage($image_full_path);

    throw new Exception('Failed to Create Work.');
}

/* Work Deletion Handler */
function handleWorkDelete($data, $connection) {
    $idWork = $data['work_select'] ?? null;

    if (!isset($data['work_select'])) {
        return strumenti::createResponse(406, 'No category selected.');
    }

    $image_path = strumenti::get_single_work($connection, $idWork)['image_path'];

    $delete_result = strumenti::delete_work($connection, $idWork);
    if ($delete_result === true) {
        $image_delete = strumenti::deleteImage($image_path);
            if ($image_delete['error_flag']) {
                return strumenti::createResponse(500, "Couldn't delete work image");
            }
        return strumenti::createResponse(201, 'Category successfully deleted.');
    }
    throw new Exception('Failed to delete category.');
}