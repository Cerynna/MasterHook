<?php
$intents = [
    "action" => [
        "suivant" => [
            [
                "text" => "suivant"
            ]
        ],
        "repeter" => [
            [
                "text" => "repeter"
            ]
        ],
        "precedent" => [
            [
                "text" => "precedent"
            ]
        ],
        "quit" => [
            [
                "text" => "quit"
            ]
        ],

    ],

    "hero" => [
        "arthur" => [
            [
                "text" => "Y a pas à dire, dès qu'il y a du dessert, le repas est tout de suite plus chaleureux !",
            ],
            [
                "text" => "Qu'est ce qu'ils foutent ces cons de Saxons ?",
            ],
            [
                "text" => "Mon père, il n'était pas ébouriffé, déjà, hein, il avait une coupe à la con mais c'était plutôt aplati et puis il était pas vaporeux, voilà ! Allez, au lit !",
            ],
            [
                "text" => "Sortez-vous les doigts du cul !!!",
            ],
            [
                "text" => "Sur le principe, la Table ronde, c'est pas obligatoire.",
            ],
            [
                "text" => "Mais qu'est-ce que vous faites là trou du cul",
            ],
        ],
        "perceval" => [
            [
                "text" => "Putain, en plein dans sa mouille !",
            ],
            [
                "text" => "C'est pas faux",
                "sound" => "cPasFaux.mp3"
            ],

        ],
    ]
];
file_put_contents('intents.json', json_encode($intents));

