<!-- Luka Tomanovic 0410/2018
     Kosta Matijevic 0034/2018
     Andrej Gajic 0303/2018    -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Bela berza</title>
    <link rel="icon" href="../assets/images/logo.png" sizes="32x32" type="image/png">
    

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/login_style.css">


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    
</head>
<body>
    <div class="login-wrap">
        <div class="login-html">
            <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">Prijava</label>
            <input id="tab-2" type="radio" name="tab" class="for-pwd"><label for="tab-2" class="tab">Registracija</label>
            
            <div class="login-form">
                <div class="sign-in-htm">
                    <form action="LoginController/login" name="login" method="post">
                        <div class="form-group row">
                            <label for="nameInputField" class="col-sm-4 col-form-label">Email/username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nameInputField" name="emailLogin"
                                    placeholder="petarpan@risingedge.rs" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surnameInputField" class="col-sm-4 col-form-label">Lozinka</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="surnameInputField" name="passwordLogin"
                                    placeholder="lozinka" value="" required>
                            </div>
                        </div>
                        
                        <div class="sign-in-btn">
                            <button class="btn btn-outline-success form-control" type="submit">Prijavi se</button>
                        </div>
                        <br>
                        <br>
                        <?php
                        $session = session(); 
                        if($session->getFlashdata("registrationError")!=null) { 
                            echo '<div class="row">';
                            echo    '<div class="col-12" id="registrationError">';
                            echo        '<div class="alert alert-danger alert-dismissible">';
                            echo            '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                            echo            $session->getFlashdata("registrationError");
                            echo        '</div>';
                            echo    '</div>';
                            echo '</div>';
                        }
                        else if($session->getFlashdata("registrationSuccess")!=null) {
                            echo '<div class="row">';
                            echo    '<div class="col-12" id="registrationSuccess">';
                            echo        '<div class="alert alert-success alert-dismissible">';
                            echo            '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                            echo            $session->getFlashdata("registrationSuccess");
                            echo        '</div>';
                            echo    '</div>';
                            echo '</div>';
                        }
                        else if($session->getFlashData("loginError") != null) {
                            echo '<div class="row">';
                            echo    '<div class="col-12" id="loginError">';
                            echo        '<div class="alert alert-danger alert-dismissible">';
                            echo            '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                            echo            $session->getFlashdata("loginError");
                            echo        '</div>';
                            echo    '</div>';
                            echo '</div>';
                        }
                        ?>

                    </form>
                    <div class="hr"></div>
                </div>
                <div class="for-pwd-htm">
                    <form action="LoginController/registration" name="registration" method="post">
                        <div class="form-group row">
                            <label for="nameInputField" class="col-sm-4 col-form-label">Ime</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nameInputField" name="nameReg"
                                    placeholder="npr. Pera" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surnameInputField" class="col-sm-4 col-form-label">Prezime</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="surnameInputField" name="surnameReg"
                                    placeholder="npr. Peric" value="" required>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="usernameInputField" class="col-sm-4 col-form-label">Korisnicko ime</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="usernameInputField" name="usernameReg"
                                    placeholder="npr. peraperic" value="" required>
                            </div>
                        </div>
                        
                        
                        <div class="form-group row">
                            <label for="nameInputField" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="nameInputField" name="emailReg"
                                    placeholder="example@domain.com" value="" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surnameInputField" class="col-sm-4 col-form-label">Lozinka</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="surnameInputField" name="passwordReg"
                                    placeholder="lozinka" value="" required>
                            </div>
                        </div>
                        <div class="register-btn">
                            <button class="btn btn-outline-danger form-control" type="submit">Potvrdi</button>
                        </div>
                        

                    </form>
                    <div class="hr"></div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; RisingEdge 2021</p>
    </footer>
    
</body>
</html>