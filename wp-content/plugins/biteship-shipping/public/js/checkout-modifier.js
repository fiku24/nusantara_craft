jQuery(document).ready(async function($) {
    const useMapForAddress = phpVars?.useMapForAddress === "yes";
    const useAreaDropdown = phpVars?.useAreaDropdown === "yes";

    const groups = ["billing", "shipping"];
    /**
     * Used for filtering postcodes by district
     */
    let biteshipAreas = new Map(groups.map(group => [group, []]));

    function fetchCities(wooStateId, group) {
        fetch(ajax_object.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_adm_lvl_2',
                woo_state_id: wooStateId, 
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    populateCitiesDropdown(data.data.areas, group);
                } else {
                    console.error('Failed to fetch cities:', data.data.message);
                }
            })
            .catch((error) => console.error('Error:', error));
    }

    function populateCitiesDropdown(areas, group) {
        const dropdown = document.querySelector(`#${group}_city`);
        dropdown.innerHTML = '';
        dropdown.add(new Option('Select a city', ''));
        areas.forEach((area) => {
            dropdown.add(new Option(area.administrative_division_level_2_name, area.administrative_division_level_2_id));
        });
    }

    function fetchDistricts(admlLvl2Id, group) {
        fetch(ajax_object.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_adm_lvl_3',
                administrative_division_level_2_id: admlLvl2Id,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // the provided areas already include the postcodes for each district
                    const areas = data.data.areas;
                    biteshipAreas.set(group, areas);
                    populateDistrictsDropdown(data.data.areas, group);
                } else {
                    console.error('Failed to fetch districts:', data.data.message);
                }
            })
            .catch((error) => console.error('Error:', error));
    }

    function populateDistrictsDropdown(areas, group) {
        const dropdown = document.querySelector(`#${group}_district`);
        dropdown.innerHTML = '';

        const districtMap = areas.reduce((acc, area) => {
            acc[area.administrative_division_level_3_id] = area.administrative_division_level_3_name;
            return acc;
        }, {});
        const districts = Object.entries(districtMap).map(([id, name]) => ({ id, name }));

        dropdown.add(new Option('Select a district', ''));
        districts.forEach((district) => {
            dropdown.add(new Option(district.name, district.id));
        });

        populatePostcodeDropdown(areas, group);
    }

    function populatePostcodeDropdown(areas, group) {
        const dropdown = document.querySelector(`#${group}_postcode`);
        dropdown.innerHTML = '';

        // create unique postcodes
        const postcodeMap = areas.reduce((acc, area) => {
            acc[area.postal_code] = area.postal_code;
            return acc;
        }, {});
        const postcodes = Object.entries(postcodeMap).map(([id, name]) => ({ id, name }));

        dropdown.add(new Option('Select a postcode', ''));
        postcodes.forEach((postcode) => {
            dropdown.add(new Option(postcode.name, postcode.id));
        });
    }

    function filterPostcodeDropdown(admlLvl3Id, group) {
        const dropdown = document.querySelector(`#${group}_postcode`);
        dropdown.innerHTML = '';

        const areas = biteshipAreas.get(group).filter((area) => area.administrative_division_level_3_id === admlLvl3Id);

        // create unique postcodes
        const postcodeMap = areas.reduce((acc, area) => {
            acc[area.postal_code] = area.postal_code;
            return acc;
        }, {});
        const postcodes = Object.entries(postcodeMap).map(([id, name]) => ({ id, name }));

        dropdown.add(new Option('Select a postcode', ''));
        postcodes.forEach((postcode) => {
            dropdown.add(new Option(postcode.name, postcode.id));
        });
    }

    function coordinateString(location) {
        return `${location.lat()},${location.lng()}`;
    }

    function getAddressFromPlace(place) {
        const address1Object = place.address_components.reduce(
            (address, component) => {
                if (component.types.includes("route")) {
                    address.route = component.short_name;
                }

                if (component.types.includes("street_number")) {
                    address.streetNumber = component.short_name;
                }

                return address;
            },
            { route: "", streetNumber: "" },
        );

        const address2Object = place.address_components.reduce(
            (address, component) => {
                if (component.types.includes("administrative_area_level_3")) {
                    address.district = component.short_name;
                }

                if (component.types.includes("administrative_area_level_4")) {
                    address.subdistrict = component.short_name;
                }

                return address;
            },
            { district: "", subdistrict: "" },
        );

        return {
            address1: `${address1Object.route} ${address1Object.streetNumber}`.trim(),
            address2: `${address2Object.subdistrict}, ${address2Object.district}`.trim(),
            postalCode: place.address_components.find((component) => component.types.includes("postal_code"))?.short_name,
            city: place.address_components.find((component) => component.types.includes("administrative_area_level_2"))?.short_name,
            province: place.address_components.find((component) => component.types.includes("administrative_area_level_1"))?.short_name,
        };
    }

    function updateOtherCoordinate(group, coordinateValue) {
        const otherGroup = groups.find((i) => i !== group);
        if (!otherGroup) return;

        const otherCoordinateInput = $(`#${otherGroup}_coordinate`);
        if (!otherCoordinateInput.length) return;

        otherCoordinateInput.val(coordinateValue).trigger("update_checkout", {
            update_shipping_method: true,
        });
    }

    function updateCoordinate(group, coordinateInput, location) {
        const coordinateValue = coordinateString(location);
        coordinateInput.val(coordinateValue);

        if (!isBillingOnly() && !isShipToDifferentAddress()) {
            return updateOtherCoordinate(group, coordinateValue);
        }

        coordinateInput.trigger("update_checkout", { update_shipping_method: true });
    }

    function updateOtherAddress(group, place) {
        const otherGroup = groups.find((i) => i !== group);
        if (!otherGroup) return;

        const address1Input = $(`#${otherGroup}_address_1`);
        const address2Input = $(`#${otherGroup}_address_2`);
        const postalCodeInput = $(`#${otherGroup}_postcode`);
        const cityInput = $(`#${otherGroup}_city`);
        // const provinceInput = $(`#${otherGroup}_state`);

        const { address1, address2, postalCode, city } = getAddressFromPlace(place);

        if (address1Input.length && address1) {
            address1Input.val(address1);
        }

        if (address2Input.length && address2) {
            address2Input.val(address2);
        }

        // Update only if input is not dropdown
        // NOTE: useAreaDropdown validation can be removed if we found the solution
        if (postalCodeInput.length && postalCode && !useAreaDropdown) {
            postalCodeInput.val(postalCode);
        }

        if (cityInput.length && city && !useAreaDropdown) {
            cityInput.val(city);
        }

        // Commented so it doesn't auto update a dropdown, because if it doesn't match, it can return empty string in billing information
        // if (provinceInput.length && province) {
        //     provinceInput.val(province);
        // }
    }

    function updateAddress(group, place) {
        const address1Input = $(`#${group}_address_1`);
        const address2Input = $(`#${group}_address_2`);
        const postalCodeInput = $(`#${group}_postcode`);
        const cityInput = $(`#${group}_city`);
        // const provinceInput = $(`#${group}_state`);

        const { address1, address2, postalCode, city } = getAddressFromPlace(place);

        if (address1Input.length && address1) {
            address1Input.val(address1);
        }

        if (address2Input.length && address2) {
            address2Input.val(address2);
        }

        // Update only if input is not dropdown
        // NOTE: useAreaDropdown can be removed if we found the solution
        if (postalCodeInput.length && postalCode && !useAreaDropdown) {
            postalCodeInput.val(postalCode);
        }

        if (cityInput.length && city && !useAreaDropdown) {
            cityInput.val(city);
        }

        // Commented so it doesn't auto update a dropdown, because if it doesn't match, it can return empty string in billing information
        // if (provinceInput.length && province) {
        //     provinceInput.val(province);
        // }

        if (!isBillingOnly() && !isShipToDifferentAddress()) {
            return updateOtherAddress(group, place);
        }
    }

    function isBillingOnly() {
        return !document.getElementById("ship-to-different-address-checkbox");
    }

    function isShipToDifferentAddress() {
        const shipToDifferentAddressSelector = "#ship-to-different-address-checkbox";
        return $(shipToDifferentAddressSelector).is(":checked");
    }

    // SHIP-1818: this trigger looks redundant — a payment method cannot change a shipping rate —
    // but it is load-bearing for the COD fee. add_extra_fee() (class-biteship-public.php:391)
    // calls is_cod(), which reads the chosen payment method from the session, so the cart total
    // only picks up or drops the COD fee when this recalculation runs. Removing it silently
    // breaks COD pricing at checkout. It also costs no extra rates request for a real shopper:
    // the package hash is unchanged, so WooCommerce serves the rates from its session cache.
    $("form.woocommerce-checkout").on("change", 'input[name="payment_method"]', function() {
        $(document.body).trigger("update_checkout", {
            update_shipping_method: true,
        });
    });

    async function addCoordinateListener(group) {
        const mapId = `${group}_map`;
        const searchBoxId = `${group}_map_searchbox`;
        const groupSelector = `.woocommerce-${group}-fields__field-wrapper`;
        const coordinateFieldSelector = `#${group}_coordinate_field`;
        const coordinateInputSelector = `#${group}_coordinate`;
        const coordinateField = $(coordinateFieldSelector);
        const coordinateInput = $(coordinateInputSelector);

        if (!coordinateField.length || !coordinateInput.length) return;

        coordinateInput.on("change", function() {
            $("body").trigger("update_checkout");
        });
        coordinateField.hide();

        if (!$(groupSelector).length) {
            return;
        }

        const mapWrapper = $("<p>", { class: "woocommerce-map-wrapper form-row form-row-wide" });
        const mapLabel = $("<label>", { text: checkoutI18n.LOCATION_LABEL });
        const mapContainer = $("<div>", { class: "woocommerce-map-container", id: mapId });
        const [lat, lng] = coordinateInput.val()?.split(",") || [];
        const savedLat = parseFloat(lat);
        const savedLng = parseFloat(lng);
        const hasSavedCoordinate = !isNaN(savedLat) && !isNaN(savedLng);
        const defaultCoordinate = { lat: savedLat || -6.175392, lng: savedLng || 106.8271528 };

        // SHIP-1818: build the map on demand instead of on page load. Google bills a map load per
        // google.maps.Map instance, and this function runs for BOTH the billing and shipping groups,
        // so every checkout page view cost two map loads whether or not the shopper touched a map.
        // The store-coordinate picker in wp-admin already uses this click-to-open pattern.
        const showMapButton = $("<button>", {
            type: "button",
            class: "button woocommerce-map-reveal",
            text: (typeof checkoutI18n !== "undefined" && checkoutI18n.SHOW_MAP_LABEL) || "Set location on map",
        });

        mapContainer.hide();
        mapWrapper.append(mapLabel);
        mapWrapper.append(showMapButton);
        mapWrapper.append(mapContainer);
        $(groupSelector).after(mapWrapper);

        let mapBuilt = false;

        function revealMap() {
            if (mapBuilt) return;
            mapBuilt = true;
            showMapButton.remove();
            mapContainer.show();
            buildMap();
        }

        showMapButton.on("click", revealMap);

        // A shopper who already has a coordinate should see it rather than a button hiding it.
        // On a fresh checkout the field is empty, which is the common case and the one that saves
        // the map load.
        if (hasSavedCoordinate) {
            revealMap();
        }

        function buildMap() {
            const map = new google.maps.Map(document.getElementById(mapId), {
                center: defaultCoordinate,
                zoom: 13,
                streetViewControl: false,
                mapTypeControl: false,
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: google.maps.ControlPosition.BOTTOM_LEFT,
                },
            });

            const marker = new google.maps.Marker({
                position: defaultCoordinate,
                map: map,
            });

            const searchBoxWrapper = $("<span>", { class: "woocommerce-searchbox-wrapper gm-control-active gm-fullscreen-control" });
            const searchBoxInput = $("<input>", {
                id: searchBoxId,
                placeholder: checkoutI18n.SEARCHBOX_PLACEHOLDER,
                autocomplete: "off",
            });
            const predictionList = $("<ul>", { class: "biteship-autocomplete-predictions" });

            searchBoxWrapper.append(searchBoxInput);
            searchBoxWrapper.append(predictionList);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(searchBoxWrapper.get(0));

            map.addListener("click", (event) => {
                marker.setPosition(event.latLng);
                updateCoordinate(group, coordinateInput, event.latLng);
            });

            map.addListener("click", (event) => {
                event.stop();
            });

            attachAutocomplete({ map, marker, coordinateInput, searchBoxInput, predictionList, group });
        }
    }

    // SHIP-1818: replaces google.maps.places.SearchBox.
    //
    // SearchBox fired a billed autocomplete request on every keystroke and exposed no debounce and
    // no session token, so "Jakarta Selatan" cost ~15 separate lookups. AutocompleteService lets us
    // debounce, and lets us pass an AutocompleteSessionToken through the predictions AND the
    // follow-up getDetails call, which is how Google groups a lookup into one billable session
    // instead of many. The token is discarded once details are fetched -- reusing it across
    // lookups would break that grouping.
    //
    // The trade is that SearchBox rendered its own dropdown and AutocompleteService does not, so
    // the prediction list below is ours (styled in public/css/biteship-public.css).
    function attachAutocomplete({ map, marker, coordinateInput, searchBoxInput, predictionList, group }) {
        const DEBOUNCE_MS = 350;
        const MIN_QUERY_LENGTH = 3;
        const autocompleteService = new google.maps.places.AutocompleteService();
        const placesService = new google.maps.places.PlacesService(map);

        let sessionToken = null;
        let debounceTimer = null;
        let lastQuery = "";

        function currentSessionToken() {
            if (!sessionToken) {
                sessionToken = new google.maps.places.AutocompleteSessionToken();
            }
            return sessionToken;
        }

        function hidePredictions() {
            predictionList.empty().hide();
        }

        function renderPredictions(predictions) {
            predictionList.empty();

            if (!predictions || !predictions.length) {
                hidePredictions();
                return;
            }

            predictions.forEach((prediction) => {
                $("<li>", { text: prediction.description, tabindex: 0 })
                    .on("click", () => selectPrediction(prediction))
                    .on("keydown", (event) => {
                        if (event.keyCode === 13) {
                            event.preventDefault();
                            selectPrediction(prediction);
                        }
                    })
                    .appendTo(predictionList);
            });

            predictionList.show();
        }

        function selectPrediction(prediction) {
            hidePredictions();
            searchBoxInput.val(prediction.description);

            placesService.getDetails(
                {
                    placeId: prediction.place_id,
                    sessionToken: currentSessionToken(),
                    // getAddressFromPlace() reads address_components; the rest position the map.
                    fields: ["address_components", "geometry", "name", "formatted_address"],
                },
                (place, status) => {
                    // The session ends with the details request either way, so drop the token.
                    sessionToken = null;

                    if (status !== google.maps.places.PlacesServiceStatus.OK || !place || !place.geometry) {
                        return;
                    }

                    marker.setPosition(place.geometry.location);

                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.panTo(place.geometry.location);
                        map.setZoom(17);
                    }

                    if (useMapForAddress && place.address_components) {
                        updateAddress(group, place);
                    }

                    updateCoordinate(group, coordinateInput, place.geometry.location);
                }
            );
        }

        searchBoxInput.on("keydown", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
            if (event.keyCode === 27) {
                hidePredictions();
            }
        });

        searchBoxInput.on("input", function() {
            const query = String(searchBoxInput.val() || "").trim();

            clearTimeout(debounceTimer);

            if (query.length < MIN_QUERY_LENGTH) {
                lastQuery = "";
                hidePredictions();
                return;
            }

            if (query === lastQuery) return;

            debounceTimer = setTimeout(function() {
                lastQuery = query;

                const request = {
                    input: query,
                    sessionToken: currentSessionToken(),
                };

                // Bias results to what the shopper is looking at, as setBounds() did before.
                const bounds = map.getBounds();
                if (bounds) {
                    request.bounds = bounds;
                }

                autocompleteService.getPlacePredictions(request, (predictions, status) => {
                    if (status !== google.maps.places.PlacesServiceStatus.OK) {
                        hidePredictions();
                        return;
                    }
                    renderPredictions(predictions);
                });
            }, DEBOUNCE_MS);
        });

        // Delayed so a click on a prediction registers before the list is torn down.
        searchBoxInput.on("blur", function() {
            setTimeout(hidePredictions, 150);
        });

        hidePredictions();
    }

    function addLocationDropdownListeners(group) {
        const stateDropdownSelector = `#${group}_state`;
        const cityDropdownSelector = `#${group}_city`;
        const districtDropdownSelector = `#${group}_district`;
        const postcodeDropdownSelector = `#${group}_postcode`;
        const stateDropdown = $(stateDropdownSelector);
        const cityDropdown = $(cityDropdownSelector);
        const districtDropdown = $(districtDropdownSelector);
        const postcodeDropdown = $(postcodeDropdownSelector);

        populateCitiesDropdown([], group); 
        populateDistrictsDropdown([], group); 
        populatePostcodeDropdown([], group);
        
        stateDropdown.on("change", function() {
            biteshipAreas.set(group, []);
            const wooStateId = stateDropdown.val();
            if (!wooStateId) { // If state is not selected
                cityDropdown.val('').trigger('change');
                populateCitiesDropdown([], group); 
            } else {
                fetchCities(wooStateId, group);
                populateDistrictsDropdown([], group);
            }
        });

        cityDropdown.on("change", function() {
            biteshipAreas.set(group, []);
            const cityId = cityDropdown.val();
            if (!cityId) {
                districtDropdown.val('').trigger('change');
                populateDistrictsDropdown([], group); 
            } else {
                const cityName = cityDropdown.find('option:selected').text();
                $(`#${group}_city_name`).val(cityName);
                fetchDistricts(cityId, group);
            }
        });

        districtDropdown.on("change", function() {
            const districtId = districtDropdown.val();
            if (districtId) {
                postcodeDropdown.val('').trigger('change');
                filterPostcodeDropdown(districtId, group);
            }
        });

        postcodeDropdown.on("change", function() {
            const postcode = postcodeDropdown.val();
            if (postcode) {
                $("body").trigger("update_checkout"); // refresh checkout details to refetch shipping carriers
            }
        });
    }

    groups.forEach((group) => {
        const coordinateFieldSelector = `#${group}_coordinate_field`;
        const coordinateInputSelector = `#${group}_coordinate`;
        const coordinateField = $(coordinateFieldSelector);
        const coordinateInput = $(coordinateInputSelector);
        
        if (coordinateField.length && coordinateInput.length) addCoordinateListener(group);
        if (useAreaDropdown) addLocationDropdownListeners(group);
    });
});
