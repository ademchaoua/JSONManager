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

$jsonManager = new JSONManager($path, $newObject, false, 'users');
$jsonManager->save();
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

$jsonManager = new JSONManager($path, $updatedObject, true, 'users', 'id', 1);
$jsonManager->save();

```

### Find an Object by Key

Finds an object in the JSON file by a specified key and value.

```php

$path = 'data.json';
$jsonManager = new JSONManager($path);

$result = $jsonManager->findByKey('id', 1, 'users');
print_r($result);

```

### Delete an Object by Key

Deletes an object from the JSON file by a specified key and value.

```php

$path = 'data.json';
$jsonManager = new JSONManager($path);

$jsonManager->deleteByKey('id', 1, 'users');
$jsonManager->save();

```

### Count Objects

Counts the number of objects in a specified location in the JSON file.

```php

$path = 'data.json';
$jsonManager = new JSONManager($path);

$count = $jsonManager->count('users');
echo "Total users: " . $count;

```

### Sort Objects by Key

Sorts objects in a specified location in the JSON file by a specified key.

```php

$path = 'data.json';
$jsonManager = new JSONManager($path);

$jsonManager->sortByKey('age', 'users');
$jsonManager->save();

```

### Paginate Objects

Paginates objects in a specified location in the JSON file.

```php 

$path = 'data.json';
$jsonManager = new JSONManager($path);

$page = 1;
$perPage = 10;
$paginatedData = $jsonManager->paginate($page, $perPage, 'users');
print_r($paginatedData);

```

### Merge Data from Another File

Merges data from another JSON file into the current JSON file.

```php

$path = 'data.json';
$otherFilePath = 'other_data.json';
$jsonManager = new JSONManager($path);

$jsonManager->mergeFromFile($otherFilePath);
$jsonManager->save();

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

$jsonManager = new JSONManager($path);
$jsonManager->mergeFromArray($dataArray);
$jsonManager->save();

```

### Backup JSON File

Creates a backup of the current JSON file.

```php

$path = 'data.json';
$backupPath = 'backup_data.json';
$jsonManager = new JSONManager($path);

$jsonManager->backup($backupPath);

```

### Restore JSON File from Backup

Restores the JSON file from a backup.

```php

$path = 'data.json';
$backupPath = 'backup_data.json';
$jsonManager = new JSONManager($path);

$jsonManager->restore($backupPath);

```

### JSONManager Class

```php

class JSONManager
{
    public $isThere;
    public $path;
    public $object;
    public $location;
    public $fileContent;
    public $keyToUpdate;
    public $valueToUpdate;

    public function __construct($path, $object = [], $isUpdate = false, $location = null, $keyToUpdate = null, $valueToUpdate = null)
    {
        $this->path = $path;
        $this->object = $object;
        $this->isThere = $isUpdate;
        $this->location = $location;
        $this->keyToUpdate = $keyToUpdate;
        $this->valueToUpdate = $valueToUpdate;

        $this->fileContent = file_exists($path) ? file_get_contents($path) : '{}';
    }

    public function save()
    {
        $data = json_decode($this->fileContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        if ($this->isThere && $this->location !== null) {
            if (isset($data[$this->location])) {
                $found = false;
                foreach ($data[$this->location] as &$existingObject) {
                    if (isset($existingObject[$this->keyToUpdate]) && $existingObject[$this->keyToUpdate] == $this->valueToUpdate) {
                        $existingObject = array_merge($existingObject, $this->object);
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $data[$this->location][] = $this->object;
                }
            } else {
                $data[$this->location] = [$this->object];
            }
        } else {
            $data[] = $this->object;
        }

        $newJson = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->path, $newJson);
    }

    public function findByKey($key, $value, $location = null)
    {
        $data = json_decode($this->fileContent, true);
        if ($location !== null && isset($data[$location])) {
            foreach ($data[$location] as $obj) {
                if (isset($obj[$key]) && $obj[$key] == $value) {
                    return $obj;
                }
            }
        }
        return null;
    }

    public function deleteByKey($key, $value, $location = null)
    {
        $data = json_decode($this->fileContent, true);
        if ($location !== null && isset($data[$location])) {
            foreach ($data[$location] as $index => $obj) {
                if (isset($obj[$key]) && $obj[$key] == $value) {
                    unset($data[$location][$index]);
                    $data[$location] = array_values($data[$location]);
                    break;
                }
            }
        }
        $this->fileContent = json_encode($data, JSON_PRETTY_PRINT);
    }

    public function count($location = null)
    {
        $data = json_decode($this->fileContent, true);
        if ($location !== null && isset($data[$location])) {
            return count($data[$location]);
        }
        return 0;
    }

    public function sortByKey($key, $location = null)
    {
        $data = json_decode($this->fileContent, true);
        if ($location !== null && isset($data[$location])) {
            usort($data[$location], function ($a, $b) use ($key) {
                return $a[$key] <=> $b[$key];
            });
        }
        $this->fileContent = json_encode($data, JSON_PRETTY_PRINT);
    }

    public function paginate($page, $perPage, $location = null)
    {
        $data = json_decode($this->fileContent, true);
        if ($location !== null && isset($data[$location])) {
            $total = count($data[$location]);
            $start = ($page - 1) * $perPage;
            return array_slice($data[$location], $start, $perPage);
        }
        return [];
    }

    public function mergeFromFile($otherFilePath)
    {
        if (!file_exists($otherFilePath)) {
            throw new Exception("File not found: {$otherFilePath}");
        }
        $otherData = file_get_contents($otherFilePath);
        $otherData = json_decode($otherData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON from other file: " . json_last_error_msg());
        }

        $mergedData = array_merge(json_decode($this->fileContent, true), $otherData);
        $newJson = json_encode($mergedData, JSON_PRETTY_PRINT);
        file_put_contents($this->path, $newJson);
    }

    public function mergeFromArray(array $dataArray)
    {
        $currentData = json_decode($this->fileContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        $mergedData = array_merge($currentData, $dataArray);
        $newJson = json_encode($mergedData, JSON_PRETTY_PRINT);
        file_put_contents($this->path, $newJson);
    }

    public function backup($backupPath)
    {
        if (!copy($this->path, $backupPath)) {
            throw new Exception("Error creating backup of the JSON file.");
        }
    }

    public function restore($backupPath)
    {
        if (!copy($backupPath, $this->path)) {
            throw new Exception("Error restoring the JSON file from backup.");
        }
        $this->fileContent = file_get_contents($this->path);
    }
}

```