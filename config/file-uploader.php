<?php
return [
    /*
    | Used to indicate on which disk the files will be saved. 
    | Always remember to specify an existing disk configured in the basic Laravel "filesystems.php" config file.
    |
    | Supported types: string
    */
    'disk' => env('FILESYSTEM_DISK', 'local'),

    /*
    | The default folder or path where files will be saved on disk. 
    | The parameter can also be left null or "/" if you want to save on the primary root of the disk.
    |
    | Supported types: null, string
    */
    'base_folder' => 'uploads',

    /*
    | Used to specify whether files can be deleted automatically when the reference model is deleted. 
    | In case you use the softDelete the files will not be deleted
    |
    | Supported types: boolean
    */
    'auto_delete' => true
];
