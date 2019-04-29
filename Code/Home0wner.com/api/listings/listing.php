<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';



if (!isset($_GET['listing'])) {
        // set response code - 401 Unauthorized
        http_response_code(404);
} else {
    $database = new Database();
    $db = $database->connect();

    $financingTypes = $db->query("SELECT id,financing_type FROM financing_types");
    while ($fT = $financingTypes->fetch_object()) {
        $financeString[$fT->id] = ucfirst(strtolower($fT->financing_type)); 
    }

    $listingSQL = "SELECT listings.id,properties.address,properties.city,properties.state,properties.zip,properties.county,properties.year_built,properties.bathrooms,properties.bedrooms,properties.heating_system,properties.cooling_system,properties.fireplace,properties.pool,properties.style,properties.basement,properties.basement_finished,properties.lot_size,properties.sq_feet,properties.hoa,properties.property_taxes,properties.property_type,listings.listing_date,listings.purchase_price,listings.accepted_financing,listing_descriptions.description,properties.property_images,listings.listing_agent FROM properties LEFT JOIN listings ON properties.id = listings.property_id LEFT JOIN listing_descriptions ON listings.id = listing_descriptions.listing_id WHERE listings.id = ? ";
    $listingDetails = $db->prepare($listingSQL);
    $listingDetails->bind_param("d", $_GET['listing']);
    $listingDetails->execute();
    //$listingDetails->store_result();
    $result = $listingDetails->get_result();
    $returned = $result->num_rows;

    if ( $returned > 0 ) {
        while ($row = $result->fetch_object()) {
            $financeOptions = [];
            $thisListing["address"] = $row->address;
            $thisListing["city"] = $row->city;
            $thisListing["state"] = $row->state;
            $thisListing["zip"] = $row->zip;
            $thisListing["county"] = $row->county;
            $thisListing["year_built"] = $row->year_built;
            $thisListing["bathrooms"] = $row->bathrooms;
            $thisListing["bedrooms"] = $row->bedrooms;
            $thisListing["heating_system"] = yesNo($row->heating_system);
            $thisListing["cooling_system"] = yesNo($row->cooling_system);
            $thisListing["fireplace"] = yesNo($row->fireplace);
            $thisListing["pool"] = yesNo($row->pool);
            $thisListing["style"] = $row->style;
            $thisListing["basement"] = yesNo($row->basement);
            $thisListing["basement_finished"] = yesNo($row->basement_finished);
            $thisListing["lot_size"] = $row->lot_size;
            $thisListing["sq_feet"] = $row->sq_feet;
            $thisListing["hoa"] = yesNo($row->hoa);
            $thisListing["property_taxes"] = $row->property_taxes;
            $thisListing["property_type"] = $row->property_type;
            $thisListing["listing_date"] = $row->listing_date;
            $thisListing["purchase_price"] = $row->purchase_price;
            $thisFinancingOptions = explode(';',$row->accepted_financing);
            foreach ($thisFinancingOptions as &$fOption) {
                $financeOptions[] = $financeString[$fOption];
            }
            $thisListing["financing_options"] = $financeOptions;
            $thisListing["description"] = $row->description;
            $thisListing["image"] = $row->property_images;
            $thisListing["agent"] = $row->listing_agent;
        }
        $db->close();
        // set response code - 200 OK
        http_response_code(200);
     
        // show products data in json format
        echo json_encode($thisListing);
    } else {
        // set response code - 404 OK
        http_response_code(404);
    }

}



?>