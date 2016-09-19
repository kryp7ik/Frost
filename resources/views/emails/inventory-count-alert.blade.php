<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Inventory Count Information</title>
    </head>
    <body>
        <h3>An Inventory Count for {{ $alerts['store'] }} has been submitted.</h3>
        <p>The following is a list of products where the count submitted did not match the expected stock.</p>
        <table style="width:500px">
            <thead>
                <th align="center">Product</th>
                <th align="center">Expected</th>
                <th align="center">Actual</th>
            </thead>
            <tbody>
                @foreach($alerts['products'] as $alert)
                    <tr>
                        <td align="center">{{ $alert['name'] }}</td>
                        <td align="center">{{ $alert['expected'] }}</td>
                        <td align="center">{{ $alert['actual'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>