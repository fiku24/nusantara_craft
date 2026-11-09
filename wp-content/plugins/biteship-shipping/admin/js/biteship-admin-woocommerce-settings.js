jQuery(document).ready(function ($) {
    const storeCoordinateField = $("#woocommerce_store_coordinate");
    if (!storeCoordinateField) {
        return;
    }

    function coordinateString({ latitude, longitude }) {
        if (!latitude || !longitude) return "";

        return `${latitude},${longitude}`;
    }

    function getCoordinate() {
        return storeCoordinateField.val().split(",");
    }

    function updateCoordinate(coordinate) {
        const coordinateValue = coordinateString(coordinate);
        storeCoordinateField.val(coordinateValue).change();
    }

    function previewCoordinate(coordinate) {
        $("#woocommerce-store-coordinate-preview").val(coordinateString(coordinate));
    }

    function onCurrentLocationClick(e) {
        e.preventDefault();

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    updateCoordinate({ latitude, longitude });
                },
                function (error) {
                    alert(error.message);
                },
            );
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    }

    function onPinLocationClick(e) {
        e.preventDefault();

        let [latitude, longitude] = getCoordinate();
        const defaultCoordinate = {
            lat: parseFloat(latitude) || -6.1754083,
            lng: parseFloat(longitude) || 106.8204524,
        };

        $(document).WCBackboneModal({
            template: "woocommerce-store-coordinate-picker",
        });

        previewCoordinate({ latitude, longitude });

        const map = new google.maps.Map(document.getElementById("woocommerce-store-coordinate-map"), {
            center: defaultCoordinate,
            zoom: 13,
            streetViewControl: false,
            mapTypeControl: false,
        });

        const marker = new google.maps.Marker({
            position: defaultCoordinate,
            map: map,
        });

        const locationInput = document.getElementById("woocommerce-store-coordinate-autocomplete");
        const autocomplete = new google.maps.places.Autocomplete(locationInput);

        map.addListener("click", (event) => {
            marker.setPosition(event.latLng);
            latitude = event.latLng.lat();
            longitude = event.latLng.lng();

            previewCoordinate({ latitude, longitude });
        });

        map.addListener("click", (event) => {
            event.stopPropagation();
        });

        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();

            if (!place.geometry) {
                return;
            }

            map.setCenter(place.geometry.location);
            marker.setPosition(place.geometry.location);
            marker.setMap(map);

            latitude = place.geometry.location.lat();
            longitude = place.geometry.location.lng();

            previewCoordinate({ latitude, longitude });
        });

        $("#woocommerce-store-coordinate-saver").click(function () {
            updateCoordinate({ latitude, longitude });
        });
    }

    const breaker = $("<br>").insertAfter(storeCoordinateField);

    const currentLocationButton = $("<button>", {
        class: "button",
        text: "Current Location",
        click: onCurrentLocationClick,
    })
        .css({ "margin-top": "3px" })
        .insertAfter(breaker);

    $("<button>", {
        class: "button",
        text: "Pin Location",
        click: onPinLocationClick,
    })
        .css({ "margin-top": "3px", "margin-left": "4px" })
        .insertAfter(currentLocationButton);
});
