// Base functions go here

function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
  }

function getParam(name) {
    return (location.search.split(name + '=')[1] || '').split('&')[0];
}

function makeShort(string,chars) {
    return jQuery.trim(string).substring(0, chars).split(" ").slice(0, -1).join(" ") + "...";
}

function makeQueryString(oParameters) {
    return "?" + Object.keys(oParameters).map(function(key) {
        if(oParameters[key]) {
            return key + "=" + encodeURIComponent(oParameters[key]);
        }
    }).filter(function(elem) {
        return !! elem;
    }).join("&");
}

function queryStringToJSON(queryString) {
    if (queryString.indexOf('?') > -1) {
        queryString = queryString.split('?')[1];
    }
    var pairs = queryString.split('&');
    var result = {};
    pairs.forEach(function(pair) {
        pair = pair.split('=');
        b = decodeURIComponent(pair[1] || '');
        try {
            result[pair[0]] = eval(b);
        }
        catch {
            result[pair[0]] = b;
        }
    });
    return result;
}
// End Base Functions

// API Data Functions

function populateSelector(selectID,apiEndpoint) {
    $.getJSON(apiEndpoint, function(data) {
        $.each(data, function(key, value) {
            //console.log(selectID + ": " + key + "," + value);
            $("#" + selectID).append('<option value="' + key + '">' + value + '</option>');
            $("#" + selectID).niceSelect("update");  
        });
    });
}

function populateCities(selectID,apiEndpoint,thisObject) {
    var selectedState = thisObject.value;
    $("#" + selectID).children('option:not(:first)').remove();
    $.getJSON(apiEndpoint,{state:selectedState}, function(data) {
        $.each(data, function(key,value) {
            //console.log(selectID + ": " + key + "," + value);
            $("#" + selectID).append('<option value="' + value + '">' + value + '</option>');
            $("#" + selectID).niceSelect("update");  
        });
    });
}

function cityZipLookup(entry) {
    var partialText = entry.value;
    if (partialText.length >= 3) {
        $("#" + entry.id).children('option:not(:first)').remove();
        if (partialText.match('/[0-9]+/')) {
            $.getJSON(CITIESDETAILAPI,{zip:partialText},function(data) {
                $.each(data, function(key,value) {

                })
            })
        }
    } else {
        return false;
    }
}

function getActiveListingsGrid(offset,orderBy) {
    $.getJSON(ACTIVELISTINGAPI,{offset:offset,orderby:orderBy}, function(data) {
        $.each(data.listings, function(i, item) {
            var listing = [];
            listing.push('<div class="col-12 col-md-6 col-xl-4" onclick="openListing('+item.listing_id+')" >');
            listing.push('<div class="single-featured-property mb-50">');
            listing.push('<div class="property-thumb"><img src="' + item.image + '" alt="">');
            listing.push('<div class="tag"><span>For Sale</span></div><div class="list-price"><p>$' + commaSeparateNumber(item.purchase_price) + '</p></div></div>');
            listing.push('<div class="property-content"><h5>'+ item.style + ' in ' + item.city +'</h5>');
            listing.push('<p class="location"><img src="img/icons/location.png" alt="">'+item.address+', '+item.state+'</p>');
            listing.push('<p>'+makeShort(item.description,130)+'</p>');
            listing.push('<div class="property-meta-data d-flex align-items-end justify-content-between"><div class="new-tag"><img src="img/icons/new.png" alt=""></div><div class="bathroom"><img src="img/icons/bathtub.png" alt=""><span>'+item.bathrooms+'</span></div');
            listing.push('<div class="garage"><img src="img/icons/garage.png" alt=""><span>'+item.bedrooms+'</span></div>');
            listing.push('<div class="space"><img src="img/icons/space.png" alt=""><span>'+item.sq_feet+' sq ft</span>');
            listing.push('<div class="listing-overlay"><div><span>View Property</span></div><div><span>Favorite</span></div></div>');
            listing.push('</div></div></div></div></div>');
            $(".listing-result-section").append(listing.join(''));
        });
    });
}

