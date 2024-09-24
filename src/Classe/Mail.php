<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;


// #############################################################################

//  Mise en place de l'envoi de mail avec mailjet

// on installe dans le terminal : composer require mailjet/mailjet-apiv3-php
// creation des dossiers src/Classe et src/Mail
// dans src mail, un fichier html par modele de mail
// on renseigne les keys de mailjet dans le .env
// on renseigne dans ce fichier le template id de mailjet
// on instancie un objet de la classe Mail là où il y a besoin.
// on appelle la méthode send avec les paramètres nécessaires.

// #############################################################################

class Mail
{


    public function send(
        $to_email,
        $to_name,
        $subject,
        $template,
        $vars = null
    ) {
        // recup d'un template
        $content = file_get_contents(dirname(__DIR__) . '/Mail/' . $template);


        // on recup les variables facultativeset on les reconnaitra avec leur clé.
        if ($vars != null) {
            foreach ($vars as $key => $var) {
                $content = str_replace('{' . $key . '}', $var, $content);
            }
        }

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

        // Define your request body

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "admin@compagnondecode.fr",
                        'Name' => "Le Compagnon de Com"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 6315527,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
