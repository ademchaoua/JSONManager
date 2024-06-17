<?php

/**
 * Class JsonManager
 * 
 * A PHP class to manage JSON data stored in a file.
 */
class JsonManager
{
    /**
     * Save data to the JSON file.
     * 
     * @param string $path Path to the JSON file
     * @param array $object Data object to save/update
     * @param bool $isUpdate Flag indicating if data should be updated (default: false)
     * @param mixed $location Specific location within the JSON structure to save/update (optional)
     * @param string $keyToUpdate Key to search for when updating data (optional)
     * @param mixed $valueToUpdate Value corresponding to $keyToUpdate to identify data to update (optional)
     * 
     * @throws Exception If there is an error decoding JSON or saving JSON content
     */
    public static function save($path, $object, $isUpdate = false, $location = null, $keyToUpdate = null, $valueToUpdate = null)
    {
        // Read the content of the JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Decode the JSON file content to an associative array
        $data = json_decode($fileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        // Ensure $data is an array
        if (!is_array($data)) {
            $data = [];
        }

        // Handle update if specified
        if ($isUpdate && $location !== null && $keyToUpdate !== null && $valueToUpdate !== null) {
            if (isset($data[$location]) && is_array($data[$location])) {
                $found = false;
                // Iterate through existing objects to find and update
                foreach ($data[$location] as &$existingObject) {
                    if (isset($existingObject[$keyToUpdate]) && $existingObject[$keyToUpdate] == $valueToUpdate) {
                        $existingObject = array_merge($existingObject, $object); // Update existing object
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    // If object not found, add it to the array
                    $data[$location][] = $object;
                }
            } else {
                // Initialize the specified location with the new object
                $data[$location] = [$object];
            }
        } else {
            // Add new object to data array
            $data[] = $object;
        }

        // Encode updated data array to JSON
        $newJson = json_encode($data, JSON_PRETTY_PRINT);

        // Save updated JSON back to the file
        if (!file_put_contents($path, $newJson)) {
            throw new Exception("Error saving JSON to file: {$path}");
        }
    }

    /**
     * Retrieve all data from the JSON file.
     * 
     * @param string $path Path to the JSON file
     * @return array Array of all data objects
     * @throws Exception If there is an error decoding JSON
     */
    public static function getAll($path)
    {
        // Read the content of the JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Decode the JSON file content to an associative array
        $data = json_decode($fileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        return $data;
    }

    /**
     * Find data in the JSON file by a specific key-value pair.
     * 
     * @param string $path Path to the JSON file
     * @param string $key Key to search for
     * @param mixed $value Value corresponding to $key to search for
     * 
     * @return array|null Array of matching data objects or null if not found
     * @throws Exception If there is an error decoding JSON
     */
    public static function findByKey($dataOrPath, $key, $value)
    {
        if (is_string($dataOrPath)) {
            // Decode the JSON string to an associative array
            $data = json_decode($dataOrPath, true);

            // Check for decoding errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error decoding JSON: " . json_last_error_msg());
            }
        } elseif (is_file($dataOrPath)) {
            // Read the content of the JSON file
            $fileContent = file_exists($dataOrPath) ? file_get_contents($dataOrPath) : '{}'; // Default to an empty JSON object if the file doesn't exist

            // Decode the JSON file content to an associative array
            $data = json_decode($fileContent, true);

            // Check for decoding errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Error decoding JSON: " . json_last_error_msg());
            }
        } else {
            throw new Exception("Invalid data source provided. Must be JSON string or valid file path.");
        }

        $results = [];

        // Function to recursively search for matching objects
        $search = function($data) use (&$results, $key, $value, &$search) {
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (isset($item[$key]) && $item[$key] == $value) {
                        $results[] = $item;
                    }
                    // Recursively search nested arrays or objects
                    $search($item);
                }
            } elseif (is_object($data)) {
                // Convert object to array
                $item = json_decode(json_encode($data), true);
                if (isset($item[$key]) && $item[$key] == $value) {
                    $results[] = $item;
                }
                // Recursively search nested objects
                $search($item);
            }
        };

        // Start searching from the top level of $data
        $search($data);

