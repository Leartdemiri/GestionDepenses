<?php
require "php/functions.php";

session_start();
checkIfLogged("home/");

header("Cross-Origin-Embedder-Policy: require-corp");
header("Cross-Origin-Opener-Policy: same-origin-allow-popups");

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB$ | Page Login</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
    <link rel="icon" type="image/png" href="./ressources/images/icon1.png">

</head>

<body>
    <main>
        <nav class="navbar">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="logo">UB<span>$</span></div>
                    <input type="checkbox" name="" id="click">
                    <label for="click" class="menu-btn">
                        <i class="material-icons">menu</i>
                    </label>
                    <ul>
                        <li><a href="#" class="active">Home</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <section>
            <div class="container">
                <div class="row full-screen align-items-center">
                    <div class="left">
                        <span class="line"></span>
                        <h2>UB<span>$</span> simplifie la gestion de vos finances. Suivez vos dépenses facilement et
                            reprenez le contrôle.</h2>
                    </div>
                    <div class="right">
                        <div class="form">
                            <div class="text-center">
                                <h6><span>Connexion</span> <span>Inscription</span></h6>
                                <input type="checkbox" class="checkbox" id="reg-log">
                                <label for="reg-log"></label>
                                <div class="card-3d-wrap">
                                    <div class="card-3d-wrapper">
                                        <form id="card-front" action="login/" method="POST">
                                            <div class="center-wrap">
                                                <h4 class="heading">Se connecter</h4>

                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-style"
                                                        placeholder="Votre Email" autocomplete="off" required>
                                                    <i class="input-icon material-icons">alternate_email</i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="password" name="password" class="form-style"
                                                        placeholder="Votre Mot De Passe" autocomplete="off" required>
                                                    <i class="input-icon material-icons">lock</i>
                                                </div>

                                                <?php displayFormErrors(); ?>


                                                <!-- Bouton de connexion Google -->
                                                <div id="d_id_onload"
                                                    data-client_id="139570543794-sf77h7hiah3l8q3l2m0u8r2r29ftu3a7.apps.googleusercontent.com"
                                                    data-context="signin" data-ux_mode="popup"
                                                    data-callback="handleCredentialResponseForLogin"
                                                    data-auto_prompt="false">
                                                </div>
                                                <div class="g_id_signin" data-type="standard" data-shape="rectangular"
                                                    data-theme="outline" data-text="sign_in_with" data-size="large"
                                                    data-logo_alignment="left">
                                                </div>

                                                <button type="submit" class="btn">Connexion</button>

                                            </div>
                                        </form>



                                        <form id="card-back" action="signup/" method="POST">
                                            <div class="center-wrap">
                                                <h4 class="heading">S'inscrire</h4>

                                                <div class="form-group">
                                                    <input type="text" name="firstname" class="form-style"
                                                        placeholder="Votre Prénom" autocomplete="off" required>
                                                    <i class="input-icon material-icons">perm_identity</i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="text" name="lastname" class="form-style"
                                                        placeholder="Votre Nom" autocomplete="off" required>
                                                    <i class="input-icon material-icons">perm_identity</i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-style"
                                                        placeholder="Votre Email" autocomplete="off" required>
                                                    <i class="input-icon material-icons">alternate_email</i>
                                                </div>

                                                <div class="form-group">
                                                    <input type="password" name="password" class="form-style"
                                                        placeholder="Votre Mot De Passe" autocomplete="off" required>
                                                    <i class="input-icon material-icons">lock</i>
                                                </div>

                                                <div class="form-group">
                                                    <select name="selectCurrency" id="selectCurrency" class="form-style"
                                                        required>
                                                        <option value="" disabled selected>Choisir une Devise</option>
                                                        <option value="CHF">CHF</option>
                                                        <option value="EUR">EUR</option>
                                                        <option value="USD">USD</option>
                                                    </select>
                                                    <i class="input-icon material-icons">attach_money</i>
                                                </div>

                                                <!-- Bouton de connexion Google -->
                                                <div id="g_id_onload"
                                                    data-client_id="139570543794-sf77h7hiah3l8q3l2m0u8r2r29ftu3a7.apps.googleusercontent.com"
                                                    data-context="signin" data-ux_mode="popup"
                                                    data-callback="handleCredentialResponseForSignUp"
                                                    data-auto_prompt="false">
                                                </div>
                                                <div class="g_id_signin" data-type="standard" data-shape="rectangular"
                                                    data-theme="outline" data-text="sign_in_with" data-size="large"
                                                    data-logo_alignment="left">
                                                </div>

                                                <?php displayFormErrors(); ?>

                                                <button type="submit" class="btn">Inscription</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
    <script>
        function handleCredentialResponseForSignUp(response) {
            // Récupère la localisation via une API publique
            fetch('https://ipapi.co/json/')
                .then(res => res.json())
                .then(location => {
                    const currency = location.currency || '';
                    fetch('./pages/signup.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'google_credential=' + encodeURIComponent(response.credential) +
                            '&currency=' + encodeURIComponent(currency)
                    })
                        .then(res => {
                            if (res.redirected) {
                                window.location.href = res.url;
                            } else {
                                return res.text();
                            }
                        })
                        .catch(() => alert("Erreur lors de l'inscription Google"));
                });
        }

        function handleCredentialResponseForLogin(response) {
            // Envoie le token Google au serveur pour connexion
            fetch('./pages/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'google_credential=' + encodeURIComponent(response.credential)
            })
                .then(res => {
                    if (res.redirected) {
                        window.location.href = res.url;
                    } else {
                        return res.text();
                    }
                })
                .catch(() => alert("Erreur lors de la connexion Google"));
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jwt-decode/3.1.2/jwt-decode.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>