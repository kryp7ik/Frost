<html>
    <head>
        <title>Test</title>
    </head>
    <body>
        <h1> New Users</h1>
        <ul>
            <li v-repeat="user: users">@{{ user }}</li>
        </ul>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/0.12.16/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.5.1/socket.io.min.js"></script>

        <script>
            var socket = io('127.0.0.1:3000');
            new Vue({
                el: 'body',

                data: {
                    users: []
                },

                ready: function() {
                    socket.on('test:App\\Events\\UserSignedUp', function(data) {
                        console.log(data);
                        this.users.push(data.username);
                    }.bind(this));
                }
            })
        </script>
    </body>
</html>