function getFeaturedListingsGrid() {
    $.getJSON(FEATUREDLISTINGAPI, function(data) {
        $.each(data.listings, function(i, item) {
            var listing = [];
            listing.push('<div class="col-12 col-md-6 col-xl-4">');
            listing.push('<div class="single-featured-property mb-50">');
            listing.push('<div class="property-thumb"><img src="' + item.image + '" alt="">');
            listing.push('<div class="tag"><span>For Sale</span></div><div class="list-price"><p>$' + commaSeparateNumber(item.purchase_price) + '</p></div></div>');
            listing.push('<div class="property-content"><h5>'+ item.style + ' in ' + item.city +'</h5>');
            listing.push('<p class="location"><img src="img/icons/location.png" alt="">'+item.address+', '+item.state+'</p>');
            listing.push('<p>'+makeShort(item.description,130)+'</p>');
            listing.push('<div class="property-meta-data d-flex align-items-end justify-content-between"><div class="new-tag"><img src="img/icons/new.png" alt=""></div><div class="bathroom"><img src="img/icons/bathtub.png" alt=""><span>'+item.bathrooms+'</span></div');
            listing.push('<div class="garage"><img src="img/icons/garage.png" alt=""><span>'+item.bedrooms+'</span></div>');
            listing.push('<div class="space"><img src="img/icons/space.png" alt=""><span>'+item.sq_feet+' sq ft</span>');
            listing.push('</div></div></div></div></div>');
            $(".listing-result-section").append(listing.join(''));
        });
    });
}

