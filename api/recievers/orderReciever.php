<?php
 /* "POST" delen: Startar en session först. Sen en ny order och en koppling till databasen.
        Kopplingen till databasen är för att hämta info om användaren 
        som är inloggad och sparad i SESSION. new Order gör att vi kan kalla på
        alla funktioner som ligger där och på så sätt skapa det som vi vill
        skicka in i databasen ex orderdatum osv.. Från cart sidan skickar vi alltså med 
        produkterna och shippingName i POST*/
include_once("../classes/orderClass.php");
include_once("../classes/shippingClass.php");
include_once("../handlers/databaseHandler.php");
try{
    if(isset($_SERVER["REQUEST_METHOD"])){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if($_POST["endpoint"] == "createOrder"){
                session_start();
                $db = new Database();
                $userLoggedIn = unserialize($_SESSION["user_loggedin"]);
                $userLoggedIn = $userLoggedIn["email"];
                $userMakingOrder = $db->collectFromDatabase("SELECT * FROM user WHERE email = '$userLoggedIn';");
                $order = new Order();
                $productInCart = json_decode($_POST["productsToOrder"], true);
                $shippingMethod = json_decode($_POST["shippingMethod"]);
        
                /* kör de olika funktionerna och sparar i olika variabler
                det vi behöver när vi sparar en order i databasen, har döpt
                variablerna efter kolumn-namnen */
                
                $orderDate = $order->orderDate();
                
                $totalPrice = $order->totalPrice($productInCart);
                
                $totalQuantity = $order->getQuantity($productInCart);
                
                $shippingID = $order->getShippingID($shippingMethod);
        
                $userID = $userMakingOrder[0]->userID;
                /* uppdaterar lagersaldot */
                $updatingUnitsInStock = $order->updateUnitsInStock($productInCart);
                /* sparar ordern i databasen */
                echo json_encode($order->saveOrder($orderDate, $totalPrice, $totalQuantity, $shippingID, $userID));
                exit; 
            }




        }else if($_SERVER["REQUEST_METHOD"] == "GET"){
            /* skicka en get request hit för att hämta shipping methods */
            $shipping = new Shipping();
            $resultFromDatabase = $shipping->getShippingMethod();
            echo json_encode($resultFromDatabase);
            exit;
        }



    }else{
        throw new Exception("No requst method was set..");
        exit;
    }

}catch(Exception $e){
    http_response_code($e->getCode());
    echo json_encode(array("status"=>$e->getCode(), "Message"=>$e->getMessage()));
}