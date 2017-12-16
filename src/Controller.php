<?php
/**
 * Created by PhpStorm.
 * User: cerynna
 * Date: 14/12/17
 * Time: 22:12
 */

namespace MasterHook;

use function array_rand;
use DateTime;
use function explode;
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


            $queryUser = $this->formatQuery($json->queryResult->queryText);


            $this->setResponse($this->checkIntent($intents, 'action', $queryUser));


            $userID = $json->originalDetectIntentRequest->payload->user->userId;
            $key = $this->database->getKeyUser($userID);

            $this->database->getData("user/$key", $user);
            $actions = explode('-', $user["last_action"]);


            foreach ($this->getResponse() as $resPonseFromHook) {
                $resPonseFromHooks = explode('-', $resPonseFromHook['action']);


                if ($resPonseFromHooks[1] == "suivant" AND $actions[0] === "hero") {
                    //$this->setResponse($this->checkIntent($actions[1], 'hero', $queryUser));
                    $this->setResponse($this->nextAction($actions));
                }

                if ($resPonseFromHook['action'] == "default") {
                    $this->setResponse($this->checkIntent($intents, 'hero', $queryUser));
                }
            }

        } else {
            $this->setResponse("Vous n'etes pas en POST");
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

    public function nextAction($actions)
    {
        $this->database->getData($actions[0] . "/" . $actions[1], $lists);

        $key = array_rand($lists);

        $returnFromBot[] =
            [
                "textToSpeech" => $lists[$key]["text"],
                "action" => $actions[0] . "-" . $actions[1] . "-" . $key,
            ];
        return $returnFromBot;
    }

    public function checkIntent($intents, $type, $queryUser)
    {
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
                    ];


                if ($texts[$key]['sound'] != null) {
                    $returnFromBot[$j] =
                        [
                            "ssml" => "cPasFaux.mp3",
                            "text" => $texts[$key]["text"],
                            "action" => $type . "-" . $intent . "-" . $key,
                        ];

                }
            } // FIND MATCH IN WORD INTENT
            elseif ($pos != 0) {
                $returnFromBot[$j] =
                    [
                        "textToSpeech" => $texts[$key]["text"],
                        "action" => $type . "-" . $intent . "-" . $key,
                    ];
                if ($texts[$key]['sound'] != null) {
                    $returnFromBot[$j] =
                        [
                            "ssml" => "cPasFaux.mp3",
                            "text" => $texts[$key]["text"],
                            "action" => $type . "-" . $intent . "-" . $key,
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
                ];
        }
        return $returnFromBot;
    }

    public function makeResponse()
    {

        $response = new \stdClass();
        $controllerResponse = array_shift($this->getResponse());

        $json = $this->getRequest();
        $userID = $json->originalDetectIntentRequest->payload->user->userId;


        $user = new User([
            "id" => $userID
        ]);

        $key = $this->database->getKeyUser($userID);
        if ($key == false) {
            $this->database->addUser($user);
            $key = $this->database->getKeyUser($userID);
        }


        $user->setLastAction($controllerResponse['action']);
        $user->setLastUse(new DateTime('now'));

        $this->database->updateUserKey($key, $user);


        $i = 0;

        foreach ($controllerResponse as $key => $item) {
            if (in_array('textToSpeech', array_keys($item))) {
                $response->fulfillmentText = $item["textToSpeech"];
                $response->fulfillmentMessages[$i]->platform = "ACTIONS_ON_GOOGLE";
                $response->fulfillmentMessages[$i]->simpleResponses->simpleResponses[]->textToSpeech = [
                    $item["textToSpeech"],
                ];
            }
            if (in_array('ssml', array_keys($item))) {
                $response->fulfillmentText = $item["text"];
                $response->fulfillmentMessages[$i]->platform = "ACTIONS_ON_GOOGLE";
                $response->fulfillmentMessages[$i]->simpleResponses->simpleResponses[]->ssml = '<speak> <audio src="https://obscure-cove-59185.herokuapp.com/web/sound/' . $item["ssml"] . '">' . $item["text"] . ' </audio></speak>';

            }
            $i++;
        }


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

}