function getSearchListingsGrid(searchString) {
    $.getJSON(SEARCHLISTINGSAPI,searchString.substring(1, searchString.length), function(data) {
        $.each(data.listings, function(i, item) {
            var listing = [];
            listing.push('<div class="col-12 col-md-6 col-xl-4">');
            listing.push('<div class="single-featured-property mb-50">');
            listing.push('<div class="property-thumb"><img src="' + item.image + '" alt="">');
            listing.push('<div class="tag"><span>For Sale</span></div><div class="list-price"><p>$' + commaSeparateNumber(item.purchase_price) + '</p></div></div>');
            listing.push('<div class="property-content"><h5>'+ item.style + ' in ' + item.city +'</h5>');
            listing.push('<p class="location"><img src="img/icons/location.png" alt="">'+item.address+', '+item.state+'</p>');
            listing.push('<p>'+makeShort(item.description,130)+'</p>');
            listing.push('<div class="property-meta-data d-flex align-items-end justify-content-between"><div class="new-tag"><img src="img/icons/new.png" alt=""></div><div class="bathroom"><img src="img/icons/bathtub.png" alt=""><span>'+item.bathrooms+'</span></div');
            listing.push('<div class="garage"><img src="img/icons/garage.png" alt=""><span>'+item.bedrooms+'</span></div>');
            listing.push('<div class="space"><img src="img/icons/space.png" alt=""><span>'+item.sq_feet+' sq ft</span>');
            listing.push('</div></div></div></div></div>');
            $(".listing-result-section").append(listing.join(''));
        });
    });
}
function getFeaturedListings() {
    $.getJSON(FEATUREDLISTINGAPI, function(data) {
        var owl = $('.featured-properties-slides, .single-listings-sliders');
        $.each(data.listings, function(i, item) {
            owl.trigger('add.owl.carousel', ['<div class="single-featured-property"><div class="property-thumb"><img src="'+item.image+'" alt=""><div class="tag"><span>For Sale</span></div><div class="list-price"><p>$'+commaSeparateNumber(item.purchase_price)+'</p></div></div><div class="property-content"><h5>'+item.style+' in '+item.city+'</h5><p class="location"><img src="img/icons/location.png" alt="">'+item.address+', '+item.state+'</p><p>'+makeShort(item.description,100)+'</p><div class="property-meta-data d-flex align-items-end justify-content-between"><div class="new-tag"><img src="img/icons/new.png" alt=""></div><div class="bathroom"><img src="img/icons/bathtub.png" alt=""><span>'+item.bathrooms+'</span></div><div class="garage"><img src="img/icons/garage.png" alt=""><span>'+item.bedrooms+'</span></div><div class="space"><img src="img/icons/space.png" alt=""><span>'+item.sq_feet+'</span></div></div></div></div>']).trigger('refresh.owl.carousel');
        });
    });
}
function getSingleListing(listingID) {
    $.getJSON(SINGLELISTINGAPI,{listing:listingID}, function(data,status,xhr) {
        var listing = [];
        listing.push('<div class="list-price"><p>$'+commaSeparateNumber(data.purchase_price)+'</p></div>');
        listing.push('<h5>'+data.address+'</h5>');
        listing.push('<p class="location"><img src="img/icons/location.png" alt="">'+data.style+' in '+data.city+', '+data.state+'</p>');
        listing.push('<p>'+data.description+'</p>');
        listing.push('<div class="property-meta-data d-flex align-items-end"><div class="new-tag"><img src="img/icons/new.png" alt=""></div><div class="bathroom"><img src="img/icons/bathtub.png" alt=""><span>'+data.bathrooms+'</span></div>');
        listing.push('<div class="garage"><img src="img/icons/garage.png" alt=""><span>'+data.bedrooms+'</span></div>');
        listing.push('<div class="space"><img src="img/icons/space.png" alt=""><span>'+data.sq_feet+'</span></div></div>');
        listing.push('<ul class="listings-core-features d-flex align-items-center">');
        if (data.heating_system == "Yes") {
            listing.push('<li><i class="fa fa-check" aria-hidden="true"></i>Heating System</li>');
        }
        if (data.cooling_system == "Yes") {
            listing.push('<li><i class="fa fa-check" aria-hidden="true"></i>Central Air</li>');
        }
        if (data.fireplace == "Yes") {
            listing.push('<li><i class="fa fa-check" aria-hidden="true"></i>Fireplace</li>');
        }
        if (data.pool == "Yes") {
            listing.push('<li><i class="fa fa-check" aria-hidden="true"></i>Pool</li>');
        }
        if (data.hoa == "Yes") {
            listing.push('<li><i class="fa fa-check" aria-hidden="true"></i>Homeowner&#39;s Association</li>');
        }
        if (data.basement == "Yes") {
            listing.push('<li><i class="fa fa-check" aria-hidden="true"></i>Basement</li>');
        }
        if (data.basement_finished == "Yes") {
            listing.push('<li><i class="fa fa-check" aria-hidden="true"></i>Finished Basement</li>');
        }
        listing.push('</ul><div class="listings-btn-groups">');
        if (isFavorite(listingID)) {
            listing.push('<a href="#" id="fav-button" class="btn south-btn" onclick="removeFavorite('+listingID+')">Favorited!</a>');
        } else {
            listing.push('<a href="#" id="fav-button" class="btn south-btn" onclick="makeFavorite('+listingID+';")>Save!</a>')
        }
        listing.push('<a href="#" class="btn south-btn active">calculate mortgage</a></div></div></div>');
        $(".listings-content").append(listing.join(''));
        $(".single-listings-sliders").append('<img src="'+data.image+'" width="1200px" alt="">');
    }).error(function(data,textStatus,xhr){
        if (data.status == 404) {
            $(".listings-content").append('<p>No listing data found for listing '+listingID+'.</p>');
            $(".realtor-info").empty();
            $("#googleMap").remove();
        } else if (data.status == 500 ) {
            $(".listings-content").append('<p>An error occurred: '+data.responseText+'.</p>');
            $(".contact-realtor-wrapper").remove();
            $("#googleMap").remove();
        }
    });
}

function buildRealtorForListing(listingID) {
    $.ajax({
        type: 'GET',
        url: AGENTFORLISTINGAPI,
        data: {listing:listingID},
        statusCode: {
            200: function(item) {
                var realtor = [];
                realtor.push('<h2>' + item.name + '</h2>');
                realtor.push('<p>Realtor</p><h6><img src="img/icons/phone-call.png" alt=""> ' + item.phone + '</h6>');
                realtor.push('<h6><img src="img/icons/envelope.png" alt=""> ' + item.email + '</h6>');
                $(".realtor---info").append(realtor.join(''));
                $("#realtor-pic").attr("src", item.image);
            },
            404: function(item) {
                console.log(decodeURIComponent(item.responseJSON.errorMsg));
                $(".realtor-info").append("<!-- " + decodeURIComponent(item.responseJSON.errorMsg) + " -->");
            }
        }
    });
}

