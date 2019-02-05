<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SignUp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/css/foundation.min.css" integrity="sha256-1mcRjtAxlSjp6XJBgrBeeCORfBp/ppyX4tsvpQVCcpA= sha384-b5S5X654rX3Wo6z5/hnQ4GBmKuIJKMPwrJXn52ypjztlnDK2w9+9hSMBz/asy9Gw sha512-M1VveR2JGzpgWHb0elGqPTltHK3xbvu3Brgjfg4cg5ZNtyyApxw/45yHYsZ/rCVbfoO5MSZxB241wWq642jLtA==" crossorigin="anonymous">
    <script class="jsbin" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.5.1/dist/js/foundation.min.js" integrity="sha256-WUKHnLrIrx8dew//IpSEmPN/NT3DGAEmIePQYIEJLLs= sha384-53StQWuVbn6figscdDC3xV00aYCPEz3srBdV/QGSXw3f19og3Tq2wTRe0vJqRTEO sha512-X9O+2f1ty1rzBJOC8AXBnuNUdyJg0m8xMKmbt9I3Vu/UOWmSg5zG+dtnje4wAZrKtkopz/PEDClHZ1LXx5IeOw==" crossorigin="anonymous"></script>
</head>
<body>
    <div id="signup_page" class="container">
        <div id="signup_content">
            <form action="signup_submit.php" method="post">
                <div class="grid-x grid-margin-x">
                    <div class="cell medium-6 medium-offset-3">
                        <h1>Sign Up</h1>
                        <div class="grid-x">
                            <div class="cell small-12">
                                <input type="text" placeholder="Username" name="username" required>
                            </div>
                        </div>
                        <div class="grid-x">
                            <div class="cell small-12">
                                <input type="email" placeholder="Email" name="email" required>
                            </div>
                        </div>
                        <div class="grid-x">
                            <div class="cell small-12">
                                <input type="password" placeholder="Password" name="pwd" required>
                            </div>
                        </div>
                        <div class="grid-x">
                            <div class="cell small-12">
                                <button type="submit" id="signup_confirm" class="button expanded">Sign Up</button>
                            </div>
                        </div>
                        <div class="grid-x">
                            <div class="cell small-12">
                                <div class="text-center">
                                    Already signed up?
                                    <a href="index.php">Log in</a>
                                    .
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cell medium-2"></div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>