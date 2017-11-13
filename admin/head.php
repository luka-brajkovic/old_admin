<link type="text/css" href="/admin/resources/css/layout.css" rel="stylesheet" />
<link type="text/css" href="/admin/resources/css/colorpicker.css" rel="stylesheet" />
<link rel="stylesheet" href="/admin/resources/js/highslide/highslide.css" type="text/css"media="screen" />
<link rel="stylesheet" href="/css/fancybox.css" type="text/css"media="screen" />	

<link rel="apple-touch-icon" sizes="180x180" href="/admin/resources/images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" href="/admin/resources/images/favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/admin/resources/images/favicon/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="/admin/resources/images/favicon/manifest.json">
<link rel="mask-icon" href="/admin/resources/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
<link rel="shortcut icon" href="/admin/resources/images/favicon/favico.ico">
<meta name="msapplication-config" content="/admin/resources/images/favicon/browserconfig.xml">
<meta name="theme-color" content="#f36e34">

<script type="text/javascript" src="/admin/resources/js/jquery.min.js"></script>
<script type="text/javascript" src="/admin/resources/js/ajaxi.js"></script>
<script type="text/javascript" src="/admin/resources/js/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript" src="/admin/resources/js/easyTooltip.js"></script>
<script type="text/javascript" src="/admin/resources/js/hoverIntent.js"></script>
<script type="text/javascript" src="/admin/resources/js/superfish.js"></script>
<script type="text/javascript" src="/admin/resources/js/colorpicker.js"></script>
<script type="text/javascript" src="/admin/resources/js/eye.js"></script>
<script type="text/javascript" src="/admin/resources/js/utils.js"></script>
<script type="text/javascript" src="/js/fancybox.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $(document).ready(function () {
            $(".fancybox").fancybox();
        });

    });
</script>

<script type="text/javascript" src="/admin/resources/js/highslide/highslide-with-html.js"></script>
<script type="text/javascript">
    hs.graphicsDir = '/admin/resources/js/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';

    hs.registerOverlay({
        html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
        position: 'top right',
        fade: 2 // fading the semi-transparent overlay looks bad in IE
    });

    hs.wrapperClassName = 'borderless';
</script>
<script type="text/javascript" src="/admin/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode: "none",
        theme: "advanced",
        plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,imagemanager,filemanager",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,
        skin: "o2k7",
        skin_variant: "silver",
        autosave_ask_before_unload: false // Disable for example purposes
    });
</script>
<script type="text/javascript" src="/admin/resources/js/custom.js"></script>