function getTeam() {
    $.getJSON(FULLTEAMAPI, function(data) {
        $.each(data, function(i, item) {
            var team = [];
            team.push('<div class="col-12 col-sm-6 col-lg-4"><div class="single-team-member mb-100 wow fadeInUp" data-wow-delay="250ms"><div class="team-member-thumb"><img src="'+item.image+'" alt="" style="width=300px;height=300px;"></div>');
            team.push('<div class="team-member-info"><div class="section-heading"><img src="img/icons/prize.png" alt=""><h2>'+item.name+'</h2><p>Realtor</p></div>');
            team.push('<div class="address"><h6><img src="img/icons/phone-call.png" alt=""> '+item.phone+'</h6><h6><img src="img/icons/envelope.png" alt=""> <a href="mailto:'+item.email+'">'+item.email+'</a></h6></div></div></div></div>');
            $(".team-list").append(team.join(''));
        });
    });
}

function getUserProfile() {
    $.ajax({
        url: USERPROFILEAPI,
        type: 'GET',
        dataType: 'json',
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        success: function(json) {
            var userProfile = [];
            userProfile.push('<form id="change-password-form" action="#" onsubmit="submitChangePass(this);"><div class="row"><div class="col-6"><h3>Welcome '+json.full_name+'</h3></div><div class="col-6"><h3>Change Password</h3></div></div>');
            userProfile.push('<div class="row"><div class="col-3"><span>First name:</span></div><div class="col-3"><span class="first-name">'+json.first_name+'</span></div><div class="col-3"><input type="password" id="change-password" name="password" placeholder="New Password" /></div></div>');
            userProfile.push('<div class="row"><div class="col-3"><span>Last name:</span></div><div class="col-3"><span class="last-name">'+json.last_name+'</span></div><div class="col-3"><input type="password" id="confirm-password" placeholder="Confirm Password" /></div></div>');
            userProfile.push('<div class="row"><div class="col-3"><span>Email:</span></div><div class="col-3"><span class="email-address">'+json.email+'</span></div><div class="col-6"><span id="pass-change-status"></span></div></div>');
            userProfile.push('<div class="row"><div class="col-3"><span>Location:</span></div><div class="col-3"><span class="my-location">'+json.city+' '+json.zip+'</span></div><div class="col-6"><input type="hidden" id="user-id" name="user_id" value="'+json.user_id+'" /><input class="btn btn-primary" id="change-password-submit" type="submit" class="login" value="Change Password" /></form></div></div>');
            $(".user-details").append(userProfile.join(''));
        }
    });
}


function submitChangePass(form) {
    // Prevent any action on the window location
    event.preventDefault();

    var loginForm = $("#change-password-form");
    var newPassword = loginForm.find('#change-password').val();
    var confirmPassword = loginForm.find('#confirm-password').val();
    var userId = loginForm.find('#user-id').val();

    if (newPassword == confirmPassword) {
        $.ajax({
            type: 'POST',
            url: PASSCHANGEAPI,
            data: loginForm.serialize(),
            statusCode: {
                200: function() {
                    $("#pass-change-status").removeClass();
                    $("#pass-change-status").addClass('text-success');
                    $("#pass-change-status").text('Password changed successfully');
                    loginForm.find('#change-password').val("");
                    loginForm.find('#confirm-password').val("");
                },
                500: function() {
                    $("#pass-change-status").removeClass();
                    $("#pass-change-status").addClass('text-danger');
                    $("#pass-change-status").text('Password change failed');
                }
            }
        });
        return false;
    } else {
        $("#pass-change-status").removeClass();
        $("#pass-change-status").addClass('text-danger');
        $("#pass-change-status").text('Passwords do not match');
        return false;
    }
    return false;
};

