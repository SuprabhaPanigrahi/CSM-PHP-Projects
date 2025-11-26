<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->name }}</td>
                <td>{{ $p->category->name }}</td>
                <td>{{ $p->subcategory->name }}</td>
                <td>{{ $p->brand->name }}</td>
                <td>{{ $p->price }}</td>
                <td>
                    <a href="{{ route('products.edit',$p) }}">Edit</a> |
                    <form action="{{ route('products.destroy',$p) }}" method="POST">
                        @csrf @method('DELETE')
                        <button>Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('products.create') }}">Add Product</a>
</body>

</html>