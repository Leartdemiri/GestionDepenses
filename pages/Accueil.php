<?php
require_once "../php/functions.php";

// - Verification if user isn't logged, if he isnt return to login page
session_start();
$user = checkIfUnlogged(OUTSIDE_TO_INDEX_PATH);
$greeting = (date("H") >= UNIVERSAL_6_PM || date("H") < UNIVERSAL_6_AM) ? "Bonsoir" : "Bonjour";
$userEconomy = readOneEconomy($user[USER_TABLE_ID]);
$baseMoney = formatMoney($userEconomy["BaseMoney"]);

?>
<!DOCTYPE html> 
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB$ | Page Accueil</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/accueil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
    <link rel="icon" type="image/png" href="../ressources/images/icon1.png">
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="logo">UB<span>$</span></div>
                <input type="checkbox" name="" id="click">
                <label for="click" class="menu-btn">
                    <i class="material-icons">menu</i>
                </label>
                <ul>
                    <li><div class="welcome-name"><?= $greeting ?>, <?= htmlspecialchars($user["Firstname"]) ?> <?= htmlspecialchars($user["Lastname"]) ?></div></li>
                    <li><a href="" class="active">Home</a></li>
                    <li><a href="../payement/" class="active">Dépense</a></li>
                    <li><a href="../logout/" class="active">LogOut</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-Accueil">
        <div class="welcome-message">
            <h2>Bienvenue sur UB<span>$</span></h2>
            <p>Gérez vos finances facilement et efficacement.</p>
        </div>
        <div class="account-balance">
            <h3>Solde actuel</h3>
            <p class="balance-amount"><?= $baseMoney . " " . $user["currency"]?> </p>
        </div>

        <div class="main-content">
            <section class="dashboard">
                <div class="container">
                    <div class="row">
                    <div class="latest-expenses">
                        <h2 class="section-title">Dernières Dépenses</h2>
                            <table class="expenses-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Montant</th>
                                        <th>
                                            Date
                                            <i class="fa-solid fa-filter" id="monthFilterIcon" style="cursor: pointer; margin-left: 5px;"></i>
                                            <select id="monthFilter" style="display: none; margin-left: 5px;">
                                                <option value="">Tous</option>
                                                <option value="01">Janvier</option>
                                                <option value="02">Février</option>
                                                <option value="03">Mars</option>
                                                <option value="04">Avril</option>
                                                <option value="05">Mai</option>
                                                <option value="06">Juin</option>
                                                <option value="07">Juillet</option>
                                                <option value="08">Août</option>
                                                <option value="09">Septembre</option>
                                                <option value="10">Octobre</option>
                                                <option value="11">Novembre</option>
                                                <option value="12">Décembre</option>
                                            </select>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        <div id="totalAmount" style="text-align: right; margin-top: 10px; font-weight: bold;">
                            Total : 0.00 CHF
                        </div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="main-graph">
                    <h2>Statistiques</h2>
                    <div class="graph-container">
                        <div class="graph">
                            <h3>Dépenses Mensuelles</h3>
                            <canvas id="monthlyExpensesChart" width="600" height="400"></canvas>
                        </div>
                        <div class="graph">
                            <h3>Répartition des Dépenses</h3>
                            <canvas id="expenseBreakdownChart" width="60" height="400"></canvas>
                        </div>
                    </div>
            </section>
        </div>
    </main>
    <footer class="global-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h2>UB<span class="footerUBS">$</span></h2>
                </div>
                <div class="footer-copyright">
                    <p>&copy; <?php echo date("Y"); ?> UB<span class="footerUBS">$</span>. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script type="module" src="../script/payment.js"></script>

</body>

</html>