function buildFavoritesGrid(orderBy) {
    $.ajax({
        type: 'GET',
        url: SAVEDHOMESAPI,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        statusCode: {
            200: function(data) {
                $.each(data, function(i,item) {
                    $.getJSON(SINGLELISTINGAPI,{listing:item.listing_id}, function(data,status,xhr) {
                        var listing = [];
                        listing.push('<div class="col-12 col-md-6 col-xl-4">');
                        listing.push('<div class="single-featured-property mb-50">');
                        listing.push('<div class="property-thumb"><img src="' + data.image + '" alt="">');
                        listing.push('<div class="tag"><span>For Sale</span></div><div class="list-price"><p>$' + commaSeparateNumber(data.purchase_price) + '</p></div></div>');
                        listing.push('<div class="property-content"><h5>'+ data.style + ' in ' + data.city +'</h5>');
                        listing.push('<p class="location"><img src="img/icons/location.png" alt="">'+data.address+', '+data.state+'</p>');
                        listing.push('<p>'+makeShort(data.description,130)+'</p>');
                        listing.push('<div class="property-meta-data d-flex align-items-end justify-content-between"><div class="new-tag"><img src="img/icons/new.png" alt=""></div><div class="bathroom"><img src="img/icons/bathtub.png" alt=""><span>'+data.bathrooms+'</span></div');
                        listing.push('<div class="garage"><img src="img/icons/garage.png" alt=""><span>'+data.bedrooms+'</span></div>');
                        listing.push('<div class="space"><img src="img/icons/space.png" alt=""><span>'+data.sq_feet+' sq ft</span>');
                        listing.push('</div></div></div></div></div>');
                        $(".listing-result-section").append(listing.join(''));
                    });
                });   
            },
                
        }
    });
}

function grabSlider($this) {
    var nextRanger = $("#advancedSearch").closest(".range");
    console.log(nextRanger.text());
}

/*
function setSearchFormValues() {

}
*/

function doAdvancedSearch() {
    event.preventDefault();
    var searchForm = $("#advancedSearch");
    var searchString = [];
    if (searchForm.find("#city-zip").val() != "") {
        var cityZip = searchForm.find("#city-zip").val().split(",");
        var stateZip = cityZip[1].split(" ");
        searchString["city"] = cityZip[0];
        searchString["zip"] = stateZip[2];
        searchString['state'] = stateZip[1];
    }
    if (! searchString['state']) {
        searchString['state'] = searchForm.find("#state").val()
        if (searchString['state'] == "All States") {
            searchString['state'] = "";
        }
    }
    searchString['bedrooms'] = searchForm.find("#bedrooms").val()
    if (searchString['bedrooms'] == "D") {
        searchString['bedrooms'] = "";
    }
    searchString['bathrooms'] = searchForm.find("#bathrooms").val()
    if (searchString['bathrooms'] == "D") {
        searchString['bathrooms'] = "";
    }
    searchString['sq_feet'] = grabSlider('.footage-slider');
    var queryString = makeQueryString(searchString);
    window.location = "/listings.html" + queryString + "&action=search";
    return false;
}

function updateSearchFormValues() {
    var searchForm = $("#advancedSearch");
    var searchQuery = location.search;
    var a = searchQuery.substring(1);
    /*
    var searchObj = a?JSON.parse(
        '{"' + a.replace(/&/g, '","').replace(/=/g,'":"') + '"}', 
         function(key, value) { 
            return key===""?value:decodeURIComponent(value) 
         }
     )
     :
     {}
     */
    var searchObj = queryStringToJSON(a);
     if (searchObj["city"] && searchObj["state"] && searchObj["zip"]) {
        var cityZip = searchObj["city"] + ", " + searchObj["state"] + " " + searchObj["zip"];
        searchForm.find("#city-zip").val(cityZip);
        searchForm.find("#search-location").val(cityZip);
     }
     
}

function isFavorite(listingID) {
    $.ajax({
        url: CHECKFAVORITESAPI,
        type: 'GET',
        dataType: 'json',
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        data: {listing: listingID},
        success: function(data,statusCode,e) {
            if (statusCode == 200) {
                return data;
            }
        }
    });    
}