        return $results;
    }

    /**
     * Delete data from the JSON file based on a specific key-value pair.
     * 
     * @param string $path Path to the JSON file
     * @param string $key Key to search for
     * @param mixed $value Value corresponding to $key to identify data to delete
     * 
     * @throws Exception If there is an error decoding JSON or saving JSON content
     */
    public static function deleteByKey($path, $key, $value)
    {
        // Read the content of the JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Decode the JSON file content to an associative array
        $data = json_decode($fileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        $updatedData = [];

        // Remove matching objects
        foreach ($data as $item) {
            if (!(isset($item[$key]) && $item[$key] == $value)) {
                $updatedData[] = $item;
            }
        }

        // Encode updated data array to JSON
        $newJson = json_encode($updatedData, JSON_PRETTY_PRINT);

        // Save updated JSON back to the file
        if (!file_put_contents($path, $newJson)) {
            throw new Exception("Error saving JSON to file: {$path}");
        }
    }

    /**
     * Count the number of objects in the JSON file.
     * 
     * @param string $path Path to the JSON file
     * @return int Number of objects
     * @throws Exception If there is an error decoding JSON
     */
    public static function count($path)
    {
        // Read the content of the JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Decode the JSON file content to an associative array
        $data = json_decode($fileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        return count($data);
    }

    /**
     * Sort data in the JSON file by a specific key.
     * 
     * @param string $path Path to the JSON file
     * @param string $key Key to sort by
     * 
     * @return array Sorted array of data objects
     * @throws Exception If there is an error decoding JSON
     */
    public static function sortByKey($path, $key)
    {
        // Read the content of the JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Decode the JSON file content to an associative array
        $data = json_decode($fileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        // Sort data by specified key
        usort($data, function ($a, $b) use ($key) {
            return $a[$key] <=> $b[$key];
        });

        return $data;
    }

    /**
     * Paginate the data in the JSON file.
     * 
     * @param string $path Path to the JSON file
     * @param int $perPage Number of items per page
     * @param int $page Page number to retrieve
     * 
     * @return array Array of data objects for the specified page
     * @throws Exception If there is an error decoding JSON or invalid pagination parameters
     */
    public static function paginate($path, $perPage, $page)
    {
        // Read the content of the JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Decode the JSON file content to an associative array
        $data = json_decode($fileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        if (!is_int($perPage) || !is_int($page) || $perPage <= 0 || $page <= 0) {
            throw new Exception("Invalid pagination parameters");
        }

        $offset = ($page - 1) * $perPage;

        // Paginate data
        $paginatedData = array_slice($data, $offset, $perPage);

        return $paginatedData;
    }

    /**
     * Merge data from another JSON file into the current JSON file.
     * 
     * @param string $path Path to the current JSON file
     * @param string $otherFilePath Path to the other JSON file to merge from
     * 
     * @throws Exception If there is an error decoding JSON or saving JSON content
     */
    public static function mergeFromFile($path, $otherFilePath)
    {
        // Read the content of the current JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Read the content of the other JSON file
        $otherFileContent = file_get_contents($otherFilePath);

        // Decode the current JSON file content to an associative array
        $currentData = json_decode($fileContent, true);

        // Decode the other JSON file content to an associative array
        $otherData = json_decode($otherFileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        // Merge data from other file
        $mergedData = array_merge($currentData, $otherData);

        // Encode the merged data back to JSON
        $newJson = json_encode($mergedData, JSON_PRETTY_PRINT);

        // Save the merged JSON back to the file
        if (!file_put_contents($path, $newJson)) {
            throw new Exception("Error saving merged JSON to file: {$path}");
        }
    }

    /**
     * Merge data from an array into the current JSON data.
     * 
     * @param string $path Path to the JSON file
     * @param array $dataArray Array of data to merge
     * 
     * @throws Exception If there is an error decoding JSON or saving JSON content
     */
    public static function mergeFromArray($path, array $dataArray)
    {
        // Read the content of the JSON file
        $fileContent = file_exists($path) ? file_get_contents($path) : '{}'; // Default to an empty JSON object if the file doesn't exist

        // Decode the current JSON file content to an associative array
        $currentData = json_decode($fileContent, true);

        // Check for decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        // Merge the arrays
        $mergedData = array_merge($currentData, $dataArray);

        // Encode the merged data back to JSON
        $newJson = json_encode($mergedData, JSON_PRETTY_PRINT);

        // Save the merged JSON back to the file
        if (!file_put_contents($path, $newJson)) {
            throw new Exception("Error saving merged JSON to file: {$path}");
        }
    }

    /**
     * Backup the current JSON file to a specified location.
     * 
     * @param string $path Path to the JSON file
     * @param string $backupPath Path to save the backup file
     * 
     * @throws Exception If there is an error copying the file
     */
    public static function backup($path, $backupPath)
    {
        if (!copy($path, $backupPath)) {
            throw new Exception("Error creating backup of the JSON file.");
        }
    }

    /**
     * Restore the JSON file from a specified backup location.
     * 
     * @param string $path Path to the JSON file
     * @param string $backupPath Path to the backup file
     * 
     * @throws Exception If there is an error copying the file
     */
    public static function restore($path, $backupPath)
    {
        if (!copy($backupPath, $path)) {
            throw new Exception("Error restoring the JSON file from backup.");
        }
    }
}
