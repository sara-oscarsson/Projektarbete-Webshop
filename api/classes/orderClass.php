<?php
class Order{
    function __construct(){
        include_once("../handlers/databaseHandler.php");
        $this->db = new Database();
    }
    public function getOrdersFromDb(){
        $result = $this->db->collectFromDatabase("SELECT * FROM `order`;");
        return $result;
    }
    /* Skicka in lista på det som ligger i cart obs tänk på att alla "nycklar" 
    heter som i databasen så blir det enklare för oss, kolla att det är en array som skickas in först!! 
    */
    public function getQuantity($productsInCart){
        $totalQuantity = [];
        $length = count($productsInCart);
        for ($i=0; $i < $length; $i++) { 
            $productPrice = intval($productsInCart[$i]["quantity"]);
            array_push($totalQuantity, $productPrice);
            
        }
        return array_sum($totalQuantity);
    }
    /* skicka in samma lista med produkter för att få totalpris*/
    public function totalPrice($productsInCart){
        $prices = [];
        $length = count($productsInCart);
        for ($i=0; $i < $length; $i++) { 
            /* return $productsInCart[$i]["product"]["productPrice"]; */
            $productPrice = intval($productsInCart[$i]["product"]["productPrice"]);
            array_push($prices, $productPrice);
        }
        return array_sum($prices);
    }
    /* skicka in shippingName ex Postnord så hämtas shippingID från databasen
    eftersom det ska in i order-tabellen */
    public function getShippingID($shippingName){
        $result = $this->db->collectFromDatabase("SELECT shippingID FROM shipping WHERE shippingName = '$shippingName';");
        /* när jag testade detta fick jag en array med ett objekt i tillbaka därför har jag sktivei som jag gjort nedanför */
        return $result;
        /* return $result[0]->shippingID; */
    }
    public function orderDate(){
        return date("Y-m-d");
    }
    /*  OBS LÄGG IN $userMakingOrder sist och kommentera in rad 50*/
    public function saveOrder($orderDate, $totalPrice, $totalQuantity, $shippingID, $userID){
    
        /* OBS SKA KOMMENTERAS IN IGEN NEDAN!!! */
        /* $userID = $userMakingOrder[0]->userID; */
        $entity = [];
        $result = $this->db->editDatabase("INSERT INTO `order` (`orderID`, `orderDate`, `totalPrice`, `totalQuantity`, `shippingID`, `userID`) VALUES (NULL, '$orderDate', '$totalPrice', '$totalQuantity', '$shippingID', '$userID');", $entity);
        return $result;
    }
    /* uppdaterar lagersaldot */
    public function updateUnitsInStock($productsInCart){
        $entity = [];
        $cartLength = count($productsInCart);
        for ($i=0; $i < $cartLength; $i++) { 
            $productID = $productsInCart[$i]["product"]["productID"];
            $Antal = intval($productsInCart[$i]["quantity"]);
            $result = $this->db->editDatabase("UPDATE `product` SET `unitsInStock` = (SELECT `unitsInStock` - '$Antal') WHERE `product`.`productID` = '$productID';", $entity);
        }
    }
}