var post; 

jQuery(function ($) {

    $("#f-login").validate({
        rules: {
            email: {
                required: true,
                minlength: 4,
                maxlength: 40
            },
            pass: {
                required: true,
                minlength: 4,
                maxlength: 20
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("has-error").parent().addClass("has-error");
        },

        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                async: false,
                url: "usuarios.html",
                data: $(form).serialize(),
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    window.location.href = url_web_plataforma;
                }
            });
        }
    });

    $("#f-remember").validate({
        rules: {
            email: {
                required: true,
                minlength: 4,
                maxlength: 40
            }
        },
        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                async: false,
                url: "ajax/remember.php",
                data: $(form).serialize(),
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        $("#error-login3").html("Se ha enviado un mail con la confirmacion.");
                    } else {
                        $("#error-login3").html(data.message);
                    }
                }
            });
        }
    });


    $("#f-create").validate({
        rules: {
            name: {
                required: true,
                minlength: 4,
                maxlength: 15
            },
            email: {
                required: true,
                minlength: 4,
                maxlength: 40
            },
            pass: {
                required: true,
                minlength: 4,
                maxlength: 20
            },
            terms: {
                required: true
            }
        },
        highlight: function (element, errorClass) {
            $(element).addClass("has-error").parent().addClass("has-error");
        },

        submitHandler: function (form) {
            $.ajax({
                type: "POST",
                async: false,
                url: "ajax/register.php",
                data: $(form).serialize(),
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    if (data.error == 0) {
                        window.location.href = '' + url_web_plataforma + '/template/#crear-proyectos';
                    } else {
                        $("#error-create").text(data.message);
                    }
                }
            });
        }
    });

});

var fblogin = function () {
    FB.login(function (response) {
        console.log(response);
        if (response) {
            statusChangeCallback(response);

            if (response.authResponse) {
                console.log(response.authResponse);
                //successful auth
                //do things like create account, redirect etc.
            } else {
                //unsuccessful auth
                //do things like notify user etc.
            }
        }
    }, {scope: 'public_profile, email'});
}

var googleUser = {};
var startApp = function () {
    gapi.load('auth2', function () {
        // Retrieve the singleton for the GoogleAuth library and set up the client.
        auth2 = gapi.auth2.init({
            client_id: google_id,
            //cookiepolicy: 'single_host_origin',
            // Request scopes in addition to 'profile' and 'email'
            scope: 'profile email'
        });

        attachSignin(document.getElementsByClassName('google')[0]);
    });   
};

function attachSignin(element) {
    //console.log(element.id);
    auth2.attachClickHandler(element, {},
            function (googleUser) {
                onSuccess(googleUser);
                //document.getElementById('hi').innerText = ' '+googleUser.getBasicProfile().getName();
            }, function (error) {
        //alert(JSON.stringify(error, undefined, 2));
    });
}
startApp();

function onSuccess(user) {
    var scope = user.getGrantedScopes();
    console.log(scope);
    var profile = user.getBasicProfile();
    var image = encodeURIComponent(profile.getImageUrl());
    var post = 'us_codigo_ref=' + profile.getId() + '\
        &us_nombre=' + profile.getName() + '\
        &us_email=' + profile.getEmail() + '\
        &us_last_name=' + profile.getFamilyName() + '\
        &us_sexo=' + '\
        &us_foto=' + image +'\
        &us_descripcion=google';
    
    postLogin(post);
}

function postLogin(post) {  
    console.log(post);
    $.ajax({
        type: "POST",
        url: "ajax/register_network.php",
        data: post,
        success: function (data) {
            console.log(data);
            window.location.href = ''+url_web_plataforma+'/'+ data.trim();
        }
    });
}

function postFriends(post) {
    $.ajax({
        type: "POST",
        url: "ajax/post_list_friends.php",
        data: post,
        success: function () {
            //console.log({redirect: url_web_plataforma});
            //window.location.href = ''+url_web_plataforma;
        }
    });    
}

function onSuccessFB() {
    FB.api('/me?fields=about,id,name,last_name,gender,picture,email,age_range,link', 
        function (response) {
            console.log(response);
            var name = response.name.split(' ');
            var post = 'funcion=login_usuario\n\
                &us_codigo_ref=' + response.id + '\
                &us_nombre=' + name[0] + '\
                &us_email=' + response.email + '\
                &us_last_name=' + response.last_name + '\
                &us_sexo=' + response.gender + '\
                &us_login=' + response.email + '\
                &us_foto=' + response.picture.data.url +'\
                &us_descripcion=facebook';        

            onSuccessFriends(response.id);
            postLogin(post);
        }
    );    
}

function onSuccessFriends(id) {
    FB.api("/me/friends",
        function (response) {
            console.log(response);
            if (response && !response.error) {
                postFriends({id: id, friends: response});
            }
        }
    );
}


function onFailure(error) {
    console.log(error);
}

function statusChangeCallback(response) {
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
        // Logged into your app and Facebook.
        //testAPI();
        onSuccessFB();
    } else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not your app.
        //document.getElementById('status').innerHTML = 'Please log ' +
        //'into this app.';
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        //document.getElementById('status').innerHTML = 'Please log ' +
        //'into Facebook.';
    }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
}

window.fbAsyncInit = function () {
    FB.init({
        appId: facebook_id,
        cookie: true, // enable cookies to allow the server to access 
        // the session
        xfbml: true, // parse social plugins on this page
        version: 'v2.7' // use graph api version 2.5
    });
};

// Load the SDK asynchronously
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/es_LA/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));