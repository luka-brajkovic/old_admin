<?php
$greske = array();

if ($f->verifyFormToken('form1')) {

    $email = $f->getValue("email");
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($greske, "email");
    } else {
        $email = $f->test_input($email);
    }

    $pass = $f->getValue("pass");
    if (strlen($pass) < 6) {
        array_push($greske, "pass");
    }
    if (empty($greske)) {
        $userData = new View("_content_users", $email, "e-mail");
        if ($userData->id) {
            if ($userData->lozinka != md5($pass)) { /* OVDE PROMENI KAKO SE ZOVE LOZINKA U ADMINU */
                array_push($greske, "pogresno");
            } else if ($userData->status == 2) {
                array_push($greske, "ne_aktiviran");
                $my_query = mysqli_query($conn, "SELECT `e-mail` FROM _content_users WHERE id = $userData->id");
                $email_neaktivan = mysqli_fetch_array($my_query);
                $email_neaktivan = $email_neaktivan["e-mail"];
            } else {
                $_SESSION["loged_user"] = $userData->id;
                $sessionID = session_id();
                $korpa = new View("korpa", $sessionID, 'session_id');
                if (!empty($korpa->id)) {
                    $f->redirect('/korpa-prijava');
                } else {
                    $_SESSION['infoTitle'] = "<h1>Dobrodošli</h1>";
                    $_SESSION['infoText'] = "<p>Poštovani, želimo Vam prijatno korišćenje naše internet prodavnice.</p>";
                    $f->redirect("/");
                }
            }
        } else {
            array_push($greske, "ne_postoji");
        }
    }
}


?>
<script>
    // This is called with the results from from FB.getLoginStatus().
    function statusChangeCallback(response) {
        console.log('statusChangeCallback');
        console.log(response);
        // The response object is returned with a status field that lets the
        // app know the current login status of the person.
        // Full docs on the response object can be found in the documentation
        // for FB.getLoginStatus().
        if (response.status === 'connected') {

            if (response.status === 'connected') {
                var user_id = response.authResponse.userID;
                var tk = response.authResponse.accessToken;
                var broj;
                $.ajax({
                    type: "POST",
                    async: false,
                    url: "/work.php",
                    data: "id=" + user_id + "&tk=" + tk + "&action=login_fb",
                    success: function (msg) {
                        console.log('OVO: ' + msg);
                        broj = msg;
                    }
                });

                broj = parseInt(broj);


                console.log("before testing")
                testAPI();

                if (broj == 0) {

                } else {

                }
            } else {
                alert("Morate da dozvolite pristupe Vašim podacima sa Facebook-a kako bi se uspešno registrovali!");
            }


        } else {
            // The person is not logged into your app or we are unable to tell.
            document.getElementById('status').innerHTML = 'Please log ' +
                'into this app.';
        }
    }

    // This function is called when someone finishes with the Login
    // Button.  See the onlogin handler attached to it in the sample
    // code below.
    function checkLoginState() {
        FB.getLoginStatus(function(response) {
            statusChangeCallback(response);
            console.log('Welcome!  Fetching your information.... ');
            var url = '/me';
            FB.api(url, {fields: 'name,email'}, function(response) {
                alert(response.name + " " + response.id + " " +response.email);
            }, {scope: 'email'});
        });
    }

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '144319329541447',
            cookie     : true,  // enable cookies to allow the server to access
                                // the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.11' // use graph api version 2.8
        });

        // Now that we've initialized the JavaScript SDK, we call
        // FB.getLoginStatus().  This function gets the state of the
        // person visiting this page and can return one of three states to
        // the callback you provide.  They can be:
        //
        // 1. Logged into your app ('connected')
        // 2. Logged into Facebook, but not your app ('not_authorized')
        // 3. Not logged into Facebook and can't tell if they are logged into
        //    your app or not.
        //
        // These three cases are handled in the callback function.

//        FB.getLoginStatus(function(response) {
//            statusChangeCallback(response);
//        });

    };

    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    // Here we run a very simple test of the Graph API after login is
    // successful.  See statusChangeCallback() for when this call is made.
    function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me?fields=name,email', function(response) {
            console.log(response);
            document.getElementById('status').innerHTML =
                'Thanks for logging in, ' + response.name + response.email+'!';
        });

    }

    function fb_logout() {
        FB.logout(function (response) {
            $.ajax({
                type: "POST",
                async: false,
                url: "/work.php",
                data: "action=logout_fb",
                success: function (msg) {
                    location.href = '/';
                }
            });
        });
    }
