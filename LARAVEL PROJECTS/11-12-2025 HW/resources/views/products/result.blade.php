<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result</title>
</head>
<body>

<h1>Form Submission Result</h1>

<h2>Request Input (all)</h2>
<pre>{{ print_r($all, true) }}</pre>

<h2>JSON Response</h2>
<pre>{{ json_encode($json, JSON_PRETTY_PRINT) }}</pre>

<h2>Uploaded Files</h2>
<pre>{{ print_r($uploadedFiles, true) }}</pre>

<h2>Redirect Example</h2>
<p>{{ $redirectMessage }}</p>

</body>
</html>
