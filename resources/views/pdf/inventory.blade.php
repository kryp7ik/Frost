<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Inventory Count Sheet</title>
        <link href="https://frostpos.com/css/bootstrap.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="row">
            <div class="col-xs-12">
                <h1 class="text-xs-center">
                    Inventory Count<br/>
                    <small>
                        Store: {{ config('store.stores')[Auth::user()->store] }}<br/>
                        {{ date('M-d-Y') }}
                    </small>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th>Product</th>
                        <th>Expected</th>
                        <th>Actual Count</th>
                    </thead>
                    <tbody>
                        @foreach($sortedInstances as $category => $instances)
                            <tr class="active">
                                <td colspan="3" class="text-xs-center"><strong>{{ $category }}</strong></td>
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
            </div>
        </div>
    </body>
</html>