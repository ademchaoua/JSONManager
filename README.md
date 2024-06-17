# JsonManager PHP Class

**JsonManager** is a PHP class designed to facilitate easy management of JSON data stored in files. It provides methods for CRUD operations, sorting, pagination, merging, backup, and restore functionalities.

## Features

- Save/Update Data: Save or update JSON data in a file. Supports updating existing data based on specific criteria.
- Retrieve Data: Retrieve all data from the JSON file.
- Find Data: Find data in the JSON file based on a specific key-value pair.
- Delete Data: Delete data from the JSON file based on a specific key-value pair.
- Count Objects: Count the number of objects in the JSON file.
- Sort Data: Sort data in the JSON file by a specific key.
- Paginate Data: Paginate the data in the JSON file.
- Merge Data: Merge data from another JSON file or an array into the current JSON data.
- Backup and Restore: Create backups of the JSON file and restore from backups.

## Requirements

- PHP 5.6 or higher (JSON extension enabled)

## Installation

No installation necessary. Simply include the JsonManager class in your PHP project.

## Usage

### Example Usage:

```php
<?php
// Include the JsonManager class
require_once 'JsonManager.php';

// Define your JSON file path
$jsonFilePath = 'data.json';

try {
    // Example: Save data to JSON file
    $dataToSave = [
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com'
    ];
    JsonManager::save($jsonFilePath, $dataToSave);

    // Example: Retrieve all data
    $allData = JsonManager::getAll($jsonFilePath);
    var_dump($allData);

    // Example: Find data by key
    $foundData = JsonManager::findByKey($jsonFilePath, 'id', 1);
    var_dump($foundData);

    // Example: Delete data by key
    JsonManager::deleteByKey($jsonFilePath, 'id', 1);

    // Example: Count objects
    $count = JsonManager::count($jsonFilePath);
    echo "Number of objects: $count\n";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
```

## Methods

`JsonManager::save($path, $object, $isUpdate = false, $location = null, $keyToUpdate = null, $valueToUpdate = null)`

Save data to the JSON file.

- **$path:** Path to the JSON file.
- **$object:** Data object to save/update.
- **$isUpdate:** Flag indicating if data should be updated (default: false).
- **$location:** Specific location within the JSON structure to save/update (optional).
- **$keyToUpdate:** Key to search for when updating data (optional).
- **$valueToUpdate:** Value corresponding to $keyToUpdate to identify data to update (optional).

Throws Exception on error.

`JsonManager::getAll($path)`
Retrieve all data from the JSON file.

- **$path:** Path to the JSON file.

Returns an array of all data objects.

Throws Exception on error.

`JsonManager::findByKey($path, $key, $value)`

Find data in the JSON file by a specific key-value pair.

- **$path:** Path to the JSON file.
- **$key:** Key to search for.
- **$value:** Value corresponding to `$key` to search for.

Returns an array of matching data objects or null if not found.

Throws Exception on error.

`JsonManager::deleteByKey($path, $key, $value)`

Delete data from the JSON file based on a specific key-value pair.

- **$path:** Path to the JSON file.
- **$key:** Key to search for.
- **$value:** Value corresponding to `$key` to identify data to delete.

Throws Exception on error.

`JsonManager::count($path)`

Count the number of objects in the JSON file.

- **$path:** Path to the JSON file.
Returns the number of objects.

Throws Exception on error.

`JsonManager::sortByKey($path, $key)`

Sort data in the JSON file by a specific key.

- **$path:** Path to the JSON file.
- **$key:** Key to sort by.
Returns a sorted array of data objects.

Throws Exception on error.

`JsonManager::paginate($path, $perPage, $page)`

Paginate the data in the JSON file.

- **$path:** Path to the JSON file.
- **$perPage:** Number of items per page.
- **$page:** Page number to retrieve.

Returns an array of data objects for the specified page.

Throws Exception on error or invalid pagination parameters.

`JsonManager::mergeFromFile($path, $otherFilePath)`

Merge data from another JSON file into the current JSON file.

- **$path:** Path to the current JSON file.
- **$otherFilePath:** Path to the other JSON file to merge from.

Throws Exception on error.

`JsonManager::mergeFromArray($path, $dataArray)`

Merge data from an array into the current JSON data.

- **$path:** Path to the JSON file.
- **$dataArray:** Array of data to merge.

Throws Exception on error.

`JsonManager::backup($path, $backupPath)`

Backup the current JSON file to a specified location.

- **$path:** Path to the JSON file.
- **$backupPath:** Path to save the backup file.

Throws Exception on error.

`JsonManager::restore($path, $backupPath)`

Restore the JSON file from a specified backup location.

- **$path:** Path to the JSON file.
- **$backupPath:** Path to the backup file.

Throws Exception on error.

## License

This project is licensed under the MIT License - see the LICENSE file for details.