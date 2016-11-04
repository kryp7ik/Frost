<!DOCTYPE html>
<html>
    <head>
        <title>FrostPOS: Touch</title>
    </head>
    <body>

        <ul>
            <li v-for="liquid in liquids">
                @{{ liquid.recipe + ' ' + liquid.size }}
            </li>
        </ul>
        @{{ message }}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.28/vue.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.1/socket.io.min.js"></script>

        <script>
            var socket = io('127.0.0.1:3000');
            var app = new Vue({
                el: 'body',

                data: {
                    liquids: [],
                    message: 'tre'
                },

                methods: {
                    removeLiquid(id) {
                        this.liquids.forEach(function(liquid, index) {
                        if(id == liquid.id) {
                            this.liquids.$remove(liquid);
                        }
                    }
                },

                ready: function() {
                    socket.on('touch:App\\Events\\LiquidProductCreated', function(data) {
                        console.log(data);
                        this.liquids.push(data.liquid);
                    }.bind(this));

                    socket.on('touch:App\\Events\\LiquidProductDeleted', function(data) {
                        console.log(data);


                    }.bind(this));
                }
            })
        </script>
    </body>
</html>