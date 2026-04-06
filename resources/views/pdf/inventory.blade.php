<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Inventory Count Sheet</title>
        <style>
            body { font-family: Helvetica, Arial, sans-serif; margin: 24px; color: #1f2937; }
            h1 { text-align: center; font-size: 22px; }
            h1 small { display: block; font-size: 14px; font-weight: normal; color: #4b5563; }
            table { width: 100%; border-collapse: collapse; margin-top: 12px; }
            table th, table td { border: 1px solid #d1d5db; padding: 6px 10px; text-align: left; font-size: 12px; }
            table thead th { background-color: #f3f4f6; }
            tr.category-row td { background-color: #e5e7eb; font-weight: bold; text-align: center; }
        </style>
    </head>
    <body>
        <h1>
            Inventory Count
            <small>
                Store: {{ config('store.stores')[Auth::user()->store] }}<br/>
                {{ date('M-d-Y') }}
            </small>
        </h1>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Expected</th>
                    <th>Actual Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sortedInstances as $category => $instances)
                    <tr class="category-row">
                        <td colspan="3"><strong>{{ $category }}</strong></td>
                    </tr>
                    @foreach($instances as $instance)
                        <tr>
                            <td>{{ $instance['name'] }}</td>
                            <td>{{ $instance['stock'] }}</td>
                            <td>___________</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </body>
</html>