</script>
<div class="container">
    <ul class="pagePosition clear" itemtype="http://data-vocabulary.org/BreadcrumbList">
        <li>
            <span>Vi ste ovde:</span>
        </li>
        <li itemprop="itemListElement" itemscope="itemscope" itemtype="http://schema.org/ListItem">
            <a title="Početna" href="/" itemprop="url">
                <span itemprop="name">Početna</span>
            </a>
        </li>
        <li>
            <span itemprop="name">Prijava</span>
        </li>
    </ul>
    <h1>Dobrodošli na <?= $csName; ?><a class="right" style="line-height: 34px;" title="<?= $csDomain; ?>" href="/">Natrag na naslovnu stranicu</a></h1>
    <div class="row">
        <div class="logCont third">
            <div class="inner">
                <h4>Prijava postojećeg korisnika:</h4>
                <div class="gray">
                    <?php
                    if (in_array("ne_aktiviran", $greske)) {
                        echo "<em style='display:block;'>* Nalog nije aktiviran. Poslali smo Vam e-mail sa aktivacionim linkom, pokušajte da ga pronađete u Vašem inboxu.<br/><br/>Ukoliko ga ne možete pronaći kliknite na <a href='/posalji-aktivaciju'>pošalji aktivaciju</a> opet.</em>";
                    }
                    ?>    
                    <?php
                    if (in_array("pogresno", $greske)) {
                        echo "<em style='display:block;'>* Uneli ste pogresne podatke. Pokusajte ponovo</em>";
                    }
                    ?>
                    <?php
                    if (in_array("ne_postoji", $greske)) {
                        echo "<em style='display:block;'>* Nalog sa ovim e-mailom ne postoji. Pokusajte ponovo</em>";
                    }

                    $newToken = $f->generateFormToken('form1');
                    ?>
                    <form action="<?php echo htmlspecialchars("/prijava"); ?>" method="post">
                        <input type="hidden" name="okinuto" value="1" />
                        <p>
                            <label>E-mail adresa:</label>
                            <input type="text" name="email" value="<?= $email; ?>" />
                            <?php
                            if (in_array("email", $greske)) {
                                echo "<em style='display:block;'>* Molimo unesite ispravnu e-mail adresu</em>";
                            }
                            ?>
                        </p>
                        <p>
                            <label>Lozinka:</label>
                            <input type="password" name="pass" value="" />
                            <?php
                            if (in_array("pass", $greske)) {
                                echo "<em style='display:block;'>* Molimo unesite ispravnu lozinku</em>";
                            }
                            ?>
                        </p>
                        <p>
                            <a id="forgotPass" href="javascript:void(0);">Zaboravljena lozinka?</a>    
                        </p>
                        <input class="more" type="hidden" value="<?= $_GET['ref']; ?>" name="ref" />
                        <input class="more" type="submit" value="Prijavi se" />
                        <input type="hidden" name="token" value="<?= $newToken; ?>">
                    </form>
                    <div class="newUser">
                        <p class="reg"><strong>Novi korisnik?</strong><br />Pridružite se hiljadama naših kupaca koji već uživaju u svim prednostima on-line kupovine.</p>
                        <a class="moreSecund" href="/registracija" title="">Registruj se</a>

                        <a class="faceLogin" onclick="fb_login()" href="#">
                            <i class="fa fa-facebook"></i> Log In
                        </a>

                        <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
                        </fb:login-button>


                        <div id="status">
                        </div>

                        <button onclick="fb_logout()">logout</button>
                    </div>
                </div>    
            </div>
        </div>
        <div class="third-x2">
            <div class="inner pictureRight">
                <?= ($db->getValue("text", "_content_html_blocks", "resource_id", "2") != '') ? $db->getValue("text", "_content_html_blocks", "resource_id", "2") : ""; ?>
            </div>    
        </div>
    </div>
</div>



<script>
//    window.fbAsyncInit = function () {
//        FB.init({
//            appId: '1710815072558243', // App ID
//            channelUrl: '//www.bigputovanja.com/', // Channel File
//            status: true, // check login status
//            cookie: true, // enable cookies to allow the server to access the session
//            oauth: true,
//            xfbml: false, // parse XFBML
//            version: 'v2.8'
//        });
//        FB.AppEvents.logPageView();
//    };
//
//    // Load the SDK asynchronously
//    (function (d, s, id) {
//        var js, fjs = d.getElementsByTagName(s)[0];
//        if (d.getElementById(id))
//            return;
//        js = d.createElement(s);
//        js.id = id;
//        js.src = "//connect.facebook.net/sr_RS/all.js#xfbml=1&appId=1710815072558243";
//        fjs.parentNode.insertBefore(js, fjs);
//    }(document, 'script', 'facebook-jssdk'));
//
//    function fb_login() {
//        FB.login(function (response) {
//
//            if (response.authResponse) {
//                var user_id = response.authResponse.userID;
//                var tk = response.authResponse.accessToken;
//                var broj;
//                $.ajax({
//                    type: "POST",
//                    async: false,
//                    url: "/work.php",
//                    data: "id=" + user_id + "&tk=" + tk + "&action=login_fb",
//                    success: function (msg) {
//                        console.log('OVO: ' + msg);
//                        broj = msg;
//                    }
//                });
//
//                broj = parseInt(broj);
//
//                if (broj == 0) {
//                    window.location = '/<?//= ($canonical != "") ? $canonical : ltrim($_SERVER['REQUEST_URI'], "/"); ?>//';
//                } else {
//                    window.location = '/<?//= ($canonical != "") ? $canonical : ltrim($_SERVER['REQUEST_URI'], "/"); ?>//';
//                }
//            } else {
//                alert("Morate da dozvolite pristupe Vašim podacima sa Facebook-a kako bi se uspešno registrovali!");
//            }
//
//        }, {scope: 'email'});
//    }
//    function fb_logout() {
//        FB.logout(function (response) {
//            $.ajax({
//                type: "POST",
//                async: false,
//                url: "/work.php",
//                data: "action=logout_fb",
//                success: function (msg) {
//                    location.href = '/';
//                }
//            });
//        });
//    }



</script>