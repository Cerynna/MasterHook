<?php
/**
 * Created by PhpStorm.
 * User: cerynna
 * Date: 14/12/17
 * Time: 22:12
 */

namespace MasterHook;

use DateTime;
use function implode;
use const PHP_EOL;
use Symfony\Component\HttpFoundation\Request;
use function var_dump;


class Controller
{
    /**
     * @var array
     */
    public $request;
    /**
     * @var array
     */
    public $response;
    /**
     * @var array
     */
    public $intent;

    public $action;

    public $database;

    public $keyUser;

    public $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Controller
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getKeyUser()
    {
        return $this->keyUser;
    }

    /**
     * @param mixed $keyUser
     * @return Controller
     */
    public function setKeyUser($keyUser)
    {
        $this->keyUser = $keyUser;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $database
     * @return Controller
     */
    public function setDatabase($database)
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return Controller
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }


    /**
     * @return array
     */
    public function getIntent()
    {
        return $this->intent;
    }

    /**
     * @param array $intent
     * @return Controller
     */
    public function setIntent($intent)
    {
        $this->intent = $intent;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     * @return Controller
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return Controller
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }


    /**
     * Controller constructor.
     */
    public function __construct()
    {

        $request = Request::createFromGlobals();

        $method = $request->server->get('REQUEST_METHOD');


        $serverName = $_SERVER['SERVER_NAME'];


        if ($method == "POST" or $serverName === '127.0.0.1') {

            $this->setDatabase(new FirebaseConnect());


            $json = json_decode(file_get_contents('php://input'));
            if ($serverName == '127.0.0.1') {
                $json = json_decode(file_get_contents('requestFromBot.json'));

            }
            file_put_contents('requestFromBot.json', json_encode($json));
            $this->database = new FirebaseConnect();
            $this->database->getData("", $intents);

            $this->setRequest($json);
            $this->setIntent($intents);


            $queryUser = $this->formatQuery($this->request->queryResult->queryText);

            $userID = $this->request->originalDetectIntentRequest->payload->user->userId;

            $this->setKeyUser($this->database->getKeyUser($userID));

            $this->setUser(new User([
                "id" => $userID
            ]));

            if ($this->keyUser == false) {
                $this->database->addUser($this->user);
                $this->setKeyUser($this->database->getKeyUser($userID));
            }


            $this->rooting($queryUser);


        } else {
            $this->setResponse([
                "textToSpeech" => "BUG",
                "action" => "bug",
            ]);
        }

        return $this;
    }


    public function formatQuery($str, $charset = 'utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return strtolower($str);
    }

    public function repeatAction($actions)
    {
        $this->database->getData($actions[0] . "/" . $actions[1] . "/" . $actions[2], $lists);

        $returnFromBot[] =
            [
                "textToSpeech" => $lists["text"],
                "action" => $actions[0] . "-" . $actions[1] . "-" . $actions[2],
                "prevAction" => implode('-', $actions),
            ];
        return $returnFromBot;
    }

    public function nextAction($actions)
    {
        $this->database->getData($actions[0] . "/" . $actions[1], $lists);

        $key = array_rand($lists);

        $returnFromBot[] =
            [
                "textToSpeech" => $lists[$key]["text"],
                "action" => $actions[0] . "-" . $actions[1] . "-" . $key,
                "prevAction" => implode('-', $actions),
            ];
        return $returnFromBot;
    }
    public function quizAction($actions){

        if ($actions[0] != "quiz")
        {
            $returnFromBot[] =
                [
                    "textToSpeech" => "Bienvenue sur le Quiz !" . PHP_EOL . "Dit 'commencer' pour débuter le jeux",
                    "action" => "quiz-menu",
                    "prevAction" => implode('-', $actions),
                ];
        }
        else{
            $queryUser = $this->formatQuery($this->request->queryResult->queryText);
            switch ($actions[1])
            {
                case "menu":
                    if ($queryUser == "commencer"){
                        $returnFromBot[] =
                            [
                                "textToSpeech" => "Premiere question",
                                "action" => "quiz-game-arthur-1-1",
                                "prevAction" => implode('-', $actions),
                            ];
                    }

                    break;
                case "game":


                        $returnFromBot[] =
                            [
                                "textToSpeech" => "question",
                                "action" => "quiz-game-arthur-1-" . ($actions[4]+1),
                                "prevAction" => implode('-', $actions),
                            ];


                    break;
                default:
                    $returnFromBot[] =
                        [
                            "textToSpeech" => "question",
                            "action" => "quiz-game-arthur-1-" . ($actions[4]+1),
                            "prevAction" => implode('-', $actions),
                        ];
                    break;

            }

        }



        return $returnFromBot;
    }
    public function checkIntent($intents, $type, $queryUser)
    {
        $this->database->getData("user/$this->keyUser", $user);
        $j = 0;
        $queryUsers = explode(' ', $queryUser);
        foreach ($intents[$type] as $intent => $texts) {

            $pos = strpos(" " . $queryUser, $intent);
            $key = array_rand($texts);

            //FIND MATCH IN STRING INTENT
            if (in_array($intent, $queryUsers)) {
                $returnFromBot[$j] =
                    [
                        "textToSpeech" => $texts[$key]["text"],
                        "action" => $type . "-" . $intent . "-" . $key,
                        "prevAction" => $user['last_action'],
                    ];


                if ($texts[$key]['sound'] != null) {
                    $returnFromBot[$j] =
                        [
                            "ssml" => "cPasFaux.mp3",
                            "text" => $texts[$key]["text"],
                            "action" => $type . "-" . $intent . "-" . $key,
                            "prevAction" => $user['last_action'],
                        ];

                }
            } // FIND MATCH IN WORD INTENT
            elseif ($pos != 0) {
                $returnFromBot[$j] =
                    [
                        "textToSpeech" => $texts[$key]["text"],
                        "action" => $type . "-" . $intent . "-" . $key,
                        "prevAction" => $user['last_action'],
                    ];
                if ($texts[$key]['sound'] != null) {
                    $returnFromBot[$j] =
                        [
                            "ssml" => "cPasFaux.mp3",
                            "text" => $texts[$key]["text"],
                            "action" => $type . "-" . $intent . "-" . $key,
                            "prevAction" => $user['last_action'],
                        ];
                }
            }
            $j++;
        }
        if (empty($returnFromBot)) {


            $returnFromBot[9999] =
                [
                    "ssml" => "cPasFaux.mp3",
                    "text" => "C'est pas faux",
                    "action" => "default",
                    "prevAction" => $user['last_action'],
                ];
        }
        return $returnFromBot;
    }

