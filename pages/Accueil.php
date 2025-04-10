<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

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
                    <li><a href="" class="active">Home</a></li>
                    <li><a href="../payement/" class="active">Dépense</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-Accueil">
        <div class="welcome-message">
            <h2>Bienvenue sur UB$</h2>
            <p>Gérez vos finances facilement et efficacement.</p>
        </div>
        <div class="main-content">
            <section class="dashboard">
                <div class="container">
                    <div class="row">


                        <div class="latest-expenses">
                            <h2 class="section-title">Dernières Dépenses</h2>
                            <ul>
                                <li>
                                    <span class="expense-title">Achat Supermarché</span>
                                    <span class="expense-amount">- 50.00 CHF</span>
                                </li>
                                <li>
                                    <span class="expense-title">Abonnement Netflix</span>
                                    <span class="expense-amount">- 15.00 CHF</span>
                                </li>
                                <li>
                                    <span class="expense-title">Essence</span>
                                    <span class="expense-amount">- 80.00 CHF</span>
                                </li>
                            </ul>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module" src="../script/payment.js"></script>


</body>

</html>