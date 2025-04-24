<?php
$filename = 'ip.txt';
if (!file_exists($filename)) {
    die(json_encode(['error' => 'Het bestand ip.txt bestaat niet.']));
}

// Lezen en verwerken van de ip.txt-gegevens
$ip_data = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$result = [];

foreach ($ip_data as $entry) {
    // Controleer op een lege regel of een foutieve structuur
    if (empty(trim($entry)) || strpos($entry, '|') === false) {
        continue; // Sla over als de regel leeg of incorrect is
    }

    // Debug: Toon de invoerregel (voor testdoeleinden)
    error_log("Processing entry: $entry");

    // Verwerk elk veld in de tekstregel
    $parts = explode(' | ', $entry); // Scheiden op " | "
    $details = [];
    foreach ($parts as $part) {
        if (strpos($part, ': ') === false) {
            continue; // Sla incorrecte velden over
        }
        list($key, $value) = explode(': ', $part, 2); // Splits alleen de eerste ": " op
        $details[trim($key)] = trim($value);
    }

    // Debug: Toon de verwerkte details
    error_log(print_r($details, true));

    // Voeg alle velden toe aan het resultaat, inclusief lege waarden
    $result[] = [
        'ip' => $details['IP'] ?? 'Onbekend',
        'date' => $details['Datum'] ?? 'Onbekend',
        'continent' => $details['Continent'] ?? 'Onbekend',
        'continentCode' => $details['Continentcode'] ?? 'Onbekend',
        'country' => $details['Land'] ?? 'Onbekend',
        'countryCode' => $details['Landcode'] ?? 'Onbekend',
        'region' => $details['Regio'] ?? 'Onbekend',
        'regionName' => $details['Regio Naam'] ?? 'Onbekend',
        'city' => $details['Stad'] ?? 'Onbekend',
        'currency' => $details['Valuta'] ?? 'Onbekend',
        'isp' => $details['ISP'] ?? 'Onbekend',
    ];
}

// Controleer of er geldige resultaten zijn
if (empty($result)) {
    die(json_encode(['error' => 'Geen geldige gegevens gevonden in ip.txt.']));
}

// Debug: Toon het resultaat in de logs
error_log(print_r($result, true));

// Output als JSON
header('Content-Type: application/json');
echo json_encode($result);
?>
