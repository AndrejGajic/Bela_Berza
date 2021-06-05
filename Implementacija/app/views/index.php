
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

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ"
        crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
        integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY"
        crossorigin="anonymous"></script>
    <script src="../assets/js/chart.js"></script>
</head>

<body onload="displayGraph()">
    
    <?php
    
        $i = 0;
        for ($i = 0; $i < 30; $i++) {
        }
    ?>
    
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
                    <li class="active-item">
                        <a href="" class="menu-item">
                            <i class="fas fa-chart-line"></i>
                            <span class="label">Berza</span>
                        </a>
                    </li>
                    <?php if($menu=='standard' || $menu=='privileged') echo('
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
                    <li>
                        <a href="#userMenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle menu-item">
                            <i class="fas fa-user"></i>
                            <span>Moj profil</span>
                        </a>
                        <ul class="collapse list-unstyled" id="userMenu">
                            <li>
                                <a href="profile">Izmeni</a>
                            </li>
                            <li>
                                <a href="privileges">Privilegije</a>
                            </li>
                            <li>
                                <a href="login">Izloguj se</a>
                            </li>       
                        </ul>
                    </li>
                     ')?>
                     <?php if($menu=='guest') echo('
                     <li>
                        <a href="login" class="menu-item">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    </li>
                    <li>
                        <a href="login" class="menu-item">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    </li>
                    ')?>
                    <?php if($menu=='admin') echo('
                    <li>
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
                     ')?>
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
                
                <?php
                    $session = session();
                    if ($session->getFlashdata('buyingStockError') != null) {
                        echo '<div class="row">';
                        echo '<div class="col-12" id="buyingStockError">';
                        echo '<div class="alert alert-danger alert-dismissible">';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '<strong>Neuspešna kupovina akcije/a!</strong>&nbsp' . $session->getFlashdata('buyingStockError');
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    } else if ($session->getFlashdata('buyingStockSuccess') != null) {
                        echo '<div class="row">';
                        echo '<div class="col-12" id="buyingStockSuccess">';
                        echo '<div class="alert alert-success alert-dismissible">';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '<strong>' . $session->getFlashdata('buyingStockSuccess') . '</strong>&nbsp';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                ?>

                <div class="row stocks">
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <a href="<?php echo site_url('Home/setChartTarget/1') ?>">
                            <img src="../assets/images/microsoft.png" alt="">
                        </a>
                        <p class="price">
                            <?php
                                echo($MSFT);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($MSFTR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">
                            <?php
                                echo($MSFTR);
                            ?>
                            %</p>
                        <button id="btnMicrosoft" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter"
                            onclick="setImageModal('../assets/images/microsoft.png', <?php echo($MSFT); ?>,'MSFT')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/apple.png" alt="">
                        <p class="price">
                            <?php
                                echo($AAPL);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($AAPLR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">
                            <?php
                                echo($AAPLR);
                            ?>
                            %</p>
                        <button id="btnApple" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter" 
                            onclick="setImageModal('../assets/images/apple.png', <?php echo($AAPL); ?>,'AAPL' )">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/amazon.png" alt="">
                        <p class="price">
                            <?php
                                echo($AMZN);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($AMZNR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($AMZNR);
                            ?> 
                            %</p>
                        <button id="btnAmazon" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter"
                            onclick="setImageModal('../assets/images/amazon.png', <?php echo($AMZN); ?>,'AMZN')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/google.png" alt="">
                        <p class="price">
                            <?php
                                echo($GOOGL);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($GOOGLR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($GOOGLR);
                            ?> %</p>
                        <button id="btnGoogle" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter"
                            onclick="setImageModal('../assets/images/google.png', <?php echo($GOOGL); ?>,'GOOGL')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/facebook.png" alt="">
                        <p class="price">
                            <?php
                                echo($FB);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($FBR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($FBR);
                            ?> %</p>
                        <button id="btnFacebook" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter"
                            onclick="setImageModal('../assets/images/facebook.png', <?php echo($FB); ?>,'FB')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/uber.png" alt="">
                        <p class="price">
                            <?php
                                echo($UBER);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($UBERR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($UBERR);
                            ?> %</p>
                        <button id="btnUber" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter" 
                            onclick="setImageModal('../assets/images/uber.png', <?php echo($UBER); ?>,'UBER')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/intel.png" alt="">
                        <p class="price">
                            <?php
                                echo($INTC);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($INTCR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($INTCR);
                            ?> %</p>
                        <button id="btnIntel" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter" 
                            onclick="setImageModal('../assets/images/intel.png', <?php echo($INTC); ?>,'INTC')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/tesla.png" alt="">
                        <p class="price">
                            <?php
                                echo($TSLA);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($TSLAR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($TSLAR);
                            ?> %</p>
                        <button id="btnTesla" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter" 
                            onclick="setImageModal('../assets/images/tesla.png', <?php echo($TSLA); ?>,'TSLA')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/bmw.png" alt="">
                        <p class="price">
                            <?php
                                echo($BAMXF);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($BAMXF >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($BAMXFR);
                            ?> %</p>
                        <button id="btnBMW" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter" 
                            onclick="setImageModal('../assets/images/bmw.png', <?php echo($BAMXF); ?>,'BAMXF')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/mcdonalds.png" alt="">
                        <p class="price">
                            <?php
                                echo($MCD);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($MCDR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($MCDR);
                            ?> %</p>
                        <button id="btnMcDonalds" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter"
                            onclick="setImageModal('../assets/images/mcdonalds.png', <?php echo($MCD); ?>)">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/samsung.png" alt="">
                        <p class="price">
                            <?php
                                echo($SSNLF);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($SSNLFR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">                            
                            <?php
                                echo($SSNLFR);
                            ?> %</p>
                        <button id="btnSamsung" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter"
                            onclick="setImageModal('../assets/images/samsung.png', <?php echo($SSNLF); ?>,'SSNLF')">KUPI</button>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 col-xl-1 stock">
                        <img src="../assets/images/xiaomi.png" alt="">
                        <p class="price">
                            <?php
                                echo($XIACF);
                            ?>
                            	&#36;
                        </p>
                        <p class="price-diff-percentage <?php if ($XIACFR >= 0) { echo "diff-positive"; } else { echo "diff-negative"; }  ?>">
                            <?php
                                echo($XIACFR);
                            ?>
                            %</p>
                        <button id="btnXiaomi" class="btn-block btn-success" data-toggle="modal"
                            data-target="#exampleModalCenter" 
                            onclick="setImageModal('../assets/images/xiaomi.png', <?php echo($XIACF); ?>,'XIACF')">KUPI</button>
                    </div>

                </div>

                <div class="row analytics">

                    <div id="chartContainer" class="col-12 col-xl-7"></div>

                    <div class="col-12 col-xl-5" id="volatileAsistent">
                            <div id="volatileStocks">
                                <h3 class="vol-table-header">VOLATILE STOCKS</h3>
                                <table class="table">
                                    <tbody>
                                        <?php
                                            foreach ($volatileStocks as $vs) {
                                                echo ('<tr>');
                                                echo ('<td class="vol-logo"><img class="vol-logo-img" src="' . $vs->imagePath . '" alt=""></td>');
                                                echo ('<td class="vol-name">' . $vs->companyName . '</td>');
                                                echo ('<td class="vol-price">' . $vs->value . '&#36' .'</td>');
                                                
                                                $volchange = "vol-change-positive";
                                                if ($vs->rate < 0) {
                                                    $volchange = "vol-change-negative";
                                                }
                                                echo ('<td class="' . $volchange . '">' . $vs->rate .'%</td>');
                                                echo ('                                            
                                                    <td class="vol-buy"><button class="btn-block btn-success"
                                                        data-toggle="modal" data-target="#exampleModalCenter"
                                                        onclick=\'setImageModal("' . $vs->imagePath . '",' . $vs->value . ','.$vs->companyName.')\'>KUPI</button>
                                                    </td>');
                                                echo ('<tr>');
                                            }
                                        ?>
                                        <!--
                                        <tr>
                                            <td class="vol-logo"><img class="vol-logo-img" src="../assets/images/xiaomi.png"
                                                alt=""></td>
                                            <td class="vol-name">Xiaomi</td>
                                            <td class="vol-price">2.61&euro;</td>
                                            <td class="vol-change-negative">-4.40%</td>
                                            <td class="vol-buy"><button class="btn-block btn-success"
                                                    data-toggle="modal" data-target="#exampleModalCenter"
                                                    onclick="setImageModal('xiaomi.png',2.61)">KUPI</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vol-logo"><img class="vol-logo-img" src="../assets/images/uber.png" alt="">
                                            </td>
                                            <td class="vol-name">Uber</td>
                                            <td class="vol-price">45.78&euro;</td>
                                            <td class="vol-change-positive">+2.51%</td>
                                            <td class="vol-buy"><button class="btn-block btn-success"
                                                    data-toggle="modal" data-target="#exampleModalCenter"
                                                    onclick="setImageModal('uber.png',45.78)">KUPI</button></td>
                                        </tr>
                                        <tr>
                                            <td class="vol-logo"><img class="vol-logo-img" src="../assets/images/facebook.png"
                                                    alt=""></td>
                                            <td class="vol-name">Facebook</td>
                                            <td class="vol-price">236.79&euro;</td>
                                            <td class="vol-change-negative">-1.21%</td>
                                            <td class="vol-buy"><button class="btn-block btn-success"
                                                    data-toggle="modal" data-target="#exampleModalCenter"
                                                    onclick="setImageModal('facebook.png',236.79)">KUPI</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="vol-logo"><img class="vol-logo-img" src="../assets/images/tesla.png"
                                                    alt=""></td>
                                            <td class="vol-name">Tesla</td>
                                            <td class="vol-price">544.01&euro;</td>
                                            <td class="vol-change-positive">+1.51%</td>
                                            <td class="vol-buy"><button class="btn-block btn-success"
                                                    data-toggle="modal" data-target="#exampleModalCenter"
                                                    onclick="setImageModal('tesla.png',544.01)">KUPI</button>
                                            </td>
                                        </tr>
                                        !-->
                                    </tbody>
                                </table>
                            </div>
                            <div class="container-fluid" id="asistentWrapper">
                                <div class="row" id="asistentHeader"> <h3>TRADE ASSISTANT</h3></div>
                                <div class="row <?php echo (' '.$assistantClass);?>" id="asistent"> </div>
                                 <?php 
                                    if($showPromo) { echo('
                                        <div class="row" id="asistentPromo"> 
                                            <a href="privileges"> <button class="btn-block btn-success">OSTVARI PRIVILEGIJE</button></a> 
                                        </div>');
                                    } else {
                                        
                                    }
                                 ?>
                            </div>
                    </div>

                </div>




            </div>
        </div>
    </div>


    <footer>
        <p>&copy; RisingEdge 2021</p>
    </footer>

    <!-- Modals -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form name="buyStocks" method="post" action="<?= site_url("Home/buyStock") ?>">
                    <input type="hidden" id="stockName" name="stockName">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Kupi akcije</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body container">
                        <div class="row mb-2">
                            <div class="col-12  mb-2">
                                <img id="modalStockImage" src="" alt="">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button id="quantityminus" type="button" class="btn btn-danger btn-number" data-type="minus"
                                            data-field="stock-quantity">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </span>
                                    <input id="quantityInputTextField" type="text" name="stock-quantity"
                                        class="form-control input-number" value="1" min="1" max="100">
                                    <span class="input-group-btn">
                                        <button id="quantityplus" type="button" class="btn btn-success btn-number" data-type="plus"
                                            data-field="stock-quantity">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9">
                                <p>Price per stock: </p>
                            </div>
                            <div class="col-3">
                                <p id="pricePerStock">100&euro;</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-9">
                                <p class="total-price">Total amount: </p>
                            </div>
                            <div class="col-3">
                                <p id="totalPrice" class="total-price">100&euro;</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                        <button type="submit" class="btn btn-outline-success">Potvrdi</button>
                    </div>
                </form>
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
    <script src="../assets/js/quantity_button.js"></script>
</body>

</html>