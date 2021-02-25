<?php

return array(

    'undeployable' 		=> '<strong>Upozorenje: </strong> Ovaj resurs, imovina je označena kao trenutno nedeljiva. Ako se ovaj status promenio, ažurirajte status imovine.',
    'does_not_exist' 	=> 'Imovina ne postoji.',
    'does_not_exist_or_not_requestable' => 'Dobar pokušaj. Ta imovina ne postoji ili nije moguće da se potražuje.',
    'assoc_users'	 	=> 'Ovaj je resurs trenutno poveren korisniku i ne može se izbrisati. Najprije proverite resurs, a zatim ponovo pokušajte brisanje. ',

    'create' => array(
        'error'   		=> 'Imovina, resurs nije kreiran, pokušajte ponovo. :(',
        'success' 		=> 'Imovina, resurs uspešno kreiran. :)'
    ),

    'update' => array(
        'error'   			=> 'Imovina nije ažurirana, pokušajte ponovo',
        'success' 			=> 'Imovina je uspešno ažurirana.',
        'nothing_updated'	=>  'Nije odabrano nijedno polje, tako da ništa nije ažurirano.',
    ),

    'restore' => array(
        'error'   		=> 'Imovina nije obnovljena, pokušajte ponovo',
        'success' 		=> 'Imovina je uspešno obnovljena.'
    ),

    'audit' => array(
        'error'   		=> 'Provera imovine nije uspela. Molim pokušajte ponovo.',
        'success' 		=> 'Provera imovine uspešno je evidentirana.'
    ),


    'deletefile' => array(
        'error'   => 'Fajl nije izbrisan. Molim pokušajte ponovo.',
        'success' => 'Fajl uspešno obrisan.',
    ),

    'upload' => array(
        'error'   => 'Fajl(ovi) nisu preneseni. Pokušajte ponovo.',
        'success' => 'Fajl(ovi) uspešno preneseni. Pokušajte ponovo.',
        'nofiles' => 'Niste odabrali nijedan fajl za prenos ili je fajl prevelik',
        'invalidfiles' => 'Jedn ili više fajlova su preveliki ili je vrsta fajla koja nije dopuštena. Dopuštene vrste su png, gif, jpg, doc, docx, pdf i txt.',
    ),

    'import' => array(
        'error'                 => 'Neke stavke nisu pravilno uvezene.',
        'errorDetail'           => 'Sledeće stavke nisu uvezene zbog grešaka.',
        'success'               => "Vaš fajl je importovan",
        'file_delete_success'   => "Vaš je fajl uspešno izbrisan",
        'file_delete_error'      => "Fajl nime moguće izbrisati",
    ),


    'delete' => array(
        'confirm'   	=> 'Jeste li sigurni da želite izbrisati ovaj resurs?',
        'error'   		=> 'Došlo je do problema s brisanjem resursa. Molim pokušajte ponovo.',
        'nothing_updated'   => 'Nijedna imovina nije odabrana, tako da ništa nije izbrisano.',
        'success' 		=> 'Imovina je uspešno obrisana.'
    ),

    'checkout' => array(
        'error'   		=> 'Imovina nije odjavljena, pokušajte ponovo',
        'success' 		=> 'Imovina je uspešno odjavljena.',
        'user_does_not_exist' => 'Korisnik je nevažeći. Molim pokušajte ponovo.',
        'not_available' => 'That asset is not available for checkout!',
        'no_assets_selected' => 'Morate odabrati barem jednu imovinu s popisa'
    ),

    'checkin' => array(
        'error'   		=> 'Imovina nije prijavljena. Pokušajte ponovo',
        'success' 		=> 'Imovina je uspešno prijavljena.',
        'user_does_not_exist' => 'Taj je korisnik nevažeći. Molim pokušajte ponovo.',
        'already_checked_in'  => 'Imovina je već prijavljena.',

    ),

    'requests' => array(
        'error'   		=> 'Imovina nije zatražena, pokušajte ponovo',
        'success' 		=> 'Imovina je uspešno zatražena.',
        'canceled'      => 'Checkout request successfully canceled'
    )

);
