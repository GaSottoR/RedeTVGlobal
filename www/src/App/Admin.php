<?php
namespace Source\App;

use CoffeeCode\Uploader\Uploader;
use League\Plates\Engine;

use Source\Models\Category;
use Source\Models\Branch;
use Source\Models\User;
use Source\Models\News;
use Source\Models\Live;
use Source\Models\Ad;
use Source\Models\Cv;
use Source\Models\Aovivo;

class Admin
{
    private $view;

    public function __construct(){
        $this->view = Engine::create(__DIR__."/../../Templates/Admin","php");
    }

    //Controladores de rotas de login do Administrador

    public function login($data){
        echo $this->view->render("login.php",["title"=> "Login | ". SITE]);
    }

    public function checkLogin($data){
        session_start();
        $user = new User();
        $email = $data["email"];
        $passwd = md5($data["passwd"]);

        if($user->login($email,$passwd)){
            $_SESSION["globaltv_user"] = 1;
            header("Location:". url("admin/"));
        }else{
            header("Location:". url("admin/login")); 
		}
    }

    public function logout($data){
        session_start();
        session_destroy();
        header("Location:". url(""));
    }

    public function isLogged(){
        session_start();

        if(isset($_SESSION["globaltv_user"])){
            return 1;
        }else{
            return 0;
        }
    
    }

    //Controlador da Home do Administrador

