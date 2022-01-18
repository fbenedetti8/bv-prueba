
/*!
 * Ladda 0.9.4 (2014-06-21, 11:24)
 * http://lab.hakim.se/ladda
 * MIT licensed
 *
 * Copyright (C) 2014 Hakim El Hattab, http://hakim.se
 */
(function(t,e){"object"==typeof exports?module.exports=e():"function"==typeof define&&define.amd?define(e):t.Spinner=e()})(this,function(){"use strict";function t(t,e){var i,n=document.createElement(t||"div");for(i in e)n[i]=e[i];return n}function e(t){for(var e=1,i=arguments.length;i>e;e++)t.appendChild(arguments[e]);return t}function i(t,e,i,n){var r=["opacity",e,~~(100*t),i,n].join("-"),o=.01+100*(i/n),a=Math.max(1-(1-t)/e*(100-o),t),s=u.substring(0,u.indexOf("Animation")).toLowerCase(),l=s&&"-"+s+"-"||"";return c[r]||(p.insertRule("@"+l+"keyframes "+r+"{"+"0%{opacity:"+a+"}"+o+"%{opacity:"+t+"}"+(o+.01)+"%{opacity:1}"+(o+e)%100+"%{opacity:"+t+"}"+"100%{opacity:"+a+"}"+"}",p.cssRules.length),c[r]=1),r}function n(t,e){var i,n,r=t.style;for(e=e.charAt(0).toUpperCase()+e.slice(1),n=0;d.length>n;n++)if(i=d[n]+e,void 0!==r[i])return i;return void 0!==r[e]?e:void 0}function r(t,e){for(var i in e)t.style[n(t,i)||i]=e[i];return t}function o(t){for(var e=1;arguments.length>e;e++){var i=arguments[e];for(var n in i)void 0===t[n]&&(t[n]=i[n])}return t}function a(t,e){return"string"==typeof t?t:t[e%t.length]}function s(t){this.opts=o(t||{},s.defaults,f)}function l(){function i(e,i){return t("<"+e+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',i)}p.addRule(".spin-vml","behavior:url(#default#VML)"),s.prototype.lines=function(t,n){function o(){return r(i("group",{coordsize:d+" "+d,coordorigin:-u+" "+-u}),{width:d,height:d})}function s(t,s,l){e(p,e(r(o(),{rotation:360/n.lines*t+"deg",left:~~s}),e(r(i("roundrect",{arcsize:n.corners}),{width:u,height:n.width,left:n.radius,top:-n.width>>1,filter:l}),i("fill",{color:a(n.color,t),opacity:n.opacity}),i("stroke",{opacity:0}))))}var l,u=n.length+n.width,d=2*u,c=2*-(n.width+n.length)+"px",p=r(o(),{position:"absolute",top:c,left:c});if(n.shadow)for(l=1;n.lines>=l;l++)s(l,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(l=1;n.lines>=l;l++)s(l);return e(t,p)},s.prototype.opacity=function(t,e,i,n){var r=t.firstChild;n=n.shadow&&n.lines||0,r&&r.childNodes.length>e+n&&(r=r.childNodes[e+n],r=r&&r.firstChild,r=r&&r.firstChild,r&&(r.opacity=i))}}var u,d=["webkit","Moz","ms","O"],c={},p=function(){var i=t("style",{type:"text/css"});return e(document.getElementsByTagName("head")[0],i),i.sheet||i.styleSheet}(),f={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"50%",left:"50%",position:"absolute"};s.defaults={},o(s.prototype,{spin:function(e){this.stop();var i=this,n=i.opts,o=i.el=r(t(0,{className:n.className}),{position:n.position,width:0,zIndex:n.zIndex});if(n.radius+n.length+n.width,r(o,{left:n.left,top:n.top}),e&&e.insertBefore(o,e.firstChild||null),o.setAttribute("role","progressbar"),i.lines(o,i.opts),!u){var a,s=0,l=(n.lines-1)*(1-n.direction)/2,d=n.fps,c=d/n.speed,p=(1-n.opacity)/(c*n.trail/100),f=c/n.lines;(function h(){s++;for(var t=0;n.lines>t;t++)a=Math.max(1-(s+(n.lines-t)*f)%c*p,n.opacity),i.opacity(o,t*n.direction+l,a,n);i.timeout=i.el&&setTimeout(h,~~(1e3/d))})()}return i},stop:function(){var t=this.el;return t&&(clearTimeout(this.timeout),t.parentNode&&t.parentNode.removeChild(t),this.el=void 0),this},lines:function(n,o){function s(e,i){return r(t(),{position:"absolute",width:o.length+o.width+"px",height:o.width+"px",background:e,boxShadow:i,transformOrigin:"left",transform:"rotate("+~~(360/o.lines*d+o.rotate)+"deg) translate("+o.radius+"px"+",0)",borderRadius:(o.corners*o.width>>1)+"px"})}for(var l,d=0,c=(o.lines-1)*(1-o.direction)/2;o.lines>d;d++)l=r(t(),{position:"absolute",top:1+~(o.width/2)+"px",transform:o.hwaccel?"translate3d(0,0,0)":"",opacity:o.opacity,animation:u&&i(o.opacity,o.trail,c+d*o.direction,o.lines)+" "+1/o.speed+"s linear infinite"}),o.shadow&&e(l,r(s("#000","0 0 4px #000"),{top:"2px"})),e(n,e(l,s(a(o.color,d),"0 0 1px rgba(0,0,0,.1)")));return n},opacity:function(t,e,i){t.childNodes.length>e&&(t.childNodes[e].style.opacity=i)}});var h=r(t("group"),{behavior:"url(#default#VML)"});return!n(h,"transform")&&h.adj?l():u=n(h,"animation"),s});
(function(t,e){"object"==typeof exports?module.exports=e(require("spin.js")):"function"==typeof define&&define.amd?define(["spin"],e):t.Ladda=e(t.Spinner)})(this,function(t){"use strict";function e(t){if(t===void 0)return console.warn("Ladda button target must be defined."),void 0;t.querySelector(".ladda-label")||(t.innerHTML='<span class="ladda-label">'+t.innerHTML+"</span>");var e,n=document.createElement("span");n.className="ladda-spinner",t.appendChild(n);var r,a={start:function(){return e||(e=o(t)),t.setAttribute("disabled",""),t.setAttribute("data-loading",""),clearTimeout(r),e.spin(n),this.setProgress(0),this},startAfter:function(t){return clearTimeout(r),r=setTimeout(function(){a.start()},t),this},stop:function(){return t.removeAttribute("disabled"),t.removeAttribute("data-loading"),clearTimeout(r),e&&(r=setTimeout(function(){e.stop()},1e3)),this},toggle:function(){return this.isLoading()?this.stop():this.start(),this},setProgress:function(e){e=Math.max(Math.min(e,1),0);var n=t.querySelector(".ladda-progress");0===e&&n&&n.parentNode?n.parentNode.removeChild(n):(n||(n=document.createElement("div"),n.className="ladda-progress",t.appendChild(n)),n.style.width=(e||0)*t.offsetWidth+"px")},enable:function(){return this.stop(),this},disable:function(){return this.stop(),t.setAttribute("disabled",""),this},isLoading:function(){return t.hasAttribute("data-loading")},remove:function(){clearTimeout(r),t.removeAttribute("disabled",""),t.removeAttribute("data-loading",""),e&&(e.stop(),e=null);for(var n=0,i=u.length;i>n;n++)if(a===u[n]){u.splice(n,1);break}}};return u.push(a),a}function n(t,e){for(;t.parentNode&&t.tagName!==e;)t=t.parentNode;return e===t.tagName?t:void 0}function r(t){for(var e=["input","textarea"],n=[],r=0;e.length>r;r++)for(var a=t.getElementsByTagName(e[r]),i=0;a.length>i;i++)a[i].hasAttribute("required")&&n.push(a[i]);return n}function a(t,a){a=a||{};var i=[];"string"==typeof t?i=s(document.querySelectorAll(t)):"object"==typeof t&&"string"==typeof t.nodeName&&(i=[t]);for(var o=0,u=i.length;u>o;o++)(function(){var t=i[o];if("function"==typeof t.addEventListener){var s=e(t),u=-1;t.addEventListener("click",function(){var e=!0,i=n(t,"FORM");if(i!==void 0)for(var o=r(i),d=0;o.length>d;d++)""===o[d].value.replace(/^\s+|\s+$/g,"")&&(e=!1);e&&(s.startAfter(1),"number"==typeof a.timeout&&(clearTimeout(u),u=setTimeout(s.stop,a.timeout)),"function"==typeof a.callback&&a.callback.apply(null,[s]))},!1)}})()}function i(){for(var t=0,e=u.length;e>t;t++)u[t].stop()}function o(e){var n,r=e.offsetHeight;0===r&&(r=parseFloat(window.getComputedStyle(e).height)),r>32&&(r*=.8),e.hasAttribute("data-spinner-size")&&(r=parseInt(e.getAttribute("data-spinner-size"),10)),e.hasAttribute("data-spinner-color")&&(n=e.getAttribute("data-spinner-color"));var a=12,i=.2*r,o=.6*i,s=7>i?2:3;return new t({color:n||"#fff",lines:a,radius:i,length:o,width:s,zIndex:"auto",top:"auto",left:"auto",className:""})}function s(t){for(var e=[],n=0;t.length>n;n++)e.push(t[n]);return e}var u=[];return{bind:a,create:e,stopAll:i}});



function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();
  
  // remove accents, swap ñ for n, etc
  var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to   = "aaaaeeeeiiiioooouuuunc------";
  for (var i=0, l=from.length ; i<l ; i++) {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-'); // collapse dashes

  return str;
}


/**
Core script to handle the entire layout and base functions
**/
var App = function() {

	"use strict";

	// IE mode
	var isIE8 = false;
	var isIE9 = false;
	var isIE10 = false;
	var responsiveHandlers = [];
	var layoutColorCodes = {
		'blue':   '#54728c',
		'red':    '#e25856',
		'green':  '#94B86E',
		'purple': '#852b99',
		'grey':   '#555555',
		'yellow': '#ffb848'
	};
	var sidebarWidth = '250px';

	//* BEGIN:CORE HANDLERS *//
	// this function handles responsive layout on screen size resize or mobile device rotate.
	var handleResponsive = function() {
		var isIE8 = ( navigator.userAgent.match(/msie [8]/i) );
		var isIE9 = ( navigator.userAgent.match(/msie [9]/i) );
		var isIE10 = !! navigator.userAgent.match(/MSIE 10/);

		if (isIE10) {
			$('html').addClass('ie10'); // detect IE10 version
		}

		$('.navbar li.nav-toggle').click(function() {
			$('body').toggleClass('nav-open');
		});

		/**
		 * Sidebar-Toggle-Button
		 */

		$('.toggle-sidebar').click(function(e) {
			e.preventDefault();

			// Reset manual divider-resize
			$('#sidebar').css('width', '');
			$('#sidebar > #divider').css('margin-left', '');
			$('#content').css('margin-left', '');

			// Toggle class
			$('#container').toggleClass('sidebar-closed');
		});

		var handleElements = function() {
			// First visible childs add .first
			$('.crumbs .crumb-buttons > li').removeClass('first');
			$('.crumbs .crumb-buttons > li:visible:first').addClass('first');

			// Remove phone-navigation
			if ($('body').hasClass('nav-open')) {
				$('body').toggleClass('nav-open');
			}

			// Add additional scrollbars
			handleScrollbars();

			// Handle project switcher width
			handleProjectSwitcherWidth();
		}

		// handles responsive breakpoints.
		$(window).setBreakpoints({
			breakpoints: [320, 480, 768, 979, 1200]
		});

		$(window).bind('exitBreakpoint320', function() {
			handleElements();
		});
		$(window).bind('enterBreakpoint320', function() {
			handleElements();
		});

		$(window).bind('exitBreakpoint480', function() {
			handleElements();
		});
		$(window).bind('enterBreakpoint480', function() {
			handleElements();
		});

		$(window).bind('exitBreakpoint768', function() {
			handleElements();
		});
		$(window).bind('enterBreakpoint768', function() {
			handleElements();
		});

		$(window).bind('exitBreakpoint979', function() {
			handleElements();
		});
		$(window).bind('enterBreakpoint979', function() {
			handleElements();
		});

		$(window).bind('exitBreakpoint1200', function() {
			handleElements();
		});
		$(window).bind('enterBreakpoint1200', function() {
			handleElements();
		});
	}

	var calculateHeight = function() {
		$('body').height('100%');

		var $header         = $('.header');
		var header_height   = $header.outerHeight();

		var document_height = $(document).height();
		var window_height   = $(window).height();

		var doc_win_diff    = document_height - window_height;

		if (doc_win_diff <= header_height) {
			var new_height  = document_height - doc_win_diff;
		} else {
			var new_height  = document_height;
		}

		new_height = new_height - header_height;

		var document_height = $(document).height();

		$('body').height(new_height);
	}

	var handleLayout = function() {
		calculateHeight();

		// For margin to top, if header is fixed
		if ($('.header').hasClass('navbar-fixed-top')) {
			$('#container').addClass('fixed-header');
		}
	}

	var handleResizeEvents = function() {
		var resizeLayout = debounce(_resizeEvents, 30);
		$(window).resize(resizeLayout);
	}

	// Executed only every 30ms
	var _resizeEvents = function() {
		calculateHeight();

		// Realign headers from DataTables (otherwise header will have an offset)
		// Only affects horizontal scrolling DataTables
		if ($.fn.dataTable) {
			var tables = $.fn.dataTable.fnTables(true);
			$(tables).each(function() {
				if (typeof $(this).data('horizontalWidth') != 'undefined') {
					$(this).dataTable().fnAdjustColumnSizing();
				}
			});
		}
	}

	/**
	 * Creates and returns a new debounced version of the passed
	 * function which will postpone its execution until after wait
	 * milliseconds have elapsed since the last time it was invoked.
	 *
	 * Source: http://underscorejs.org/
	 */
	var debounce = function(func, wait, immediate) {
		var timeout, args, context, timestamp, result;
		return function() {
			context = this;
			args = arguments;
			timestamp = new Date();
			var later = function() {
				var last = (new Date()) - timestamp;
				if (last < wait) {
					timeout = setTimeout(later, wait - last);
				} else {
					timeout = null;
					if (!immediate) result = func.apply(context, args);
				}
			};
			var callNow = immediate && !timeout;
			if (!timeout) {
				timeout = setTimeout(later, wait);
			}
			if (callNow) result = func.apply(context, args);
			return result;
		};
	};

	/**
	 * Swipe Events
	 */
	var handleSwipeEvents = function() {
		// Enable feature only on small widths
		if ($(window).width() <= 767) {

			$('body').on('movestart', function(e) {
				// If the movestart is heading off in an upwards or downwards
				// direction, prevent it so that the browser scrolls normally.
				if ((e.distX > e.distY && e.distX < -e.distY) || (e.distX < e.distY && e.distX > -e.distY)) {
					e.preventDefault();
				}

				// Prevents showing sidebar while scrolling through projects
				var $parentClass = $(e.target).parents('#project-switcher');

				if ($parentClass.length) {
					e.preventDefault();
				}
			}).on('swipeleft', function(e) {
				// Hide sidebar on swipeleft
				$('body').toggleClass('nav-open');
			}).on('swiperight', function(e) {
				// Show sidebar on swiperight
				$('body').toggleClass('nav-open');
			});

		}
	}

	var handleSidebarMenu = function() {
		var arrow_class_open   = 'icon-angle-down',
			arrow_class_closed = 'icon-angle-left';

		$('li:has(ul)', '#sidebar-content ul').each(function() {
			if ($(this).hasClass('current') || $(this).hasClass('open-default')) {
				$('>a', this).append("<i class='arrow " + arrow_class_open + "'></i>");
			} else {
				$('>a', this).append("<i class='arrow " + arrow_class_closed + "'></i>");
			}
		});

		if ($('#sidebar').hasClass('sidebar-fixed')) {
			$('#sidebar-content').append('<div class="fill-nav-space"></div>');
		}

		$('#sidebar-content ul > li > a').on('click', function (e) {

			if ($(this).next().hasClass('sub-menu') == false) {
				return;
			}

			// Toggle on small devices instead of accordion
			if ($(window).width() > 767) {
				var parent = $(this).parent().parent();

				parent.children('li.open').children('a').children('i.arrow').removeClass(arrow_class_open).addClass(arrow_class_closed);
				parent.children('li.open').children('.sub-menu').slideUp(200);
				parent.children('li.open-default').children('.sub-menu').slideUp(200);
				parent.children('li.open').removeClass('open').removeClass('open-default');
			}

			var sub = $(this).next();
			if (sub.is(":visible")) {
				$('i.arrow', $(this)).removeClass(arrow_class_open).addClass(arrow_class_closed);
				$(this).parent().removeClass('open');
				sub.slideUp(200, function() {
					$(this).parent().removeClass('open-fixed').removeClass('open-default');
					calculateHeight();
				});
			} else {
				$('i.arrow', $(this)).removeClass(arrow_class_closed).addClass(arrow_class_open);
				$(this).parent().addClass('open');
				sub.slideDown(200, function() {
					calculateHeight();
				});
			}

			e.preventDefault();
		});

		var _handleResizeable = function() {
			$('#divider.resizeable').mousedown(function(e){
				e.preventDefault();

				var divider_width = $('#divider').width();
				$(document).mousemove(function(e){
					var sidebar_width = e.pageX+divider_width;
					if (sidebar_width <= 300 && sidebar_width >= (divider_width * 2 - 3)) {
						if (sidebar_width >= 240 && sidebar_width <= 260) {
							$('#sidebar').css("width", 250);
							$('#sidebar-content').css("width", 250);
							$('#content').css("margin-left", 250);
							$('#divider').css("margin-left", 250);
						} else {
							$('#sidebar').css("width",sidebar_width);
							$('#sidebar-content').css("width", sidebar_width);
							$('#content').css("margin-left",sidebar_width);
							$('#divider').css("margin-left",sidebar_width);
						}

					}

				})
			});
			$(document).mouseup(function(e){
				$(document).unbind('mousemove');
			});
		}

		_handleResizeable();
	}

	var handleScrollbars = function() {
		var android_chrome = /android.*chrom(e|ium)/.test(navigator.userAgent.toLowerCase());

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) && android_chrome == false) {
			$('#sidebar').css('overflow-y', 'auto');
		} else {
			if ($('#sidebar').hasClass('sidebar-fixed') || $(window).width() <= 767) {

				// Since Chrome on Android has problems with scrolling only in sidebar,
				// this is a workaround for this
				//
				// Awaiting update from Google

				if (android_chrome) {
					var wheelStepInt = 100;
					$('#sidebar').attr('style', 'position: absolute !important;');

					// Fix for really high tablet resolutions
					if ($(window).width() > 979) {
						$('#sidebar').css('margin-top', '-52px');
					}

					// Only hide sidebar on phones
					if ($(window).width() <= 767) {
						$('#sidebar').css('margin-left', '-250px').css('margin-top', '-52px');
					}
				} else {
					var wheelStepInt = 7;
				}

				$('#sidebar-content').slimscroll({
					'height': '100%',
					wheelStep: wheelStepInt
				});
			}
		}
	}

	var handleThemeSwitcher = function() {
		// Add/ Removes theme-* to/ from body
		function _changeTheme(theme) {
			// Remove theme-*
			$('body').removeClass(function (index, css) {
				return (css.match (/\btheme-\S+/g) || []).join(' ');
			});

			// Select theme
			$('body').addClass('theme-' + theme);

			// Store it for page refresh
			$.cookie('theme', theme, { path: '/' });

			// Button styles
			if (theme == 'dark') {
				_toggleBtnInverse('add');
			} else {
				_toggleBtnInverse('remove');
			}
		}

		// Add/ Removes .btn-inverse to/ from switcher
		function _toggleBtnInverse(state) {
			$('#theme-switcher .btn').each(function() {
				if (state == 'add') {
					$(this).addClass('btn-inverse');
				} else {
					$(this).removeClass('btn-inverse');
				}
			});
		}

		if ($.cookie) {
			// Handles click-event on switcher
			$('#theme-switcher label').click(function() {
				var self = $(this).find('input');
				var theme = self.data('theme');

				_changeTheme(theme);
			});

			// Checks, if cookie exists
			// (If user actually changed the theme via switcher)
			if ($.cookie('theme')) {
				var cookie_theme = $.cookie('theme');
				_changeTheme(cookie_theme);

				// To select the right switch
				$('#theme-switcher input').each(function() {
					var self = $(this);
					var theme = self.data('theme');

					if (theme == cookie_theme) {
						self.parent().addClass('active');
					} else {
						self.parent().removeClass('active');
					}
				});

				// Button styles
				if (cookie_theme == 'dark') {
					_toggleBtnInverse('add');
				} else {
					_toggleBtnInverse('remove');
				}
			}
		}
	}

	var handleWidgets = function() {
		$('.widget .toolbar .widget-collapse').click(function() {
			var widget         = $(this).parents(".widget");
			var widget_content = widget.children(".widget-content");
			var widget_chart   = widget.children(".widget-chart");
			var divider        = widget.children(".divider");

			if (widget.hasClass('widget-closed')) {
				// Open Widget
				$(this).children('i').removeClass('icon-angle-up').addClass('icon-angle-down');
				widget_content.slideDown(200, function() {
					widget.removeClass('widget-closed');
				});
				widget_chart.slideDown(200);
				divider.slideDown(200);
			} else {
				// Close Widget
				$(this).children('i').removeClass('icon-angle-down').addClass('icon-angle-up');
				widget_content.slideUp(200, function() {
					widget.addClass('widget-closed');
				});
				widget_chart.slideUp(200);
				divider.slideUp(200);
			}
		});
	}

	var handleCheckableTables = function() {
		$( '.table-checkable thead th.checkbox-column :checkbox' ).on('change', function() {
			var checked = $( this ).prop( 'checked' );

			var data_horizontalWidth = $(this).parents('table.table-checkable').data('horizontalWidth');
			if (typeof data_horizontalWidth != 'undefined') {
				var $checkable_table_body = $( this ).parents('.dataTables_scroll').find('.dataTables_scrollBody tbody');
			} else {
				var $checkable_table_body = $( this ).parents('table').children('tbody');
			}

			$checkable_table_body.each(function(i, tbody) {
				$(tbody).find('.checkbox-column').each(function(j, cb) {
					var cb_self = $( ':checkbox', $(cb) ).prop( "checked", checked ).trigger('change');

					if (cb_self.hasClass('uniform')) {
						$.uniform.update(cb_self);
					}

					$(cb).closest('tr').toggleClass( 'checked', checked );
				});
			});
		});
		$( '.table-checkable tbody tr td.checkbox-column :checkbox' ).on('change', function() {
			var checked = $( this ).prop( 'checked' );
			$( this ).closest('tr').toggleClass( 'checked', checked );
		});
	}

	var handleTabs = function() {
		// function to fix left/right tab contents
		var fixTabHeight = function(tab) {
			$(tab).each(function() {
				var content = $($($(this).attr("href")));
				var tab = $(this).parent().parent();
				if (tab.height() > content.height()) {
					content.css('min-height', tab.height());
				}
			});
		}

		// fix tab content on tab click
		$('body').on('click', '.nav.nav-tabs.tabs-left a[data-toggle="tab"], .nav.nav-tabs.tabs-right a[data-toggle="tab"]', function(){
			fixTabHeight($(this));
		});

		// fix tab contents for left/right tabs
		fixTabHeight('.nav.nav-tabs.tabs-left > li.active > a[data-toggle="tab"], .nav.nav-tabs.tabs-right > li.active > a[data-toggle="tab"]');

		// activate tab if tab id provided in the URL
		if (location.hash) {
			var tabid = location.hash.substr(1);
			$('a[href="#'+tabid+'"]').click();
		}
	}

	var handleScrollers = function() {
		$('.scroller').each(function () {
			$(this).slimScroll({
					size: '7px',
					opacity: '0.2',
					position: 'right',
					height: $(this).attr('data-height'),
					alwaysVisible: ($(this).attr('data-always-visible') == '1' ? true : false),
					railVisible: ($(this).attr('data-rail-visible') == '1' ? true : false),
					disableFadeOut: true
				});
		});
	}

	var handleProjectSwitcher = function() {
		handleProjectSwitcherWidth();

		$('.project-switcher-btn').click(function (e) {
			e.preventDefault();

			_hideVisibleProjectSwitcher(this);

			$(this).parent().toggleClass('open');

			// Define default project switcher
			var data_projectSwitcher = _getProjectSwitcherID(this);

			$(data_projectSwitcher).slideToggle(200, function() {
				$(this).toggleClass('open');
			});
		});

		// Hide project switcher on click elsewhere the element
		$('body').click(function(e) {
			var classes = e.target.className.split(' ');

			if ($.inArray('project-switcher', classes) == -1 && $.inArray('project-switcher-btn', classes) == -1
				&& $(e.target).parents().index($('.project-switcher')) == -1 && $(e.target).parents('.project-switcher-btn').length == 0) {

				_hideVisibleProjectSwitcher();

			}
		});

		/*
		 * Horizontal scrollbars
		 */

		$('.project-switcher #frame').each(function () {
			$(this).slimScrollHorizontal({
				width: '100%',
				alwaysVisible: true,
				color: '#fff',
				opacity: '0.2',
				size: '5px'
			});
		});

		var _hideVisibleProjectSwitcher = function(el) {
			$('.project-switcher').each(function () {
				var $projectswitcher = $(this);

				// Only slide up visible project switcher
				if ($projectswitcher.is(':visible')) {
					var data_projectSwitcher = _getProjectSwitcherID(el);

					if (data_projectSwitcher != ('#' + $projectswitcher.attr('id'))) {
						$(this).slideUp(200, function() {
							$(this).toggleClass('open');

							// Remove all clicked states from toggle buttons
							$('.project-switcher-btn').each(function () {
								// Define default project switcher
								var data_projectSwitcher = _getProjectSwitcherID(this);

								if (data_projectSwitcher == ('#' + $projectswitcher.attr('id'))) {
									$(this).parent().removeClass('open');
								}
							});
						});
					}
				}
			});
		}

		var _getProjectSwitcherID = function(el) {
			// Define default project switcher
			var data_projectSwitcher = $(el).data('projectSwitcher');
			if (typeof data_projectSwitcher == 'undefined') {
				data_projectSwitcher = '#project-switcher';
			}

			return data_projectSwitcher;
		}
	}

	/**
	 * Calculates project switcher width
	 */
	var handleProjectSwitcherWidth = function() {
		$('.project-switcher').each(function () {
			// To fix the hidden-width()-bug
			var $projectswitcher = $(this);
			$projectswitcher.css('position', 'absolute').css('margin-top', '-1000px').show();

			// Iterate through each li
			var total_width = 0;
			$('ul li', this).each(function() {
				total_width += $(this).outerWidth(true) + 15;
			});

			// And finally hide it again
			$projectswitcher.css('position', 'relative').css('margin-top', '0').hide();

			$('ul', this).width(total_width);
		});
	}

	//* END:CORE HANDLERS *//

	return {

		//main function to initiate template pages
		init: function() {
			//core handlers
			handleResponsive(); // Checks for IE-version, click-handler for sidebar-toggle-button, Breakpoints
			handleLayout(); // Calls calculateHeight()
			handleResizeEvents(); // Calls _resizeEvents() every 30ms on resizing
			handleSwipeEvents(); // Enables feature to swipe to the left or right on mobile phones to open the sidebar
			handleSidebarMenu(); // Handles navigation
			handleScrollbars(); // Adds styled scrollbars for sidebar on desktops
			handleThemeSwitcher(); // Bright/ Dark Switcher
			handleWidgets(); // Handle collapse and expand from widgets
			handleCheckableTables(); // Checks all checkboxes in a table if master checkbox was toggled
			handleTabs(); // Fixes tab height
			handleScrollers(); // Initializes slimscroll for scrollable widgets
			handleProjectSwitcher(); // Adds functionality for project switcher at the header
		},

		getLayoutColorCode: function(name) {
			if (layoutColorCodes[name]) {
				return layoutColorCodes[name];
			} else {
				return '';
			}
		},

		// Wrapper function to block elements (indicate loading)
		blockUI: function () {
			$.blockUI({    
				message: '<div class="loading">Loading&#8230;</div>',
				css: {
					border: 'none',
					padding: '0px',
					backgroundColor: '#000',
					'-webkit-border-radius': '10px',
					'-moz-border-radius': '10px',
					opacity: .5,
					color: '#fff'
				}
			});
		},

		// Wrapper function to unblock elements (finish loading)
		unblockUI: function () {
			$.unblockUI();
		}

	};

}();


$(document).ready(function(){
	$('table').first().addClass('tableSticky');
	
	if ($('.tableSticky').length > 0 && $('.tableSticky').is(':visible')) {
		//Tablas con header sticky
		$('.tableSticky').parent().append('<table id="header-fixed" class="table"><thead><tr></tr></thead></table>');
		
		for (var i=0; i<$('.tableSticky thead tr th').length; i++){
			var ancho = $('.tableSticky thead tr th:nth(' + i + ')').width() + 16;
			var content = $('.tableSticky thead tr th:nth(' + i + ')').html();
			$('#header-fixed thead tr').append('<th style="width:' +ancho+'px;">' + content + '</th>');
		}
		
		var tableOffset = $(".tableSticky").offset().top;
		
		$(window).bind("scroll", function() {
		    var offset = $(this).scrollTop();
	
		    if (offset >= tableOffset && $('#header-fixed').is(":hidden")) {
		        //$fixedHeader.show();
		        $('#header-fixed').css('display','inherit');
		    }
		    else if (offset < tableOffset) {
		        $('#header-fixed').hide();
		    }
		});
	}	
});
