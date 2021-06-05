<!-- Luka Tomanovic 0410/2018
     Kosta Matijevic 0034/2018-->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Bela berza</title>
    <link rel="icon" href="../assets/images/logo.png" sizes="32x32" type="image/png">

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
        integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/profile_style.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ"
        crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY"
        crossorigin="anonymous"></script>

</head>

<body>
    <div class="body-wrraper">
        <div class="wrapper">
            <!-- Sidebar  -->
            <nav id="sidebar" class="basic">
                <div id="sidebarSelector" class="sidebar-header">
                    <div id="user-image"><img src="<?php echo $imgPath?>" alt="" /></div>
                    <div id="user-data-info">
                        <h3><?php
                            if($menu=='guest')echo('Gost');
                            else echo($name.' '.$surname);
                            ?></h3>
                        <p><?php
                            if($menu=='standard') echo('Standard user');
                            else if($menu=='privileged') echo('Privileged user');
                            else if($menu=='guest') echo('Guest');
                            else if($menu=='admin') echo('Administrator');
                            ?></p>
                    </div>
                    <strong><?php echo(substr($name,0,1).substr($surname,0,1))?></strong>
                </div>

                <ul class="list-unstyled components">
                    <li>
                        <a href="home" class="menu-item">
                            <i class="fas fa-chart-line"></i>
                            <span class="label">Berza</span>
                        </a>
                    </li>
                    <li>
                        <a href="collection" class="menu-item">
                            <i class="fas fa-briefcase"></i>
                            <span>Moja riznica</span>
                        </a>

                    </li>
                    <li>
                        <a href="wallet" class="menu-item">
                            <i class="fas fa-wallet"></i>
                            <span>Moj novcanik</span>
                        </a>
                    </li>
                    <li class="active-item">
                        <a href="#userMenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle menu-item">
                            <i class="fas fa-user"></i>
                            <span>Moj profil</span>
                        </a>
                        <ul class="collapse list-unstyled " id="userMenu">
                            <li>
                                <a class="active-profile-item" href="#">Izmeni</a>
                            </li>
                            <li>
                                <a href="privileges">Privilegije</a>
                            </li>
                            <li>
                                <a href="<?= site_url("LogoutController/logout") ?>">Izloguj se</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="" class="menu-item">
                            <i class="fas fa-question"></i>
                            <span>FAQ</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Page Content  -->
            <div class="container-fluid" id="contentAndTitle">
                <!-- <button type="button" id="sidebarCollapse" class="btn btn-success">
                    <i class="fas fa-align-left"></i>
                    <span>Meni</span>
                </button>-->

                <div class="row">
                    <div id="sidebarCollapse" class="navbar box col-12">
                        <i class="fas fa-bars"></i>
                        <span>Meni</span>
                    </div>
                </div>


                <div class="row">
                    <header id="siteTitle" class="col-12">
                        <img src="../assets/images/logo.png" alt="">
                        <h1>BELA BERZA</h1>
                    </header>
                </div>

                <hr>


                <div class="container-fluid" id="profContainer">
                    <h4>Email</h4>
                    <hr>
                    <div class="row" id="profName">
                        <div class="col-8">
                            <h1>petarpan@risingedge.rs</h1>
                        </div>
                        <div class="col-1"><i data-toggle="modal" data-target="#emailModal"
                                class="fas fa-edit edit-icon"></i></div>
                    </div>
                    <h4>Password</h4>
                    <hr>
                    <div class="row" id="profPassword">
                        <div class="col-8">
                            <h1>*******</h1>
                        </div>
                        <div class="col-1"><i data-toggle="modal" data-target="#passwordModal"
                                class="fas fa-edit edit-icon"></i></div>
                    </div>
                    <h4>Profile picture</h4>
                    <hr>
                    <div class="row" id="profPic">
                        <div class="col-8"><img id="profilePict" src="../assets/images/user.png" alt="profile picture"></div>
                        <div class="col-1"><i data-toggle="modal" data-target="#pictureModal"
                                class="fas fa-edit edit-icon"></i></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer>
        <p>&copy; RisingEdge 2021</p>
    </footer>


    <!-- Modals -->
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Promeni lozinku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body container" style="display: flex; flex-direction: column;">
                    <input style="margin-top:10px;" type="password" placeholder="Trenutna lozinka">
                    <input style="margin-top:10px;" type="password" placeholder="Nova lozinka">
                    <input style="margin-top:10px;" type="password" placeholder="Potvrdi novu lozinku">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                    <button type="button" class="btn btn-outline-success">Potvrdi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Promeni email adresu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body container" style="display: flex; flex-direction: column;">
                    <input style="margin-top:10px;" type="email" placeholder="Nova email adresa">
                    <input style="margin-top:10px;" type="password" placeholder="Potvrdi lozinku">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                    <button type="button" class="btn btn-outline-success">Potvrdi</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pictureModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Promeni sliku profila</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body container" style="display: flex; flex-direction: row;">
                    <input type="file">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                    <button type="button" class="btn btn-outline-success">Potvrdi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>
    <script src="../assets/js/navbar.js"></script>
    <script type="text/javascript" src="../assets/js/canvasjs.stock.min.js"></script>
    <script src="../assets/js/chart.js"></script>
    <script src="../assets/js/quantity_button.js"></script>
</body>

</html>