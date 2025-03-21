<?php

/**
 * Language file for LoaiNguoiDung (User Type) module in English
 */

return [
    // Module information
    'moduleTitle'        => 'User Types',
    'manageTitle'        => 'Manage User Types',
    'createNew'          => 'Add New User Type',
    'editTitle'          => 'Edit User Type',
    'viewTrash'          => 'View Trash',
    'backToList'         => 'Back to List',
    'trashTitle'         => 'Deleted User Types',
    
    // Form fields
    'formTitle'          => 'User Type Information',
    'tenLoai'            => 'Type Name',
    'tenLoaiPlaceholder' => 'Enter type name',
    'tenLoaiHelp'        => 'Name of the user type (e.g. Admin, Manager, Customer)',
    'moTa'               => 'Description',
    'moTaPlaceholder'    => 'Enter description',
    'moTaHelp'           => 'Brief description of this user type and its permissions',
    
    // Buttons
    'save'               => 'Save',
    'cancel'             => 'Cancel',
    'delete'             => 'Delete',
    'restore'            => 'Restore',
    'deletePermanent'    => 'Delete Permanently',
    
    // Messages
    'createSuccess'      => 'User type created successfully',
    'updateSuccess'      => 'User type updated successfully',
    'deleteSuccess'      => 'User type deleted successfully',
    'restoreSuccess'     => 'User type restored successfully',
    'permanentDeleteSuccess' => 'User type permanently deleted',
    'deleteConfirm'      => 'Are you sure you want to delete this user type?',
    'permanentDeleteConfirm' => 'Are you sure you want to permanently delete this user type? This action cannot be undone!',
    'deleteMultipleConfirm' => 'Are you sure you want to delete the selected user types?',
    'restoreMultipleConfirm' => 'Are you sure you want to restore the selected user types?',
    'permanentDeleteMultipleConfirm' => 'Are you sure you want to permanently delete the selected user types? This action cannot be undone!',
    'emptyList'          => 'No user types found',
    'emptyTrash'         => 'Trash is empty',
    
    // Validation errors
    'tenLoaiRequired'    => 'Type name is required',
    'tenLoaiMin'         => 'Type name must be at least 3 characters long',
    'tenLoaiMax'         => 'Type name cannot exceed 100 characters',
    'tenLoaiUnique'      => 'A user type with this name already exists',
    'moTaMax'            => 'Description cannot exceed 255 characters',
]; 