function makeFavorite(listingID) {
    event.preventDefault;
    $.ajax({
        url: MAKEFAVORITESAPI,
        type: 'POST',
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        data: {listing: listingID},
        statusCode: {
            200: function() {
                $("#fav-button").text('Saved!');
                return false;
            },
            404: function () {
                $("#fav-button").text('Saved Failed!');
                return false;
            }
        }
    });  
}

function checkFinancingStatus(id) {
    $.ajax({
        url: CHECKFINANCINGAPI,
        type: 'GET',
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        statusCode : {
            200: getCreditDetails(id),
            404: showCreditApp()
        }
    });
}

function hasDoneCreditPull(id) {
    $.ajax({
        url: CHECKFINANCINGAPI,
        type: 'GET',
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        statusCode : {
            200: function() { 
                $('#my-finance-button').html('See My Financing!');
            },
            404: function() { 
                $('#financing-details').removeClass('btn-primary');
                $('#financing-details').addClass('btn-success');
                $('#financing-details').html('Apply For Financing!');
            }
        }
    });
}

function getCreditDetails(id) {
    $.ajax({
        url: CREDITDETAILSAPI,
        type: 'POST',
        data: {user_id: id},
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        statusCode: {
            200: function(data) {
                var financeDetails = [];
                financeDetails.push('<div class="row">');
                financeDetails.push('<div class="col-xl-6"><div id="creditScoreGage" style="width:400px; height:320px"></div></div>');
                financeDetails.push('<div class="row"><div class="col-sm-6"><h1 class="text-success">$'+commaSeparateNumber(data.income)+'</h1><h4>Monthly Income</h4><br><h1 class="text-danger">$'+commaSeparateNumber(data.monthly_debts)+'</h1><h4>Monthly Debts</h4></div></div></div>');
                $("#financing-details").append(financeDetails.join(''));
                var g = new JustGage({
                    id: "creditScoreGage",
                    value: data.credit_score,
                    min: 300,
                    max: 850,
                    title: "My Credit Score",
                    customSectors: {
                        ranges: [{
                            color: '#ff3300',
                            lo: 300,
                            hi: 669
                        }, {
                            color: '#ff9966',
                            lo: 580,
                            hi: 669
                        }, {
                            color: '#ccff99',
                            lo: 670,
                            hi: 799
                        }, {
                            color: '#00cc00',
                            lo: 800,
                            hi: 850
                        }]
                    }
                });
            }
        }
    });
}

function showCreditApp() {
    var creditApp = [];
    creditApp.push('<div class="jumbotron"><h1>Get Your Financing Details</h1><p>You&#39;re just a few seconds away from many exciting and competivie financing packages to help you get into your dream home fast! Click below to get started!</p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#creditAppModal">Apply Now</button></div>');
    creditApp.push('<div class="modal" id="creditAppModal"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Apply for Financing</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><form name="creditAppForm" id="creditAppForm"><div class="row"><div class="col-sm-6"><span>Social Security Number</span></div>');
    creditApp.push('<div class="col-sm-6"><input id="SSN" type="password" name="SSN" placeholder="XXX-XX-XXXX" /></div>');
    creditApp.push('</div><div class="row"><div class="col-sm-6"><span>Yearly Income</span></div><div class="col-sm-6"><input type="text" id="income" name="income" placeholder="e.g. 40000" /></div></div>');
    creditApp.push('<div class="row"><div class="col-sm-6"><span>Monthly Debts</span></div><div class="col-sm-6"><input type="text" id="monthly_debts" name="monthly_debts" placeholder="e.g. 2100" /></div></div></div>');
    creditApp.push('<div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button><button type="button" class="btn btn-success" onclick="submitCreditApp(this);" data-dismiss="modal">Apply</button>');
    creditApp.push('</form></div></div></div></div>');
    $(".financing-details").append(creditApp.join(''));
}

