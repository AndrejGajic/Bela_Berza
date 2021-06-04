<!-- Luka Tomanovic 0410/2018
     Kosta Matijevic 0034/2018
     Andrej Gajic 0303/2018    -->

<!DOCTYPE html>
<html>
<?php
use App\Models\TransactionModel;
?>
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
    <link rel="stylesheet" href="../assets/css/wallet_style.css">

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
                        echo($name.' '.$surname);
                        ?></h3>
                    <p><?php
                        if($menu=='standard') echo('Standard user');
                        else if($menu=='privileged') echo('Privileged user');
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
                <li class="active-item">
                    <a href="" class="menu-item">
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
                            <a href="<?= site_url("LogoutController/logout") ?>">Izloguj se</a>
                        </li>
                    </ul>
                </li>
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


            <div class="container" id="walletContainer">
                <div class="row">
                    <div class="col-12">
                        <h1>STANJE:&nbsp; <?php echo "$userBalance"; ?> &euro;</h1>
                    </div>
                </div>
                <?php
                $session = session();
                if($session->getFlashdata('transactionError')!=null){
                    echo '<div class="row">';
                    echo    '<div class="col-12" id="transactionError">';
                    echo        '<div class="alert alert-danger alert-dismissible">';
                    echo            '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                    echo            '<strong>Neuspešna transakcija!</strong>&nbsp'.$session->getFlashdata('transactionError');
                    echo        '</div>';
                    echo    '</div>';
                    echo '</div>';
                }else if($session->getFlashdata('transactionSuccess')!=null){
                    echo '<div class="row">';
                    echo    '<div class="col-12" id="transactionSuccess">';
                    echo        '<div class="alert alert-success alert-dismissible">';
                    echo            '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                    echo            '<strong>'.$session->getFlashdata('transactionSuccess').'</strong>&nbsp';
                    echo        '</div>';
                    echo    '</div>';
                    echo '</div>';
                }
                ?>
                <div class="row">
                    <div class="col-6" id="walletInBtn">
                        <button type="button" class=" btn btn-success" data-toggle="modal"
                                data-target="#exampleModalCenter1">UPLATA</button>
                    </div>
                    <div class="col-6" id="walletOutBtn">
                        <button type="button" class=" btn btn-danger" data-toggle="modal"
                                data-target="#exampleModalCenter2">ISPLATA</button>
                    </div>
                </div>
                <div class="row" id="walletTableName">
                    <h2>PREGLED ISTORIJE UPLATA I ISPLATA</h2>
                </div>
                <div class="row" id="walletTable">
                    <table class="table">
                        <?php if($transactions != null) {?>
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tip transakcije</th>
                            <th scope="col">Datum i vreme</th>
                            <th scope="col">Iznos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $cnt = 1;
                        foreach($transactions as $transaction) {
                            echo '<tr>';
                            echo '<th scope="row">'.$cnt.'</th>';
                            $cnt++;
                            if($transaction->type == 0) {
                                echo '<td class="wallet-in">Uplata</td>';
                            }
                            else {
                                echo '<td class="wallet-out">Isplata</td>';
                            }
                            echo '<td>'.$transaction->timestamp.'</td>';
                            if($transaction->type == 0) {
                                echo '<td class="wallet-in">+'.$transaction->amount.'&euro;</td>';
                            }
                            else {
                                echo '<td class="wallet-out">-'.$transaction->amount.'&euro;</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                        <?php }
                        else {
                            $transactionType = $session->get("transactionType");
                            if($transactionType == null || $transactionType == 0) {
                                echo "Nemate ni uplate ni isplate na vasem nalogu!";
                            }
                            else if($transactionType == 1) {
                                echo "Nema uplata na vas nalog!";
                            }
                            else {
                                echo "Nema isplata sa vaseg naloga!";
                            }
                        } ?>
                        </tbody>
                    </table>
                </div>
                <form action="WalletController/filter" name="filter" method="post">
                    <div class="row">
                        <div class="col-6 text-center">
                            <label for="tip"> Izaberite tip transakcije: &nbsp;&nbsp;</label>
                            <select name="tip">
                                <option value="sve">Sve</option>
                                <option value="uplate">Uplate</option>
                                <option value="isplate">Isplate</option>
                            </select>
                        </div>
                        <div class="col-6 text-left">
                            <button class="btn btn-outline-primary form-control" type="submit">Filtriraj</button>
                        </div>
                    </div>
                </form>
                <!-- PREGLED KUPLJENIH I PRODATIH AKCIJA ULOGOVANOG KORISNIKA -->
                <div class="row" id="walletTableName">
                    <h2>PREGLED TRANSAKCIJA (kupljenih i prodatih akcija)</h2>
                </div>
                <div class="row" id="walletTable">
                    <table class="table">
                        <?php if($actions != null) {?>
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tip transakcije</th>
                            <th scope="col">Datum i vreme</th>
                            <th scope="col">Naziv akcije </th>
                            <th scope="col">Kolicina</th>
                            <th scope="col">Iznos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $cnt = 1;
                        foreach($actions as $action) {
                            echo '<tr>';
                            echo '<th scope="row">'.$cnt.'</th>';
                            $cnt++;
                            if($action->type == 1) {
                                echo '<td class="wallet-in">Prodaja</td>';
                            }
                            else {
                                echo '<td class="wallet-out">Kupovina</td>';
                            }
                            echo '<td>'.$action->timestamp.'</td>';
                            echo '<td>'.(new \App\Models\StockModel())->find($action->IdStock)->companyName.'</td>';
                            echo '<td>'.$action->quantity.'</td>';
                            if($action->type == 1) {
                                echo '<td class="wallet-in">+'.$action->totalPrice.'&euro;</td>';
                            }
                            else {
                                echo '<td class="wallet-out">-'.$action->totalPrice.'&euro;</td>';
                            }
                            echo '</tr>';
                        }
                        ?>
                        <?php }
                        else {
                            echo 'Ne postoje akcije sa zadatim filtrom!';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <form action="WalletController/filterActions" name="filterActions" method="post">
                    <div class="row">
                        <div class="col-6 text-center">
                            <label for="tipAkcije"> Izaberite tip transakcije: &nbsp;&nbsp;</label>
                            <select name="tipAkcije">
                                <option value="sve">Sve</option>
                                <option value="uplate">Kupovine</option>
                                <option value="isplate">Prodaje</option>
                            </select>
                        </div>
                        <div class="col-6 text-left">
                            <button class="btn btn-outline-primary form-control" type="submit">Filtriraj</button>
                        </div>
                    </div>
                </form>


            </div>




        </div>
    </div>
</div>

<footer>
    <p>&copy; RisingEdge 2021</p>
</footer>




<!-- Modal Uplata -->
<div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Uplata novca</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="paymentForm" method="post" action="<?= site_url("WalletController/payment") ?>">
                    <div class="form-group row">
                        <label for="amountInputFieldGroupPayment" class="col-sm-4 col-form-label">Iznos</label>
                        <div class="col-sm-8">
                            <div class="input-group" id="amountInputFieldGroupPayment">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">&euro;</div>
                                </div>
                                <input type="number" name="amountInputFieldPayment" class="form-control" placeholder="0&euro;" value="0" min="0.01"
                                       step="0.01" required>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nameInputFieldPayment" class="col-sm-4 col-form-label">Ime</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nameInputFieldPayment" name="nameInputFieldPayment" placeholder="npr. Pera"
                                   value="" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="surnameInputFieldPayment" class="col-sm-4 col-form-label">Prezime</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="surnameInputFieldPayment" name="surnameInputFieldPayment" placeholder="npr. Peric"
                                   value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="creditCardNumberInput" class="col-sm-4 col-form-label">Broj
                            kartice</label>
                        <div class="col-sm-8">
                            <input id="creditCardNumberInput" name="creditCardNumberInput" type="tel" class="form-control" inputmode="numeric"
                                   pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}" autocomplete="cc-number"
                                   maxlength="19" placeholder="xxxx-xxxx-xxxx-xxxx" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="expirationDateInput" class="col-sm-4 col-form-label">Datum isteka</label>
                        <div class="col-sm-8">
                            <input type="month" class="form-control" id="expirationDateInput" name="expirationDateInput" min="<?= date('Y-m'); ?>"
                                   value="<?= date('Y-m'); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="CVC" class="col-sm-4 col-form-label">CVC</label>
                        <div class="col-sm-8">
                            <input id="CVC" name="CVC" type="tel" class="form-control" inputmode="numeric" pattern="[0-9]{3}"
                                   maxlength="3" placeholder="xxx" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                        <button class="btn btn-outline-success" type="submit">Potvrdi</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Isplata -->
<div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Isplata novca</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="withdrawForm" method="post" action="<?= site_url("WalletController/withdraw") ?>">

                    <div class="form-group row">
                        <label for="nameInputFieldWithdraw" class="col-sm-4 col-form-label">Ime</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nameInputFieldWithdraw" name="nameInputFieldWithdraw" value="Petar" required
                                   readonly="true">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="surnameInputFieldWithdraw" class="col-sm-4 col-form-label">Prezime</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="surnameInputFieldWithdraw" name="surnameInputFieldWithdraw" value="Pan" required
                                   readonly="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="amountInputFieldGroupWithdraw" class="col-sm-4 col-form-label">Iznos</label>
                        <div class="col-sm-8">
                            <div class="input-group" id="amountInputFieldGroupWithdraw">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">&euro;</div>
                                </div>
                                <input type="number" name="amountInputFieldWithdraw" class="form-control" placeholder="0&euro;" value="0" min="<?php if($userBalance>0)
                                    echo "0.01";
                                else  echo "0.00";
                                ?>"
                                       max="<?php echo "$userBalance"; ?>" step="0.01" required>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bankAccountNumberInput" class="col-sm-4 col-form-label">Broj
                            računa</label>
                        <div class="col-sm-8">
                            <input id="bankAccountNumberInput" type="tel" class="form-control" name="bankAccountNumberInput" inputmode="numeric"
                                   pattern="[0-9]{3}-[0-9]{13}-[0-9]{2}" maxlength="20" autocomplete="cc-number" placeholder="xxx-xxxxxxxxxxxxx-xx" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Odustani</button>
                        <button class="btn btn-outline-success" type="submit">Potvrdi</button>
                    </div>

                </form>
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
</body>

</html>