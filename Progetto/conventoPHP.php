<?php

    require_once "dbConnection.php";    //chiama file dbConnection.php per la connessione al database

    $page = file_get_contents('convento.html');  //nome pagina del sentiero

    $dbAccess = new DBAccess();          //classe definita in dbConnection.php
    $connection = $dbAccess->openDBConnection();    //Richiede collegamneto utilizzando una funzione del file dbConnection.php
    
    if($connection) {

        //
        // NEWS
        //
        $listNews = $dbAccess->getNews();   //Richiede articoli utilizzando una funzione del file dbConnection.php
        $definition = "";
        if ($listNews) {        //creo la parte di articoli in html
            $num = 6;
            foreach ($listNews as $news) {
                if($num == 0) {
                    break;
                }
                $Anteprima = substr($news['Testo'],0,150) . " ...";   //prende primi 150 caratteri
                $definition .= '<div class="card">';
                $definition .= '<a href="singoloNotizia.php? Titolo=' . $news['Titolo'] .'">';
                    $definition .= '<div class="t-cont">';
                        $definition .= '<h3>' . $news['Titolo'] . '</h3>';
                    $definition .= '</div>';
                    $definition .= '<div class="img-cont">';
                        $definition .= '<img src="data:charset=utf-8;base64, ' . $news['Immagine'] . '" alt="' . $news['AltImmagine'] . '"/>';
                    $definition .= '</div>';
                    $definition .= '<div class="card-body">';
                        $definition .= '<p class="card-text">' . $Anteprima . '</p>';
                    $definition .= '</div>';
                    $definition .= '</a>';
                $definition .= '</div>';
                $num -= 1;
            }
        }
        else {
            $definition = "<p>Nessun articolo presente</p>";    //se ho il DB vuoto mostro un messaggio
        }

        $page = str_replace("<listNews />",$definition,$page); //rimpiazzo la parte vuota che ho scritto in html con quella generata in php

        // 
        // COMMENTS
        //
        $listComments = $dbAccess->getComments();
        $definition = "";
        if ($listComments) { 
            $num = 4;
            foreach ($listComments as $comment) {
                if($num == 0) {
                    break;
                }
                $Anteprima = substr($comment['Testo'],0,150) . " ...";   //prende primi 100 caratteri  //prende primi 150 caratteri
                $definition .= '<div class="card">';
                 $definition .= '<a href="singoloCommento.php?Titolo=' . $comment['Titolo'] .'">';
                    $definition .= '<div class="t-cont">';
                        $definition .= '<h3>' . $comment['Titolo'] . '</h3>';
                    $definition .= '</div>';
                    $definition .= '<div class="img-cont">';
                        $definition .= '<img src="data:charset=utf-8;base64, ' . $comment['Immagine'] . '" alt="' . $comment['AltImmagine'] . '"/>';
                    $definition .= '</div>';
                    $definition .= '<div class="card-body">';
                        $definition .= '<p class="card-text">' . $Anteprima . '</p>';
                    $definition .= '</div>';
                    $definition .= '</a>';
                $definition .= '</div>'; 
                $num -= 1;
            }
        }
        else {
            $definition = "<p>Nessun articolo presente</p>";    //se ho il DB vuoto mostro un messaggio
        }

        $page = str_replace("<listComments />",$definition,$page); //rimpiazzo la parte vuota che ho scritto in html con quella generata in php

        // 
        // VIDEO
        //
        $listVideos = $dbAccess->getVideos();
        $definition = "";
        if ($listVideos) { 
            $num = 6;
            foreach ($listVideos as $video) {
                if($num == 0) {
                    break;
                }
                $definition .= '<div class="card-video">';
                    // $definition .= '<div class="embed-responsive embed-responsive-16by9 z-depth-1-half">';
                        $definition .= '<iframe width="260" height="155" src="' . $video['Link'] . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                    // $definition .= '</div>';
                $definition .= '</div>'; 
                $num -= 1;
            }
        }
        else {
            $definition = "<p>Nessun articolo presente</p>";    //se ho il DB vuoto mostro un messaggio
        }

       echo str_replace("<listVideos />",$definition,$page); //rimpiazzo la parte vuota che ho scritto in html con quella generata in php
    }

?>