    public function home($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }
        echo $this->view->render("home.php",[
            "title"=> "Admin Home | ". SITE,
        ]);
    }

    //Controlador das ações com usuários

    public function viewUsers($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }
        $users = (new User())->find()->fetch(1);
        echo $this->view->render("users.php",[
            "title"=> "Usuários | ". SITE,
            "users" => $users
        ]);
    }

    public function newUser($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }
        echo $this->view->render("newuser.php",["title"=> "Novo usuário | ". SITE]);
    }

    public function saveUser($data){
        $nick = $data["nickname"];
        $email = $data["email"];
        $pass = md5($data["passwd"]);

        if(!isset($data["user_id"])){
            $user = new User();
            $user->add($nick,$email,$pass);

            if($user->fail()){
                echo "Algo deu errado :( <br>";
                echo $user->fail()->getMessage();
                die();
            }
        }else{
            $user = new User();
            $user->alter($data["user_id"],$nick,$email,$pass);

            if($user->fail()){
                echo "Algo deu errado :( <br>";
                echo $user->fail()->getMessage();
                die();
            }
		}

        header("Location:".url("admin/usuarios"));
    }

    public function alterUser($data)
    {
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $user = (new User)->findById($data["user_id"]);

        echo $this->view->render("alteruser.php",[
            "title"=> "Alterar Usuário | ". SITE,
            "user" => $user
        ]);

	}

    public function removeUser($data){

        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $user = new User;
        $user->remove($data["user_id"]);

        header("Location:".url("admin/usuarios"));
    }

    //Controlador de ações com categorias

    public function viewCategories($data){

        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $categories = (new Category)->getAll();

        echo $this->view->render("categories.php",[
            "title"=> "Categorias | ". SITE,
            "cats" => $categories
        ]);
    }

    public function newCategory($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        echo $this->view->render("newcategory.php",[
            "title"=> "Nova Categoria | ". SITE
        ]);
    }

    public function saveCategory($data){
        
        if(!isset($data["cat_id"])){
            $cat = new Category;
            $cat->add($data["desc"]);

            if($cat->fail()){
                echo"Algo deu errado :(";
                echo $cat->fail()->getMessage();
                die();
            }
        }
        else 
        {
            $cat = new Category;
            $cat->alter($data["cat_id"], $data["desc"]);

            if($cat->fail()){
                echo"Algo deu errado :(";
                echo $cat->fail()->getMessage();
                die();
            }
        }

        header("Location:". url("/admin/categorias"));
    }

    public function alterCategory($data)
    {
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $cat = (new Category)->findById($data["cat_id"]);

        echo $this->view->render("altercategory.php",[
            "title"=> "Alterar Categoria | ". SITE,
            "cat" => $cat
        ]);

	}

    public function removeCategory($data){

        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $cat = new Category;
        $cat->remove($data['cat_id']);

        header("Location:". url("admin/categorias"));
    }

    //Controlador de ações com Filiais

    public function viewBranches($data){

        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $branch = (new Branch)->find()->fetch(true);

        echo $this->view->render("branches.php",[
            "title"=> "TVS | ". SITE,
            "branches" => $branch
        ]);
    }

    public function newBranch($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

       echo $this->view->render("newbranch.php",[
            "title"=> "TVS | " . SITE
       ]);
    }

    public function alterBranch($data)
    {
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $branch = (new Branch)->findById($data["branch_id"]);

        echo $this->view->render("alterbranches.php",[
            "title"=> "Alterar Região | ". SITE,
            "branch" => $branch
        ]);

	}

    public function saveBranch($data){

    if(!isset($data["branch_id"])){
        $branch = new Branch;
        $branch->add($data["name"]);
       
        if($branch->fail()){
            echo"Algo deu errado :(";
            echo $branch->fail()->getMessage();
            die();
        }
    }else
    {
        $branch = new Branch;
        $branch->alter($data["branch_id"], $data["name"]);
	}

        header("Location:". url("admin/regioes"));
    }

    public function removeBranch($data){

        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $branch = new Branch;
        $branch->remove($data['branch_id']);

        header("Location:". url("admin/regioes"));
    }

    //Controladores de ações com notícias

    public function viewNews($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $news = (new News)->find()->fetch(true);

        if(isset($news)):
            foreach($news as $new){
                $new->category = $new->categoryName();
                $new->branch = $new->branchName();
            }
        endif;


        echo $this->view->render("news.php",[
            "title"=> "Notícias | ". SITE,
            "news" => $news
        ]);
    }

    public function alterNews($data)
    {
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $news = (new News)->findById($data["news_id"]);

        $cats = (new Category)->find()->fetch(true);
        $branches = (new Branch)->find()->fetch(true);

        echo $this->view->render("alternews.php",[
            "title"=> "Alterar Notícia | ". SITE,
            "cats" => $cats,
            "branches" => $branches,
            "news" => $news
        ]);
	}

    public function newNews($data)
    {
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $cats = (new Category)->find()->fetch(true);
        $branches = (new Branch)->find()->fetch(true);


        echo $this->view->render("newnews.php",[
            "title" => "Nova notícia | ". SITE,
            "cats" => $cats,
            "branches" => $branches
        ]);
    }

    public function saveNews($data){
        $upload = new \CoffeeCode\Uploader\Image("uploads","images");
        $files = $_FILES;

        if(!isset($data["news_id"])){
        
            if(!empty($files["thumb"])){
                $file = $files["thumb"];
        
                if(empty($file["type"]) || !in_array($file["type"],$upload::isAllowed())){
                    echo "Sem imagem ou imagem inválida para a thumb! <br><a href='".url("admin/noticias/nova")."'>Voltar</a>"; 
                    die();
                }else{
                    $url = $upload->upload($file,pathinfo($file["name"], PATHINFO_FILENAME),1920);
                }
            }
            
            $news = new News;
            $news->add($data["title"], $data["caption"],preg_replace('/&nbsp;/','  ',$data["content"]),$url, $data["date"], $data["branch"], $data["category"]);
                    
            
            if($news->fail()){
                $news->fail()->getMessage();
            }
        }else{
            if(!empty($files["thumb"])){
                $file = $files["thumb"];
                if(!empty($file["type"]) && in_array($file["type"],$upload::isAllowed())){
                    $url = $upload->upload($file,pathinfo($file["name"], PATHINFO_FILENAME),1920);
                }
            }

            $news = new News;
            if(!isset($url)){
                $news->alter($data["news_id"],$data["title"], $data["caption"], preg_replace('/&nbsp;/','  ',$data["content"]),null, $data["category"], $data["branch"],$data["date"]);
            }else{
                $news->alter($data["news_id"],$data["title"], $data["caption"], preg_replace('/&nbsp;/','  ',$data["content"]),$url, $data["category"], $data["branch"],$data["date"]);
            }

            if($news->fail()){
                $news->fail()->getMessage();
            }
        }
        header("Location:". url("admin/noticias"));
    }

    public function removeNews($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }
      
        $news = new News;
        $news->remove($data["news_id"]);

        header("Location:". url("/admin/noticias"));
    }

    //Controladores de ação com lives
    public function viewLives($data){
         if(!$this->isLogged()){
            header("Location:".url("admin/login"));
         }

         $lives = (new Live)->find()->fetch(true);
         $lives_on = (new Aovivo)->find()->fetch(true);

         echo $this->view->render("lives.php",[
            "title" => "Lives | ". SITE,
            "lives" => $lives,
            "lives_on" => $lives_on
         ]);

    }

    public function newLive($data)
    {
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $branches = (new Branch)->find()->fetch(true);

        echo $this->view->render("newlive.php",[
            "title" => "Nova live | ". SITE,
            "branches" => $branches
        ]);
    }

    public function saveLive($data){
        $upload = new \CoffeeCode\Uploader\Media("uploads","lives");
        $files = $_FILES;

        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        if(!empty($files["live"])){
                $file = $files["live"];
        
                if(empty($file["type"]) || !in_array($file["type"],$upload::isAllowed())){
                    echo "Sua mídia é invalida. <br><a href='".url("admin/lives/nova")."'>Voltar</a>"; 
                    die();
                }else{
                    $url = $upload->upload($file,pathinfo($file["name"], PATHINFO_FILENAME),1920);
                }
        }
           
        $live = new Live;
        $live->add($url, $data["title"], $data["branch"], $data["date"], $data["status"]);

        header("Location:". url("admin/lives"));
	}

    public function removeLive($data){
         if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }
        
        $live = new Live;
        $live->remove($data["id_lives"]);

        header("Location:". url("admin/lives"));
    }

    //Controlador de Lives ao vivo
    public function newOnlive($data){
     if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $branches = (new Branch)->find()->fetch(true);

        echo $this->view->render("newonlive.php",[
            "title" => "Link para lives AO VIVO | ". SITE,
            "branches" => $branches
        ]);
	}

    public function saveOnlive($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $aovivo = new Aovivo;
        $aovivo->add($data["on_link"], $data["branch"], $data["status"], $data["titulo"]);

        if($aovivo->fail()){
            $aovivo->fail()->getMessage();
        }

        header("Location:". url("admin/lives"));
	}

    public function removeOnlive($data){
        if(!$this->isLogged()){
           header("Location:".url("admin/login"));
       }
       
       $aovivo = new Aovivo;
       $aovivo->remove($data["aovivo_id"]);

       header("Location:". url("admin/lives"));
   }

    //Controlador Ads
    public function viewAds($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $ads = (new Ad)->find()->fetch(true);

        echo $this->view->render("ads.php",[
            "title" => "Nova notícia | ". SITE,
            "ads" => $ads
        ]);
    }

    public function newAd($data)
    {
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }


        echo $this->view->render("newad.php",[
            "title" => "Nova notícia | ". SITE
        ]);
    }
    
    public function saveAd($data){
        $upload = new \CoffeeCode\Uploader\Image("uploads","ads");
        $files = $_FILES;
        
        if(!empty($files["thumb"])){
            $file = $files["thumb"];
    
            if(empty($file["type"]) || !in_array($file["type"],$upload::isAllowed())){
                echo "Sem imagem ou imagem inválida para a thumb! <br><a href='".url("admin/noticias/nova")."'>Voltar</a>"; 
                die();
            }else{
                $url = $upload->upload($file,pathinfo($file["name"], PATHINFO_FILENAME),1920);
            }
        }
        
        $ad = new Ad;
        $ad->add($data["description"],$url,$data["link"]);
                
        
        if($ad->fail()){
            $ad->fail()->getMessage();
        }
        header("Location:". url("admin/anuncios"));
    }

    public function removeAd($data){
        echo "caralhooo";

        if(!$this->isLogged()){
           header("Location:".url("admin/login"));
       }
       
       $live = new Ad;
       $live->remove($data["ad_id"]);

       header("Location:". url("admin/anuncios"));
   }

    //Controlador de ação do CVs
    public function viewCvs($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $cvs = (new Cv)->find()->fetch(true);

        echo $this->view->render("cvs.php",[
            "title" => "Conversas | ". SITE,
            "cvs" => $cvs
        ]);
    }

    public function newCv($data){
     if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $branches = (new Branch)->find()->fetch(true);

        echo $this->view->render("newcv.php",[
            "title" => "Novo link para conversar | ". SITE,
            "branches" => $branches
        ]);
	}

    public function saveCv($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

        $cv = new Cv;
        $cv->add($data["cv_link"], $data["branch"]);

        if($cv->fail()){
            $cv->fail()->getMessage();
        }

        header("Location:". url("admin/converses"));
	}

    public function removeCv($data){
        if(!$this->isLogged()){
            header("Location:".url("admin/login"));
        }

       $cv = new Cv;
       $cv->remove($data["cv_id"]);

       header("Location:". url("admin/converses"));
	}
}
?>
