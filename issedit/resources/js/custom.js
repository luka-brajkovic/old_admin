$.expr[':'].icontains = function (obj, index, meta, stack) {
    return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
};

$(function () {

    $("ul.nav").superfish({
        animation: {
            height: "show",
            width: "show"
        }, speed: 100
    });

    //tooltip
    $(".tooltip").easyTooltip();

    // Check all the checkboxes when the head one is selected:
    $('.checkall').click(
            function () {
                $(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
            }
    );

    $(".close").click(
            function () {
                $(this).fadeTo(400, 0, function () { // Links with the class "close" will close parent
                    $(this).slideUp(400);
                });
                return false;
            }
    );


    //sortable, portlets
    $(".column").sortable({
        connectWith: '.column'
    });

    $(".sort").sortable({
        connectWith: '.sort'
    });


    $(".portlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
            .find(".portlet-header")
            .addClass("ui-widget-header ui-corner-all")
            .prepend('<span class="ui-icon ui-icon-circle-arrow-s"></span>')
            .end()
            .find(".portlet-content");

    $(".portlet-header .ui-icon").click(function () {
        $(this).toggleClass("ui-icon-minusthick");
        $(this).parents(".portlet:first").find(".portlet-content").toggle();
    });

    $(".column").disableSelection();



    // Accordion
    $("#accordion").accordion({header: "h3"});

    // Tabs
    $('#tabs').tabs();

    // Dialog			
    $('#dialog').dialog({
        autoOpen: false,
        width: 500,
        buttons: {
            "Ok": function () {
                $(this).dialog("close");
            },
            "Cancel": function () {
                $(this).dialog("close");
            }
        }
    });

    // Dialog Link
    $('#dialog_link').click(function () {
        $('#dialog').dialog('open');
        return false;
    });

    // Datepicker
    $('.datepicker').datepicker({
        inline: true,
        dateFormat: "yy-mm-dd"
    });

    //Colorpicker
    $('input.colorpickerHolder').ColorPicker({
        onSubmit: function (hsb, hex, rgb, el) {
            $(el).val(hex);
            $(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            $(this).ColorPickerSetColor(this.value);
        }
    })
            .bind('keyup', function () {
                $(this).ColorPickerSetColor(this.value);
            });

    // Slider
    $('#slider').slider({
        range: true,
        values: [20, 70]
    });

    // Progressbar
    $("#progressbar").progressbar({
        value: 40
    });

    //hover states on the static widgets
    $('#dialog_link, ul#icons li').hover(
            function () {
                $(this).addClass('ui-state-hover');
            },
            function () {
                $(this).removeClass('ui-state-hover');
            }
    );



    $('table.categories').sortable({
        axis: 'y',
        items: 'tr:not(.header)',
        handle: '.handle',
        placeholder: 'sortable-class',
        containment: 'parent',
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width())
            });

            return $helper;
        },
        forcePlaceholderSize: true,
        delay: 150,
        update: function (event, ui) {
            order = $(this).sortable('serialize');
            var parent_id = $(this).attr("id");
            var type = $(this).attr("type");
            $.ajax({
                type: "POST",
                url: "work.php",
                data: order + "&parent_id=" + parent_id + "&type=" + type + "&action=order",
                success: function (msg) {

                }
            });
        }
    });

    if ($('table.category_table').size() > 0) {
        $('table.category_table').sortable({
            axis: 'y',
            items: 'tr:not(.header)',
            handle: '.handle',
            placeholder: 'sortable-class',
            containment: 'parent',
            helper: function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width())
                });

                return $helper;
            },
            forcePlaceholderSize: true,
            delay: 150,
            update: function (event, ui) {
                order = $(this).sortable('serialize');
                $.ajax({
                    type: "POST",
                    url: "work.php",
                    data: order + "&action=order",
                    success: function (msg) {

                    }
                });
            }
        });
    }

    //adding sortable for conent type fields
    if ($('table.ct_fields_table').size() > 0) {
        $('table.ct_fields_table').sortable({
            axis: 'y',
            items: 'tr:not(.header)',
            handle: '.handle',
            placeholder: 'sortable-class',
            containment: 'parent',
            helper: function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width())
                });

                return $helper;
            },
            forcePlaceholderSize: true,
            delay: 150,
            update: function (event, ui) {
                order = $(this).sortable('serialize');
                $.ajax({
                    type: "POST",
                    url: "work.php",
                    data: order + "&action=order",
                    success: function (msg) {

                    }
                });
            }
        });
    }

    $('table.pictures_table').sortable({
        axis: 'y',
        items: 'tr:not(.header)',
        handle: '.handle',
        placeholder: 'sortable-class',
        containment: 'parent',
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width())
            });

            return $helper;
        },
        forcePlaceholderSize: true,
        delay: 150,
        update: function (event, ui) {
            order = $(this).sortable('serialize');
            var picture_id = $(this).attr("id");
            var type = $(this).attr("type");
            $.ajax({
                type: "POST",
                url: "work.php",
                data: order + "&picture_id=" + picture_id + "&type=" + type + "&action=order",
                success: function (msg) {

                }
            });
        }
    });


    if ($('table.c_table').size() > 0) {
        $('table.c_table').sortable({
            axis: 'y',
            items: 'tr:not(.header)',
            handle: '.handle',
            placeholder: 'sortable-class',
            containment: 'parent',
            helper: function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width())
                });

                return $helper;
            },
            forcePlaceholderSize: true,
            delay: 150,
            update: function (event, ui) {
                order = $(this).sortable('serialize');
                $.ajax({
                    type: "POST",
                    url: "work.php",
                    data: order + "&action=order",
                    success: function (msg) {

                    }
                });
            }
        });
    }

    $('table.menu_items').sortable({
        axis: 'y',
        items: 'tr:not(.header)',
        handle: '.handle',
        placeholder: 'sortable-class',
        containment: 'parent',
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width())
            });

            return $helper;
        },
        forcePlaceholderSize: true,
        delay: 150,
        update: function (event, ui) {
            order = $(this).sortable('serialize');
            var parent_id = $(this).attr("parent");

            $.ajax({
                type: "POST",
                url: "work.php",
                data: order + "&parent_id=" + parent_id + "&action=order_items",
                success: function (msg) {

                }
            });
        }
    });

    $("#search_input").live("keyup", function () {
        var val = jQuery.trim($(this).val());
        if (val == "") {
            $("table.fullwidth tbody tr").show();
        } else {
            $("table.fullwidth tbody tr").hide();
            $("table.fullwidth tbody td:icontains(" + val + ")").parent().show();
        }
    });

    $(".save_edit").live("click", function () {
        $('#edit').val("1");
        $("#add_category").submit();
    });

    $('.save_edit_content').live("click", function () {
        $('#edit').val("1");
        $("#add_content").submit();
    });

    //TinyMce selecots
    $('textarea.tinymceControl:visible, textarea.tinymceControl:hidden').each(function () {
        var tinyId = $(this).attr("id");
        tinyMCE.execCommand('mceAddControl', false, tinyId);
    });

    $("#add_new_const").live("click", function () {
        $(".tab").find("fieldset").each(function () {
            var lang = $(this).closest(".tab").attr("lang");
            var nump = $(".rowset:visible").size() + 1;
            var html = '<p class="rowset" konstanta="' + nump + '" style="clear: both; margin-bottom: 10px; width: 100%; height: 28px;">' +
                    '<input type="text" style="float: left;" class="mf constant" name="' + lang + '[constant][]" />' +
                    '<input type="text" style="float: left;" class="mf value" name="' + lang + '[value][]" />' +
                    '<a href="javascript:void(0);" class="remove_constant" style="display: block; width:16px;  margin-left:5px; margin-top: 5px; float: left;">' +
                    '<span class="ui-icon ui-icon-closethick"></span>' +
                    '</a></p>';
            $(this).append(html);
        });
    });

    $(".remove_constant").live("click", function () {
        var constnt = $(this).closest(".constant").val();
        var value = $(this).closest(".value").val();
        var cclass = $(this).closest("p").attr("konstanta");
        if (constnt != "" || value != "") {
            var status = confirm("Are you sure you want to remove this constant?");
            if (status) {
                $('p[konstanta="' + cclass + '"]').remove();
            }
        } else {
            $('p[konstanta="' + cclass + '"]').remove();
        }
    });

});
