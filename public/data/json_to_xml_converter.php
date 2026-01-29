<?php
// Sample JSON data (you can also read from a file using file_get_contents)
$jsonData = file_get_contents('movies_library.json'); // or put your JSON string here

// Decode JSON to PHP array
$data = json_decode($jsonData, true);

// Function to convert array to XML safely
function arrayToXml($data, SimpleXMLElement &$xmlData) {
    foreach ($data as $key => $value) {
        // Use generic 'item' tag for numeric keys
        if (is_numeric($key)) {
            $key = "item";
        }

        if (is_array($value)) {
            $subnode = $xmlData->addChild($key);
            arrayToXml($value, $subnode);
        } else {
            // Ensure all special XML characters are escaped
            $xmlData->addChild($key, htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
        }
    }
}

// Create new XML object
$xmlData = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root></root>');

// Convert array to XML
arrayToXml($data, $xmlData);

// Set proper header for XML
header('Content-Type: application/xml');

// Output XML
echo $xmlData->asXML();
?>
