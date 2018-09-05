<html>
    <head>
    <meta charset="utf-8">
        <script src="https://apis.google.com/js/client.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>
            function auth() {
                var config = {
                    'client_id': '765521841797-0e60q5uom07bg3ec0t1943lro44rmve9.apps.googleusercontent.com',
                    'scope': 'https://www.googleapis.com/auth/contacts.readonly'
                };
                
                gapi.auth.authorize(config, function () {
                    fetch(gapi.auth.getToken());
                });
            }
            function fetch(token) {
                console.log(token);
                $.ajax({
                    url: 'https://www.google.com/m8/feeds/contacts/default/full?alt=json',
                    dataType: 'jsonp',
                    data: token
                }).done(function (data) {
                    console.log(JSON.stringify(data));
                    $('#contacts').html(data);
                });
            }
        </script>
    </head>

    <body>
        <button onclick="auth();">Ver contactos</button>
        <div id="contacts"></div>
    </body>
</html>