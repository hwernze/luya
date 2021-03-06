<?php

return [
    // global
    'language_name' => 'English',
    'button_save' => 'Save',
    'button_abort' => 'Cancel',
    'button_send' => 'Send',

    // login
    'login_pre_title' => 'Login for {title}',
    'login_mail' => 'Email',
    'login_password' => 'Password',
    'login_btn_login' => 'Login',
    'login_securetoken' => 'Security code',
    'login_securetoken_info' => 'Enter the security code that was sent to your email.',

    // menu nodes
    'menu_node_system' => 'System',
    'menu_node_filemanager' => 'File Manager',

    // menu groups
    'menu_group_access' => 'Access',
    'menu_group_system' => 'System',
    'menu_group_images' => 'Images',

    // menu items
    'menu_access_item_user' => 'Users',
    'menu_access_item_group' => 'Groups',
    'menu_system_item_language' => 'Languages',
    'menu_system_item_tags' => 'Tags',
    'menu_images_item_effects' => 'Effects',
    'menu_images_item_filters' => 'Filters',

    // admin/dashboard
    'dashboard_title' => 'Welcome.',
    'dashboard_text' => 'You can switch between modules in the main navigation at the top of the screen.<br />The side navigation to the left gives you access to functions of the selected module.',

    // layout
    'layout_select_file' => 'Choose file',
    'layout_deleted_file' => 'You can\'t apply a filter, because the original file was deleted. Upload or choose a new file to apply a filter.',
    'layout_no_filter' => 'No Filter',
    'layout_debug_table_key' => 'Name',
    'layout_debug_table_value' => 'Value',
    'layout_filemanager_save_dir' => 'Save?',
    'layout_filemanager_remove_dir' => 'Delete directory?',
    'layout_filemanager_remove_dir_not_empty' => 'The directory is not empty - delete anyway?',
    'layout_filemanager_remove_selected_files' => 'Delete marked files',
    'layout_filemanager_move_selected_files' => 'Move to',
    'layout_filemanager_upload_error' => 'Error while uploading file.',
    'layout_filemanager_col_name' => 'Name',
    'layout_filemanager_col_type' => 'Type',
    'layout_filemanager_col_date' => 'Creation Date',
    'layout_filemanager_detail_name' => 'Filename',
    'layout_filemanager_detail_date' => 'Creation Date',
    'layout_filemanager_detail_filetype' => 'File Type',
    'layout_filemanager_detail_size' => 'Size',
    'layout_filemanager_detail_id' => 'Internal ID',
    'layout_filemanager_detail_download' => 'Download',
    'layout_btn_reload' => 'reload',
    'layout_btn_version' => 'Version',
    'layout_btn_useronline' => 'Online Users',
    'layout_btn_logout' => 'Logout',
    'layout_btn_profile' => 'Profile',
    'layout_debug_luya_version' => 'Luya Version',
    'layout_debug_id' => 'ID',
    'layout_debug_sitetitle' => 'Site title',
    'layout_debug_remotetoken' => 'Remote token',
    'layout_debug_assetmanager_forcecopy' => 'AssetManager forceCopy', // no translation
    'layout_debug_transfer_exceptions' => 'Transfer Exceptions', // no translation
    'layout_debug_yii_debug' => 'YII_DEBUG', // no translation
    'layout_debug_yii_env' => 'YII_ENV', // no translation
    'layout_debug_app_language' => 'Yii App Language', // no translation
    'layout_debug_luya_language' => 'Luya Language', // no translation
    'layout_debug_yii_timezone' => 'Yii Timezone', // no translation
    'layout_debug_php_timezone' => 'PHP Timezone', // no translation
    'layout_debug_php_ini_memory_limit' => 'PHP memory_limit', // no translation
    'layout_debug_php_ini_max_exec' => 'PHP max_execution_time', // no translation
    'layout_debug_php_ini_post_max_size' => 'PHP post_max_size', // no translation
    'layout_debug_php_ini_upload_max_file' => 'PHP upload_max_filesize', // no translation
    'layout_search_min_letters' => 'Please enter a search term with at least <b>three letters</b>.',
    'layout_search_no_results' => 'No entries where found.',
    'layout_filemanager_upload_files' => 'Add file',
    'layout_filemanager_folder' => 'Folder',
    'layout_filemanager_add_folder' => 'Add folder',
    'layout_filemanager_root_dir' => 'Root directory',
    
    // aws/groupauth
    'aws_groupauth_select_all' => 'Mark all',
    'aws_groupauth_deselect_all' => 'Unmark all',
    'aws_groupauth_th_module' => 'Modules',
    'aws_groupauth_th_function' => 'Function',
    'aws_groupauth_th_add' => 'Add',
    'aws_groupauth_th_edit' => 'Edit',
    'aws_groupauth_th_remove' => 'Delete',

    // models/group
    'model_group_name' => 'Name',
    'model_group_description' => 'Description',
    'model_group_user_buttons' => 'User',
    'model_group_btn_aws_groupauth' => 'Authorizations',

    //views/ngrest/crud
    'ngrest_crud_btn_list' => 'Entries',
    'ngrest_crud_btn_add' => 'Add',
    'ngrest_crud_btn_close' => 'Close',
    'ngrest_crud_search_text' => 'Enter search term...',
    'ngrest_crud_rows_count' => 'Entires',
    'ngrest_crud_btn_create' => 'Create',

    // apis
    'api_storage_image_upload_error' => 'The following error occured while uploading an image \'{error}\'.',
    'api_storage_file_upload_success' => 'Files have been uploaded successfully.',
    'api_sotrage_file_upload_error' => 'The following error occured while upload a file \'{error}\'.',
    'api_sotrage_file_upload_empty_error' => 'No files to upload found, have you selected any files?',
    
    // aws/changepassword
    'aws_changepassword_info' => 'Please enter a new passwort for this user. The passwort must have a length of at least 6 chars.',
    'aws_changepassword_succes' => 'The password have been encrypted and stored successful.',
    'aws_changepassword_new_pass' => 'New password',
    'aws_changepassword_new_pass_retry' => 'New passwort repeat',

// added translation in 1.0.0-beta3:

    // models/LoginForm
    'model_loginform_email_label' => 'Email',
    'model_loginform_password_label' => 'Password',
    'model_loginform_wrong_user_or_password' => 'Wrong user or password.',
    
    'ngrest_select_no_selection' => 'Select nothing',
    
    // js data
    'js_ngrest_error' => 'An error occured while loading.',
    'js_ngrest_rm_page' => 'Do you really want to delete this entry? This can not be undone.',
    'js_ngrest_rm_confirm' => 'The record was deleted successfully.',
    'js_ngrest_rm_update' => 'The record was updated successfully.',
    'js_ngrest_rm_success' => 'The new record was inserted successfully.',
    'js_tag_exists' => 'exists already and can not be added.',
    'js_tag_success' => 'Tag information was saved.',
    'js_admin_reload' => 'The system was updated and has to be reloaded. Please save your changes in the current form. (Clicking "cancel" will display this dialog again in 30 seconds.)',
    'js_dir_till' => 'to',
    'js_dir_set_date' => 'Set current date',
    'js_dir_table_add_row' => 'Add row',
    'js_dir_table_add_column' => 'Add column',
    'js_dir_image_description' => 'Description',
    'js_dir_no_selection' => 'No entries available yet. Add new entries by clicking the <span class="green-text">+</span> below to the left.',
    'js_dir_image_upload_ok' => 'The image was created successfully.',
    'js_dir_image_filter_error' => 'There was an error when applying the filter to the file.',
    'js_dir_upload_wait' => 'Your data is being uploaded and processed. This can take several minutes.',
    'js_dir_manager_upload_image_ok' => 'The file was uploaded successfully.',
    'js_dir_manager_rm_file_confirm' => 'Do you really want to delete this file?',
    'js_dir_manager_rm_file_ok' => 'The file was deleted successfully.',
    'js_zaa_server_proccess' => 'The server is processing your data, please hang on.',
    
// added translation in 1.0.0-beta4:

    'ngrest_crud_empty_row' => 'No data has been added to this table yet.',
    
// added translation in 1.0.0-beta5:

    // aws/gallery
    'aws_gallery_empty' => 'Please select some images on the left to add them into the gallery album.',
    'aws_gallery_images' => 'Album images',

];