    public function makeResponse()
    {

        $response = new \stdClass();
        $controllerResponse = array_shift($this->getResponse());

        if (empty ($controllerResponse['prevAction'])) {
            $controllerResponse['prevAction'] = $controllerResponse['action'];
        }

        $this->user->setLastAction($controllerResponse['action']);
        $this->user->setPrevAction($controllerResponse['prevAction']);
        $this->user->setLastUse(new DateTime('now'));


        $this->database->updateUserKey($this->keyUser, $this->user);


        $i = 0;


        if (in_array('textToSpeech', array_keys($controllerResponse))) {
            $response->fulfillmentText = $controllerResponse["textToSpeech"];
            $response->fulfillmentMessages[$i]->platform = "ACTIONS_ON_GOOGLE";
            $response->fulfillmentMessages[$i]->simpleResponses->simpleResponses[]->textToSpeech = [
                $controllerResponse["textToSpeech"],
            ];
        }
        if (in_array('ssml', array_keys($controllerResponse))) {
            $response->fulfillmentText = $controllerResponse["text"];
            $response->fulfillmentMessages[$i]->platform = "ACTIONS_ON_GOOGLE";
            $response->fulfillmentMessages[$i]->simpleResponses->simpleResponses[]->ssml = '<speak> <audio src="https://obscure-cove-59185.herokuapp.com/web/sound/' . $controllerResponse["ssml"] . '">' . $controllerResponse["text"] . ' </audio></speak>';

        }
        $i++;


        $response->source = "webhook";

        if ($_SERVER['SERVER_NAME'] === "127.0.0.1") {

            $debug = [];
            array_push($debug, ["Request" => $this->getRequest()]);
            array_push($debug, ["Intent" => $this->getIntent()]);

            $response->debug = $debug;
        }
        file_put_contents('responseFromBot.json', json_encode($response));
        return json_encode($response);

    }


    public function rooting($queryUser)
    {
        $this->database->getData("user/$this->keyUser", $user);

        $actions = explode('-', $user["last_action"]);
        if ($user["last_action"] == "default")
        {
            $actions = explode('-', $user["prev_action"]);
        }


        $this->setResponse($this->checkIntent($this->intent, 'action', $queryUser));



        foreach ($this->getResponse() as $resPonseFromHook) {
            $resPonseFromHooks = explode('-', $resPonseFromHook['action']);


            if ($resPonseFromHooks[1] == "suivant") {
                //$this->setResponse($this->checkIntent($actions[1], 'hero', $queryUser));
                $this->setResponse($this->nextAction($actions));
            }
            if ($resPonseFromHooks[1] == "repeter") {
                //$this->setResponse($this->checkIntent($actions[1], 'hero', $queryUser));
                $this->setResponse($this->repeatAction($actions));
            }
            if ($resPonseFromHooks[1] == "quiz") {
                //$this->setResponse($this->checkIntent($actions[1], 'hero', $queryUser));
                $this->setResponse($this->quizAction($actions));
            }

            if ($resPonseFromHooks[0] == "default" AND $actions[0] == "quiz") {
                $this->setResponse($this->quizAction($actions));
            }

            elseif ($resPonseFromHooks[0] == "default") {
                $this->setResponse($this->checkIntent($this->intent, 'hero', $queryUser));
            }

        }
    }
}