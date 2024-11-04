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

      <!-- Icono al lado del titulo -->
      <link rel="shortcut icon" href="/media/22c99181-53b8-4a66-867f-15ed423e9fad.jpg" type="image/xpng">
</head>

<body>
    
<!---------------------------------------------------------------------------------------------- -->
<div class="container px-5 my-5">
     <div class="row justify-content-center">
       <div class="col-lg-8">
         <div class="card border-0 rounded-3 shadow-lg">
           <div class="card-body p-4">
               <div class="h1 fw-light">Sign Up</div> <br>
    <center>
        <img src="/media/22c99181-53b8-4a66-867f-15ed423e9fad.jpg" alt="photo" width="60" height="60">
    </center>
<form action="/structure/login.php" method="POST">
   <!--Nombre de Usuario-->
   <div class="form-floating mb-3">
      <label class="form-label">Nombre de usuario</label>
      <input type="text" class="form-control" id="name" name="name" required>
   </div>
   <!--Email-->
   <div class="form-floating mb-3">
      <label class="form-label">Email</label>
      <input type="email" id="mail" class="form-control" name="mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"/>
      </div>
   <!--Contraseña-->
   <div class="form-floating mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" id="password" class="form-control" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
         title="Debe contener al menos un número y una mayúscula y una minúscula, y al menos 8 o más carácteres"/> 
   </div>
   <!--Descripcion-->
   <div class="form-floating mb-3">
      <label class="form-label">Descripcion</label>
      <input type="text" class="form-control" id="age" name="age">
   </div>
<!--Boton de enviar-->
   <br>
   <div class="text-center">
      <button type="submit" class="btn btn-primary">Registrar</button>
   </div>
  
</form>


</body>
</html>