<div>
    <h1><a href="<?= $path ?>">Calculator</a> / <?= $title ?></h1>
    <button id="list_button" onclick="show()">
        <i id="account_icon" class="fa fa-light fa-user"></i>
    </button>
</div>
<div id="list">
    <?php
    if (isset($_SESSION["id"])) {
        echo "<a href='" . $path . "account/profile.php'>Profile</a>
                    <a href='" . $path . "account/logout.php'>Logout</a>";
    } else {
        echo "<a href='" . $path . "account/login.php'>Login</a>
                <a href='" . $path . "account/registration.php'>Registration</a>";
    }
    ?>
</div>

<script>
    // Funzione per mostrare la lista dei link;
    function show() {
        let show = document.getElementById("list").style.display
        if (show == "none") {
            document.getElementById("list").style.display = "flex"
        } else {
            document.getElementById("list").style.display = "none"
        }
    }
</script>
