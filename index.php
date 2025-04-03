<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UB$ | Page d'accueil</title>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/material-icons@1.13.12/iconfont/material-icons.min.css">
    <link rel="icon" type="image/png" href="./ressources/images/icon1.png">

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
                            <h6><span>Connexion</span> <span>Création de compte</span></h6>
                            <input type="checkbox" class="checkbox" id="reg-log">
                            <label for="reg-log"></label>
                            <div class="card-3d-wrap">
                                <div class="card-3d-wrapper">
                                    <form class="card-front" action="login/" method="POST">
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
                                            <button type="submit" class="btn">Submit</button>
                                            <p class="text-center"><a href="#" class="link">Mot de passe oublié?</a></p>
                                        </div>
                                    </form>

                                    <form id="card-back" action="signup/" method="POST">
                                        <div class="center-wrap">
                                            <h4 class="heading">Créer un compte</h4>
                                            <div class="form-group">
                                                <input type="text" class="form-style" placeholder="Votre Prénom"
                                                    autocomplete="off">
                                                <i class="input-icon material-icons">perm_identity</i>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-style" placeholder="Votre Nom"
                                                    autocomplete="off">
                                                <i class="input-icon material-icons">perm_identity</i>
                                            </div>

                                            <div class="form-group">
                                                <input type="email" class="form-style" placeholder="Votre Email"
                                                    autocomplete="off">
                                                <i class="input-icon material-icons">alternate_email</i>
                                            </div>

                                            <div class="form-group">
                                                <input type="password" class="form-style"
                                                    placeholder="Votre Mot De Passe" autocomplete="off">
                                                <i class="input-icon material-icons">lock</i>
                                            </div>

                                            <div class="form-group">
                                                <input type="text" class="form-style" placeholder="Votre Devise"
                                                    autocomplete="off">
                                                <i class="input-icon material-icons">attach_money</i>
                                            </div>

                                            <a href="#" class="btn">Submit</a>
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
</body>

</html>