function submitCreditApp() {
    event.preventDefault();
    var creditApp = $("#creditAppForm");
    $.ajax({
        url: CREDITPULLAPI,
        type: 'POST',
        data: creditApp.serialize(),
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Authorization", "Bearer " + getJWT());
        },
        statusCode: {
            200: function () {
                $("#financing-details").empty();
                var myID = userIDFromJWT();
                checkFinancingStatus(myID);
            }
        }

    });
    $('#creditAppModal').modal('toggle');
}
// End Data API Functions

// UI Functions 

function openListing(id) {
    window.location = "/single-listings.html?listing=" + id;
}

function buildFinanceDetails(financeStatus) {
    console.log(financeStatus)
    if (financeStatus) {
        var myID = userIDFromJWT();
        alert(myID);
        var myCredit = getCreditDetails(myID);
    }
}
// Begin Authentication Funcations
function storeJWT(JWT) {
    localStorage.setItem('home0wner-auth',JWT.responseText);
}

function getJWT() {
    var myJWT = localStorage.getItem('home0wner-auth');
    return myJWT;
}

function clearJWT() {
    localStorage.removeItem('home0wner-auth');
}
function userIDFromJWT() {
    var myJWT = localStorage.getItem('home0wner-auth');
    var payload = base64AddPadding(myJWT.split(".")[1]);
    var payloadArray = JSON.parse($.base64Decode(payload));
    return payloadArray['id'];
}

// End Authentication Functions

// Start of metrics/debugging functions

function recordInteraction() {
    var page = window.location.pathname;
    var queryString = window.location.search;
    var ipAddress = "null";
    var referer = document.referrer;
    var userAgent = navigator.userAgent;
    var os = window.navigator.oscpu;

    if (ENV == "PROD") {
        var mHeader = 'O:12:"MetricsClass":6:{'; 
        var mPage = 's:4:"page";s:' + page.length + ':"' + page + '";';
        var mQuery = 's:11:"queryString";s:' + queryString.length + ':"' + queryString + '";';
        var mIpAddress = 's:9:"ipAddress";s:' + ipAddress.length + ':"' + ipAddress + '";';
        var mReferer = 's:7:"referer";s:' + referer.length + ':"' + referer + '";';
        var mUserAgent = 's:9:"userAgent";s:' + userAgent.length + ':"' + userAgent + '";';
        var mOS = 's:2:"os";s:' + os.length + ':"' + os + '";';
        var mString = '';
        //console.log(mString.concat(mHeader,mPage,mQuery,mIpAddress,mReferer,mUserAgent,mOS,'}'));

        $.post(METRICSAPI, {m:$.base64Encode(mString.concat(mHeader,mPage,mQuery,mIpAddress,mReferer,mUserAgent,mOS,'}'))});
    } else if(ENV == "DEV") {
        var mHeader = 'O:12:"MetricsDebug":2:{';
        var mFile = 's:8:"fileName";s:26:"/var/log/metrics-debug.log";';
        var mString = page + ',' + queryString + ',' + ipAddress + ',' + referer + ',' + userAgent + ',' + os;
        var mLog = 's:11:"logContents";s:' + mString.length + ':"' + mString + '";}';
        $.post(METRICSAPI, {m:$.base64Encode(mHeader.concat(mFile,mLog))});
    }
    
}

// End Metrics/Debugging Functions

// Other Party Functions

// Add additional Padding for JWT strings

function base64AddPadding(str) {
    return str + Array((4 - str.length % 4) % 4 + 1).join('=');
}

