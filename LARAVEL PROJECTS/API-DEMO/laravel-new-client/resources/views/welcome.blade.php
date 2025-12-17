<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Hello Client</h1>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'http://localhost:8080/api/products',
                method: 'GET',
                success: function(response){
                    console.log(response);
                },
                error: function(error){
                    console.log(error);
                }
            })
        });
    </script>
</body>

</html>