<?php

$prompt = "You are given the name of an item [Car]. Your task is to determine whether this item is something that a person could realistically lose within the premises of an educational institute or organization and would reasonably report as lost (e.g., pen, bag, laptop, ID card).
Return Yes if the item is common, portable, and realistic to lose in such an environment.
Return No if the item is too large, uncommon, absurd, or implausible to be lost or reported in such a context (e.g., tank, missile, refrigerator).
Examples:
    Pen → Valid Lost Item
    Laptop → Valid Lost Item
    Tank → Invalid Lost Item
    Missile → Invalid Lost Item
    Notebook → Valid Lost Item
    Air Conditioner → Invalid Lost Item";
// Create JSON body
$data = json_encode(["prompt" => $prompt]);

// URL of the local Node.js server
$url = 'http://5.5.5.5:4000/gemini';

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Execute and get response
$response = curl_exec($ch);
curl_close($ch);

// Show result
$result = json_decode($response, true);
echo "<h3>Gemini Says:</h3>";

// if($result['text'] == 'Yes')
    echo "<pre>" . htmlspecialchars($result['text']) . "</pre>";

?>
