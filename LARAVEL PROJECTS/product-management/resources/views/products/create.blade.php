<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <label>Category</label>
        <select name="category_id" id="category">
            <option value="">Select</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <label>Subcategory</label>
        <select name="subcategory_id" id="subcategory">
            <option value="">Select Category First</option>
        </select>

        <label>Brand</label>
        <select name="brand_id">
            @foreach($brands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>

        <label>Price</label>
        <input type="text" name="price">

        <label>Name</label>
        <input type="text" name="name">

        <button type="submit">Save</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $('#category').on('change', function() {
            let category_id = $(this).val();

            $('#subcategory').html('<option>Loading...</option>');

            $.get('/get-subcategories/' + category_id, function(res) {
                let html = '<option value="">Select</option>';

                res.forEach(sub => {
                    html += `<option value="${sub.id}">${sub.name}</option>`;
                });

                $('#subcategory').html(html);
            });
        });
    </script>
</body>

</html>