<?php

//INSERISCE TITOLO, ALT E TEST
function sostitute($page,$end,$message,$Titolo,$AltImmagine,$Testo) {
    $page = str_replace('<message />', $message, $page);
    $page = str_replace('name="Titolo"', 'name="Titolo" ' . $end .' value="'.$Titolo.'"', $page);
    $page = str_replace('name="AltImmagine"', 'name="AltImmagine" ' . $end .' value="'.$AltImmagine.'"', $page);
    $page = str_replace('name="Testo">', 'name="Testo" ' . $end . '>' .$Testo, $page);
    return $page;
}


//CONTROLLA DOPPIONI
function exists($page,$error,$Titolo,$imgContent,$AltImmagine,$Testo,$cell) {
    if($Titolo == $cell['Titolo']) {
        $page = str_replace('<errorTitle />','<p style="color:red;">Titolo gia esistente</p>', $page);
        $error = false;
    }
    if($imgContent == $cell['Immagine']) {
        $page = str_replace('<errorImage />', '<p style="color:red;">Immagine gia esistente</p>', $page);
        $error = false;
    }
    if($AltImmagine == $cell['AltImmagine']) {
        $page = str_replace('<errorAlt />','<p style="color:red;">AltImmagine gia esistente</p>', $page);
        $error = false;
    }
    if($Testo == $cell['Testo']) {
        $page = str_replace('<errorText />','<p style="color:red;">Testo gia esistente</p>', $page);
        $error = false;
    }
    return array($error,$page);
}


//INSERISCE IMMAGINE IN CASO DI NUOVO INSERIMeNTO E POI CHIAMA SOSTITUTE()
function insertForm($page,$Titolo,$Immagine,$AltImmagine,$Testo) {
    echo "insertForm <br>";
    $message = 'Inserimento andato a buon fine';
    $end = 'readonly';
    $stringToreplace = '<input type="file" id="Immagine" accept="image/*" name="Immagine"/>';
    $newString = '<img style="width:80%; height:80%;" src="' . $Immagine . '"/>';
    $page = str_replace($stringToreplace,$newString,$page);
    $page = str_replace('<action />','admin.php?session=true',$page);
    $page = str_replace('<buttonName />','Torna alla home amministratore',$page);
    return sostitute($page,$end,$message,$Titolo,$AltImmagine,$Testo);
}


//INSERISCE IMMAGINE IN CASO DI MODIFICA E POI CHIAMA SOSTITUTE()
function updateForm($page,$Titolo,$Immagine,$AltImmagine,$Testo) {
    echo "updateForm <br>";
    $page = str_replace('<errorImage />', '<img style="width:80%; height:80%;" src="' . $Immagine . '"/>',$page);
    return sostitute($page,'','',$Titolo,$AltImmagine,$Testo);
}


function compile($page,$table,$ID) {
    echo "compile <br>";
    require_once "dbConnection.php"; 
    $dbAccess = new DBAccess();
    $connection = $dbAccess->openDBConnection();            
    if($connection) {
        $list = $dbAccess->getFile($table); 
        foreach ($list as $cell) {
            if($ID == $cell['ID']) {
                $Titolo = $cell['Titolo'];
                $Immagine = $cell['Immagine'];
                $AltImmagine = $cell['AltImmagine'];
                $Testo = $cell['Testo'];
            }
        }
        $page = updateForm($page,$Titolo,$Immagine,$AltImmagine,$Testo);
    }
    return $page;
}   


function buildForm($page,$table,$ID,$session) {
    if($session =="Modifica") {
        $page = str_replace('<buttonName />','Modifica',$page);
        $page = str_replace('<buttonName />', 'Modifica '.$table, $page);
        $page = compile($page,$table,$ID);
        $page = str_replace('<action />','upload.php?table='.$table.'&ID='.$ID,$page);
    }
    else {
        $page = str_replace('<titlePage />', 'Inserimento '.$table, $page);
        $page = str_replace('<buttonName />','Inserisci',$page);
        $page = str_replace('<action />','upload.php?table='.$table.'&ID='.$ID,$page);
    }
    
    return $page;
}


function footer($page,$session) {
    if($session==true) {
        $page = str_replace('<admin />','<a href="logout.php">Logout</a>',$page);
    }
    else {
        $page = str_replace('<admin />','<a href="login.html">Login</a>',$page);
    }
    return $page;
}


function menu($page,$table,$session) {
    $menu='';
    $tabelle=['Home','Articoli','Associazioni','Commenti','Notizie','Storia'];
    foreach($tabelle as $li) {
        if($li == $table) {
            $menu .= '<li class="notlink">'.$li.'</li>';
        }
        else if($li == 'Home' || $li == 'Storia') {
            $menu .= '<li><a href="'.$li.'.php">'.$li.'</a></li>';
        }
        else {
            $menu .= '<li><a href="view.php?table='.$li.'">'.$li.'</a></li>';
        }
    }
    if($session==true) {
        if($table == 'Admin') {
            $menu .= '<li class="notlink">Admin</li>';
        }
        else {
            $menu .= '<li><a href="Admin.php">Admin</a></li>';
        }
    }
    $page =  str_replace("<menu />",$menu,$page);
    return $page;
}


function buildHTML($page,$table,$session) {
    $page = menu($page,$table,$session);
    $page = footer($page,$session);
    return $page;
}

?>