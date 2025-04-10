<?php
require_once '../php/crud.php';
readAllSpendTypes();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB$ | Dépense</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/payement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
    <link rel="icon" type="image/png" href="../ressources/images/icon1.png">
    <script>
        // JavaScript pour afficher/masquer le champ "Type de Dépense"
        function toggleExpenseType() {
            const actionType = document.getElementById('actionType');
            const expenseTypeGroup = document.getElementById('expenseTypeGroup');
            if (actionType.value === 'addMoney') {
                expenseTypeGroup.style.display = 'none';
            } else {
                expenseTypeGroup.style.display = 'block';
            }
        }
    </script>
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
                    <li><a href="./Accueil.php" class="active">Home</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="form-container">
        <h6>Gérer vos finances</h6>
        <form action="payement.php" method="POST">
            <div class="form-group">
                <select name="actionType" id="actionType" class="form-style" required onchange="toggleExpenseType()">
                    <option value="" disabled selected>Choisir une action</option>
                    <option value="addExpense">Ajouter une dépense</option>
                    <option value="addMoney">Ajouter de l'argent</option>
                </select>
                <i class="input-icon material-icons">swap_horiz</i>
            </div>
            <div class="form-group">
                <input type="number" name="amount" class="form-style" placeholder="Montant" required>
                <i class="input-icon material-icons">attach_money</i>
            </div>
            <div class="form-group" id="expenseTypeGroup">
                <select name="spendType" class="form-style">
                    <option value="" disabled selected>Type de Dépense</option>
                    <?php 

                    for ($i = 0; $i < 10; $i++) {
                        echo "<option value='$i'>Type $i</option>";
                    } 
                    <option value="1">Achats</option>
                    ?>            
                </select>
                <i class="input-icon material-icons">category</i>
            </div>
            <button type="submit" class="btn">Valider</button>
        </form>
    </div>
</body>

</html>