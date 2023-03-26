<?php

class Constructor
{
    public function __construct(Core $core)
    {

        if ($core->FrontController->checkUrl()) {
            $this->build($core);
        } else {
            echo "Error 404";
        }
    }

    public function build(Core $core): void
    {
        $core->Tracker->trackUser();
        $controller = $core->FrontController->Router->Controller;
        $action = $core->FrontController->Router->Action;
        $parameters = $core->FrontController->Router->Parameters;

        include_once("App/" . $controller . "/" . $controller . ".php");

        $launchController = new $controller($core);

        if (!empty($action)) {
            $action = "VIEW_" . $action;
            $launchController->$action($parameters);
        }
    }
}