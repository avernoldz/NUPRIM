<?php
session_start();
session_regenerate_id();

if (isset($_SESSION['userid'])) {
    header("Location:dashboard.php?userid=$_SESSION[userid]");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../../Connections/cdn.php" ?>
    <link rel="stylesheet" href="../../CSS/root.css">
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CSS/side-bar.css">
    <title>Sign in</title>
</head>

<body>
    <?php
    include "../../Connections/Include.php";

    if (isset($_GET['Logout'])) {
        session_destroy();
        unset($_SESSION['userid']);
        header("Location:signin.php");
    }

    ?>

    <div class="row sign">
        <div class="col-6">
        </div>
        <div class="col-6">
            <div class="row sign-in">
                <div class="col sign" style="width:60%">
                    <h1 class="mb-4 w-700" style="font-size: 32px;">Sign in</h1>
                    <?php
                    if (isset($_GET['login-first'])) {
                        echo "
              <div class='row login-first mb-4'>
                  <div class='col'>
                      <label>You must log in to continue.</label>
                  </div>
              </div>
              ";
                    }

                    if (isset($_GET['wrong-password-or-email'])) {
                        echo "
      <div class='row error mb-4'>
          <div class='col'>
              <label>Email or password is incorrect, please try again.</label>
          </div>
      </div>
      ";
                    }
                    ?>

                    <form action="" method="POST" class="w-full flex flex-col flex-wrap content-center items-end">
                        <div class="mb-4 w-9/12">
                            <label for="username" class="form-label">Username or Email</label>
                            <input type="email" class="form-control bg-[#ebebeb] p-[0.75rem] w-full"
                                id="username" placeholder="name@example.com" required name="username">
                        </div>

                        <div class="mb-4 w-9/12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control bg-[#ebebeb] p-[0.75rem]" id="password"
                                placeholder="********" required name="password">
                        </div>

                        <a href="#" class="text-[var(--link)] mb-4">Forgot password?</a>

                        <button
                            class="bg-[var(--primary-blue)] p-[0.75rem] btn text-[#ffffff] w-9/12 hover:bg-[var(--blue-900)] hover:text-[#ffffff]"
                            type="submit" name="sign-in">Sign
                            in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['sign-in'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM user where email = '$username'";
        $results = mysqli_query($conn, $query);

        if (mysqli_num_rows($results) > 0) {
            $rows = mysqli_fetch_array($results);
            $pwd_hashed = $rows['password'];

            if (password_verify($password, $pwd_hashed)) {
                $_SESSION['userid'] = $rows['userid'];
                echo "<script>window.location.href='dashboard.php?userid=$rows[userid]';</script>";
            } else {
                echo "<script>window.location.href='signin.php?wrong-password-or-email';</script>";
            }
        } else {
            echo "<script>window.location.href='signin.php?wrong-password-or-email';</script>";
        }
    }
    ?>
</body>

</html>