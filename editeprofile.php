    <?php
    require_once("templates/header.php");
    require_once("dao/UserDAO.php");
    require_once("models/User.php");

    $user = new User();

    $userDao = new UserDAO($conn, $BASE_URL);

    $userData =  $userDao->verifyToken(true);

    $fullName = $user->getFullName($userData);

    if ($userData->image == "") {

        $userData->image = "user.png";
    }
    ?>

    <div id="main-container" class="container-fluid edit-profile-page">
        <div class="col-md-12">
            <form action="<?php $BASE_URL ?>user_process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="update">
                <div class="row">
                    <div class="col-md-4">
                        <h1><?php echo $fullName ?></h1>
                        <p class="page-description">Altere seus dados no formulário abaixo:</p>
                        <div class="form-group">
                            <label for="name">Nome:</label>
                            <input type="text" class="form-control my-2" id="name" name="name" placeholder="Digite seu nome" value="<?php echo $userData->name ?>">
                        </div>

                        <div class="form-group">
                            <label for="lastname">Sobrenome:</label>
                            <input type="text" class="form-control my-2" id="lastname" name="lastname" placeholder="Digite seu sobrenome" value="<?php echo $userData->lastname ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail:</label>
                            <input type="text" readonly class="form-control my-2 disabled" id="email" name="email" placeholder="Digite seu e-mail" value="<?php echo $userData->email ?>">
                        </div>
                        <input type="submit" class="btn card-btn" value="Salvar Alterações">
                    </div>
                    <div class="col-md-4">
                        <div id="profile-image-container" style="background-image: url('<?= $BASE_URL ?>img/users/<?php echo $userData->image ?>')"></div>
                        <div class="form-group">
                            <label for="image">Foto de Perfil:</label>
                            <input type="file" class="form-control-file my-2" name="image">
                        </div>

                        <div class="form-group">
                            <label for="bio">Sobre Você:</label>
                            <textarea class="form-control my-2" name="bio" id="io" rows="6" placeholder="Fale um pouco sobre você..."><?php echo $userData->bio ?></textarea>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row" id="change-password-container">
                <div class="col-md-4">
                    <h2>Alterar senha:</h2>
                    <p class="page-description">Digite a nova senha e confirme, para alterar a senha atual:</p>
                    <form action="<?php $BASE_URL ?>user_process.php" method="POST">
                        <input type="hidden" name="type" value="changepassword">
                        <div class="form-group">
                            <label for="password">Senha:</label>
                            <input type="password" class="form-control my-2" id="password" name="password" placeholder="Digite a sua nova senha">
                        </div>
                        <div class="form-group">
                            <label for="confirmpassword">Confirmação de senha:</label>
                            <input type="password" class="form-control my-2" id="confirmpassword" name="confirmpassword" placeholder="Confirme a sua nova senha">
                        </div>
                        <input type="submit" class="btn card-btn" value="Alterar Senha">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once("templates/footer.php");
    ?>