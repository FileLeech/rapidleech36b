/**
* ===========================
 * Facebox (for jQuery)
 * version: 1.2 (05/05/2008)
 * @requires jQuery v1.2 or later
 *  Simply bind a function to any of these hooks:
 *   $(document).bind('reveal.facebox', function() { ...stuff to do after the facebox and contents are revealed... })
 *
* Location    : ./static/jQ_ajax
* Machine     :
* Property of : RL OLC 36B
*
* Re-generated   : 1812-09 // ddmm-yy
* ===========================
 */
(function($) {
	$.facebox = function(data, klass) {
		$.facebox.loading()

		if (data.ajax) fillFaceboxFromAjax(data.ajax, klass)
		else if (data.image) fillFaceboxFromImage(data.image, klass)
		else if (data.div) fillFaceboxFromHref(data.div, klass)
		else if ($.isFunction(data)) data.call($)
		else $.facebox.reveal(data, klass)
	}

	/*
   * Public, $.facebox methods
   */

	$.extend($.facebox, {
		settings: {
			opacity      : 0,
			overlay      : true,
			titlePop 	   : 'Delete file?',
			loadingText  : '', // Loading...
			loadingImage : '', // static/images/loading.gif
			closeImage   : 'static/images/nav_close6.png',
			imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
			faceboxHtml  : '\
    <div id="facebox" style="display:none;"> \
      <div class="fbpopup"> \
        <table id="anu"> \
          <tbody> \
            <tr> \
              <td class="tl"/><td class="bu"/><td class="tr"/> \
            </tr> \
            <tr> \
              <td class="bu"/> \
              <td class="body"> \
                <div class="header"> \
                  <span id="thead_title"></span> \
                  <a href="#" class="close"> \
                    <div title="close" class="close_image" /></div> \
                  </a> \
                </div> \
			    <div class="content"></div> \
              </td> \
              <td class="bu"/> \
            </tr> \
            <tr> \
              <td class="bl"/><td class="bu"/><td class="br"/> \
            </tr> \
          </tbody> \
        </table> \
      </div> \
    </div>'
		},

		loading: function() {
			init()
			if ($('#facebox .ngeload').length == 1) return true
			showOverlay()

			$('#facebox .content').empty()
			$('#facebox .body').children().hide().end().
			append('<div class="ngeload">'+($.facebox.settings.loadingImage ? '<center><img src="'+$.facebox.settings.loadingImage+'"/></center>' :
				$.facebox.settings.loadingText)+'</div>')

			$('#facebox').css({
				top:	getPageScroll()[1] + (getPageHeight() / 10),
				//top:	getPageScroll()[1] + ($('#wgaris').offset().top - 20 ),
				left:	$(window).width() / 3
			//left:	($('#wgaris').width()) + 10
			}).show()

			$(document).bind('keydown.facebox', function(e) {
				if (e.keyCode == 27) $.facebox.close()
				return true
			})
			$(document).trigger('loading.facebox')
		},

		reveal: function(data, klass) {
			$(document).trigger('beforeReveal.facebox')
			if (klass) $('#facebox .content').addClass(klass)
			//$('#facebox #thead_title').append($.facebox.settings.titlePop)
			$('#facebox #thead_title').html($.facebox.settings.titlePop)
			$('#facebox .content').append(data)
			$('#facebox .ngeload').remove()
			$('#facebox .body').children().fadeIn('normal')
			$('#facebox').css('left', $(window).width() / 2 - ($('#facebox table').width() / 2))
			//$('#facebox').css('left', ($('#wgaris').width()) + 10)
			$(document).trigger('reveal.facebox').trigger('afterReveal.facebox')
		},

		close: function() {
			$(document).trigger('close.facebox')
			return false
		}
	})

	/*
   * Public, $.fn methods
   */

	$.fn.facebox = function(settings) {
		if ($(this).length == 0) return

		init(settings)

		function clickHandler() {
			$.facebox.loading(true)

			// support for rel="facebox.inline_popup" syntax, to add a class
			// also supports deprecated "facebox[.inline_popup]" syntax
			var klass = this.rel.match(/facebox\[?\.(\w+)\]?/)
			if (klass) klass = klass[1]

			fillFaceboxFromHref(this.href, klass)
			return false
		}

		return this.bind('click.facebox', clickHandler)
	}

	/*
   * Private methods
   */

	// called one time to setup facebox on this page
	function init(settings) {
		if ($.facebox.settings.inited) return true
		else $.facebox.settings.inited = true

		$(document).trigger('init.facebox')
		//makeCompatible()
		var imageTypes = $.facebox.settings.imageTypes.join('|')
		$.facebox.settings.imageTypesRegexp = new RegExp('\.(' + imageTypes + ')$', 'i')

		if (settings) $.extend($.facebox.settings, settings)
		makeCompatible()

		$('body').append($.facebox.settings.faceboxHtml)

		var preload = [ new Image(), new Image() ]
		preload[0].src = $.facebox.settings.closeImage
		preload[1].src = $.facebox.settings.loadingImage

		$('#facebox').find('.b:first, .bl, .br, .tl, .tr').each(function() {
			preload.push(new Image())
			preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
		})
		$('#facebox .close_image').css('background-image', 'url('+$.facebox.settings.closeImage+')').click($.facebox.close)
	}

	// getPageScroll() by quirksmode.com
	function getPageScroll() {
		var xScroll, yScroll;
		if (self.pageYOffset) {
			yScroll = self.pageYOffset;
			xScroll = self.pageXOffset;
		} else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
			yScroll = document.documentElement.scrollTop;
			xScroll = document.documentElement.scrollLeft;
		} else if (document.body) {// all other Explorers
			yScroll = document.body.scrollTop;
			xScroll = document.body.scrollLeft;
		}
		return new Array(xScroll,yScroll)
	}

	// Adapted from getPageSize() by quirksmode.com
	function getPageHeight() {
		var windowHeight
		if (self.innerHeight) {	// all except Explorer
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
			windowHeight = document.body.clientHeight;
		}
		return windowHeight
	}

	// Backwards compatibility
	function makeCompatible() {
		var $s = $.facebox.settings

		$s.loadingImage = $s.loading_image || $s.loadingImage
		$s.loadingText = $s.loading_text || $s.loadingText
		$s.closeImage = $s.close_image || $s.closeImage
		$s.imageTypes = $s.image_types || $s.imageTypes
		$s.faceboxHtml = $s.facebox_html || $s.faceboxHtml
	}

	// Figures out what you want to display and displays it
	// formats are:
	//     div: #id
	//   image: blah.extension
	//    ajax: anything else
	function fillFaceboxFromHref(href, klass) {
		// div
		if (href.match(/#/)) {
			var url    = window.location.href.split('#')[0]
			var target = href.replace(url,'')
			if (target == '#') return
			$.facebox.reveal($(target).html(), klass)

		// image
		} else if (href.match($.facebox.settings.imageTypesRegexp)) {
			fillFaceboxFromImage(href, klass)
		// ajax
		} else {
			fillFaceboxFromAjax(href, klass)
		}
	}

	function fillFaceboxFromImage(href, klass) {
		var image = new Image()
		image.onload = function() {
			$.facebox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
		}
		image.src = href
	}

	function fillFaceboxFromAjax(href, klass) {
		$.get(href, function(data) {
			$.facebox.reveal(data, klass)
		})
	}

	function skipOverlay() {
		return $.facebox.settings.overlay == false || $.facebox.settings.opacity === null
	}

	function showOverlay() {
		if (skipOverlay()) return

		if ($('#facebox_overlay').length == 0)
			$("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

		$('#facebox_overlay').hide().addClass("facebox_overlayBG")
		.css('opacity', $.facebox.settings.opacity)
		.click(function() {
			$(document).trigger('close.facebox')
		})
		.fadeIn(120)
		return false
	}

	function hideOverlay() {
		if (skipOverlay()) return

		$('#facebox_overlay').fadeOut(180, function(){
			$("#facebox_overlay").removeClass("facebox_overlayBG")
			$("#facebox_overlay").addClass("facebox_hide")
			$("#facebox_overlay").remove()
		})

		return false
	}

	/*
   * Bindings
   */
	$(document).bind('close.facebox', function() {
		$(document).unbind('keydown.facebox')
		$('#facebox').fadeOut(function() {
			$('#facebox .content').removeClass().addClass('content')
			hideOverlay()
			$('#facebox .ngeload').remove()
		})
	})

})(jQuery);
//--- end jQ_ajax/