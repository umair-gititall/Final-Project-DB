<?php

$promptno = $_GET['number'] ?? null;
$item = $_GET['item'] ?? null;
$prompt = "";
$question = $_GET['question'] ?? null;
$answer = $_GET['answer'] ?? null;


if (!$promptno) {
    echo json_encode(["error" => "Missing ID"]);
    exit;
}

if($promptno == 1){
$prompt = "Given an item [".$item."], respond Yes if it's realistic to lose at a school or university (e.g., small, portable, common).
Respond No if it's absurd or too large to lose (e.g., tank, missile).
Examples:
Pen → Yes
Laptop → Yes
Tank → No
Notebook → Yes
Air Conditioner → No";
}
else if($promptno == 2)
{
    $prompt = "Is the answer a possible correct or partially correct response to the question? Reply only Yes or No.
Question: " . $question .
" Answer: ". $answer;
}

$data = json_encode(["prompt" => $prompt]);
$url = 'http://localhost:3000/gemini';

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
echo $result['text'];
?>


<?php