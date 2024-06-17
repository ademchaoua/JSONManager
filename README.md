# JSONManager Class

The `JSONManager` class provides a set of methods to manage JSON files, allowing you to create, update, find, delete, count, sort, paginate, merge, backup, and restore JSON data.

## Installation

Include the `JSONManager` class in your project:

```php
require_once 'path/to/JSONManager.php';
```

## Usage

Below are examples of how to use the various methods provided by the JSONManager class.

### Create a New Object

Creates a new object in the JSON file.

```php
$path = 'data.json';
$newObject = [
    'id' => 1,
    'name' => 'John Doe',
    'age' => 30,
    'city' => 'New York'
];

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->save($newObject, false, 'users');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Update an Existing Object

Updates an existing object in the JSON file based on a specified key and value.

```php
$path = 'data.json';
$updatedObject = [
    'id' => 1,
    'name' => 'John Doe',
    'age' => 31,
    'city' => 'Los Angeles'
];

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->save($updatedObject, true, 'users', 'id', 1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Find an Object by Key

Finds an object in the JSON file by a specified key and value.

```php

$path = 'data.json';

try {
    $jsonManager = new JSONManager($path);
    $result = $jsonManager->findByKey('id', 1, 'users');
    print_r($result);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Delete an Object by Key

Deletes an object from the JSON file by a specified key and value.

```php

$path = 'data.json';

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->deleteByKey('id', 1, 'users');
    $jsonManager->save();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Count Objects

Counts the number of objects in a specified location in the JSON file.

```php

$path = 'data.json';

try {
    $jsonManager = new JSONManager($path);
    $count = $jsonManager->count('users');
    echo "Total users: " . $count;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Sort Objects by Key

Sorts objects in a specified location in the JSON file by a specified key.

```php

$path = 'data.json';

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->sortByKey('age', 'users');
    $jsonManager->save();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Paginate Objects

Paginates objects in a specified location in the JSON file.

```php 

$path = 'data.json';

try {
    $jsonManager = new JSONManager($path);
    $page = 1;
    $perPage = 10;
    $paginatedData = $jsonManager->paginate($page, $perPage, 'users');
    print_r($paginatedData);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Merge Data from Another File

Merges data from another JSON file into the current JSON file.

```php

$path = 'data.json';
$otherFilePath = 'other_data.json';

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->mergeFromFile($otherFilePath);
    $jsonManager->save();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Merge Data from an Array

Merges data from an array into the current JSON file.

```php

$path = 'data.json';
$dataArray = [
    [
        'id' => 2,
        'name' => 'Jane Smith',
        'age' => 28,
        'city' => 'Chicago'
    ]
];

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->mergeFromArray($dataArray);
    $jsonManager->save();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Backup JSON File

Creates a backup of the current JSON file.

```php

$path = 'data.json';
$backupPath = 'backup_data.json';

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->backup($backupPath);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

### Restore JSON File from Backup

Restores the JSON file from a backup.

```php

$path = 'data.json';
$backupPath = 'backup_data.json';

try {
    $jsonManager = new JSONManager($path);
    $jsonManager->restore($backupPath);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

```

In each example, the try block encapsulates operations that may throw exceptions, while the catch block handles any caught exceptions by displaying an error message. Adjust the error handling in catch blocks based on your application's requirements, such as logging errors or displaying them to users.