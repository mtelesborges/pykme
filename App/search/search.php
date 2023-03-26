<?php
class search {
    public Core $Core;
    public function __construct(Core $core){
        $this->Core = $core;
        $db = $this->Core->getDB();

        $lat = $_GET["lat"] ?? 0;
        $lng = $_GET["lng"] ?? 0;

        $sql = <<<SQL
            /**
             * https://www.movable-type.co.uk/scripts/latlong.html
             * a = sin²(Δφ/2) + cos φ1 ⋅ cos φ2 ⋅ sin²(Δλ/2)
             * c = 2 ⋅ atan2( √a, √(1−a) )
             * d = R ⋅ c
             */
            with cte_distance as (
                with cte as (
                    select
                        *,
                        lat * 3.14/180 as q1,
                        ? * 3.14/180 as q2,
                        (? - lat) * 3.14/180 d1,
                        (? - lng) * 3.14/180 d2
                    from
                        shops
                    where
                         status != 'deleted'
                )
                select
                    *,
                    atan2(sqrt(a), sqrt(1-a)) * 2 * 6371 as distance
                from
                    (select
                        *,
                        sin(d1/2) * sin(d1/2) + cos(q1) * cos(q2) * sin(d2/2) * sin(d2/2) as a
                    from 
                        cte
                    ) as a
            ) select * from cte_distance limit 20
        SQL;

        $shops = $db->query($sql, array("iii", $lat, $lat, $lng), false);

        $view = [
            "File" 				=> "search.php",
            "PageTitle" 		=> $core->Translator->translate("Take your pick! Food, groceries and more."),
            "PageDescription" 	=> $core->Translator->translate("Welcome to pykme.com. We are a marketplace for restaurants, retail, shops and taxi"),
            "Design"			=> "Intro",
            "CSS"				=> array("home.css"),
            "JS"				=> array(""),
            "Keywords"			=> array(
                $core->Translator->translate("Delivery"),
                $core->Translator->translate("Restaurants"),
                $core->Translator->translate("Groceries"),
                $core->Translator->translate("Free delivery Software"),
            ),
            "Data"				=> $shops,
            "Core"				=> $this->Core
        ];
        $this->Core->FrontController->render($view);
    }

}