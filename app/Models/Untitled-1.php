<?php

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Read the raw input as JSON
    // Read the raw input as form data
    $rawData = file_get_contents("php://input");
    parse_str($rawData, $postReceivedData);
    
    // Check if all required parameters are present
    if (isset($postReceivedData["language_id"]) && isset($postReceivedData["question_number"]) && isset($postReceivedData["answer"]) && isset($postReceivedData["phone_number"]) && isset($postReceivedData["start"]) && isset($postReceivedData["end"])) {
    
    $start = $postReceivedData["start"];
                $language_id = $postReceivedData["language_id"];
                $status = $postReceivedData["status"];
                $question_number = $postReceivedData["question_number"];
                $answer = $postReceivedData["answer"];
                $phone_number = $postReceivedData["phone_number"];
    			$next_ivr = $postReceivedData["next"];
                $end = $postReceivedData["end"];
        
        // Prepare data for the external API
        // Prepare data for the external API
                $postData = [
                    'start' => $start,
                    'language_id' => $language_id,
                    'status' => $status,
                    'question_number' => $question_number,
                    'answer' => $answer,
                    'phone_number' => $phone_number,
                	'next_ivr' => $next_ivr,
                    'end' => $end
                ];

                // Convert data to JSON
                $jsonData = json_encode($postData);

         // Create a new cURL resource
                $ch = curl_init("https://akros.researchzambia.tech/api/ivr");

                // Set cURL options
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonData)
                ]);

                // Execute cURL session and get the response
                $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL resource
        curl_close($ch);

        // Output the response from the external API
        echo $response;
        
    } else {
        // Missing parameters
        http_response_code(400);
        echo "Bad Request: Missing parameters";
    }
} else {
    // Invalid request method
    http_response_code(405);
    echo "We are good";
}

?>
