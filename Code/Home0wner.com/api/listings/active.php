<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';

$database = new Database();
$db = $database->connect();

$financingTypes = $db->query("SELECT id,financing_type FROM financing_types");
while ($fT = $financingTypes->fetch_object()) {
    $financeString[$fT->id] = ucfirst(strtolower($fT->financing_type)); 
}

if (isset($_GET["orderby"])) {
    $order = "ORDER BY " . $_GET["orderby"];
}

if (isset($_GET["offset"])) {
    $limit = "LIMIT 10 OFFSET " . $_GET['offset'];
} else {
    $limit = "LIMIT 10";
}

if (isset($order) && isset($limit)) {
    $boundary = $order . " " . $limit;
} else {
    $boundary = "ORDER BY listings.listing_date DESC " . $limit;
}

if ($properties = $db->query("SELECT listings.id,properties.address,properties.city,properties.state,properties.zip,properties.county,properties.year_built,properties.bathrooms,properties.bedrooms,properties.heating_system,properties.cooling_system,properties.fireplace,properties.pool,properties.style,properties.basement,properties.basement_finished,properties.lot_size,properties.sq_feet,properties.hoa,properties.property_taxes,properties.property_type,listings.listing_date,listings.purchase_price,listings.accepted_financing,listing_descriptions.description,properties.property_images FROM properties LEFT JOIN listings ON properties.id = listings.property_id LEFT JOIN listing_descriptions ON listings.id = listing_descriptions.listing_id WHERE listings.status = 1 " . $boundary)) {
    $listingsArray["count"] = $properties->num_rows;
    $listingsArray["listings"] = [];
    $i = 0;
    while ($row = $properties->fetch_object()) {
        $financeOptions = [];
        $listingsArray["listings"][$i]["listing_id"] = $row->id;
        $listingsArray["listings"][$i]["address"] = $row->address;
        $listingsArray["listings"][$i]["city"] = $row->city;
        $listingsArray["listings"][$i]["state"] = $row->state;
        $listingsArray["listings"][$i]["zip"] = $row->zip;
        $listingsArray["listings"][$i]["county"] = $row->county;
        $listingsArray["listings"][$i]["year_built"] = $row->year_built;
        $listingsArray["listings"][$i]["bathrooms"] = $row->bathrooms;
        $listingsArray["listings"][$i]["bedrooms"] = $row->bedrooms;
        $listingsArray["listings"][$i]["heating_system"] = yesNo($row->heating_system);
        $listingsArray["listings"][$i]["cooling_system"] = yesNo($row->cooling_system);
        $listingsArray["listings"][$i]["fireplace"] = yesNo($row->fireplace);
        $listingsArray["listings"][$i]["pool"] = yesNo($row->pool);
        $listingsArray["listings"][$i]["style"] = $row->style;
        $listingsArray["listings"][$i]["basement"] = yesNo($row->basement);
        $listingsArray["listings"][$i]["basement_finished"] = yesNo($row->basement_finished);
        $listingsArray["listings"][$i]["lot_size"] = $row->lot_size;
        $listingsArray["listings"][$i]["sq_feet"] = $row->sq_feet;
        $listingsArray["listings"][$i]["hoa"] = yesNo($row->hoa);
        $listingsArray["listings"][$i]["property_taxes"] = $row->property_taxes;
        $listingsArray["listings"][$i]["property_type"] = $row->property_type;
        $listingsArray["listings"][$i]["listing_date"] = $row->listing_date;
        $listingsArray["listings"][$i]["purchase_price"] = $row->purchase_price;
        $thisFinancingOptions = explode(';',$row->accepted_financing);
        foreach ($thisFinancingOptions as &$fOption) {
            $financeOptions[] = $financeString[$fOption];
        }
        $listingsArray["listings"][$i]["financing_options"] = $financeOptions;
        $listingsArray["listings"][$i]["description"] = $row->description;
        $listingsArray["listings"][$i]["image"] = $row->property_images;
        $i++;
    }


    $db->close();
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($listingsArray);
} else {
    // set response code - 404 OK
    http_response_code(404);
}

?>