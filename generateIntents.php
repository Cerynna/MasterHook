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
        "quiz" => [
            [
                "text" => "quiz"
            ]
        ],

    ],

    "hero" => [
        "arthur" => [
            ["text" => "Y a pas à dire, dès qu'il y a du dessert, le repas est tout de suite plus chaleureux !"],
            ["text" => "Qu'est ce qu'ils foutent ces cons de Saxons ?"],
            ["text" => "Mon père, il n'était pas ébouriffé, déjà, hein, il avait une coupe à la con mais c'était plutôt aplati et puis il était pas vaporeux, voilà ! Allez, au lit !"],
            ["text" => "Sortez-vous les doigts du cul !!!"],
            ["text" => "Sur le principe, la Table ronde, c'est pas obligatoire."],
            ["text" => "Mais qu'est-ce que vous faites là trou du cul"],
        ],
        "preceval" => [
            ["text" => "Putain, en plein dans sa mouille !"],
            [
                "text" => "C’est pas faux.",
                "sound" => "cPasFaux.mp3",
            ],
            ["text" => "Toi un jour je te crame ta famille, toi."],
            ["text" => "Faut arrêter ces conneries de nord et de sud ! Une fois pour toutes, le nord, suivant comment on est tourné, ça change tout !"],
            ["text" => "Sire, Sire ! On en a gros !"],
            ["text" => "Ah ! oui... j' l'ai fait trop fulgurant, là. Ça va ?"],
            ["text" => "Sire, avec tout le respect, est-ce que la reine a les fesses blanches ?"],
            ["text" => "Ça sert à rien, un siège, si elle est enceinte, il faut des linges blancs et une bassine d'eau chaude."],
        ],
        "attila" => [
            ["text" => "Je te mettrai à genoux, Arthur de Bretagne !"],
            ["text" => "Pourquoi pas ?"],
            ["text" => "Je vais tout casser, ici, MOI! Kaamelott Kaamelott : y va rester un tas de caaaailloux, comme ça! Je veux l'or, tout l'or sinon c'est la guerre !"],
            ["text" => "Cette fois-ci, on part avec les femmes ! HAHAAAHA !!!!"],
        ],

    ]
];
file_put_contents('intents.json', json_encode($intents));

