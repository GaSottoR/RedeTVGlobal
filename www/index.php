<?php
    require_once __DIR__."/vendor/autoload.php";

    use CoffeeCode\Router\Router;

    $router = new Router(BASE_URL);

    $router->namespace("Source\App");

    $router->group(null);
    $router->get("/","Web:home");
    $router->get("/regiao/{branch}","Web:regionNews");
    $router->get("/regiao/{branch}/categoria/{category}","Web:categoryNews");
    $router->get("/regiao/{branch}/categoria/{category}/noticia/{news_id}","Web:newsDetails");
    $router->get("/lives","Web:shareLives");
    $router->get("/converse","Web:converseWeb");


    $router->group("admin");
    $router->get("/","Admin:viewNews");

    $router->get("/login","Admin:login");
    $router->post("/login","Admin:checkLogin");
    $router->get("/logout","Admin:logout");

    $router->get("/usuarios","Admin:viewUsers");
    $router->get("/usuarios/novo","Admin:newUser");
    $router->post("/usuarios/novo","Admin:saveUser");
    $router->get("/usuarios/alter/{user_id}","Admin:alterUser");
    $router->post("/usuarios/alter/{user_id}","Admin:saveUser");
    $router->get("/usuarios/delete/{user_id}","Admin:removeUser");


    $router->get("/categorias","Admin:viewCategories");
    $router->get("/categorias/nova","Admin:newCategory");
    $router->post("/categorias/nova","Admin:saveCategory");
    $router->get("/categorias/alter/{cat_id}","Admin:alterCategory");
    $router->post("/categorias/alter/{cat_id}","Admin:saveCategory");
    $router->get("/categorias/delete/{cat_id}","Admin:removeCategory");

    $router->get("/regioes","Admin:viewBranches");
    $router->get("/regioes/nova","Admin:newBranch");
    $router->post("/regioes/nova","Admin:saveBranch");
    $router->get("/regioes/delete/{branch_id}","Admin:removeBranch");
    $router->get("/regioes/alter/{branch_id}","Admin:alterBranch");
    $router->post("/regioes/alter/{branch_id}","Admin:saveBranch");
    

    $router->get("/noticias","Admin:viewNews");
    $router->get("/noticias/nova","Admin:newNews");
    $router->post("/noticias/nova","Admin:saveNews");
    $router->get("/noticias/delete/{news_id}","Admin:removeNews");  
    $router->get("/noticias/alter/{news_id}","Admin:alterNews");
    $router->post("/noticias/alter/{news_id}","Admin:saveNews");

    $router->get("/lives","Admin:viewLives");
    $router->get("/lives/nova","Admin:newLive");
    $router->post("/lives/nova","Admin:saveLive");
    $router->get("/lives/delete/{id_lives}","Admin:removeLive"); 

    $router->get("/onlives/nova","Admin:newOnlive");
    $router->post("/onlives/nova","Admin:saveOnlive");
    $router->get("/onlives/delete/{aovivo_id}","Admin:removeOnlive"); 

    $router->get("/anuncios","Admin:viewAds"); 
    $router->get("/anuncios/novo","Admin:newAd"); 
    $router->post("/anuncios/novo","Admin:saveAd"); 
    $router->get("/anuncios/delete/{ad_id}","Admin:removeAd"); 

    $router->get("/converses","Admin:viewCvs"); 
    $router->get("/converses/novo","Admin:newCv"); 
    $router->post("/converses/novo","Admin:saveCv"); 
    $router->get("/converses/delete/{cv_id}","Admin:removeCv"); 

    $router->group("ooops");
    $router->get("/{errcode}","Web:error");

    $router->dispatch();

    // if($router->error()){
    //     $router->redirect("/ooops/{$router->error()}");
    // }
?>