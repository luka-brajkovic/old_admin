<script type="text/javascript">

    jQuery.extend({
        getQueryParameters: function (str) {
            return (str || document.location.href).replace(/(^\?)/, '').split("&").map(function (n) {
                return n = n.split("="), this[n[0]] = n[1], this
            }.bind({}))[0];
        }

    });

    serialize = function (obj, prefix) {
        var str = [];
        for (var p in obj) {
            if (obj.hasOwnProperty(p)) {
                var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                str.push(typeof v == "object" ?
                        serialize(v, k) :
                        k + "=" + v);
            }
        }
        return str.join("&");
    }

    function buildQueryString(name, value, name1, value1) {

        if (name === "cat") {
            var num_of_checked = $("#category_filter ul li span.active").length;
            if (num_of_checked == 0) {
                var url = document.location.href;
                var urlArray = url.split("/");
                urlArray.pop();
                var customUrl = urlArray.join();
                var newCustomUrl = customUrl.replace(",", "/");

                window.location.href = newCustomUrl;
            }
        }
        var url = document.location.href;

        url = url.split("&");
        var queryParams = $.getQueryParameters();

        delete queryParams[url[0]];
        delete queryParams[name];
        delete queryParams[name1];
        if (name != "" && value != "") {
            queryParams[name] = value;
        }

        if (name1 != "" && value1 != "") {
            queryParams[name1] = value1;
        }
        if (name != "" && value == "") {
            delete queryParams[name];
        }
        if (name1 != "" && value1 == "") {
            delete queryParams[name];
        }
        var duplicate = [];
        for (var key in queryParams) {

            if (jQuery.inArray(key, duplicate) > 0) {
                delete queryParams[key];
            } else {
                duplicate.push(key);
            }
            if (name == "cat") {

                if (key.match(/cat/g)) {

                    delete queryParams[key];
                }
            }
            if (name == "brand" && value == "") {
                if (key.match(/brand/g)) {
                    delete queryParams[key];
                }
            }
        }
        if (queryParams == {}) {
            var newUrl = url[0];
        } else {

            var test = serialize(queryParams);

            var testArr = test.split("&");

            var testLen = testArr.length;
            console.log(testLen);
            var nastavakUrl = "";

            for (var i = testLen - 1; i < testLen && i >= 0; i--) {
                if (test[i] != '') {
                    nastavakUrl += "&" + testArr[i];
                    console.log(nastavakUrl);
                }
            }

            console.log(nastavakUrl);
            var newUrl = url[0] + nastavakUrl;
        }


        return newUrl;
    }

    $(document).ready(function () {
        $('#po_strani').change(function () {
            var val = $(this).val();
            var newUrl = buildQueryString('po_strani', val, 'page', '1');
            window.location.href = newUrl;
        });
        $('#sortiranje').change(function () {
            var val = $(this).val();
            var newUrl = buildQueryString('sortiranje', val, '', '');
            window.location.href = newUrl;
        });

        $('.pagination').click(function () {
            var page = $(this).data('page');
            var newUrl = buildQueryString('page', page, '', '');
            window.location.href = newUrl;
        });

        $('#priceChange').click(function () {
            var valMin = $('#minPrice').val();
            var valMax = $('#maxPrice').val();
            if(valMin > valMax){
                valMax = 1;
                console.log(valMin + ' !!1 ' + valMax);
                var newUrl = buildQueryString('min_cena', valMin, 'max_cena', valMax);
                window.location.href = newUrl;
            } else if (valMin > 0 && valMax < 1) {
                valMax = 1;
                console.log(valMin + ' !!2 ' + valMax);
                var newUrl = buildQueryString('min_cena', valMin, 'max_cena', valMax);
                window.location.href = newUrl;
            } else if (valMin < 1 && valMax > 0) {
                valMin = 1;
                console.log(valMin + ' !!3 ' + valMax);
                var newUrl = buildQueryString('max_cena', valMax, 'max_cena', valMax);
                window.location.href = newUrl;
            } else if (valMin > 0 && valMax > 0) {
                console.log(valMin + ' !!4 ' + valMax);
                var newUrl = buildQueryString('min_cena', valMin, 'max_cena', valMax);
                window.location.href = newUrl;
            }
        });

        var widthRightSide = $(".filters").width();

        $('.checkClick.brand').click(function () {
            var string = '';

            $('.checkClick.brand.active').each(function (i) {
                string = string + '&brand[' + i + ']=' + $(this).parent().find('input[type="checkbox"]').val();
            });
            if (string == "") {
                var newUrl = buildQueryString('brand', '', 'page', '1');
            } else {
                var newUrl = buildQueryString('brand', '', 'page', '1');
                if (newUrl.indexOf('&filters=true') > 0) {
                    console.log(newUrl);
                    newUrl = newUrl.replace('&filters=true', string + '&filters=true');
                } else {
                    newUrl = newUrl + string;
                }
            }
            window.location.href = newUrl;

        });

        $('.checkClick.cat').click(function () {
            var string = '';

            $('.checkClick.cat.active').each(function (i) {
                string = string + '&cat[' + i + ']=' + $(this).parent().find('input[type="checkbox"]').val();
            });
            console.log(string);
            if (string == "") {
                var newUrl = buildQueryString('cat', '', 'page', '1');
            } else {

                var newUrl = buildQueryString('cat', '', 'page', '1');
                newUrl = newUrl + string;
            }

            window.location.href = newUrl;

        });

        $('.checkClick.filter').click(function () {
            var string = '&filters=true';
            var holder = $(this).parent().parent();
            var header_id = "";
            var server_url = window.location.href;
            var server_without_filters = server_url.split("&filters=true");
            server_without_filters = server_without_filters[0];
            if ($(".filter_group ul.check").find(".checkClick.filter.active").length > 0) {
                server_without_filters += "&filters=true";
                $(".filter_group ul.check").each(function () {

                    if ($(this).find(".checkClick.filter.active").length > 0) {
                        var header_id = $(this).data("id");
                        server_without_filters += "&" + header_id + "=";
                        var values = "";
                        $(this).find(".checkClick.filter.active").each(function () {
                            var value_id = $(this).parent().find("input[type='checkbox']").val();
                            values += "-" + value_id + "-+";
                        });
                        values = values.substring(0, values.length - 1);
                        server_without_filters += values;
                    }

                });

            }
            if (server_without_filters.indexOf("&page=") > 0) {

                var lookingForPage = server_without_filters.split("&page=");

                var lookingForPageRest = lookingForPage[1].split("&");

                if (lookingForPageRest[0] != '') {
                    server_without_filters = server_without_filters.replace("&page=" + lookingForPageRest[0], "&page=1");
                }
            }
            console.log(server_without_filters);

            window.location.href = server_without_filters;

        });

    });
    function showMoreFilters(a) {
        $("#doNotShowFilter-" + a).toggle("fast", "swing"), $(".filterArrowUp-" + a).toggle("fast", "swing"), $(".filterArrowDown-" + a).toggle("fast", "swing"), $("#open-" + a).html("Prikaži manje"), $(".active-" + a).html("Prikaži sve"), $("#open-" + a).toggleClass("active-" + a)
    }
</script>