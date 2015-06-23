jQuery(document).ready(function($) {

  $('#post').prepend('<h2 class="nav-tab-wrapper" id="chch-pusf-tabs"><a class="nav-tab nav-tab-active" href="#" title="Templates" data-target="chch-pusf-tab-1">Templates</a><a class="nav-tab" href="#" title="Settings" data-target="chch-pusf-tab-2">Settings</a></h2>');

  $('#wpbody-content > .wrap').prepend('<a class="button button-secondary right button-hero" style="margin: 25px 0px 0px 2px; padding: 0px 20px; height: 47px;" href="https://shop.chop-chop.org/contact" target="_blank">Contact Support</a><a class="button button-primary right button-hero" href="http://ch-ch.org/pupro" style="margin: 25px 20px 0 2px;">Get Pro</a>');

  $('#chch-pusf-tabs a').on('click', function(e) {
    e.preventDefault();
    var target = $(this).attr('data-target');

    if (!$(this).hasClass('nav-tab-active')) {
      $('.chch-pusf-tab').hide();
      $('#chch-pusf-tabs a').removeClass('nav-tab-active');
      $(this).addClass('nav-tab-active');

      $('.' + target).show();
    }
  });

  $('.chch-pusf-template-acivate').on('click', function(e) {
    e.preventDefault();
    var template = $(this).attr('data-template');
    var base = $(this).attr('data-base');

    $('#poststuff .theme-browser .theme.active').removeClass('active');
    var theme = $(this).closest('.theme');
    theme.addClass('active');


    $('#_chch_pusf_template').val(template);
    $('#_chch_pusf_template_base').val(base);
    $('#publish').trigger('click');
  });

  $('.chch-pusf-customize-close').on('click', function(e) {
    e.preventDefault();
    var template = $(this).attr('data-template');

    $('#chch-pusf-customize-form-' + template).hide();
  });

  $('.chch-pusf-template-edit').on('click', function(e) {
    e.preventDefault();
    var thisEl = $(this);
    template = thisEl.attr('data-template');
    base = thisEl.attr('data-base');
    id = thisEl.attr('data-postid');
    nounce = thisEl.attr('data-nounce');

    $.ajax({
      url: chch_pusf_ajax_object.ajaxUrl,
      async: true,
      type: "POST",
      data: {
        action: "chch_pusf_load_preview_module",
        template: template,
        base: base,
        nounce: nounce,
        id: id

      },
      success: function(data) {

        if (!$('#' + base + '-css').length) {
          $('head').append('<link rel="stylesheet" id="' + base + '-css"  href="' + chch_pusf_ajax_object.chch_pusf_url + 'public/templates/' + base + '/css/base.css" type="text/css" media="all" />');
        }

        if (!$('#' + template + '-css').length) {
          $('head').append('<link rel="stylesheet" id="' + template + '-css"  href="' + chch_pusf_ajax_object.chch_pusf_url + 'public/templates/' + base + '/' + template + '/css/style.css" type="text/css" media="all" />');
        }

        theme = thisEl.closest('.theme');
        previewWrapper = $('#chch-pusf-customize-form-' + template);
        $('#cc-pu-customize-preview-' + template).html(data);

        $('.theme').removeClass('active');
        theme.addClass('active');

        $('#_chch_pusf_template').val(template);
        $('#_chch_pusf_template_base').val(base);

        previewWrapper.find('.chch-pusf-option-active .chch-pusf-customize-style').trigger('change');

        previewWrapper.show();
      }
    });
  });


  $('#_chch_pusf_auto_closed').on('change', function() {
    var target = $('.cmb_id__chch_pusf_close_timer');

    if ($(this).is(':checked')) {
      $(target).removeClass('hide-section');
    } else {
      $(target).addClass('hide-section');
    }
  });

  $('#_chch_pusf_auto_closed').trigger('change');

  /////LIVE PREVIEW SCRIPTS
  $(".accordion-section-title").on('click', function(e) {

    var el = $(this);
    var target = el.next('.accordion-section-content');
    if (!$(this).hasClass('open')) {
      $(".accordion-section-title").removeClass('open');
      el.addClass('open');
      target.slideDown('fast');
    } else {
      el.removeClass('open');
      target.slideUp('fast');
    }
  });



  $('.chch-pusf-customize-style').on('change', function(e) {
    var el = $(this);

    var elId = el.attr('id');
    var elType = el.attr('type');
    var template = el.attr('data-template');
    var target = el.attr('data-customize-target');
    var styleAttr = el.attr('data-attr');
    var elValue = el.val();
    var elUnit = el.attr('data-unit');

    if (typeof elUnit === "undefined") {
      elUnit = '';
    }

    if (styleAttr == 'background-image') {
      $('#cc-pu-customize-preview-' + template + ' ' + target).css('background-image', 'url(' + elValue + ')');

      var n = elId.search("_image");
      if (n > 0) {
        $('#cc-pu-customize-preview-' + template + ' ' + target).css('background-size', 'cover');
      }
    } else {
      $('#cc-pu-customize-preview-' + template + ' ' + target).css(styleAttr, elValue + elUnit);
    }

  });

  $('.chch-pusf-customize-content').on('keyup', function(e) {
    var el = $(this);
    var template = el.attr('data-template');
    var target = el.attr('data-customize-target');
    var elAttr = el.attr('data-attr');
    var elValue = el.val();

    if (typeof elAttr === "undefined") {
      $('#cc-pu-customize-preview-' + template + ' ' + target).text(elValue);
    } else {
      $('#cc-pu-customize-preview-' + template + ' ' + target).attr(elAttr, elValue);
    }
  });

  $('.chch-pusf-colorpicker').wpColorPicker({
    change: _.throttle(function() {
      var el = $(this);
      var template = el.attr('data-template');
      var elType = el.attr('type');
      var target = el.attr('data-customize-target');
      var styleAttr = el.attr('data-attr');
      var elValue = el.val();
      $('#cc-pu-customize-preview-' + template + ' ' + target).css(styleAttr, elValue);
    })
  });

  $('.revealer').on('change', function() {
    var el = $(this);
    var target = el.attr('data-customize-target');

    if (el.hasClass('active')) {
      $('#' + target).slideUp('fast');
      el.removeClass('active');
    } else {
      $('#' + target).slideDown('fast');
      el.addClass('active');
    }
  });

  $('.remover-checkbox').on('change', function() {
    var target = $(this).attr('data-customize-target');

    if ($(this).is(':checked')) {
      $(target).hide();
    } else {
      $(target).show();
    }
  });

  $('.revealer-group').on('change', function() {
    var el = $(this);
    var template = el.attr('data-template');
    var eltarget = el.attr('data-customize-target');
    var elAttr = el.attr('data-attr');

    var group = el.attr('data-group');
    var thisOption = el.find(":selected");
    var target = thisOption.val();

    $('#cc-pu-customize-preview-' + template + ' ' + eltarget).css('background-size', 'auto');
    if (target == 'no') {
      $('#cc-pu-customize-preview-' + template + ' ' + eltarget).css(elAttr, 'url()');

    }

    $('#chch-pusf-customize-form-' + template + ' .' + group).slideUp();
    $('#chch-pusf-customize-form-' + template + ' #' + target).slideDown();
    $('#chch-pusf-customize-form-' + template + ' #' + target).find('.chch-pusf-customize-style').trigger('change');

  });

  ///// WP MEDIA UPLOAD JS
  var custom_uploader;


  $('.chch-pusf-image-upload').click(function(e) {

    e.preventDefault();
    target = $(this).attr('data-target');

    //If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }

    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
        text: 'Choose Image'
      },
      multiple: false
    });

    //When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
      attachment = custom_uploader.state().get('selection').first().toJSON();
      console.log(attachment);
      $('#' + target).val(attachment.url);
      $('#' + target).trigger('change');
    });

    //Open the uploader dialog
    custom_uploader.open();

  });

});