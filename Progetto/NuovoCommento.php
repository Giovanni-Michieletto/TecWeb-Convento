<?php

    $page = file_get_contents('nuovoCommentoForm.html');

    $message = '';
    $Titolo = '';
    $Immagine = '';
    $AltImmagine = '';
    $Testo = '';

    $end = '';

    $errorTitle = '';
    $errorImage = '';
    $errorAlt = '';
    $errorText = '';

    if(isset($_POST['submit'])) {

        $Titolo = $_POST['Titolo'];
        $AltImmagine = $_POST['AltImmagine'];
        $Immagine = $_FILES['Immagine']['name'];
        $Testo = $_POST['Testo'];
                
        if( strlen($Titolo)!=0 && strlen($Immagine)!=0 && strlen($AltImmagine)!=0 && strlen($Testo)!=0 ) {

            require_once "dbConnection.php"; 

            $dbAccess = new DBAccess();

            $connection = $dbAccess->openDBConnection();

            $imgContent = base64_encode(file_get_contents($_FILES['Immagine']['tmp_name'])); 

            if($connection) {

                $listComments = $dbAccess->getComments();

                foreach ($listComments as $comment) {
                    if($Titolo == $comment['Titolo']) {
                        $errorTitle = 'Titolo gia esistente';
                    }
                    if($imgContent == $comment['Immagine']) {
                        $errorImage = 'Immagine gia esistente';
                    }
                    if($AltImmagine == $comment['AltImmagine']) {
                        $errorAlt = 'AltImmagine gia esistente';
                    }
                    if($Testo == $comment['Testo']) {
                        $errorText = 'Testo gia esistente';
                    }
                }

                if( strlen($errorTitle)==0 && strlen($errorImage)==0 && strlen($errorAlt)==0 && strlen($errorText)==0 ) {

                    $insertion = $dbAccess->insertComments($Titolo,$imgContent,$AltImmagine,$Testo);
                
                    if($insertion == true) {
                        $message = '<div id="conferma"><h1>Commento inserito correttamente</h1></div>';
                        $end = 'readonly';
                        $stringToreplace = '<input type="file" id="Immagine" accept="image/*" name="Immagine"/>';
                        $newString = '<img style="width:80%; height:80%;" src="data:charset=utf-8;base64, ' . $imgContent . '"/>';
                        $page = str_replace($stringToreplace,$newString,$page);
                        $page = str_replace('action="nuovoCommento.php"','action="nuovoCommentoForm.html"',$page);
                        $page = str_replace('>Inserisci<','>Nuovo form di inserimento<',$page);
                    }
                    else {
                        $message = '<div id="conferma"><p>Errore nell\'inserimento del commento</p></div>';

                    }
                }
            }
        }
        else {
            if(strlen($Titolo)==0) {
                $errorTitle = 'Titolo troppo corto';
            }
            if(strlen($errorImage)==0){
                $errorImage  = 'Reinserire immagine';
            }
            if(strlen($AltImmagine) == 0) {
                $errorAlt = 'Alt Immagine troppo corto';
            }
            if(strlen($Testo)==0) {
                $errorText = 'Testo troppo corto';
            }
        }
    }

    $page = str_replace('<errorTitle />','<p style="color:red;">' . $errorTitle . '</p>', $page);
    $page = str_replace('<errorImage />', '<p style="color:red;">' . $errorImage . '</p>', $page);
    $page = str_replace('<errorAlt />','<p style="color:red;">' . $errorAlt . '</p>', $page);
    $page = str_replace('<errorText />','<p style="color:red;">' . $errorText . '</p>', $page);

    $page = str_replace('<message />', $message, $page);

    $page = str_replace('name="Titolo"', 'name="Titolo" ' . $end .' value="'.$Titolo.'"', $page);
    $page = str_replace('name="AltImmagine"', 'name="AltImmagine" ' . $end .' value="'.$AltImmagine.'"', $page);
    $page = str_replace('name="Testo">', 'name="Testo" ' . $end . '>' .$Testo, $page);

    echo $page;
?>