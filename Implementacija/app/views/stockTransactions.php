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
                <li >
                    <a href="regconfirmation" class="menu-item">
                        <i class="fas fa-address-book"></i>
                        <span>Pregled registracija</span>
                    </a>
                </li>
                <li class="active-item">
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
                    <h2>PREGLED SVIH KUPOVINA I PRODAJA AKCIJA</h2>
                </div>
                <?php
                if (!$error) {
                    echo('
                         <div class="row" id="confirmTable">
                            <table class="table">
                                 <thead class="thead-dark">
                                    <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Korisnik</th>
                                    <th scope="col">Tip transakcije</th>
                                    <th scope="col">Datum i vreme</th>
                                    <th scope="col">Vrsta akcije </th>
                                    <th scope="col">Kolicina</th>
                                    <th scope="col">Iznos</th>
                                    </tr>
                                 </thead>
                                 <tbody>');
                }
                ?>

                <?php
                $cnt = 1;
                foreach ($infos as $info) {

                    echo '<tr>';
                    echo '<td scope="row">'.$cnt.'</td>';
                    echo '<td scope="row">'.$info['username'].'</td>';
                    $cnt++;
                    if($info['type'] == 1) {
                        echo '<td class="wallet-in">Prodaja</td>';
                    }
                    else {
                        echo '<td class="wallet-out">Kupovina</td>';
                    }
                    echo '<td>'.$info['timestamp'].'</td>';
                    echo '<td>'.$info['stockName'].'</td>';
                    echo '<td>'.$info['quantity'].'</td>';
                    if($info['type'] == 1) {
                        echo '<td class="wallet-in">+'.$info['totalPrice'].'&euro;</td>';
                    }
                    else {
                        echo '<td class="wallet-out">-'.$info['totalPrice'].'&euro;</td>';
                    }
                    echo '</tr>';
                }
                ?>
                <?php if ($error) {
                    echo('<div class="alert alert-warning" role="alert">' . $error . '</div>');
                } else {
                    echo('</tbody></table> </div>');
                } ?>
            </div>
            <?php if(!$error) echo('
            <form action="StockTransactionHistoryController/filterTransactions" name="filterTransactions" method="post">
                <div class="row">
                    <div class="col-6 text-center">
                        <label for="tipTransakcije"> Izaberite tip transakcije: &nbsp;&nbsp;</label>
                        <select name="tipTransakcije">
                            <option value="sve">Sve</option>
                            <option value="kupovine">Kupovine</option>
                            <option value="prodaje">Prodaje</option>
                        </select>
                    </div>
                    <div class="col-6 text-left">
                        <button class="btn btn-outline-primary form-control" type="submit">Filtriraj</button>
                    </div>
                </div>
            </form>');?>

        </div>
    </div>
</div>

<footer>
    <p>&copy; RisingEdge 2021</p>
</footer>


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
<script src="../assets/js/quantity_button.js"></script>
</body>

</html>
