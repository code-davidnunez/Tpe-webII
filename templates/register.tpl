{include file="header.tpl" }

    <div class="container">
        <div class="row text-black">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-4">
                <h1 class="display-4 py-2 text-truncate">Registrarse</h1>
                <div class="px-2">
                    <form action= "insertUser" method="POST" class="justify-content-center">
                        <div class="form-group">
                            <input type="text" class="form-control" name="user" placeholder="Usuario">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="pass" placeholder="Contraseña">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

{include file="footer.tpl"}