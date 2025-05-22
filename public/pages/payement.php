<?php
require_once '../../src/functions.php';
session_start();

require_once '../../src/logger.php';
$logger = getLogger();

// Vérification de l'utilisateur connecté
$user = checkIfUnlogged("../index.php");

// Récupération des types de dépenses
$SpendTypes = readAllSpendTypes();

// Récupération des données économiques de l'utilisateur
$economy = readOneEconomy($user[USER_TABLE_ID]);
if (!$economy) {
    try {
        DataBase::begin();
        createEconomy(0, 0, 0, $user[USER_TABLE_ID]);
        DataBase::commit();
        $economy = readOneEconomy($user[USER_TABLE_ID]);
    } catch (Throwable $e) {
        DataBase::rollback();
        header("Location: payement.php?error=economy_creation_failed");
        exit();
    }
}

$currentBalance = $economy['BaseMoney'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionType = filter_input(INPUT_POST, 'actionType', FILTER_SANITIZE_STRING);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $spendType = filter_input(INPUT_POST, 'spendType', FILTER_VALIDATE_INT);
    try {
        if (!$actionType || $amount === null || $amount <= 0) {
            throw new Exception("Données invalides. Veuillez remplir tous les champs correctement.");
        }

        $oldBalance = $currentBalance;
        DataBase::begin();

        if ($actionType === 'addExpense' && $spendType) {
            $newBalance = $oldBalance - $amount;
            if ($newBalance < 0) {
                throw new Exception("Solde insuffisant pour effectuer cette dépense.");
            }
            createSpending($economy['idEconomy'], $spendType, $amount);
            $logger->info("Dépense ajoutée", [
                'userId' => $user[USER_TABLE_ID],
                'spendType' => $spendType,
                'amount' => $amount
            ]);
        } elseif ($actionType === 'addMoney') {
            $newBalance = $oldBalance + $amount;
            $logger->info("Argent ajouté", [
                'userId' => $user[USER_TABLE_ID],
                'amount' => $amount
            ]);
        } else {
            throw new Exception("Type d'action invalide ou type de dépense manquant.");
        }

        updateEconomy(
            $economy['monthlyLimit'],
            $economy['spendAim'],
            $newBalance,
            $user[USER_TABLE_ID]
        );

        DataBase::commit();
        $logger->info("Économie mise à jour", [
            'userId' => $user[USER_TABLE_ID],
            'oldBalance' => $oldBalance,
            'newBalance' => $newBalance
        ]);
        header("Location: payement.php?success=1");
        exit();
    } catch (Throwable $e) {
        DataBase::rollback();
        $logger->error("Erreur lors de la mise à jour du solde", [
            'userId' => $user[USER_TABLE_ID],
            'oldBalance' => $currentBalance,
            'amount' => $amount ?? null,
            'actionType' => $actionType ?? null,
            'message' => $e->getMessage()
        ]);
        header("Location: payement.php?error=" . urlencode($e->getMessage()));
        exit();
    }


}
?>
<!DOCTYPE html>
<html lang="fr">

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
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="logo">UB<span>$</span></div>
                <input type="checkbox" id="click">
                <label for="click" class="menu-btn">
                    <i class="material-icons">menu</i>
                </label>
                <ul>
                    <li><a href="../home/" class="active">Home</a></li>
                    <li><a href="" class="active">Dépense</a></li>
                    <li><a href="../logout/" class="active">LogOut</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="form-container">
        <h6>Gérer vos finances</h6>
        <p style="color: white;">Solde actuel: <strong><?= number_format($currentBalance, 2, ".", " ") ?> CHF</strong>
        </p>

        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php elseif (isset($_GET['success'])): ?>
            <p style="color: green;">Action effectuée avec succès !</p>
        <?php endif; ?>

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
                <input type="number" name="amount" class="form-style" placeholder="Montant" required min="0" step="0.01"
                    max="999999999999999">
                <i class="input-icon material-icons">attach_money</i>
            </div>
            <div class="form-group" id="expenseTypeGroup" style="display: none;">
                <select name="spendType" class="form-style">
                    <option value="" disabled selected>Type de Dépense</option>
                    <?php foreach ($SpendTypes as $spendType): ?>
                        <option value="<?= $spendType['idSpendingType'] ?>"><?= htmlspecialchars($spendType['Type']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <i class="input-icon material-icons">category</i>
            </div>
            <button type="submit" class="btn">Valider</button>
        </form>
    </div>

    <footer class="global-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h2>UB<span class="footerUBS">$</span></h2>
                </div>
                <div class="footer-copyright">
                    <p>&copy; <?= date("Y") ?> UB<span class="footerUBS">$</span>. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleExpenseType() {
            const actionType = document.getElementById('actionType').value;
            const expenseTypeGroup = document.getElementById('expenseTypeGroup');
            expenseTypeGroup.style.display = actionType === 'addExpense' ? 'block' : 'none';
        }
    </script>
</body>

</html>