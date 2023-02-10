    <?php

    require_once("globals.php");
    require_once("db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    // Resgata o tipo de formulário

    $type = filter_input(INPUT_POST, "type");

    // Verifição do tipo de formulário

    if ($type === "register") {

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        // Verificação de dados mínimos

        if ($name && $lastname && $email && $password) {

            // Verificar se as senhas são iguais
            if ($password === $confirmpassword) {

                // Verifica se o e-mail já está cadastrado no sitesma
                if ($userDao->findByEmail($email) === false) {

                    $user = new User();

                    // Criação de token e senha
                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;

                    $userDao->create($user, $auth);
                } else {
                    // Enviar uma mensagem de erro, 
                    $message->setMessage("O e-mail já está cadastrado, digite outro e-mail.", "error", "back");
                }
            } else {
                // Enviar uma mensagem de erro, senhas diferentes
                $message->setMessage("As senhas informadas não correspondem.", "error", "back");
            }
        } else {

            // Enviar uma mensagem de erro, dados faltantes
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }
    } else if ($type === "login") {

        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");

        // Tenta autenticar o usuário
        if ($userDao->authenticateUser($email, $password)) {

            $message->setMessage("Seja bem-vindo!", "success", "editeprofile.php");

            // Redireciona o usuário, caso não consiga autenticar

        } else {
            // Enviar uma mensagem de erro, 
            $message->setMessage("usuário e/ou senha incorretos, tente novamente.", "error", "back");
        }
    } else {

        // Enviar uma mensagem de erro, 
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }
