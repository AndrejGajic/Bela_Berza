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
    <link rel="stylesheet" href="../assets/css/confirm_style.css">

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
                <div id="user-image"><img src="<?php echo $imgPath ?>" alt=""/></div>
                <div id="user-data-info">
                    <h3><?php echo $username ?></h3>
                    <p>Administrator</p>
                </div>
                <strong>A</strong>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="home" class="menu-item">
                        <i class="fas fa-chart-line"></i>
                        <span class="label">Berza</span>
                    </a>
                </li>
                <li class="active-item">
                    <a href="regconfirmation" class="menu-item">
                        <i class="fas fa-address-book"></i>
                        <span>Pregled registracija</span>
                    </a>
                </li>
                <li>
                    <a href="stocktransactions" class="menu-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span>&nbsp;Pregled transakcija</span>
                    </a>

                </li>
                <li>
                    <a href="login" class="menu-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="label">Izloguj se</span>
                    </a>
                </li>
                <li>
                <li>
                    <a href="#" class="menu-item">
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


            <div class="container" id="confirmContainer">
                <div class="row" id="confirmTableName">
                    <h2>PREGLED REGISTRACIJA NA ČEKANJU</h2>
                </div>
                <?php
                if (!$error) {
                    echo('
                         <div class="row" id="confirmTable">
                            <table class="table">
                                 <thead class="thead-dark">
                                     <tr>
                                         <th scope="col">#</th>
                                         <th scope="col">Korisničko ime</th>
                                         <th scope="col">Datum registracije</th>
                                         <th scope="col">Email</th>
                                         <th scope="col">IP</th>
                                         <th scope="col">Action</th>
                                     </tr>
                                 </thead>
                                 <tbody>');
                }
                ?>

                <?php
                $cnt = 1;
                foreach ($regs as $reg) {
                    $methodCallConfirm = "RegConfirmationController/confirmRegistration/" . $reg->username;
                    $methodCallReject = "RegConfirmationController/rejectRegistration/" . $reg->username;
                    $locationConfirm = site_url($methodCallConfirm);
                    $locationReject = site_url($methodCallReject);
                    $actionConfirm = 'window.location=\''.$locationConfirm.'\'';
                    $actionReject = 'window.location=\''.$locationReject.'\'';
                    $greenBtn = '<a data-toggle="modal" data-target="#confirmModal" onclick="setConfirmModal(\''.$reg->username.'\',\''.$locationConfirm.'\')"><i class="fas fa-check-circle"></i></a>';
                    $redBtn = '<a data-toggle="modal" data-target="#rejectModal" onclick="setRejectModal(\''.$reg->username.'\',\''.$locationReject.'\')"><i class="fas fa-times-circle"></i></a>';

                    echo('<tr><th scope="row">' . $cnt . '</th>
                                        <td>' . $reg->username . '</td>
                                        <td>' . $reg->date . '</td>
                                        <td>' . $reg->email . '</td>
                                        <td>' . $reg->ipAddress . '</td>
                                        <td>' . $greenBtn . $redBtn . '</td>');
                    $cnt++;
                }
                ?>
                <?php if ($error) {
                    echo('<div class="alert alert-warning" role="alert">' . $error . '</div>');
                } else {
                    echo('</tbody></table> </div>');
                } ?>
            </div>

        </div>
    </div>
</div>

<footer>
    <p>&copy; RisingEdge 2021</p>
</footer>


<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Potvrda registracije</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body container">
                <p id='confirmText'>Da li ste sigurni da zelite da potvrdite registraciju korisnika?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                <button id='confirmBtn'  type="button" class="btn btn-outline-success"
                        onclick="">Potvrdi</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Odbijanje registracije</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body container">
                <p id="rejectText">Da li ste sigurni da zelite da odbijete registraciju korisnika?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                <button id='rejectBtn' type="button" class="btn btn-outline-success"
                        onclick="">Potvrdi</button>
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
<script src="../assets/js/confirmation.js"></script>
<script src="../assets/js/script.js"></script>
</body>

</html>