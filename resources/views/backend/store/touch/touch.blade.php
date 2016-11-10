<!DOCTYPE html>
<meta name="csrf_token" content="{{ csrf_token() }}">
<html>
    <head>
        <title>FrostPOS: Touch</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            .complete {
                position:absolute;
                width:100%;
                bottom:0px;
                height:100px
            }
        </style>
    </head>
    <body>
        @include('shared.touch-navbar')

        <div id="app">
            <table class="table table-striped">
                <thead>
                    <th>Complete</th>
                    <th>Recipe</th>
                    <th>Size</th>
                    <th>Ice</th>
                    <th>Add Menthol</th>
                    <th>Nicotine</th>
                    <th>VG%</th>
                    <th>Add Premix</th>
                </thead>
                <tbody>
                    <tr v-for="liquid in liquids">
                        <td><button v-on:click="completeOrder( liquid.id )" class="btn btn-info btn-lg">Complete</button></td>
                        <td>@{{ liquid.extra ? liquid.recipe + ' XTRA' : liquid.recipe }}</td>
                        <td>@{{ liquid.size + 'ml' }}</td>
                        <td>@{{ mentholDisplay[liquid.menthol] }}</td>
                        <td>@{{ iceToAdd( liquid ) + 'ml' }}</td>
                        <td>@{{ liquid.nicotine }}mg Add: @{{ nicotineToAdd( liquid ) }}ml</td>
                        <td>@{{ liquid.vg == 100 ? 'MAX' : liquid.vg + '%' }}</td>
                        <td>@{{ addPremix( liquid ) }}ml</td>
                    </tr>
                </tbody>
            </table>
            <button v-on:click="completeAll" class="btn btn-success complete">Complete All</button>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.28/vue.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.1/socket.io.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                }
            });
            var socket = io('127.0.0.1:3000');

            var app = new Vue({
                el: '#app',

                data: {
                    liquids: [],
                    iceMultipliers: {
                        0:0,
                        1:0.04,
                        2:0.08,
                        3:0.16,
                        4:0.32
                    },
                    mentholDisplay: {
                        0:'None',
                        1:'Light',
                        2:'Medium',
                        3:'Heavy',
                        4:'Super'
                    }
                },

                methods: {
                    iceToAdd: function(liquid) {
                        return (liquid.size * this.iceMultipliers[liquid.menthol]).toFixed(1);
                    },
                    nicotineToAdd: function(liquid) {
                        return (liquid.nicotine * (liquid.size / 100)).toFixed(1);
                    },
                    addPremix: function(liquid) {
                        return (liquid.size - ((liquid.nicotine * (liquid.size / 100)) + (liquid.size * this.iceMultipliers[liquid.menthol]))).toFixed(1);
                    },
                    refreshLiquids: function() {
                        $.getJSON('/admin/store/touch/get-liquids', function(liquidList) {
                            this.liquids = liquidList;
                        }.bind(this));
                    },
                    completeOrder: function(id) {
                        $.post('/admin/store/touch/complete', {'id': id}, function() {
                            this.refreshLiquids();
                        }.bind(this));
                    },
                    completeAll: function() {
                        var ids = {};
                        this.liquids.forEach(function (liquid) {
                            ids[liquid.id] = liquid.id;
                        }.bind(this));
                        $.post('/admin/store/touch/complete', ids, function() {
                            this.refreshLiquids();
                        }.bind(this));
                    },

                },

                ready: function() {
                    socket.on('touch:App\\Events\\LiquidProductCreated', function(data) {
                        this.liquids.push(data.liquid);
                    }.bind(this));

                    socket.on('touch:App\\Events\\LiquidProductDeleted', function(data) {
                        this.liquids = this.liquids.filter(function (item) {
                            return data.liquid.id != item.id;
                        });
                    }.bind(this));

                    this.refreshLiquids();
                }
            });
        </script>
    </body>
</html>