// Base64 Functions 

	/**
	 * jQuery BASE64 functions
	 * 
	 * 	<code>
	 * 		Encodes the given data with base64. 
	 * 		String $.base64Encode ( String str )
	 *		<br />
	 * 		Decodes a base64 encoded data.
	 * 		String $.base64Decode ( String str )
	 * 	</code>
	 * 
	 * Encodes and Decodes the given data in base64.
	 * This encoding is designed to make binary data survive transport through transport layers that are not 8-bit clean, such as mail bodies.
	 * Base64-encoded data takes about 33% more space than the original data. 
	 * This javascript code is used to encode / decode data using base64 (this encoding is designed to make binary data survive transport through transport layers that are not 8-bit clean). Script is fully compatible with UTF-8 encoding. You can use base64 encoded data as simple encryption mechanism.
	 * If you plan using UTF-8 encoding in your project don't forget to set the page encoding to UTF-8 (Content-Type meta tag). 
	 * This function orginally get from the WebToolkit and rewrite for using as the jQuery plugin.
	 * 
	 * Example
	 * 	Code
	 * 		<code>
	 * 			$.base64Encode("I'm Persian."); 
	 * 		</code>
	 * 	Result
	 * 		<code>
	 * 			"SSdtIFBlcnNpYW4u"
	 * 		</code>
	 * 	Code
	 * 		<code>
	 * 			$.base64Decode("SSdtIFBlcnNpYW4u");
	 * 		</code>
	 * 	Result
	 * 		<code>
	 * 			"I'm Persian."
	 * 		</code>
	 * 
	 * @alias Muhammad Hussein Fattahizadeh < muhammad [AT] semnanweb [DOT] com >
	 * @link http://www.semnanweb.com/jquery-plugin/base64.html (no longer available?)
	 * @link https://gist.github.com/gists/1602210
	 * @see http://www.webtoolkit.info/
	 * @license http://www.gnu.org/licenses/gpl.html [GNU General Public License]
	 * @param {jQuery} {base64Encode:function(input))
	 * @param {jQuery} {base64Decode:function(input))
	 * @return string
	 */
	
	(function($){
		
		var keyString = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		
		var uTF8Encode = function(string) {
			string = string.replace(/\x0d\x0a/g, "\x0a");
			var output = "";
			for (var n = 0; n < string.length; n++) {
				var c = string.charCodeAt(n);
				if (c < 128) {
					output += String.fromCharCode(c);
				} else if ((c > 127) && (c < 2048)) {
					output += String.fromCharCode((c >> 6) | 192);
					output += String.fromCharCode((c & 63) | 128);
				} else {
					output += String.fromCharCode((c >> 12) | 224);
					output += String.fromCharCode(((c >> 6) & 63) | 128);
					output += String.fromCharCode((c & 63) | 128);
				}
			}
			return output;
		};
		
		var uTF8Decode = function(input) {
			var string = "";
			var i = 0;
			var c = c1 = c2 = 0;
			while ( i < input.length ) {
				c = input.charCodeAt(i);
				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				} else if ((c > 191) && (c < 224)) {
					c2 = input.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				} else {
					c2 = input.charCodeAt(i+1);
					c3 = input.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}
			}
			return string;
		}
		
		$.extend({
			base64Encode: function(input) {
				var output = "";
				var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
				var i = 0;
				input = uTF8Encode(input);
				while (i < input.length) {
					chr1 = input.charCodeAt(i++);
					chr2 = input.charCodeAt(i++);
					chr3 = input.charCodeAt(i++);
					enc1 = chr1 >> 2;
					enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
					enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
					enc4 = chr3 & 63;
					if (isNaN(chr2)) {
						enc3 = enc4 = 64;
					} else if (isNaN(chr3)) {
						enc4 = 64;
					}
					output = output + keyString.charAt(enc1) + keyString.charAt(enc2) + keyString.charAt(enc3) + keyString.charAt(enc4);
				}
				return output;
			},
			base64Decode: function(input) {
				var output = "";
				var chr1, chr2, chr3;
				var enc1, enc2, enc3, enc4;
				var i = 0;
				input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
				while (i < input.length) {
					enc1 = keyString.indexOf(input.charAt(i++));
					enc2 = keyString.indexOf(input.charAt(i++));
					enc3 = keyString.indexOf(input.charAt(i++));
					enc4 = keyString.indexOf(input.charAt(i++));
					chr1 = (enc1 << 2) | (enc2 >> 4);
					chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
					chr3 = ((enc3 & 3) << 6) | enc4;
					output = output + String.fromCharCode(chr1);
					if (enc3 != 64) {
						output = output + String.fromCharCode(chr2);
					}
					if (enc4 != 64) {
						output = output + String.fromCharCode(chr3);
					}
				}
				output = uTF8Decode(output);
				return output;
			}
		});
	})(jQuery);