<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
</head>
<body>
    
<!---------------------------------------------------------------------------------------------- -->
<div class="container px-5 my-5">
     <div class="row justify-content-center">
       <div class="col-lg-8">
         <div class="card border-0 rounded-3 shadow-lg">
           <div class="card-body p-4">
               <div class="h1 fw-light">Log In</div> <br>
    <center>
        <img src="../media/22c99181-53b8-4a66-867f-15ed423e9fad.jpg" alt="Italian Trulli" width="60" height="60">
    </center>
<form action="/main.php" method="POST">
   <!--Nombre de usuario-->
   <div class="form-floating mb-3">
      <label class="form-label">Nombre de usuario</label>
      <input type="text" class="form-control" id="name" name="name" required pattern="^[a-zA-Z0-9]+$" 
             title="El nombre de usuario solo puede contener letras y números.">
   </div>

   <!--Contraseña-->
   <div class="form-floating mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" class="form-control" id="password" name="password" required 
             pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
             title="La contraseña debe tener al menos 8 caracteres, una letra mayúscula, una minúscula, un número y un carácter especial.">
   </div>
   <!--Botón de enviar-->
   <br>
   <div class="text-center">
      <button type="submit" class="btn btn-primary">Acceder</button>
   </div>
   <a href="signup.php">¿Aún no tienes una cuenta?</a>
</form>
</body>
</html>
