<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee</title>
</head>

<body>
        <table >
            <thead>
                <tr>
                    <th class="border-bottom-0">#</th>
                    <th class="border-bottom-0"> {{__('home.productNo')}}</th>
                    <th class="border-bottom-0"> {{__('home.productname')}}</th>
                    <th class="border-bottom-0"> {{__('home.productlocation')}}</th>
                    <th class="border-bottom-0"> {{__('home.stock')}}</th>
                    <th class="border-bottom-0">{{__('users.branch')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)


                <tr>
                    <td>{{$product->id}}</td>
                    <td dir=ltr>{{$product->Product_Code}}</td>
                    <td>{{$product->product_name}}</td>
                    <td>{{$product->Product_Location}}</td>
                    <td>{{$product->numberofpice}}</td>
                    <td>{{$product->branch->name}}</td>
                </tr>


                @endforeach
            </tbody>

        </table>






        <br>

</body>

</html>