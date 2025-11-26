<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country & State Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .loading {
            display: none;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center bg-info p-3 rounded">Country & State</h1>
        <form action="" id="countryStateForm">
            <div class="mb-3">
                <label for="cdropdown" class="form-label">Country :</label>
                <select name="cdropdown" id="cdropdown" class="form-control">
                    <option value="">~~select~~</option>
                    <!-- countries will be populated here -->
                </select>
                <div id="countryLoading" class="loading">Loading countries...</div>
            </div>
            <div class="mb-3">
                <label for="sdropdown" class="form-label">State :</label>
                <select name="sdropdown" id="sdropdown" class="form-control" disabled>
                    <option value="">~~select~~</option>
                    <!-- states will be populated here -->
                </select>
                <div id="stateLoading" class="loading">Loading states...</div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            loadCountries();
            
            // When country selection changes, load states
            $('#cdropdown').on('change', function(){
                const countryId = $(this).val();
                if(countryId) {
                    loadStates(countryId);
                } else {
                    // Reset state dropdown if no country selected
                    $('#sdropdown').html('<option value="">~~select~~</option>').prop('disabled', true);
                }
            });
            
            function loadCountries() {
                $('#countryLoading').show();
                $.ajax({
                    url: 'process/processCountry.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#countryLoading').hide();
                        if(response.error) {
                            alert('Error: ' + response.error);
                        } else {
                            let options = '<option value="">~~select~~</option>';
                            response.forEach(function(country) {
                                options += `<option value="${country.id}">${country.name}</option>`;
                            });
                            $('#cdropdown').html(options);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#countryLoading').hide();
                        alert('Error loading countries: ' + error);
                    }
                });
            }
            
            function loadStates(countryId) {
                $('#stateLoading').show();
                $('#sdropdown').prop('disabled', true);
                
                $.ajax({
                    url: 'process/processCountry.php',
                    method: 'POST',
                    data: { country_id: countryId },
                    dataType: 'json',
                    success: function(response) {
                        $('#stateLoading').hide();
                        if(response.error) {
                            alert('Error: ' + response.error);
                        } else {
                            let options = '<option value="">~~select~~</option>';
                            response.forEach(function(state) {
                                options += `<option value="${state.id}">${state.name}</option>`;
                            });
                            $('#sdropdown').html(options).prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#stateLoading').hide();
                        alert('Error loading states: ' + error);
                    }
                });
            }
        });
    </script>
</body>
</html>