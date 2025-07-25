/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 * 
 * Dual licensed under the MIT and GPL licenses
 * 
 */
(function ($) {
	var ColorPicker = function () {
		var
			ids = {},
			inAction,
			charMin = 65,
			visible,
			tpl = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color"></div><div class="colorpicker_hex"><input type="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_submit"></div></div>',
			defaults = {
				eventName: 'click',
				onShow: function () {},
				onBeforeShow: function(){},
				onHide: function () {},
				onChange: function () {},
				onSubmit: function () {},
				color: 'ff0000',
				livePreview: true,
				flat: false
			},
			fillRGBFields = function  (hsb, cal) {
				var rgb = HSBToRGB(hsb);
				$(cal).data( 'colorpicker').fields
					.eq(1).val(rgb.r).end()
					.eq(2).val(rgb.g).end()
					.eq(3).val(rgb.b).end();
			},
			fillHSBFields = function  (hsb, cal) {
				$(cal).data( 'colorpicker').fields
					.eq(4).val(hsb.h).end()
					.eq(5).val(hsb.s).end()
					.eq(6).val(hsb.b).end();
			},
			fillHexFields = function (hsb, cal) {
				$(cal).data( 'colorpicker').fields
					.eq(0).val(HSBToHex(hsb)).end();
			},
			setSelector = function (hsb, cal) {
				$(cal).data( 'colorpicker').selector.css( 'backgroundColor', '#' + HSBToHex({h: hsb.h, s: 100, b: 100}));
				$(cal).data( 'colorpicker').selectorIndic.css({
					left: parseInt(150 * hsb.s/100, 10),
					top: parseInt(150 * (100-hsb.b)/100, 10)
				});
			},
			setHue = function (hsb, cal) {
				$(cal).data( 'colorpicker').hue.css( 'top', parseInt(150 - 150 * hsb.h/360, 10));
			},
			setCurrentColor = function (hsb, cal) {
				$(cal).data( 'colorpicker').currentColor.css( 'backgroundColor', '#' + HSBToHex(hsb));
			},
			setNewColor = function (hsb, cal) {
				$(cal).data( 'colorpicker').newColor.css( 'backgroundColor', '#' + HSBToHex(hsb));
			},
			keyDown = function (ev) {
				var pressedKey = ev.charCode || ev.keyCode || -1;
				if ((pressedKey > charMin && pressedKey <= 90) || pressedKey == 32) {
					return false;
				}
				var cal = $(this).parent().parent();
				if (cal.data( 'colorpicker').livePreview === true) {
					change.apply(this);
				}
			},
			change = function (ev) {
				var cal = $(this).parent().parent(), col;
				if (this.parentNode.className.indexOf( '_hex') > 0) {
					cal.data( 'colorpicker').color = col = HexToHSB(fixHex(this.value));
				} else if (this.parentNode.className.indexOf( '_hsb') > 0) {
					cal.data( 'colorpicker').color = col = fixHSB({
						h: parseInt(cal.data( 'colorpicker').fields.eq(4).val(), 10),
						s: parseInt(cal.data( 'colorpicker').fields.eq(5).val(), 10),
						b: parseInt(cal.data( 'colorpicker').fields.eq(6).val(), 10)
					});
				} else {
					cal.data( 'colorpicker').color = col = RGBToHSB(fixRGB({
						r: parseInt(cal.data( 'colorpicker').fields.eq(1).val(), 10),
						g: parseInt(cal.data( 'colorpicker').fields.eq(2).val(), 10),
						b: parseInt(cal.data( 'colorpicker').fields.eq(3).val(), 10)
					}));
				}
				if (ev) {
					fillRGBFields(col, cal.get(0));
					fillHexFields(col, cal.get(0));
					fillHSBFields(col, cal.get(0));
				}
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
				cal.data( 'colorpicker').onChange.apply(cal, [col, HSBToHex(col), HSBToRGB(col)]);
			},
			blur = function (ev) {
				var cal = $(this).parent().parent();
				cal.data( 'colorpicker').fields.parent().removeClass( 'colorpicker_focus' );
			},
			focus = function () {
				charMin = this.parentNode.className.indexOf( '_hex') > 0 ? 70 : 65;
				$(this).parent().parent().data( 'colorpicker').fields.parent().removeClass( 'colorpicker_focus' );
				$(this).parent().addClass( 'colorpicker_focus' );
			},
			downIncrement = function (ev) {
				var field = $(this).parent().find( 'input').focus();
				var current = {
					el: $(this).parent().addClass( 'colorpicker_slider'),
					max: this.parentNode.className.indexOf( '_hsb_h') > 0 ? 360 : (this.parentNode.className.indexOf( '_hsb') > 0 ? 100 : 255),
					y: ev.pageY,
					field: field,
					val: parseInt(field.val(), 10),
					preview: $(this).parent().parent().data( 'colorpicker').livePreview					
				};
				$(document).bind( 'mouseup', current, upIncrement);
				$(document).bind( 'mousemove', current, moveIncrement);
			},
			moveIncrement = function (ev) {
				ev.data.field.val(Math.max(0, Math.min(ev.data.max, parseInt(ev.data.val + ev.pageY - ev.data.y, 10))));
				if (ev.data.preview) {
					change.apply(ev.data.field.get(0), [true]);
				}
				return false;
			},
			upIncrement = function (ev) {
				change.apply(ev.data.field.get(0), [true]);
				ev.data.el.removeClass( 'colorpicker_slider').find( 'input').focus();
				$(document).unbind( 'mouseup', upIncrement);
				$(document).unbind( 'mousemove', moveIncrement);
				return false;
			},
			downHue = function (ev) {
				var current = {
					cal: $(this).parent(),
					y: $(this).offset().top
				};
				current.preview = current.cal.data( 'colorpicker').livePreview;
				$(document).bind( 'mouseup', current, upHue);
				$(document).bind( 'mousemove', current, moveHue);
			},
			moveHue = function (ev) {
				change.apply(
					ev.data.cal.data( 'colorpicker')
						.fields
						.eq(4)
						.val(parseInt(360*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.y))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upHue = function (ev) {
				fillRGBFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				$(document).unbind( 'mouseup', upHue);
				$(document).unbind( 'mousemove', moveHue);
				return false;
			},
			downSelector = function (ev) {
				var current = {
					cal: $(this).parent(),
					pos: $(this).offset()
				};
				current.preview = current.cal.data( 'colorpicker').livePreview;
				$(document).bind( 'mouseup', current, upSelector);
				$(document).bind( 'mousemove', current, moveSelector);
			},
			moveSelector = function (ev) {
				change.apply(
					ev.data.cal.data( 'colorpicker')
						.fields
						.eq(6)
						.val(parseInt(100*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.pos.top))))/150, 10))
						.end()
						.eq(5)
						.val(parseInt(100*(Math.max(0,Math.min(150,(ev.pageX - ev.data.pos.left))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upSelector = function (ev) {
				fillRGBFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				$(document).unbind( 'mouseup', upSelector);
				$(document).unbind( 'mousemove', moveSelector);
				return false;
			},
			enterSubmit = function (ev) {
				$(this).addClass( 'colorpicker_focus' );
			},
			leaveSubmit = function (ev) {
				$(this).removeClass( 'colorpicker_focus' );
			},
			clickSubmit = function (ev) {
				var cal = $(this).parent();
				var col = cal.data( 'colorpicker').color;
				cal.data( 'colorpicker').origColor = col;
				setCurrentColor(col, cal.get(0));
				cal.data( 'colorpicker').onSubmit(col, HSBToHex(col), HSBToRGB(col), cal.data( 'colorpicker').el);
			},
			show = function (ev) {
				var cal = $( '#' + $(this).data( 'colorpickerId'));
				cal.data( 'colorpicker').onBeforeShow.apply(this, [cal.get(0)]);
				var pos = $(this).offset();
				var viewPort = getViewport();
				var top = pos.top + this.offsetHeight;
				var left = pos.left;
				if (top + 176 > viewPort.t + viewPort.h) {
					top -= this.offsetHeight + 176;
				}
				if (left + 356 > viewPort.l + viewPort.w) {
					left -= 356;
				}
				cal.css({left: left + 'px', top: top + 'px'});
				if (cal.data( 'colorpicker').onShow.apply(this, [cal.get(0)]) != false) {
					cal.show();
				}
				$(document).bind( 'mousedown', {cal: cal}, hide);
				return false;
			},
			hide = function (ev) {
				if (!isChildOf(ev.data.cal.get(0), ev.target, ev.data.cal.get(0))) {
					if (ev.data.cal.data( 'colorpicker').onHide.apply(this, [ev.data.cal.get(0)]) != false) {
						ev.data.cal.hide();
					}
					$(document).unbind( 'mousedown', hide);
				}
			},
			isChildOf = function(parentEl, el, container) {
				if (parentEl == el) {
					return true;
				}
				if (parentEl.contains) {
					return parentEl.contains(el);
				}
				if ( parentEl.compareDocumentPosition ) {
					return !!(parentEl.compareDocumentPosition(el) & 16);
				}
				var prEl = el.parentNode;
				while(prEl && prEl != container) {
					if (prEl == parentEl)
						return true;
					prEl = prEl.parentNode;
				}
				return false;
			},
			getViewport = function () {
				var m = document.compatMode == 'CSS1Compat';
				return {
					l : window.pageXOffset || (m ? document.documentElement.scrollLeft : document.body.scrollLeft),
					t : window.pageYOffset || (m ? document.documentElement.scrollTop : document.body.scrollTop),
					w : window.innerWidth || (m ? document.documentElement.clientWidth : document.body.clientWidth),
					h : window.innerHeight || (m ? document.documentElement.clientHeight : document.body.clientHeight)
				};
			},
			fixHSB = function (hsb) {
				return {
					h: Math.min(360, Math.max(0, hsb.h)),
					s: Math.min(100, Math.max(0, hsb.s)),
					b: Math.min(100, Math.max(0, hsb.b))
				};
			}, 
			fixRGB = function (rgb) {
				return {
					r: Math.min(255, Math.max(0, rgb.r)),
					g: Math.min(255, Math.max(0, rgb.g)),
					b: Math.min(255, Math.max(0, rgb.b))
				};
			},
			fixHex = function (hex) {
				var len = 6 - hex.length;
				if (len > 0) {
					var o = [];
					for (var i=0; i<len; i++) {
						o.push( '0' );
					}
					o.push(hex);
					hex = o.join( '' );
				}
				return hex;
			}, 
			HexToRGB = function (hex) {
				var hex = parseInt(((hex.indexOf( '#') > -1) ? hex.substring(1) : hex), 16);
				return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
			},
			HexToHSB = function (hex) {
				return RGBToHSB(HexToRGB(hex));
			},
			RGBToHSB = function (rgb) {
				var hsb = {
					h: 0,
					s: 0,
					b: 0
				};
				var min = Math.min(rgb.r, rgb.g, rgb.b);
				var max = Math.max(rgb.r, rgb.g, rgb.b);
				var delta = max - min;
				hsb.b = max;
				if (max != 0) {
					
				}
				hsb.s = max != 0 ? 255 * delta / max : 0;
				if (hsb.s != 0) {
					if (rgb.r == max) {
						hsb.h = (rgb.g - rgb.b) / delta;
					} else if (rgb.g == max) {
						hsb.h = 2 + (rgb.b - rgb.r) / delta;
					} else {
						hsb.h = 4 + (rgb.r - rgb.g) / delta;
					}
				} else {
					hsb.h = -1;
				}
				hsb.h *= 60;
				if (hsb.h < 0) {
					hsb.h += 360;
				}
				hsb.s *= 100/255;
				hsb.b *= 100/255;
				return hsb;
			},
			HSBToRGB = function (hsb) {
				var rgb = {};
				var h = Math.round(hsb.h);
				var s = Math.round(hsb.s*255/100);
				var v = Math.round(hsb.b*255/100);
				if(s == 0) {
					rgb.r = rgb.g = rgb.b = v;
				} else {
					var t1 = v;
					var t2 = (255-s)*v/255;
					var t3 = (t1-t2)*(h%60)/60;
					if(h==360) h = 0;
					if(h<60) {rgb.r=t1;	rgb.b=t2; rgb.g=t2+t3}
					else if(h<120) {rgb.g=t1; rgb.b=t2;	rgb.r=t1-t3}
					else if(h<180) {rgb.g=t1; rgb.r=t2;	rgb.b=t2+t3}
					else if(h<240) {rgb.b=t1; rgb.r=t2;	rgb.g=t1-t3}
					else if(h<300) {rgb.b=t1; rgb.g=t2;	rgb.r=t2+t3}
					else if(h<360) {rgb.r=t1; rgb.g=t2;	rgb.b=t1-t3}
					else {rgb.r=0; rgb.g=0;	rgb.b=0}
				}
				return {r:Math.round(rgb.r), g:Math.round(rgb.g), b:Math.round(rgb.b)};
			},
			RGBToHex = function (rgb) {
				var hex = [
					rgb.r.toString(16),
					rgb.g.toString(16),
					rgb.b.toString(16)
				];
				$.each(hex, function (nr, val) {
					if (val.length == 1) {
						hex[nr] = '0' + val;
					}
				});
				return hex.join( '' );
			},
			HSBToHex = function (hsb) {
				return RGBToHex(HSBToRGB(hsb));
			},
			restoreOriginal = function () {
				var cal = $(this).parent();
				var col = cal.data( 'colorpicker').origColor;
				cal.data( 'colorpicker').color = col;
				fillRGBFields(col, cal.get(0));
				fillHexFields(col, cal.get(0));
				fillHSBFields(col, cal.get(0));
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
			};
		return {
			init: function (opt) {
				opt = $.extend({}, defaults, opt||{});
				if (typeof opt.color == 'string') {
					opt.color = HexToHSB(opt.color);
				} else if (opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined) {
					opt.color = RGBToHSB(opt.color);
				} else if (opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined) {
					opt.color = fixHSB(opt.color);
				} else {
					return this;
				}
				return this.each(function () {
					if (!$(this).data( 'colorpickerId')) {
						var options = $.extend({}, opt);
						options.origColor = opt.color;
						var id = 'collorpicker_' + parseInt(Math.random() * 1000);
						$(this).data( 'colorpickerId', id);
						var cal = $(tpl).attr( 'id', id);
						if (options.flat) {
							cal.appendTo(this).show();
						} else {
							cal.appendTo(document.body);
						}
						options.fields = cal
											.find( 'input')
												.bind( 'keyup', keyDown)
												.bind( 'change', change)
												.bind( 'blur', blur)
												.bind( 'focus', focus);
						cal
							.find( 'span').bind( 'mousedown', downIncrement).end()
							.find( '>div.colorpicker_current_color').bind( 'click', restoreOriginal);
						options.selector = cal.find( 'div.colorpicker_color').bind( 'mousedown', downSelector);
						options.selectorIndic = options.selector.find( 'div div' );
						options.el = this;
						options.hue = cal.find( 'div.colorpicker_hue div' );
						cal.find( 'div.colorpicker_hue').bind( 'mousedown', downHue);
						options.newColor = cal.find( 'div.colorpicker_new_color' );
						options.currentColor = cal.find( 'div.colorpicker_current_color' );
						cal.data( 'colorpicker', options);
						cal.find( 'div.colorpicker_submit')
							.bind( 'mouseenter', enterSubmit)
							.bind( 'mouseleave', leaveSubmit)
							.bind( 'click', clickSubmit);
						fillRGBFields(options.color, cal.get(0));
						fillHSBFields(options.color, cal.get(0));
						fillHexFields(options.color, cal.get(0));
						setHue(options.color, cal.get(0));
						setSelector(options.color, cal.get(0));
						setCurrentColor(options.color, cal.get(0));
						setNewColor(options.color, cal.get(0));
						if (options.flat) {
							cal.css({
								position: 'relative',
								display: 'block'
							});
						} else {
							$(this).bind(options.eventName, show);
						}
					}
				});
			},
			showPicker: function() {
				return this.each( function () {
					if ($(this).data( 'colorpickerId')) {
						show.apply(this);
					}
				});
			},
			hidePicker: function() {
				return this.each( function () {
					if ($(this).data( 'colorpickerId')) {
						$( '#' + $(this).data( 'colorpickerId')).hide();
					}
				});
			},
			setColor: function(col) {
				if (typeof col == 'string') {
					col = HexToHSB(col);
				} else if (col.r != undefined && col.g != undefined && col.b != undefined) {
					col = RGBToHSB(col);
				} else if (col.h != undefined && col.s != undefined && col.b != undefined) {
					col = fixHSB(col);
				} else {
					return this;
				}
				return this.each(function(){
					if ($(this).data( 'colorpickerId')) {
						var cal = $( '#' + $(this).data( 'colorpickerId'));
						cal.data( 'colorpicker').color = col;
						cal.data( 'colorpicker').origColor = col;
						fillRGBFields(col, cal.get(0));
						fillHSBFields(col, cal.get(0));
						fillHexFields(col, cal.get(0));
						setHue(col, cal.get(0));
						setSelector(col, cal.get(0));
						setCurrentColor(col, cal.get(0));
						setNewColor(col, cal.get(0));
					}
				});
			}
		};
	}();
	$.fn.extend({
		ColorPicker: ColorPicker.init,
		ColorPickerHide: ColorPicker.hidePicker,
		ColorPickerShow: ColorPicker.showPicker,
		ColorPickerSetColor: ColorPicker.setColor
	});
})(jQuery);/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 * 
 * Dual licensed under the MIT and GPL licenses
 * 
 */
(function ($) {
	var ColorPicker = function () {
		var
			ids = {},
			inAction,
			charMin = 65,
			visible,
			tpl = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color"></div><div class="colorpicker_hex"><input type="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_submit"></div></div>',
			defaults = {
				eventName: 'click',
				onShow: function () {},
				onBeforeShow: function(){},
				onHide: function () {},
				onChange: function () {},
				onSubmit: function () {},
				color: 'ff0000',
				livePreview: true,
				flat: false
			},
			fillRGBFields = function  (hsb, cal) {
				var rgb = HSBToRGB(hsb);
				$(cal).data( 'colorpicker').fields
					.eq(1).val(rgb.r).end()
					.eq(2).val(rgb.g).end()
					.eq(3).val(rgb.b).end();
			},
			fillHSBFields = function  (hsb, cal) {
				$(cal).data( 'colorpicker').fields
					.eq(4).val(hsb.h).end()
					.eq(5).val(hsb.s).end()
					.eq(6).val(hsb.b).end();
			},
			fillHexFields = function (hsb, cal) {
				$(cal).data( 'colorpicker').fields
					.eq(0).val(HSBToHex(hsb)).end();
			},
			setSelector = function (hsb, cal) {
				$(cal).data( 'colorpicker').selector.css( 'backgroundColor', '#' + HSBToHex({h: hsb.h, s: 100, b: 100}));
				$(cal).data( 'colorpicker').selectorIndic.css({
					left: parseInt(150 * hsb.s/100, 10),
					top: parseInt(150 * (100-hsb.b)/100, 10)
				});
			},
			setHue = function (hsb, cal) {
				$(cal).data( 'colorpicker').hue.css( 'top', parseInt(150 - 150 * hsb.h/360, 10));
			},
			setCurrentColor = function (hsb, cal) {
				$(cal).data( 'colorpicker').currentColor.css( 'backgroundColor', '#' + HSBToHex(hsb));
			},
			setNewColor = function (hsb, cal) {
				$(cal).data( 'colorpicker').newColor.css( 'backgroundColor', '#' + HSBToHex(hsb));
			},
			keyDown = function (ev) {
				var pressedKey = ev.charCode || ev.keyCode || -1;
				if ((pressedKey > charMin && pressedKey <= 90) || pressedKey == 32) {
					return false;
				}
				var cal = $(this).parent().parent();
				if (cal.data( 'colorpicker').livePreview === true) {
					change.apply(this);
				}
			},
			change = function (ev) {
				var cal = $(this).parent().parent(), col;
				if (this.parentNode.className.indexOf( '_hex') > 0) {
					cal.data( 'colorpicker').color = col = HexToHSB(fixHex(this.value));
				} else if (this.parentNode.className.indexOf( '_hsb') > 0) {
					cal.data( 'colorpicker').color = col = fixHSB({
						h: parseInt(cal.data( 'colorpicker').fields.eq(4).val(), 10),
						s: parseInt(cal.data( 'colorpicker').fields.eq(5).val(), 10),
						b: parseInt(cal.data( 'colorpicker').fields.eq(6).val(), 10)
					});
				} else {
					cal.data( 'colorpicker').color = col = RGBToHSB(fixRGB({
						r: parseInt(cal.data( 'colorpicker').fields.eq(1).val(), 10),
						g: parseInt(cal.data( 'colorpicker').fields.eq(2).val(), 10),
						b: parseInt(cal.data( 'colorpicker').fields.eq(3).val(), 10)
					}));
				}
				if (ev) {
					fillRGBFields(col, cal.get(0));
					fillHexFields(col, cal.get(0));
					fillHSBFields(col, cal.get(0));
				}
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
				cal.data( 'colorpicker').onChange.apply(cal, [col, HSBToHex(col), HSBToRGB(col)]);
			},
			blur = function (ev) {
				var cal = $(this).parent().parent();
				cal.data( 'colorpicker').fields.parent().removeClass( 'colorpicker_focus' );
			},
			focus = function () {
				charMin = this.parentNode.className.indexOf( '_hex') > 0 ? 70 : 65;
				$(this).parent().parent().data( 'colorpicker').fields.parent().removeClass( 'colorpicker_focus' );
				$(this).parent().addClass( 'colorpicker_focus' );
			},
			downIncrement = function (ev) {
				var field = $(this).parent().find( 'input').focus();
				var current = {
					el: $(this).parent().addClass( 'colorpicker_slider'),
					max: this.parentNode.className.indexOf( '_hsb_h') > 0 ? 360 : (this.parentNode.className.indexOf( '_hsb') > 0 ? 100 : 255),
					y: ev.pageY,
					field: field,
					val: parseInt(field.val(), 10),
					preview: $(this).parent().parent().data( 'colorpicker').livePreview					
				};
				$(document).bind( 'mouseup', current, upIncrement);
				$(document).bind( 'mousemove', current, moveIncrement);
			},
			moveIncrement = function (ev) {
				ev.data.field.val(Math.max(0, Math.min(ev.data.max, parseInt(ev.data.val + ev.pageY - ev.data.y, 10))));
				if (ev.data.preview) {
					change.apply(ev.data.field.get(0), [true]);
				}
				return false;
			},
			upIncrement = function (ev) {
				change.apply(ev.data.field.get(0), [true]);
				ev.data.el.removeClass( 'colorpicker_slider').find( 'input').focus();
				$(document).unbind( 'mouseup', upIncrement);
				$(document).unbind( 'mousemove', moveIncrement);
				return false;
			},
			downHue = function (ev) {
				var current = {
					cal: $(this).parent(),
					y: $(this).offset().top
				};
				current.preview = current.cal.data( 'colorpicker').livePreview;
				$(document).bind( 'mouseup', current, upHue);
				$(document).bind( 'mousemove', current, moveHue);
			},
			moveHue = function (ev) {
				change.apply(
					ev.data.cal.data( 'colorpicker')
						.fields
						.eq(4)
						.val(parseInt(360*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.y))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upHue = function (ev) {
				fillRGBFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				$(document).unbind( 'mouseup', upHue);
				$(document).unbind( 'mousemove', moveHue);
				return false;
			},
			downSelector = function (ev) {
				var current = {
					cal: $(this).parent(),
					pos: $(this).offset()
				};
				current.preview = current.cal.data( 'colorpicker').livePreview;
				$(document).bind( 'mouseup', current, upSelector);
				$(document).bind( 'mousemove', current, moveSelector);
			},
			moveSelector = function (ev) {
				change.apply(
					ev.data.cal.data( 'colorpicker')
						.fields
						.eq(6)
						.val(parseInt(100*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.pos.top))))/150, 10))
						.end()
						.eq(5)
						.val(parseInt(100*(Math.max(0,Math.min(150,(ev.pageX - ev.data.pos.left))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upSelector = function (ev) {
				fillRGBFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data( 'colorpicker').color, ev.data.cal.get(0));
				$(document).unbind( 'mouseup', upSelector);
				$(document).unbind( 'mousemove', moveSelector);
				return false;
			},
			enterSubmit = function (ev) {
				$(this).addClass( 'colorpicker_focus' );
			},
			leaveSubmit = function (ev) {
				$(this).removeClass( 'colorpicker_focus' );
			},
			clickSubmit = function (ev) {
				var cal = $(this).parent();
				var col = cal.data( 'colorpicker').color;
				cal.data( 'colorpicker').origColor = col;
				setCurrentColor(col, cal.get(0));
				cal.data( 'colorpicker').onSubmit(col, HSBToHex(col), HSBToRGB(col), cal.data( 'colorpicker').el);
			},
			show = function (ev) {
				var cal = $( '#' + $(this).data( 'colorpickerId'));
				cal.data( 'colorpicker').onBeforeShow.apply(this, [cal.get(0)]);
				var pos = $(this).offset();
				var viewPort = getViewport();
				var top = pos.top + this.offsetHeight;
				var left = pos.left;
				if (top + 176 > viewPort.t + viewPort.h) {
					top -= this.offsetHeight + 176;
				}
				if (left + 356 > viewPort.l + viewPort.w) {
					left -= 356;
				}
				cal.css({left: left + 'px', top: top + 'px'});
				if (cal.data( 'colorpicker').onShow.apply(this, [cal.get(0)]) != false) {
					cal.show();
				}
				$(document).bind( 'mousedown', {cal: cal}, hide);
				return false;
			},
			hide = function (ev) {
				if (!isChildOf(ev.data.cal.get(0), ev.target, ev.data.cal.get(0))) {
					if (ev.data.cal.data( 'colorpicker').onHide.apply(this, [ev.data.cal.get(0)]) != false) {
						ev.data.cal.hide();
					}
					$(document).unbind( 'mousedown', hide);
				}
			},
			isChildOf = function(parentEl, el, container) {
				if (parentEl == el) {
					return true;
				}
				if (parentEl.contains) {
					return parentEl.contains(el);
				}
				if ( parentEl.compareDocumentPosition ) {
					return !!(parentEl.compareDocumentPosition(el) & 16);
				}
				var prEl = el.parentNode;
				while(prEl && prEl != container) {
					if (prEl == parentEl)
						return true;
					prEl = prEl.parentNode;
				}
				return false;
			},
			getViewport = function () {
				var m = document.compatMode == 'CSS1Compat';
				return {
					l : window.pageXOffset || (m ? document.documentElement.scrollLeft : document.body.scrollLeft),
					t : window.pageYOffset || (m ? document.documentElement.scrollTop : document.body.scrollTop),
					w : window.innerWidth || (m ? document.documentElement.clientWidth : document.body.clientWidth),
					h : window.innerHeight || (m ? document.documentElement.clientHeight : document.body.clientHeight)
				};
			},
			fixHSB = function (hsb) {
				return {
					h: Math.min(360, Math.max(0, hsb.h)),
					s: Math.min(100, Math.max(0, hsb.s)),
					b: Math.min(100, Math.max(0, hsb.b))
				};
			}, 
			fixRGB = function (rgb) {
				return {
					r: Math.min(255, Math.max(0, rgb.r)),
					g: Math.min(255, Math.max(0, rgb.g)),
					b: Math.min(255, Math.max(0, rgb.b))
				};
			},
			fixHex = function (hex) {
				var len = 6 - hex.length;
				if (len > 0) {
					var o = [];
					for (var i=0; i<len; i++) {
						o.push( '0' );
					}
					o.push(hex);
					hex = o.join( '' );
				}
				return hex;
			}, 
			HexToRGB = function (hex) {
				var hex = parseInt(((hex.indexOf( '#') > -1) ? hex.substring(1) : hex), 16);
				return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
			},
			HexToHSB = function (hex) {
				return RGBToHSB(HexToRGB(hex));
			},
			RGBToHSB = function (rgb) {
				var hsb = {
					h: 0,
					s: 0,
					b: 0
				};
				var min = Math.min(rgb.r, rgb.g, rgb.b);
				var max = Math.max(rgb.r, rgb.g, rgb.b);
				var delta = max - min;
				hsb.b = max;
				if (max != 0) {
					
				}
				hsb.s = max != 0 ? 255 * delta / max : 0;
				if (hsb.s != 0) {
					if (rgb.r == max) {
						hsb.h = (rgb.g - rgb.b) / delta;
					} else if (rgb.g == max) {
						hsb.h = 2 + (rgb.b - rgb.r) / delta;
					} else {
						hsb.h = 4 + (rgb.r - rgb.g) / delta;
					}
				} else {
					hsb.h = -1;
				}
				hsb.h *= 60;
				if (hsb.h < 0) {
					hsb.h += 360;
				}
				hsb.s *= 100/255;
				hsb.b *= 100/255;
				return hsb;
			},
			HSBToRGB = function (hsb) {
				var rgb = {};
				var h = Math.round(hsb.h);
				var s = Math.round(hsb.s*255/100);
				var v = Math.round(hsb.b*255/100);
				if(s == 0) {
					rgb.r = rgb.g = rgb.b = v;
				} else {
					var t1 = v;
					var t2 = (255-s)*v/255;
					var t3 = (t1-t2)*(h%60)/60;
					if(h==360) h = 0;
					if(h<60) {rgb.r=t1;	rgb.b=t2; rgb.g=t2+t3}
					else if(h<120) {rgb.g=t1; rgb.b=t2;	rgb.r=t1-t3}
					else if(h<180) {rgb.g=t1; rgb.r=t2;	rgb.b=t2+t3}
					else if(h<240) {rgb.b=t1; rgb.r=t2;	rgb.g=t1-t3}
					else if(h<300) {rgb.b=t1; rgb.g=t2;	rgb.r=t2+t3}
					else if(h<360) {rgb.r=t1; rgb.g=t2;	rgb.b=t1-t3}
					else {rgb.r=0; rgb.g=0;	rgb.b=0}
				}
				return {r:Math.round(rgb.r), g:Math.round(rgb.g), b:Math.round(rgb.b)};
			},
			RGBToHex = function (rgb) {
				var hex = [
					rgb.r.toString(16),
					rgb.g.toString(16),
					rgb.b.toString(16)
				];
				$.each(hex, function (nr, val) {
					if (val.length == 1) {
						hex[nr] = '0' + val;
					}
				});
				return hex.join( '' );
			},
			HSBToHex = function (hsb) {
				return RGBToHex(HSBToRGB(hsb));
			},
			restoreOriginal = function () {
				var cal = $(this).parent();
				var col = cal.data( 'colorpicker').origColor;
				cal.data( 'colorpicker').color = col;
				fillRGBFields(col, cal.get(0));
				fillHexFields(col, cal.get(0));
				fillHSBFields(col, cal.get(0));
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
			};
		return {
			init: function (opt) {
				opt = $.extend({}, defaults, opt||{});
				if (typeof opt.color == 'string') {
					opt.color = HexToHSB(opt.color);
				} else if (opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined) {
					opt.color = RGBToHSB(opt.color);
				} else if (opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined) {
					opt.color = fixHSB(opt.color);
				} else {
					return this;
				}
				return this.each(function () {
					if (!$(this).data( 'colorpickerId')) {
						var options = $.extend({}, opt);
						options.origColor = opt.color;
						var id = 'collorpicker_' + parseInt(Math.random() * 1000);
						$(this).data( 'colorpickerId', id);
						var cal = $(tpl).attr( 'id', id);
						if (options.flat) {
							cal.appendTo(this).show();
						} else {
							cal.appendTo(document.body);
						}
						options.fields = cal
											.find( 'input')
												.bind( 'keyup', keyDown)
												.bind( 'change', change)
												.bind( 'blur', blur)
												.bind( 'focus', focus);
						cal
							.find( 'span').bind( 'mousedown', downIncrement).end()
							.find( '>div.colorpicker_current_color').bind( 'click', restoreOriginal);
						options.selector = cal.find( 'div.colorpicker_color').bind( 'mousedown', downSelector);
						options.selectorIndic = options.selector.find( 'div div' );
						options.el = this;
						options.hue = cal.find( 'div.colorpicker_hue div' );
						cal.find( 'div.colorpicker_hue').bind( 'mousedown', downHue);
						options.newColor = cal.find( 'div.colorpicker_new_color' );
						options.currentColor = cal.find( 'div.colorpicker_current_color' );
						cal.data( 'colorpicker', options);
						cal.find( 'div.colorpicker_submit')
							.bind( 'mouseenter', enterSubmit)
							.bind( 'mouseleave', leaveSubmit)
							.bind( 'click', clickSubmit);
						fillRGBFields(options.color, cal.get(0));
						fillHSBFields(options.color, cal.get(0));
						fillHexFields(options.color, cal.get(0));
						setHue(options.color, cal.get(0));
						setSelector(options.color, cal.get(0));
						setCurrentColor(options.color, cal.get(0));
						setNewColor(options.color, cal.get(0));
						if (options.flat) {
							cal.css({
								position: 'relative',
								display: 'block'
							});
						} else {
							$(this).bind(options.eventName, show);
						}
					}
				});
			},
			showPicker: function() {
				return this.each( function () {
					if ($(this).data( 'colorpickerId')) {
						show.apply(this);
					}
				});
			},
			hidePicker: function() {
				return this.each( function () {
					if ($(this).data( 'colorpickerId')) {
						$( '#' + $(this).data( 'colorpickerId')).hide();
					}
				});
			},
			setColor: function(col) {
				if (typeof col == 'string') {
					col = HexToHSB(col);
				} else if (col.r != undefined && col.g != undefined && col.b != undefined) {
					col = RGBToHSB(col);
				} else if (col.h != undefined && col.s != undefined && col.b != undefined) {
					col = fixHSB(col);
				} else {
					return this;
				}
				return this.each(function(){
					if ($(this).data( 'colorpickerId')) {
						var cal = $( '#' + $(this).data( 'colorpickerId'));
						cal.data( 'colorpicker').color = col;
						cal.data( 'colorpicker').origColor = col;
						fillRGBFields(col, cal.get(0));
						fillHSBFields(col, cal.get(0));
						fillHexFields(col, cal.get(0));
						setHue(col, cal.get(0));
						setSelector(col, cal.get(0));
						setCurrentColor(col, cal.get(0));
						setNewColor(col, cal.get(0));
					}
				});
			}
		};
	}();
	$.fn.extend({
		ColorPicker: ColorPicker.init,
		ColorPickerHide: ColorPicker.hidePicker,
		ColorPickerShow: ColorPicker.showPicker,
		ColorPickerSetColor: ColorPicker.setColor
	});
})(jQuery);var DateFormatter;!function(){"use strict";var D,s,r,a,n;D=function(e,t){return"string"==typeof e&&"string"==typeof t&&e.toLowerCase()===t.toLowerCase()},s=function(e,t,a){var n=a||"0",r=e.toString();return r.length<t?s(n+r,t):r},r=function(e){var t,a;for(e=e||{},t=1;t<arguments.length;t++)if(a=arguments[t])for(var n in a)a.hasOwnProperty(n)&&("object"==typeof a[n]?r(e[n],a[n]):e[n]=a[n]);return e},a=function(e,t){for(var a=0;a<t.length;a++)if(t[a].toLowerCase()===e.toLowerCase())return a;return-1},n={dateSettings:{days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],daysShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],monthsShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],meridiem:["AM","PM"],ordinal:function(e){var t=e%10,a={1:"st",2:"nd",3:"rd"};return 1!==Math.floor(e%100/10)&&a[t]?a[t]:"th"}},separators:/[ \-+\/\.T:@]/g,validParts:/[dDjlNSwzWFmMntLoYyaABgGhHisueTIOPZcrU]/g,intParts:/[djwNzmnyYhHgGis]/g,tzParts:/\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,tzClip:/[^-+\dA-Z]/g},(DateFormatter=function(e){var t=this,a=r(n,e);t.dateSettings=a.dateSettings,t.separators=a.separators,t.validParts=a.validParts,t.intParts=a.intParts,t.tzParts=a.tzParts,t.tzClip=a.tzClip}).prototype={constructor:DateFormatter,getMonth:function(e){var t;return 0===(t=a(e,this.dateSettings.monthsShort)+1)&&(t=a(e,this.dateSettings.months)+1),t},parseDate:function(e,t){var a,n,r,o,i,s,d,u,l,f,c=this,m=!1,h=!1,g=c.dateSettings,p={date:null,year:null,month:null,day:null,hour:0,min:0,sec:0};if(!e)return null;if(e instanceof Date)return e;if("U"===t)return(r=parseInt(e))?new Date(1e3*r):e;switch(typeof e){case"number":return new Date(e);case"string":break;default:return null}if(!(a=t.match(c.validParts))||0===a.length)throw new Error("Invalid date format definition.");for(n=e.replace(c.separators,"\0").split("\0"),r=0;r<n.length;r++)switch(o=n[r],i=parseInt(o),a[r]){case"y":case"Y":if(!i)return null;l=o.length,p.year=2===l?parseInt((i<70?"20":"19")+o):i,m=!0;break;case"m":case"n":case"M":case"F":if(isNaN(i)){if(!(0<(s=c.getMonth(o))))return null;p.month=s}else{if(!(1<=i&&i<=12))return null;p.month=i}m=!0;break;case"d":case"j":if(!(1<=i&&i<=31))return null;p.day=i,m=!0;break;case"g":case"h":if(f=n[d=-1<a.indexOf("a")?a.indexOf("a"):-1<a.indexOf("A")?a.indexOf("A"):-1],-1<d)u=D(f,g.meridiem[0])?0:D(f,g.meridiem[1])?12:-1,1<=i&&i<=12&&-1<u?p.hour=i+u-1:0<=i&&i<=23&&(p.hour=i);else{if(!(0<=i&&i<=23))return null;p.hour=i}h=!0;break;case"G":case"H":if(!(0<=i&&i<=23))return null;p.hour=i,h=!0;break;case"i":if(!(0<=i&&i<=59))return null;p.min=i,h=!0;break;case"s":if(!(0<=i&&i<=59))return null;p.sec=i,h=!0}if(!0===m&&p.year&&p.month&&p.day)p.date=new Date(p.year,p.month-1,p.day,p.hour,p.min,p.sec,0);else{if(!0!==h)return null;p.date=new Date(0,0,0,p.hour,p.min,p.sec,0)}return p.date},guessDate:function(e,t){if("string"!=typeof e)return e;var a,n,r,o,i,s,d=e.replace(this.separators,"\0").split("\0"),u=t.match(this.validParts),l=new Date,f=0;if(!/^[djmn]/g.test(u[0]))return e;for(r=0;r<d.length;r++){if(f=2,i=d[r],s=parseInt(i.substr(0,2)),isNaN(s))return null;switch(r){case 0:"m"===u[0]||"n"===u[0]?l.setMonth(s-1):l.setDate(s);break;case 1:"m"===u[0]||"n"===u[0]?l.setDate(s):l.setMonth(s-1);break;case 2:if(n=l.getFullYear(),f=(a=i.length)<4?a:4,!(n=parseInt(a<4?n.toString().substr(0,4-a)+i:i.substr(0,4))))return null;l.setFullYear(n);break;case 3:l.setHours(s);break;case 4:l.setMinutes(s);break;case 5:l.setSeconds(s)}0<(o=i.substr(f)).length&&d.splice(r+1,0,o)}return l},parseFormat:function(e,n){var a,t=this,r=t.dateSettings,o=/\\?(.?)/gi,i=function(e,t){return a[e]?a[e]():t};return a={d:function(){return s(a.j(),2)},D:function(){return r.daysShort[a.w()]},j:function(){return n.getDate()},l:function(){return r.days[a.w()]},N:function(){return a.w()||7},w:function(){return n.getDay()},z:function(){var e=new Date(a.Y(),a.n()-1,a.j()),t=new Date(a.Y(),0,1);return Math.round((e-t)/864e5)},W:function(){var e=new Date(a.Y(),a.n()-1,a.j()-a.N()+3),t=new Date(e.getFullYear(),0,4);return s(1+Math.round((e-t)/864e5/7),2)},F:function(){return r.months[n.getMonth()]},m:function(){return s(a.n(),2)},M:function(){return r.monthsShort[n.getMonth()]},n:function(){return n.getMonth()+1},t:function(){return new Date(a.Y(),a.n(),0).getDate()},L:function(){var e=a.Y();return e%4==0&&e%100!=0||e%400==0?1:0},o:function(){var e=a.n(),t=a.W();return a.Y()+(12===e&&t<9?1:1===e&&9<t?-1:0)},Y:function(){return n.getFullYear()},y:function(){return a.Y().toString().slice(-2)},a:function(){return a.A().toLowerCase()},A:function(){var e=a.G()<12?0:1;return r.meridiem[e]},B:function(){var e=3600*n.getUTCHours(),t=60*n.getUTCMinutes(),a=n.getUTCSeconds();return s(Math.floor((e+t+a+3600)/86.4)%1e3,3)},g:function(){return a.G()%12||12},G:function(){return n.getHours()},h:function(){return s(a.g(),2)},H:function(){return s(a.G(),2)},i:function(){return s(n.getMinutes(),2)},s:function(){return s(n.getSeconds(),2)},u:function(){return s(1e3*n.getMilliseconds(),6)},e:function(){return/\((.*)\)/.exec(String(n))[1]||"Coordinated Universal Time"},I:function(){return new Date(a.Y(),0)-Date.UTC(a.Y(),0)!=new Date(a.Y(),6)-Date.UTC(a.Y(),6)?1:0},O:function(){var e=n.getTimezoneOffset(),t=Math.abs(e);return(0<e?"-":"+")+s(100*Math.floor(t/60)+t%60,4)},P:function(){var e=a.O();return e.substr(0,3)+":"+e.substr(3,2)},T:function(){return(String(n).match(t.tzParts)||[""]).pop().replace(t.tzClip,"")||"UTC"},Z:function(){return 60*-n.getTimezoneOffset()},c:function(){return"Y-m-d\\TH:i:sP".replace(o,i)},r:function(){return"D, d M Y H:i:s O".replace(o,i)},U:function(){return n.getTime()/1e3||0}},i(e,e)},formatDate:function(e,t){var a,n,r,o,i,s="";if("string"==typeof e&&!(e=this.parseDate(e,t)))return null;if(e instanceof Date){for(r=t.length,a=0;a<r;a++)"S"!==(i=t.charAt(a))&&"\\"!==i&&(0<a&&"\\"===t.charAt(a-1)?s+=i:(o=this.parseFormat(i,e),a!==r-1&&this.intParts.test(i)&&"S"===t.charAt(a+1)&&(n=parseInt(o)||0,o+=this.dateSettings.ordinal(n)),s+=o));return s}return""}}}();var datetimepickerFactory=function(L){"use strict";var s={i18n:{ar:{months:["كانون الثاني","شباط","آذار","نيسان","مايو","حزيران","تموز","آب","أيلول","تشرين الأول","تشرين الثاني","كانون الأول"],dayOfWeekShort:["ن","ث","ع","خ","ج","س","ح"],dayOfWeek:["الأحد","الاثنين","الثلاثاء","الأربعاء","الخميس","الجمعة","السبت","الأحد"]},ro:{months:["Ianuarie","Februarie","Martie","Aprilie","Mai","Iunie","Iulie","August","Septembrie","Octombrie","Noiembrie","Decembrie"],dayOfWeekShort:["Du","Lu","Ma","Mi","Jo","Vi","Sâ"],dayOfWeek:["Duminică","Luni","Marţi","Miercuri","Joi","Vineri","Sâmbătă"]},id:{months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],dayOfWeekShort:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],dayOfWeek:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"]},is:{months:["Janúar","Febrúar","Mars","Apríl","Maí","Júní","Júlí","Ágúst","September","Október","Nóvember","Desember"],dayOfWeekShort:["Sun","Mán","Þrið","Mið","Fim","Fös","Lau"],dayOfWeek:["Sunnudagur","Mánudagur","Þriðjudagur","Miðvikudagur","Fimmtudagur","Föstudagur","Laugardagur"]},bg:{months:["Януари","Февруари","Март","Април","Май","Юни","Юли","Август","Септември","Октомври","Ноември","Декември"],dayOfWeekShort:["Нд","Пн","Вт","Ср","Чт","Пт","Сб"],dayOfWeek:["Неделя","Понеделник","Вторник","Сряда","Четвъртък","Петък","Събота"]},fa:{months:["فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","اسفند"],dayOfWeekShort:["یکشنبه","دوشنبه","سه شنبه","چهارشنبه","پنجشنبه","جمعه","شنبه"],dayOfWeek:["یک‌شنبه","دوشنبه","سه‌شنبه","چهارشنبه","پنج‌شنبه","جمعه","شنبه","یک‌شنبه"]},ru:{months:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],dayOfWeekShort:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],dayOfWeek:["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"]},uk:{months:["Січень","Лютий","Березень","Квітень","Травень","Червень","Липень","Серпень","Вересень","Жовтень","Листопад","Грудень"],dayOfWeekShort:["Ндл","Пнд","Втр","Срд","Чтв","Птн","Сбт"],dayOfWeek:["Неділя","Понеділок","Вівторок","Середа","Четвер","П'ятниця","Субота"]},en:{months:["January","February","March","April","May","June","July","August","September","October","November","December"],dayOfWeekShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayOfWeek:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},el:{months:["Ιανουάριος","Φεβρουάριος","Μάρτιος","Απρίλιος","Μάιος","Ιούνιος","Ιούλιος","Αύγουστος","Σεπτέμβριος","Οκτώβριος","Νοέμβριος","Δεκέμβριος"],dayOfWeekShort:["Κυρ","Δευ","Τρι","Τετ","Πεμ","Παρ","Σαβ"],dayOfWeek:["Κυριακή","Δευτέρα","Τρίτη","Τετάρτη","Πέμπτη","Παρασκευή","Σάββατο"]},de:{months:["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"],dayOfWeekShort:["So","Mo","Di","Mi","Do","Fr","Sa"],dayOfWeek:["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag"]},nl:{months:["januari","februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december"],dayOfWeekShort:["zo","ma","di","wo","do","vr","za"],dayOfWeek:["zondag","maandag","dinsdag","woensdag","donderdag","vrijdag","zaterdag"]},tr:{months:["Ocak","Şubat","Mart","Nisan","Mayıs","Haziran","Temmuz","Ağustos","Eylül","Ekim","Kasım","Aralık"],dayOfWeekShort:["Paz","Pts","Sal","Çar","Per","Cum","Cts"],dayOfWeek:["Pazar","Pazartesi","Salı","Çarşamba","Perşembe","Cuma","Cumartesi"]},fr:{months:["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"],dayOfWeekShort:["Dim","Lun","Mar","Mer","Jeu","Ven","Sam"],dayOfWeek:["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"]},es:{months:["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],dayOfWeekShort:["Dom","Lun","Mar","Mié","Jue","Vie","Sáb"],dayOfWeek:["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"]},th:{months:["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"],dayOfWeekShort:["อา.","จ.","อ.","พ.","พฤ.","ศ.","ส."],dayOfWeek:["อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัส","ศุกร์","เสาร์","อาทิตย์"]},pl:{months:["styczeń","luty","marzec","kwiecień","maj","czerwiec","lipiec","sierpień","wrzesień","październik","listopad","grudzień"],dayOfWeekShort:["nd","pn","wt","śr","cz","pt","sb"],dayOfWeek:["niedziela","poniedziałek","wtorek","środa","czwartek","piątek","sobota"]},pt:{months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],dayOfWeekShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sab"],dayOfWeek:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"]},ch:{months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],dayOfWeekShort:["日","一","二","三","四","五","六"]},se:{months:["Januari","Februari","Mars","April","Maj","Juni","Juli","Augusti","September","Oktober","November","December"],dayOfWeekShort:["Sön","Mån","Tis","Ons","Tor","Fre","Lör"]},km:{months:["មករា​","កុម្ភៈ","មិនា​","មេសា​","ឧសភា​","មិថុនា​","កក្កដា​","សីហា​","កញ្ញា​","តុលា​","វិច្ឆិកា","ធ្នូ​"],dayOfWeekShort:["អាទិ​","ច័ន្ទ​","អង្គារ​","ពុធ​","ព្រហ​​","សុក្រ​","សៅរ៍"],dayOfWeek:["អាទិត្យ​","ច័ន្ទ​","អង្គារ​","ពុធ​","ព្រហស្បតិ៍​","សុក្រ​","សៅរ៍"]},kr:{months:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],dayOfWeekShort:["일","월","화","수","목","금","토"],dayOfWeek:["일요일","월요일","화요일","수요일","목요일","금요일","토요일"]},it:{months:["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre"],dayOfWeekShort:["Dom","Lun","Mar","Mer","Gio","Ven","Sab"],dayOfWeek:["Domenica","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato"]},da:{months:["Januar","Februar","Marts","April","Maj","Juni","Juli","August","September","Oktober","November","December"],dayOfWeekShort:["Søn","Man","Tir","Ons","Tor","Fre","Lør"],dayOfWeek:["søndag","mandag","tirsdag","onsdag","torsdag","fredag","lørdag"]},no:{months:["Januar","Februar","Mars","April","Mai","Juni","Juli","August","September","Oktober","November","Desember"],dayOfWeekShort:["Søn","Man","Tir","Ons","Tor","Fre","Lør"],dayOfWeek:["Søndag","Mandag","Tirsdag","Onsdag","Torsdag","Fredag","Lørdag"]},ja:{months:["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],dayOfWeekShort:["日","月","火","水","木","金","土"],dayOfWeek:["日曜","月曜","火曜","水曜","木曜","金曜","土曜"]},vi:{months:["Tháng 1","Tháng 2","Tháng 3","Tháng 4","Tháng 5","Tháng 6","Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11","Tháng 12"],dayOfWeekShort:["CN","T2","T3","T4","T5","T6","T7"],dayOfWeek:["Chủ nhật","Thứ hai","Thứ ba","Thứ tư","Thứ năm","Thứ sáu","Thứ bảy"]},sl:{months:["Januar","Februar","Marec","April","Maj","Junij","Julij","Avgust","September","Oktober","November","December"],dayOfWeekShort:["Ned","Pon","Tor","Sre","Čet","Pet","Sob"],dayOfWeek:["Nedelja","Ponedeljek","Torek","Sreda","Četrtek","Petek","Sobota"]},cs:{months:["Leden","Únor","Březen","Duben","Květen","Červen","Červenec","Srpen","Září","Říjen","Listopad","Prosinec"],dayOfWeekShort:["Ne","Po","Út","St","Čt","Pá","So"]},hu:{months:["Január","Február","Március","Április","Május","Június","Július","Augusztus","Szeptember","Október","November","December"],dayOfWeekShort:["Va","Hé","Ke","Sze","Cs","Pé","Szo"],dayOfWeek:["vasárnap","hétfő","kedd","szerda","csütörtök","péntek","szombat"]},az:{months:["Yanvar","Fevral","Mart","Aprel","May","Iyun","Iyul","Avqust","Sentyabr","Oktyabr","Noyabr","Dekabr"],dayOfWeekShort:["B","Be","Ça","Ç","Ca","C","Ş"],dayOfWeek:["Bazar","Bazar ertəsi","Çərşənbə axşamı","Çərşənbə","Cümə axşamı","Cümə","Şənbə"]},bs:{months:["Januar","Februar","Mart","April","Maj","Jun","Jul","Avgust","Septembar","Oktobar","Novembar","Decembar"],dayOfWeekShort:["Ned","Pon","Uto","Sri","Čet","Pet","Sub"],dayOfWeek:["Nedjelja","Ponedjeljak","Utorak","Srijeda","Četvrtak","Petak","Subota"]},ca:{months:["Gener","Febrer","Març","Abril","Maig","Juny","Juliol","Agost","Setembre","Octubre","Novembre","Desembre"],dayOfWeekShort:["Dg","Dl","Dt","Dc","Dj","Dv","Ds"],dayOfWeek:["Diumenge","Dilluns","Dimarts","Dimecres","Dijous","Divendres","Dissabte"]},"en-GB":{months:["January","February","March","April","May","June","July","August","September","October","November","December"],dayOfWeekShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayOfWeek:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]},et:{months:["Jaanuar","Veebruar","Märts","Aprill","Mai","Juuni","Juuli","August","September","Oktoober","November","Detsember"],dayOfWeekShort:["P","E","T","K","N","R","L"],dayOfWeek:["Pühapäev","Esmaspäev","Teisipäev","Kolmapäev","Neljapäev","Reede","Laupäev"]},eu:{months:["Urtarrila","Otsaila","Martxoa","Apirila","Maiatza","Ekaina","Uztaila","Abuztua","Iraila","Urria","Azaroa","Abendua"],dayOfWeekShort:["Ig.","Al.","Ar.","Az.","Og.","Or.","La."],dayOfWeek:["Igandea","Astelehena","Asteartea","Asteazkena","Osteguna","Ostirala","Larunbata"]},fi:{months:["Tammikuu","Helmikuu","Maaliskuu","Huhtikuu","Toukokuu","Kesäkuu","Heinäkuu","Elokuu","Syyskuu","Lokakuu","Marraskuu","Joulukuu"],dayOfWeekShort:["Su","Ma","Ti","Ke","To","Pe","La"],dayOfWeek:["sunnuntai","maanantai","tiistai","keskiviikko","torstai","perjantai","lauantai"]},gl:{months:["Xan","Feb","Maz","Abr","Mai","Xun","Xul","Ago","Set","Out","Nov","Dec"],dayOfWeekShort:["Dom","Lun","Mar","Mer","Xov","Ven","Sab"],dayOfWeek:["Domingo","Luns","Martes","Mércores","Xoves","Venres","Sábado"]},hr:{months:["Siječanj","Veljača","Ožujak","Travanj","Svibanj","Lipanj","Srpanj","Kolovoz","Rujan","Listopad","Studeni","Prosinac"],dayOfWeekShort:["Ned","Pon","Uto","Sri","Čet","Pet","Sub"],dayOfWeek:["Nedjelja","Ponedjeljak","Utorak","Srijeda","Četvrtak","Petak","Subota"]},ko:{months:["1월","2월","3월","4월","5월","6월","7월","8월","9월","10월","11월","12월"],dayOfWeekShort:["일","월","화","수","목","금","토"],dayOfWeek:["일요일","월요일","화요일","수요일","목요일","금요일","토요일"]},lt:{months:["Sausio","Vasario","Kovo","Balandžio","Gegužės","Birželio","Liepos","Rugpjūčio","Rugsėjo","Spalio","Lapkričio","Gruodžio"],dayOfWeekShort:["Sek","Pir","Ant","Tre","Ket","Pen","Šeš"],dayOfWeek:["Sekmadienis","Pirmadienis","Antradienis","Trečiadienis","Ketvirtadienis","Penktadienis","Šeštadienis"]},lv:{months:["Janvāris","Februāris","Marts","Aprīlis ","Maijs","Jūnijs","Jūlijs","Augusts","Septembris","Oktobris","Novembris","Decembris"],dayOfWeekShort:["Sv","Pr","Ot","Tr","Ct","Pk","St"],dayOfWeek:["Svētdiena","Pirmdiena","Otrdiena","Trešdiena","Ceturtdiena","Piektdiena","Sestdiena"]},mk:{months:["јануари","февруари","март","април","мај","јуни","јули","август","септември","октомври","ноември","декември"],dayOfWeekShort:["нед","пон","вто","сре","чет","пет","саб"],dayOfWeek:["Недела","Понеделник","Вторник","Среда","Четврток","Петок","Сабота"]},mn:{months:["1-р сар","2-р сар","3-р сар","4-р сар","5-р сар","6-р сар","7-р сар","8-р сар","9-р сар","10-р сар","11-р сар","12-р сар"],dayOfWeekShort:["Дав","Мяг","Лха","Пүр","Бсн","Бям","Ням"],dayOfWeek:["Даваа","Мягмар","Лхагва","Пүрэв","Баасан","Бямба","Ням"]},"pt-BR":{months:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],dayOfWeekShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sáb"],dayOfWeek:["Domingo","Segunda","Terça","Quarta","Quinta","Sexta","Sábado"]},sk:{months:["Január","Február","Marec","Apríl","Máj","Jún","Júl","August","September","Október","November","December"],dayOfWeekShort:["Ne","Po","Ut","St","Št","Pi","So"],dayOfWeek:["Nedeľa","Pondelok","Utorok","Streda","Štvrtok","Piatok","Sobota"]},sq:{months:["Janar","Shkurt","Mars","Prill","Maj","Qershor","Korrik","Gusht","Shtator","Tetor","Nëntor","Dhjetor"],dayOfWeekShort:["Die","Hën","Mar","Mër","Enj","Pre","Shtu"],dayOfWeek:["E Diel","E Hënë","E Martē","E Mërkurë","E Enjte","E Premte","E Shtunë"]},"sr-YU":{months:["Januar","Februar","Mart","April","Maj","Jun","Jul","Avgust","Septembar","Oktobar","Novembar","Decembar"],dayOfWeekShort:["Ned","Pon","Uto","Sre","čet","Pet","Sub"],dayOfWeek:["Nedelja","Ponedeljak","Utorak","Sreda","Četvrtak","Petak","Subota"]},sr:{months:["јануар","фебруар","март","април","мај","јун","јул","август","септембар","октобар","новембар","децембар"],dayOfWeekShort:["нед","пон","уто","сре","чет","пет","суб"],dayOfWeek:["Недеља","Понедељак","Уторак","Среда","Четвртак","Петак","Субота"]},sv:{months:["Januari","Februari","Mars","April","Maj","Juni","Juli","Augusti","September","Oktober","November","December"],dayOfWeekShort:["Sön","Mån","Tis","Ons","Tor","Fre","Lör"],dayOfWeek:["Söndag","Måndag","Tisdag","Onsdag","Torsdag","Fredag","Lördag"]},"zh-TW":{months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],dayOfWeekShort:["日","一","二","三","四","五","六"],dayOfWeek:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"]},zh:{months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],dayOfWeekShort:["日","一","二","三","四","五","六"],dayOfWeek:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"]},ug:{months:["1-ئاي","2-ئاي","3-ئاي","4-ئاي","5-ئاي","6-ئاي","7-ئاي","8-ئاي","9-ئاي","10-ئاي","11-ئاي","12-ئاي"],dayOfWeek:["يەكشەنبە","دۈشەنبە","سەيشەنبە","چارشەنبە","پەيشەنبە","جۈمە","شەنبە"]},he:{months:["ינואר","פברואר","מרץ","אפריל","מאי","יוני","יולי","אוגוסט","ספטמבר","אוקטובר","נובמבר","דצמבר"],dayOfWeekShort:["א'","ב'","ג'","ד'","ה'","ו'","שבת"],dayOfWeek:["ראשון","שני","שלישי","רביעי","חמישי","שישי","שבת","ראשון"]},hy:{months:["Հունվար","Փետրվար","Մարտ","Ապրիլ","Մայիս","Հունիս","Հուլիս","Օգոստոս","Սեպտեմբեր","Հոկտեմբեր","Նոյեմբեր","Դեկտեմբեր"],dayOfWeekShort:["Կի","Երկ","Երք","Չոր","Հնգ","Ուրբ","Շբթ"],dayOfWeek:["Կիրակի","Երկուշաբթի","Երեքշաբթի","Չորեքշաբթի","Հինգշաբթի","Ուրբաթ","Շաբաթ"]},kg:{months:["Үчтүн айы","Бирдин айы","Жалган Куран","Чын Куран","Бугу","Кулжа","Теке","Баш Оона","Аяк Оона","Тогуздун айы","Жетинин айы","Бештин айы"],dayOfWeekShort:["Жек","Дүй","Шей","Шар","Бей","Жум","Ише"],dayOfWeek:["Жекшемб","Дүйшөмб","Шейшемб","Шаршемб","Бейшемби","Жума","Ишенб"]},rm:{months:["Schaner","Favrer","Mars","Avrigl","Matg","Zercladur","Fanadur","Avust","Settember","October","November","December"],dayOfWeekShort:["Du","Gli","Ma","Me","Gie","Ve","So"],dayOfWeek:["Dumengia","Glindesdi","Mardi","Mesemna","Gievgia","Venderdi","Sonda"]},ka:{months:["იანვარი","თებერვალი","მარტი","აპრილი","მაისი","ივნისი","ივლისი","აგვისტო","სექტემბერი","ოქტომბერი","ნოემბერი","დეკემბერი"],dayOfWeekShort:["კვ","ორშ","სამშ","ოთხ","ხუთ","პარ","შაბ"],dayOfWeek:["კვირა","ორშაბათი","სამშაბათი","ოთხშაბათი","ხუთშაბათი","პარასკევი","შაბათი"]}},ownerDocument:document,contentWindow:window,value:"",rtl:!1,format:"Y/m/d H:i",formatTime:"H:i",formatDate:"Y/m/d",startDate:!1,step:60,monthChangeSpinner:!0,closeOnDateSelect:!1,closeOnTimeSelect:!0,closeOnWithoutClick:!0,closeOnInputClick:!0,openOnFocus:!0,timepicker:!0,datepicker:!0,weeks:!1,defaultTime:!1,defaultDate:!1,minDate:!1,maxDate:!1,minTime:!1,maxTime:!1,minDateTime:!1,maxDateTime:!1,allowTimes:[],opened:!1,initTime:!0,inline:!1,theme:"",touchMovedThreshold:5,onSelectDate:function(){},onSelectTime:function(){},onChangeMonth:function(){},onGetWeekOfYear:function(){},onChangeYear:function(){},onChangeDateTime:function(){},onShow:function(){},onClose:function(){},onGenerate:function(){},withoutCopyright:!0,inverseButton:!1,hours12:!1,next:"xdsoft_next",prev:"xdsoft_prev",dayOfWeekStart:0,parentID:"body",timeHeightInTimePicker:25,timepickerScrollbar:!0,todayButton:!0,prevButton:!0,nextButton:!0,defaultSelect:!0,scrollMonth:!0,scrollTime:!0,scrollInput:!0,lazyInit:!1,mask:!1,validateOnBlur:!0,allowBlank:!0,yearStart:1950,yearEnd:2050,monthStart:0,monthEnd:11,style:"",id:"",fixed:!1,roundTime:"round",className:"",weekends:[],highlightedDates:[],highlightedPeriods:[],allowDates:[],allowDateRe:null,disabledDates:[],disabledWeekDays:[],yearOffset:0,beforeShowDay:null,enterLikeTab:!0,showApplyButton:!1,insideParent:!1},E=null,n=null,R="en",a={meridiem:["AM","PM"]},r=function(){var e=s.i18n[R],t={days:e.dayOfWeek,daysShort:e.dayOfWeekShort,months:e.months,monthsShort:L.map(e.months,function(e){return e.substring(0,3)})};"function"==typeof DateFormatter&&(E=n=new DateFormatter({dateSettings:L.extend({},a,t)}))},o={moment:{default_options:{format:"YYYY/MM/DD HH:mm",formatDate:"YYYY/MM/DD",formatTime:"HH:mm"},formatter:{parseDate:function(e,t){if(i(t))return n.parseDate(e,t);var a=moment(e,t);return!!a.isValid()&&a.toDate()},formatDate:function(e,t){return i(t)?n.formatDate(e,t):moment(e).format(t)},formatMask:function(e){return e.replace(/Y{4}/g,"9999").replace(/Y{2}/g,"99").replace(/M{2}/g,"19").replace(/D{2}/g,"39").replace(/H{2}/g,"29").replace(/m{2}/g,"59").replace(/s{2}/g,"59")}}}};L.datetimepicker={setLocale:function(e){var t=s.i18n[e]?e:"en";R!==t&&(R=t,r())},setDateFormatter:function(e){if("string"==typeof e&&o.hasOwnProperty(e)){var t=o[e];L.extend(s,t.default_options),E=t.formatter}else E=e}};var t={RFC_2822:"D, d M Y H:i:s O",ATOM:"Y-m-dTH:i:sP",ISO_8601:"Y-m-dTH:i:sO",RFC_822:"D, d M y H:i:s O",RFC_850:"l, d-M-y H:i:s T",RFC_1036:"D, d M y H:i:s O",RFC_1123:"D, d M Y H:i:s O",RSS:"D, d M Y H:i:s O",W3C:"Y-m-dTH:i:sP"},i=function(e){return-1!==Object.values(t).indexOf(e)};function m(e,t,a){this.date=e,this.desc=t,this.style=a}L.extend(L.datetimepicker,t),r(),window.getComputedStyle||(window.getComputedStyle=function(a){return this.el=a,this.getPropertyValue=function(e){var t=/(-([a-z]))/g;return"float"===e&&(e="styleFloat"),t.test(e)&&(e=e.replace(t,function(e,t,a){return a.toUpperCase()})),a.currentStyle[e]||null},this}),Array.prototype.indexOf||(Array.prototype.indexOf=function(e,t){var a,n;for(a=t||0,n=this.length;a<n;a+=1)if(this[a]===e)return a;return-1}),Date.prototype.countDaysInMonth=function(){return new Date(this.getFullYear(),this.getMonth()+1,0).getDate()},L.fn.xdsoftScroller=function(D,y){return this.each(function(){var o,i,s,d,u,l=L(this),a=function(e){var t,a={x:0,y:0};return"touchstart"===e.type||"touchmove"===e.type||"touchend"===e.type||"touchcancel"===e.type?(t=e.originalEvent.touches[0]||e.originalEvent.changedTouches[0],a.x=t.clientX,a.y=t.clientY):"mousedown"!==e.type&&"mouseup"!==e.type&&"mousemove"!==e.type&&"mouseover"!==e.type&&"mouseout"!==e.type&&"mouseenter"!==e.type&&"mouseleave"!==e.type||(a.x=e.clientX,a.y=e.clientY),a},f=0,c=100,n=!1,r=0,m=0,h=0,t=!1,g=0,p=function(){};"hide"!==y?(L(this).hasClass("xdsoft_scroller_box")||(o=l.children().eq(0),f=Math.abs(parseInt(o.css("marginTop"),10)),i=l[0].clientHeight,s=o[0].offsetHeight,d=L('<div class="xdsoft_scrollbar"></div>'),u=L('<div class="xdsoft_scroller"></div>'),d.append(u),l.addClass("xdsoft_scroller_box").append(d),p=function(e){var t=a(e).y-r+g;t<0&&(t=0),t+u[0].offsetHeight>h&&(t=h-u[0].offsetHeight),l.trigger("scroll_element.xdsoft_scroller",[c?t/c:0])},u.on("touchstart.xdsoft_scroller mousedown.xdsoft_scroller",function(e){i||l.trigger("resize_scroll.xdsoft_scroller",[y]),r=a(e).y,g=parseInt(u.css("marginTop"),10),h=d[0].offsetHeight,"mousedown"===e.type||"touchstart"===e.type?(D.ownerDocument&&L(D.ownerDocument.body).addClass("xdsoft_noselect"),L([D.ownerDocument.body,D.contentWindow]).on("touchend mouseup.xdsoft_scroller",function e(){L([D.ownerDocument.body,D.contentWindow]).off("touchend mouseup.xdsoft_scroller",e).off("mousemove.xdsoft_scroller",p).removeClass("xdsoft_noselect")}),L(D.ownerDocument.body).on("mousemove.xdsoft_scroller",p)):(t=!0,e.stopPropagation(),e.preventDefault())}).on("touchmove",function(e){t&&(e.preventDefault(),p(e))}).on("touchend touchcancel",function(){t=!1,g=0}),l.on("scroll_element.xdsoft_scroller",function(e,t){i||l.trigger("resize_scroll.xdsoft_scroller",[t,!0]),t=1<t?1:t<0||isNaN(t)?0:t,f=parseFloat(Math.abs((o[0].offsetHeight-i)*t).toFixed(4)),u.css("marginTop",c*t),o.css("marginTop",-f)}).on("resize_scroll.xdsoft_scroller",function(e,t,a){var n,r;i=l[0].clientHeight,s=o[0].offsetHeight,r=(n=i/s)*d[0].offsetHeight,1<n?u.hide():(u.show(),u.css("height",parseInt(10<r?r:10,10)),c=d[0].offsetHeight-u[0].offsetHeight,!0!==a&&l.trigger("scroll_element.xdsoft_scroller",[t||f/(s-i)]))}),l.on("mousewheel",function(e){var t,a,n=(t=e.originalEvent,a=0,"detail"in t&&(a=t.detail),"wheelDelta"in t&&(a=-t.wheelDelta/120),"wheelDeltaY"in t&&(a=-t.wheelDeltaY/120),"axis"in t&&t.axis===t.HORIZONTAL_AXIS&&(a=0),a*=10,"deltaY"in t&&(a=t.deltaY),a&&t.deltaMode&&(1===t.deltaMode?a*=40:a*=800),a),r=Math.max(0,f-n);return l.trigger("scroll_element.xdsoft_scroller",[r/(s-i)]),e.stopPropagation(),!1}),l.on("touchstart",function(e){n=a(e),m=f}),l.on("touchmove",function(e){if(n){e.preventDefault();var t=a(e);l.trigger("scroll_element.xdsoft_scroller",[(m-(t.y-n.y))/(s-i)])}}),l.on("touchend touchcancel",function(){n=!1,m=0})),l.trigger("resize_scroll.xdsoft_scroller",[y])):l.find(".xdsoft_scrollbar").hide()})},L.fn.datetimepicker=function(H,a){var n,r,o=this,p=17,D=13,y=27,v=37,b=38,k=39,x=40,T=9,S=116,M=65,w=67,j=86,J=90,z=89,N=!1,I=L.isPlainObject(H)||!H?L.extend(!0,{},s,H):L.extend(!0,{},s),i=0;return n=function(O){var t,n,a,r,W,h,_=L('<div class="xdsoft_datetimepicker xdsoft_noselect"></div>'),e=L('<div class="xdsoft_copyright"><a target="_blank" href="http://xdsoft.net/jqplugins/datetimepicker/">xdsoft.net</a></div>'),g=L('<div class="xdsoft_datepicker active"></div>'),F=L('<div class="xdsoft_monthpicker"><button type="button" class="xdsoft_prev"></button><button type="button" class="xdsoft_today_button"></button><div class="xdsoft_label xdsoft_month"><span></span><i></i></div><div class="xdsoft_label xdsoft_year"><span></span><i></i></div><button type="button" class="xdsoft_next"></button></div>'),C=L('<div class="xdsoft_calendar"></div>'),o=L('<div class="xdsoft_timepicker active"><button type="button" class="xdsoft_prev"></button><div class="xdsoft_time_box"></div><button type="button" class="xdsoft_next"></button></div>'),u=o.find(".xdsoft_time_box").eq(0),P=L('<div class="xdsoft_time_variant"></div>'),i=L('<button type="button" class="xdsoft_save_selected blue-gradient-button">Save Selected</button>'),Y=L('<div class="xdsoft_select xdsoft_monthselect"><div></div></div>'),A=L('<div class="xdsoft_select xdsoft_yearselect"><div></div></div>'),s=!1,d=0;I.id&&_.attr("id",I.id),I.style&&_.attr("style",I.style),I.weeks&&_.addClass("xdsoft_showweeks"),I.rtl&&_.addClass("xdsoft_rtl"),_.addClass("xdsoft_"+I.theme),_.addClass(I.className),F.find(".xdsoft_month span").after(Y),F.find(".xdsoft_year span").after(A),F.find(".xdsoft_month,.xdsoft_year").on("touchstart mousedown.xdsoft",function(e){var t,a,n=L(this).find(".xdsoft_select").eq(0),r=0,o=0,i=n.is(":visible");for(F.find(".xdsoft_select").hide(),W.currentTime&&(r=W.currentTime[L(this).hasClass("xdsoft_month")?"getMonth":"getFullYear"]()),n[i?"hide":"show"](),t=n.find("div.xdsoft_option"),a=0;a<t.length&&t.eq(a).data("value")!==r;a+=1)o+=t[0].offsetHeight;return n.xdsoftScroller(I,o/(n.children()[0].offsetHeight-n[0].clientHeight)),e.stopPropagation(),!1});var l=function(e){var t=e.originalEvent,a=t.touches?t.touches[0]:t;this.touchStartPosition=this.touchStartPosition||a;var n=Math.abs(this.touchStartPosition.clientX-a.clientX),r=Math.abs(this.touchStartPosition.clientY-a.clientY);Math.sqrt(n*n+r*r)>I.touchMovedThreshold&&(this.touchMoved=!0)};function f(){var e,t=!1;return I.startDate?t=W.strToDate(I.startDate):(t=I.value||(O&&O.val&&O.val()?O.val():""))?(t=W.strToDateTime(t),I.yearOffset&&(t=new Date(t.getFullYear()-I.yearOffset,t.getMonth(),t.getDate(),t.getHours(),t.getMinutes(),t.getSeconds(),t.getMilliseconds()))):I.defaultDate&&(t=W.strToDateTime(I.defaultDate),I.defaultTime&&(e=W.strtotime(I.defaultTime),t.setHours(e.getHours()),t.setMinutes(e.getMinutes()))),t&&W.isValidDate(t)?_.data("changed",!0):t="",t||0}function c(m){var h=function(e,t){var a=e.replace(/([\[\]\/\{\}\(\)\-\.\+]{1})/g,"\\$1").replace(/_/g,"{digit+}").replace(/([0-9]{1})/g,"{digit$1}").replace(/\{digit([0-9]{1})\}/g,"[0-$1_]{1}").replace(/\{digit[\+]\}/g,"[0-9_]{1}");return new RegExp(a).test(t)},g=function(e,t){if(!(e="string"==typeof e||e instanceof String?m.ownerDocument.getElementById(e):e))return!1;if(e.createTextRange){var a=e.createTextRange();return a.collapse(!0),a.moveEnd("character",t),a.moveStart("character",t),a.select(),!0}return!!e.setSelectionRange&&(e.setSelectionRange(t,t),!0)};m.mask&&O.off("keydown.xdsoft"),!0===m.mask&&(E.formatMask?m.mask=E.formatMask(m.format):m.mask=m.format.replace(/Y/g,"9999").replace(/F/g,"9999").replace(/m/g,"19").replace(/d/g,"39").replace(/H/g,"29").replace(/i/g,"59").replace(/s/g,"59")),"string"===L.type(m.mask)&&(h(m.mask,O.val())||(O.val(m.mask.replace(/[0-9]/g,"_")),g(O[0],0)),O.on("paste.xdsoft",function(e){var t=(e.clipboardData||e.originalEvent.clipboardData||window.clipboardData).getData("text"),a=this.value,n=this.selectionStart;return a=a.substr(0,n)+t+a.substr(n+t.length),n+=t.length,h(m.mask,a)?(this.value=a,g(this,n)):""===L.trim(a)?this.value=m.mask.replace(/[0-9]/g,"_"):O.trigger("error_input.xdsoft"),e.preventDefault(),!1}),O.on("keydown.xdsoft",function(e){var t,a=this.value,n=e.which,r=this.selectionStart,o=this.selectionEnd,i=r!==o;if(48<=n&&n<=57||96<=n&&n<=105||8===n||46===n){for(t=8===n||46===n?"_":String.fromCharCode(96<=n&&n<=105?n-48:n),8===n&&r&&!i&&(r-=1);;){var s=m.mask.substr(r,1),d=r<m.mask.length,u=0<r;if(!(/[^0-9_]/.test(s)&&d&&u))break;r+=8!==n||i?1:-1}if(e.metaKey&&(i=!(r=0)),i){var l=o-r,f=m.mask.replace(/[0-9]/g,"_"),c=f.substr(r,l).substr(1);a=a.substr(0,r)+(t+c)+a.substr(r+l)}else{a=a.substr(0,r)+t+a.substr(r+1)}if(""===L.trim(a))a=f;else if(r===m.mask.length)return e.preventDefault(),!1;for(r+=8===n?0:1;/[^0-9_]/.test(m.mask.substr(r,1))&&r<m.mask.length&&0<r;)r+=8===n?0:1;h(m.mask,a)?(this.value=a,g(this,r)):""===L.trim(a)?this.value=m.mask.replace(/[0-9]/g,"_"):O.trigger("error_input.xdsoft")}else if(-1!==[M,w,j,J,z].indexOf(n)&&N||-1!==[y,b,x,v,k,S,p,T,D].indexOf(n))return!0;return e.preventDefault(),!1}))}F.find(".xdsoft_select").xdsoftScroller(I).on("touchstart mousedown.xdsoft",function(e){var t=e.originalEvent;this.touchMoved=!1,this.touchStartPosition=t.touches?t.touches[0]:t,e.stopPropagation(),e.preventDefault()}).on("touchmove",".xdsoft_option",l).on("touchend mousedown.xdsoft",".xdsoft_option",function(){if(!this.touchMoved){void 0!==W.currentTime&&null!==W.currentTime||(W.currentTime=W.now());var e=W.currentTime.getFullYear();W&&W.currentTime&&W.currentTime[L(this).parent().parent().hasClass("xdsoft_monthselect")?"setMonth":"setFullYear"](L(this).data("value")),L(this).parent().parent().hide(),_.trigger("xchange.xdsoft"),I.onChangeMonth&&L.isFunction(I.onChangeMonth)&&I.onChangeMonth.call(_,W.currentTime,_.data("input")),e!==W.currentTime.getFullYear()&&L.isFunction(I.onChangeYear)&&I.onChangeYear.call(_,W.currentTime,_.data("input"))}}),_.getValue=function(){return W.getCurrentTime()},_.setOptions=function(e){var l={};I=L.extend(!0,{},I,e),e.allowTimes&&L.isArray(e.allowTimes)&&e.allowTimes.length&&(I.allowTimes=L.extend(!0,[],e.allowTimes)),e.weekends&&L.isArray(e.weekends)&&e.weekends.length&&(I.weekends=L.extend(!0,[],e.weekends)),e.allowDates&&L.isArray(e.allowDates)&&e.allowDates.length&&(I.allowDates=L.extend(!0,[],e.allowDates)),e.allowDateRe&&"[object String]"===Object.prototype.toString.call(e.allowDateRe)&&(I.allowDateRe=new RegExp(e.allowDateRe)),e.highlightedDates&&L.isArray(e.highlightedDates)&&e.highlightedDates.length&&(L.each(e.highlightedDates,function(e,t){var a,n=L.map(t.split(","),L.trim),r=new m(E.parseDate(n[0],I.formatDate),n[1],n[2]),o=E.formatDate(r.date,I.formatDate);void 0!==l[o]?(a=l[o].desc)&&a.length&&r.desc&&r.desc.length&&(l[o].desc=a+"\n"+r.desc):l[o]=r}),I.highlightedDates=L.extend(!0,[],l)),e.highlightedPeriods&&L.isArray(e.highlightedPeriods)&&e.highlightedPeriods.length&&(l=L.extend(!0,[],I.highlightedDates),L.each(e.highlightedPeriods,function(e,t){var a,n,r,o,i,s,d;if(L.isArray(t))a=t[0],n=t[1],r=t[2],d=t[3];else{var u=L.map(t.split(","),L.trim);a=E.parseDate(u[0],I.formatDate),n=E.parseDate(u[1],I.formatDate),r=u[2],d=u[3]}for(;a<=n;)o=new m(a,r,d),i=E.formatDate(a,I.formatDate),a.setDate(a.getDate()+1),void 0!==l[i]?(s=l[i].desc)&&s.length&&o.desc&&o.desc.length&&(l[i].desc=s+"\n"+o.desc):l[i]=o}),I.highlightedDates=L.extend(!0,[],l)),e.disabledDates&&L.isArray(e.disabledDates)&&e.disabledDates.length&&(I.disabledDates=L.extend(!0,[],e.disabledDates)),e.disabledWeekDays&&L.isArray(e.disabledWeekDays)&&e.disabledWeekDays.length&&(I.disabledWeekDays=L.extend(!0,[],e.disabledWeekDays)),!I.open&&!I.opened||I.inline||O.trigger("open.xdsoft"),I.inline&&(s=!0,_.addClass("xdsoft_inline"),O.after(_).hide()),I.inverseButton&&(I.next="xdsoft_prev",I.prev="xdsoft_next"),I.datepicker?g.addClass("active"):g.removeClass("active"),I.timepicker?o.addClass("active"):o.removeClass("active"),I.value&&(W.setCurrentTime(I.value),O&&O.val&&O.val(W.str)),isNaN(I.dayOfWeekStart)?I.dayOfWeekStart=0:I.dayOfWeekStart=parseInt(I.dayOfWeekStart,10)%7,I.timepickerScrollbar||u.xdsoftScroller(I,"hide"),I.minDate&&/^[\+\-](.*)$/.test(I.minDate)&&(I.minDate=E.formatDate(W.strToDateTime(I.minDate),I.formatDate)),I.maxDate&&/^[\+\-](.*)$/.test(I.maxDate)&&(I.maxDate=E.formatDate(W.strToDateTime(I.maxDate),I.formatDate)),I.minDateTime&&/^\+(.*)$/.test(I.minDateTime)&&(I.minDateTime=W.strToDateTime(I.minDateTime).dateFormat(I.formatDate)),I.maxDateTime&&/^\+(.*)$/.test(I.maxDateTime)&&(I.maxDateTime=W.strToDateTime(I.maxDateTime).dateFormat(I.formatDate)),i.toggle(I.showApplyButton),F.find(".xdsoft_today_button").css("visibility",I.todayButton?"visible":"hidden"),F.find("."+I.prev).css("visibility",I.prevButton?"visible":"hidden"),F.find("."+I.next).css("visibility",I.nextButton?"visible":"hidden"),c(I),I.validateOnBlur&&O.off("blur.xdsoft").on("blur.xdsoft",function(){if(I.allowBlank&&(!L.trim(L(this).val()).length||"string"==typeof I.mask&&L.trim(L(this).val())===I.mask.replace(/[0-9]/g,"_")))L(this).val(null),_.data("xdsoft_datetime").empty();else{var e=E.parseDate(L(this).val(),I.format);if(e)L(this).val(E.formatDate(e,I.format));else{var t=+[L(this).val()[0],L(this).val()[1]].join(""),a=+[L(this).val()[2],L(this).val()[3]].join("");!I.datepicker&&I.timepicker&&0<=t&&t<24&&0<=a&&a<60?L(this).val([t,a].map(function(e){return 9<e?e:"0"+e}).join(":")):L(this).val(E.formatDate(W.now(),I.format))}_.data("xdsoft_datetime").setCurrentTime(L(this).val())}_.trigger("changedatetime.xdsoft"),_.trigger("close.xdsoft")}),I.dayOfWeekStartPrev=0===I.dayOfWeekStart?6:I.dayOfWeekStart-1,_.trigger("xchange.xdsoft").trigger("afterOpen.xdsoft")},_.data("options",I).on("touchstart mousedown.xdsoft",function(e){return e.stopPropagation(),e.preventDefault(),A.hide(),Y.hide(),!1}),u.append(P),u.xdsoftScroller(I),_.on("afterOpen.xdsoft",function(){u.xdsoftScroller(I)}),_.append(g).append(o),!0!==I.withoutCopyright&&_.append(e),g.append(F).append(C).append(i),I.insideParent?L(O).parent().append(_):L(I.parentID).append(_),W=new function(){var r=this;r.now=function(e){var t,a,n=new Date;return!e&&I.defaultDate&&(t=r.strToDateTime(I.defaultDate),n.setFullYear(t.getFullYear()),n.setMonth(t.getMonth()),n.setDate(t.getDate())),n.setFullYear(n.getFullYear()),!e&&I.defaultTime&&(a=r.strtotime(I.defaultTime),n.setHours(a.getHours()),n.setMinutes(a.getMinutes()),n.setSeconds(a.getSeconds()),n.setMilliseconds(a.getMilliseconds())),n},r.isValidDate=function(e){return"[object Date]"===Object.prototype.toString.call(e)&&!isNaN(e.getTime())},r.setCurrentTime=function(e,t){"string"==typeof e?r.currentTime=r.strToDateTime(e):r.isValidDate(e)?r.currentTime=e:e||t||!I.allowBlank||I.inline?r.currentTime=r.now():r.currentTime=null,_.trigger("xchange.xdsoft")},r.empty=function(){r.currentTime=null},r.getCurrentTime=function(){return r.currentTime},r.nextMonth=function(){void 0!==r.currentTime&&null!==r.currentTime||(r.currentTime=r.now());var e,t=r.currentTime.getMonth()+1;return 12===t&&(r.currentTime.setFullYear(r.currentTime.getFullYear()+1),t=0),e=r.currentTime.getFullYear(),r.currentTime.setDate(Math.min(new Date(r.currentTime.getFullYear(),t+1,0).getDate(),r.currentTime.getDate())),r.currentTime.setMonth(t),I.onChangeMonth&&L.isFunction(I.onChangeMonth)&&I.onChangeMonth.call(_,W.currentTime,_.data("input")),e!==r.currentTime.getFullYear()&&L.isFunction(I.onChangeYear)&&I.onChangeYear.call(_,W.currentTime,_.data("input")),_.trigger("xchange.xdsoft"),t},r.prevMonth=function(){void 0!==r.currentTime&&null!==r.currentTime||(r.currentTime=r.now());var e=r.currentTime.getMonth()-1;return-1===e&&(r.currentTime.setFullYear(r.currentTime.getFullYear()-1),e=11),r.currentTime.setDate(Math.min(new Date(r.currentTime.getFullYear(),e+1,0).getDate(),r.currentTime.getDate())),r.currentTime.setMonth(e),I.onChangeMonth&&L.isFunction(I.onChangeMonth)&&I.onChangeMonth.call(_,W.currentTime,_.data("input")),_.trigger("xchange.xdsoft"),e},r.getWeekOfYear=function(e){if(I.onGetWeekOfYear&&L.isFunction(I.onGetWeekOfYear)){var t=I.onGetWeekOfYear.call(_,e);if(void 0!==t)return t}var a=new Date(e.getFullYear(),0,1);return 4!==a.getDay()&&a.setMonth(0,1+(4-a.getDay()+7)%7),Math.ceil(((e-a)/864e5+a.getDay()+1)/7)},r.strToDateTime=function(e){var t,a,n=[];return e&&e instanceof Date&&r.isValidDate(e)?e:((n=/^([+-]{1})(.*)$/.exec(e))&&(n[2]=E.parseDate(n[2],I.formatDate)),a=n&&n[2]?(t=n[2].getTime()-6e4*n[2].getTimezoneOffset(),new Date(r.now(!0).getTime()+parseInt(n[1]+"1",10)*t)):e?E.parseDate(e,I.format):r.now(),r.isValidDate(a)||(a=r.now()),a)},r.strToDate=function(e){if(e&&e instanceof Date&&r.isValidDate(e))return e;var t=e?E.parseDate(e,I.formatDate):r.now(!0);return r.isValidDate(t)||(t=r.now(!0)),t},r.strtotime=function(e){if(e&&e instanceof Date&&r.isValidDate(e))return e;var t=e?E.parseDate(e,I.formatTime):r.now(!0);return r.isValidDate(t)||(t=r.now(!0)),t},r.str=function(){var e=I.format;return I.yearOffset&&(e=(e=e.replace("Y",r.currentTime.getFullYear()+I.yearOffset)).replace("y",String(r.currentTime.getFullYear()+I.yearOffset).substring(2,4))),E.formatDate(r.currentTime,e)},r.currentTime=this.now()},i.on("touchend click",function(e){e.preventDefault(),_.data("changed",!0),W.setCurrentTime(f()),O.val(W.str()),_.trigger("close.xdsoft")}),F.find(".xdsoft_today_button").on("touchend mousedown.xdsoft",function(){_.data("changed",!0),W.setCurrentTime(0,!0),_.trigger("afterOpen.xdsoft")}).on("dblclick.xdsoft",function(){var e,t,a=W.getCurrentTime();a=new Date(a.getFullYear(),a.getMonth(),a.getDate()),e=W.strToDate(I.minDate),a<(e=new Date(e.getFullYear(),e.getMonth(),e.getDate()))||(t=W.strToDate(I.maxDate),(t=new Date(t.getFullYear(),t.getMonth(),t.getDate()))<a||(O.val(W.str()),O.trigger("change"),_.trigger("close.xdsoft")))}),F.find(".xdsoft_prev,.xdsoft_next").on("touchend mousedown.xdsoft",function(){var a=L(this),n=0,r=!1;!function e(t){a.hasClass(I.next)?W.nextMonth():a.hasClass(I.prev)&&W.prevMonth(),I.monthChangeSpinner&&(r||(n=setTimeout(e,t||100)))}(500),L([I.ownerDocument.body,I.contentWindow]).on("touchend mouseup.xdsoft",function e(){clearTimeout(n),r=!0,L([I.ownerDocument.body,I.contentWindow]).off("touchend mouseup.xdsoft",e)})}),o.find(".xdsoft_prev,.xdsoft_next").on("touchend mousedown.xdsoft",function(){var o=L(this),i=0,s=!1,d=110;!function e(t){var a=u[0].clientHeight,n=P[0].offsetHeight,r=Math.abs(parseInt(P.css("marginTop"),10));o.hasClass(I.next)&&n-a-I.timeHeightInTimePicker>=r?P.css("marginTop","-"+(r+I.timeHeightInTimePicker)+"px"):o.hasClass(I.prev)&&0<=r-I.timeHeightInTimePicker&&P.css("marginTop","-"+(r-I.timeHeightInTimePicker)+"px"),u.trigger("scroll_element.xdsoft_scroller",[Math.abs(parseInt(P[0].style.marginTop,10)/(n-a))]),d=10<d?10:d-10,s||(i=setTimeout(e,t||d))}(500),L([I.ownerDocument.body,I.contentWindow]).on("touchend mouseup.xdsoft",function e(){clearTimeout(i),s=!0,L([I.ownerDocument.body,I.contentWindow]).off("touchend mouseup.xdsoft",e)})}),t=0,_.on("xchange.xdsoft",function(e){clearTimeout(t),t=setTimeout(function(){void 0!==W.currentTime&&null!==W.currentTime||(W.currentTime=W.now());for(var e,t,a,n,r,o,i,s,d,u,l="",f=new Date(W.currentTime.getFullYear(),W.currentTime.getMonth(),1,12,0,0),c=0,m=W.now(),h=!1,g=!1,p=!1,D=!1,y=[],v=!0,b="";f.getDay()!==I.dayOfWeekStart;)f.setDate(f.getDate()-1);for(l+="<table><thead><tr>",I.weeks&&(l+="<th></th>"),e=0;e<7;e+=1)l+="<th>"+I.i18n[R].dayOfWeekShort[(e+I.dayOfWeekStart)%7]+"</th>";for(l+="</tr></thead>",l+="<tbody>",!1!==I.maxDate&&(h=W.strToDate(I.maxDate),h=new Date(h.getFullYear(),h.getMonth(),h.getDate(),23,59,59,999)),!1!==I.minDate&&(g=W.strToDate(I.minDate),g=new Date(g.getFullYear(),g.getMonth(),g.getDate())),!1!==I.minDateTime&&(p=W.strToDate(I.minDateTime),p=new Date(p.getFullYear(),p.getMonth(),p.getDate(),p.getHours(),p.getMinutes(),p.getSeconds())),!1!==I.maxDateTime&&(D=W.strToDate(I.maxDateTime),D=new Date(D.getFullYear(),D.getMonth(),D.getDate(),D.getHours(),D.getMinutes(),D.getSeconds())),!1!==D&&(u=31*(12*D.getFullYear()+D.getMonth())+D.getDate());c<W.currentTime.countDaysInMonth()||f.getDay()!==I.dayOfWeekStart||W.currentTime.getMonth()===f.getMonth();){y=[],c+=1,a=f.getDay(),n=f.getDate(),r=f.getFullYear(),M=f.getMonth(),o=W.getWeekOfYear(f),d="",y.push("xdsoft_date"),i=I.beforeShowDay&&L.isFunction(I.beforeShowDay.call)?I.beforeShowDay.call(_,f):null,I.allowDateRe&&"[object RegExp]"===Object.prototype.toString.call(I.allowDateRe)&&(I.allowDateRe.test(E.formatDate(f,I.formatDate))||y.push("xdsoft_disabled")),I.allowDates&&0<I.allowDates.length&&-1===I.allowDates.indexOf(E.formatDate(f,I.formatDate))&&y.push("xdsoft_disabled");var k=31*(12*f.getFullYear()+f.getMonth())+f.getDate();(!1!==h&&h<f||!1!==p&&f<p||!1!==g&&f<g||!1!==D&&u<k||i&&!1===i[0])&&y.push("xdsoft_disabled"),-1!==I.disabledDates.indexOf(E.formatDate(f,I.formatDate))&&y.push("xdsoft_disabled"),-1!==I.disabledWeekDays.indexOf(a)&&y.push("xdsoft_disabled"),O.is("[disabled]")&&y.push("xdsoft_disabled"),i&&""!==i[1]&&y.push(i[1]),W.currentTime.getMonth()!==M&&y.push("xdsoft_other_month"),(I.defaultSelect||_.data("changed"))&&E.formatDate(W.currentTime,I.formatDate)===E.formatDate(f,I.formatDate)&&y.push("xdsoft_current"),E.formatDate(m,I.formatDate)===E.formatDate(f,I.formatDate)&&y.push("xdsoft_today"),0!==f.getDay()&&6!==f.getDay()&&-1===I.weekends.indexOf(E.formatDate(f,I.formatDate))||y.push("xdsoft_weekend"),void 0!==I.highlightedDates[E.formatDate(f,I.formatDate)]&&(t=I.highlightedDates[E.formatDate(f,I.formatDate)],y.push(void 0===t.style?"xdsoft_highlighted_default":t.style),d=void 0===t.desc?"":t.desc),I.beforeShowDay&&L.isFunction(I.beforeShowDay)&&y.push(I.beforeShowDay(f)),v&&(l+="<tr>",v=!1,I.weeks&&(l+="<th>"+o+"</th>")),l+='<td data-date="'+n+'" data-month="'+M+'" data-year="'+r+'" class="xdsoft_date xdsoft_day_of_week'+f.getDay()+" "+y.join(" ")+'" title="'+d+'"><div>'+n+"</div></td>",f.getDay()===I.dayOfWeekStartPrev&&(l+="</tr>",v=!0),f.setDate(n+1)}l+="</tbody></table>",C.html(l),F.find(".xdsoft_label span").eq(0).text(I.i18n[R].months[W.currentTime.getMonth()]),F.find(".xdsoft_label span").eq(1).text(W.currentTime.getFullYear()+I.yearOffset),M=b="";var x=0;if(!1!==I.minTime){var T=W.strtotime(I.minTime);x=60*T.getHours()+T.getMinutes()}var S=1440;if(!1!==I.maxTime){T=W.strtotime(I.maxTime);S=60*T.getHours()+T.getMinutes()}if(!1!==I.minDateTime){T=W.strToDateTime(I.minDateTime);if(E.formatDate(W.currentTime,I.formatDate)===E.formatDate(T,I.formatDate)){var M=60*T.getHours()+T.getMinutes();x<M&&(x=M)}}if(!1!==I.maxDateTime){T=W.strToDateTime(I.maxDateTime);if(E.formatDate(W.currentTime,I.formatDate)===E.formatDate(T,I.formatDate))(M=60*T.getHours()+T.getMinutes())<S&&(S=M)}if(s=function(e,t){var a,n=W.now(),r=I.allowTimes&&L.isArray(I.allowTimes)&&I.allowTimes.length;n.setHours(e),e=parseInt(n.getHours(),10),n.setMinutes(t),t=parseInt(n.getMinutes(),10),y=[];var o=60*e+t;(O.is("[disabled]")||S<=o||o<x)&&y.push("xdsoft_disabled"),(a=new Date(W.currentTime)).setHours(parseInt(W.currentTime.getHours(),10)),r||a.setMinutes(Math[I.roundTime](W.currentTime.getMinutes()/I.step)*I.step),(I.initTime||I.defaultSelect||_.data("changed"))&&a.getHours()===parseInt(e,10)&&(!r&&59<I.step||a.getMinutes()===parseInt(t,10))&&(I.defaultSelect||_.data("changed")?y.push("xdsoft_current"):I.initTime&&y.push("xdsoft_init_time")),parseInt(m.getHours(),10)===parseInt(e,10)&&parseInt(m.getMinutes(),10)===parseInt(t,10)&&y.push("xdsoft_today"),b+='<div class="xdsoft_time '+y.join(" ")+'" data-hour="'+e+'" data-minute="'+t+'">'+E.formatDate(n,I.formatTime)+"</div>"},I.allowTimes&&L.isArray(I.allowTimes)&&I.allowTimes.length)for(c=0;c<I.allowTimes.length;c+=1)s(W.strtotime(I.allowTimes[c]).getHours(),M=W.strtotime(I.allowTimes[c]).getMinutes());else for(e=c=0;c<(I.hours12?12:24);c+=1)for(e=0;e<60;e+=I.step){var w=60*c+e;w<x||(S<=w||s((c<10?"0":"")+c,M=(e<10?"0":"")+e))}for(P.html(b),H="",c=parseInt(I.yearStart,10);c<=parseInt(I.yearEnd,10);c+=1)H+='<div class="xdsoft_option '+(W.currentTime.getFullYear()===c?"xdsoft_current":"")+'" data-value="'+c+'">'+(c+I.yearOffset)+"</div>";for(A.children().eq(0).html(H),c=parseInt(I.monthStart,10),H="";c<=parseInt(I.monthEnd,10);c+=1)H+='<div class="xdsoft_option '+(W.currentTime.getMonth()===c?"xdsoft_current":"")+'" data-value="'+c+'">'+I.i18n[R].months[c]+"</div>";Y.children().eq(0).html(H),L(_).trigger("generate.xdsoft")},10),e.stopPropagation()}).on("afterOpen.xdsoft",function(){var e,t,a,n;I.timepicker&&(P.find(".xdsoft_current").length?e=".xdsoft_current":P.find(".xdsoft_init_time").length&&(e=".xdsoft_init_time"),e?(t=u[0].clientHeight,(a=P[0].offsetHeight)-t<(n=P.find(e).index()*I.timeHeightInTimePicker+1)&&(n=a-t),u.trigger("scroll_element.xdsoft_scroller",[parseInt(n,10)/(a-t)])):u.trigger("scroll_element.xdsoft_scroller",[0]))}),n=0,C.on("touchend click.xdsoft","td",function(e){e.stopPropagation(),n+=1;var t=L(this),a=W.currentTime;if(null==a&&(W.currentTime=W.now(),a=W.currentTime),t.hasClass("xdsoft_disabled"))return!1;a.setDate(1),a.setFullYear(t.data("year")),a.setMonth(t.data("month")),a.setDate(t.data("date")),_.trigger("select.xdsoft",[a]),O.val(W.str()),I.onSelectDate&&L.isFunction(I.onSelectDate)&&I.onSelectDate.call(_,W.currentTime,_.data("input"),e),_.data("changed",!0),_.trigger("xchange.xdsoft"),_.trigger("changedatetime.xdsoft"),(1<n||!0===I.closeOnDateSelect||!1===I.closeOnDateSelect&&!I.timepicker)&&!I.inline&&_.trigger("close.xdsoft"),setTimeout(function(){n=0},200)}),P.on("touchstart","div",function(e){this.touchMoved=!1}).on("touchmove","div",l).on("touchend click.xdsoft","div",function(e){if(!this.touchMoved){e.stopPropagation();var t=L(this),a=W.currentTime;if(null==a&&(W.currentTime=W.now(),a=W.currentTime),t.hasClass("xdsoft_disabled"))return!1;a.setHours(t.data("hour")),a.setMinutes(t.data("minute")),_.trigger("select.xdsoft",[a]),_.data("input").val(W.str()),I.onSelectTime&&L.isFunction(I.onSelectTime)&&I.onSelectTime.call(_,W.currentTime,_.data("input"),e),_.data("changed",!0),_.trigger("xchange.xdsoft"),_.trigger("changedatetime.xdsoft"),!0!==I.inline&&!0===I.closeOnTimeSelect&&_.trigger("close.xdsoft")}}),g.on("mousewheel.xdsoft",function(e){return!I.scrollMonth||(e.deltaY<0?W.nextMonth():W.prevMonth(),!1)}),O.on("mousewheel.xdsoft",function(e){return!I.scrollInput||(!I.datepicker&&I.timepicker?(0<=(a=P.find(".xdsoft_current").length?P.find(".xdsoft_current").eq(0).index():0)+e.deltaY&&a+e.deltaY<P.children().length&&(a+=e.deltaY),P.children().eq(a).length&&P.children().eq(a).trigger("mousedown"),!1):I.datepicker&&!I.timepicker?(g.trigger(e,[e.deltaY,e.deltaX,e.deltaY]),O.val&&O.val(W.str()),_.trigger("changedatetime.xdsoft"),!1):void 0)}),_.on("changedatetime.xdsoft",function(e){if(I.onChangeDateTime&&L.isFunction(I.onChangeDateTime)){var t=_.data("input");I.onChangeDateTime.call(_,W.currentTime,t,e),delete I.value,t.trigger("change")}}).on("generate.xdsoft",function(){I.onGenerate&&L.isFunction(I.onGenerate)&&I.onGenerate.call(_,W.currentTime,_.data("input")),s&&(_.trigger("afterOpen.xdsoft"),s=!1)}).on("click.xdsoft",function(e){e.stopPropagation()}),a=0,h=function(e,t){do{if(!(e=e.parentNode)||!1===t(e))break}while("HTML"!==e.nodeName)},r=function(){var e,t,a,n,r,o,i,s,d,u,l,f,c;if(e=(s=_.data("input")).offset(),t=s[0],u="top",a=e.top+t.offsetHeight-1,n=e.left,r="absolute",d=L(I.contentWindow).width(),f=L(I.contentWindow).height(),c=L(I.contentWindow).scrollTop(),I.ownerDocument.documentElement.clientWidth-e.left<g.parent().outerWidth(!0)){var m=g.parent().outerWidth(!0)-t.offsetWidth;n-=m}"rtl"===s.parent().css("direction")&&(n-=_.outerWidth()-s.outerWidth()),I.fixed?(a-=c,n-=L(I.contentWindow).scrollLeft(),r="fixed"):(i=!1,h(t,function(e){return null!==e&&("fixed"===I.contentWindow.getComputedStyle(e).getPropertyValue("position")?!(i=!0):void 0)}),i&&!I.insideParent?(r="fixed",a+_.outerHeight()>f+c?(u="bottom",a=f+c-e.top):a-=c):a+_[0].offsetHeight>f+c&&(a=e.top-_[0].offsetHeight+1),a<0&&(a=0),n+t.offsetWidth>d&&(n=d-t.offsetWidth)),o=_[0],h(o,function(e){if("relative"===I.contentWindow.getComputedStyle(e).getPropertyValue("position")&&d>=e.offsetWidth)return n-=(d-e.offsetWidth)/2,!1}),l={position:r,left:I.insideParent?t.offsetLeft:n,top:"",bottom:""},I.insideParent?l[u]=t.offsetTop+t.offsetHeight:l[u]=a,_.css(l)},_.on("open.xdsoft",function(e){var t=!0;I.onShow&&L.isFunction(I.onShow)&&(t=I.onShow.call(_,W.currentTime,_.data("input"),e)),!1!==t&&(_.show(),r(),L(I.contentWindow).off("resize.xdsoft",r).on("resize.xdsoft",r),I.closeOnWithoutClick&&L([I.ownerDocument.body,I.contentWindow]).on("touchstart mousedown.xdsoft",function e(){_.trigger("close.xdsoft"),L([I.ownerDocument.body,I.contentWindow]).off("touchstart mousedown.xdsoft",e)}))}).on("close.xdsoft",function(e){var t=!0;F.find(".xdsoft_month,.xdsoft_year").find(".xdsoft_select").hide(),I.onClose&&L.isFunction(I.onClose)&&(t=I.onClose.call(_,W.currentTime,_.data("input"),e)),!1===t||I.opened||I.inline||_.hide(),e.stopPropagation()}).on("toggle.xdsoft",function(){_.is(":visible")?_.trigger("close.xdsoft"):_.trigger("open.xdsoft")}).data("input",O),d=0,_.data("xdsoft_datetime",W),_.setOptions(I),W.setCurrentTime(f()),O.data("xdsoft_datetimepicker",_).on("open.xdsoft focusin.xdsoft mousedown.xdsoft touchstart",function(){O.is(":disabled")||O.data("xdsoft_datetimepicker").is(":visible")&&I.closeOnInputClick||I.openOnFocus&&(clearTimeout(d),d=setTimeout(function(){O.is(":disabled")||(s=!0,W.setCurrentTime(f(),!0),I.mask&&c(I),_.trigger("open.xdsoft"))},100))}).on("keydown.xdsoft",function(e){var t,a=e.which;return-1!==[D].indexOf(a)&&I.enterLikeTab?(t=L("input:visible,textarea:visible,button:visible,a:visible"),_.trigger("close.xdsoft"),t.eq(t.index(this)+1).focus(),!1):-1!==[T].indexOf(a)?(_.trigger("close.xdsoft"),!0):void 0}).on("blur.xdsoft",function(){_.trigger("close.xdsoft")})},r=function(e){var t=e.data("xdsoft_datetimepicker");t&&(t.data("xdsoft_datetime",null),t.remove(),e.data("xdsoft_datetimepicker",null).off(".xdsoft"),L(I.contentWindow).off("resize.xdsoft"),L([I.contentWindow,I.ownerDocument.body]).off("mousedown.xdsoft touchstart"),e.unmousewheel&&e.unmousewheel())},L(I.ownerDocument).off("keydown.xdsoftctrl keyup.xdsoftctrl").off("keydown.xdsoftcmd keyup.xdsoftcmd").on("keydown.xdsoftctrl",function(e){e.keyCode===p&&(N=!0)}).on("keyup.xdsoftctrl",function(e){e.keyCode===p&&(N=!1)}).on("keydown.xdsoftcmd",function(e){91===e.keyCode&&!0}).on("keyup.xdsoftcmd",function(e){91===e.keyCode&&!1}),this.each(function(){var t,e=L(this).data("xdsoft_datetimepicker");if(e){if("string"===L.type(H))switch(H){case"show":L(this).select().focus(),e.trigger("open.xdsoft");break;case"hide":e.trigger("close.xdsoft");break;case"toggle":e.trigger("toggle.xdsoft");break;case"destroy":r(L(this));break;case"reset":this.value=this.defaultValue,this.value&&e.data("xdsoft_datetime").isValidDate(E.parseDate(this.value,I.format))||e.data("changed",!1),e.data("xdsoft_datetime").setCurrentTime(this.value);break;case"validate":e.data("input").trigger("blur.xdsoft");break;default:e[H]&&L.isFunction(e[H])&&(o=e[H](a))}else e.setOptions(H);return 0}"string"!==L.type(H)&&(!I.lazyInit||I.open||I.inline?n(L(this)):(t=L(this)).on("open.xdsoft focusin.xdsoft mousedown.xdsoft touchstart",function e(){t.is(":disabled")||t.data("xdsoft_datetimepicker")||(clearTimeout(i),i=setTimeout(function(){t.data("xdsoft_datetimepicker")||n(t),t.off("open.xdsoft focusin.xdsoft mousedown.xdsoft touchstart",e).trigger("open.xdsoft")},100))}))}),o},L.fn.datetimepicker.defaults=s};!function(e){"function"==typeof define&&define.amd?define(["jquery","jquery-mousewheel"],e):"object"==typeof exports?module.exports=e(require("jquery")):e(jQuery)}(datetimepickerFactory),function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e:e(jQuery)}(function(c){var m,h,e=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],t="onwheel"in document||9<=document.documentMode?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],g=Array.prototype.slice;if(c.event.fixHooks)for(var a=e.length;a;)c.event.fixHooks[e[--a]]=c.event.mouseHooks;var p=c.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var e=t.length;e;)this.addEventListener(t[--e],n,!1);else this.onmousewheel=n;c.data(this,"mousewheel-line-height",p.getLineHeight(this)),c.data(this,"mousewheel-page-height",p.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var e=t.length;e;)this.removeEventListener(t[--e],n,!1);else this.onmousewheel=null;c.removeData(this,"mousewheel-line-height"),c.removeData(this,"mousewheel-page-height")},getLineHeight:function(e){var t=c(e),a=t["offsetParent"in c.fn?"offsetParent":"parent"]();return a.length||(a=c("body")),parseInt(a.css("fontSize"),10)||parseInt(t.css("fontSize"),10)||16},getPageHeight:function(e){return c(e).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};function n(e){var t,a=e||window.event,n=g.call(arguments,1),r=0,o=0,i=0,s=0,d=0;if((e=c.event.fix(a)).type="mousewheel","detail"in a&&(i=-1*a.detail),"wheelDelta"in a&&(i=a.wheelDelta),"wheelDeltaY"in a&&(i=a.wheelDeltaY),"wheelDeltaX"in a&&(o=-1*a.wheelDeltaX),"axis"in a&&a.axis===a.HORIZONTAL_AXIS&&(o=-1*i,i=0),r=0===i?o:i,"deltaY"in a&&(r=i=-1*a.deltaY),"deltaX"in a&&(o=a.deltaX,0===i&&(r=-1*o)),0!==i||0!==o){if(1===a.deltaMode){var u=c.data(this,"mousewheel-line-height");r*=u,i*=u,o*=u}else if(2===a.deltaMode){var l=c.data(this,"mousewheel-page-height");r*=l,i*=l,o*=l}if(t=Math.max(Math.abs(i),Math.abs(o)),(!h||t<h)&&y(a,h=t)&&(h/=40),y(a,t)&&(r/=40,o/=40,i/=40),r=Math[1<=r?"floor":"ceil"](r/h),o=Math[1<=o?"floor":"ceil"](o/h),i=Math[1<=i?"floor":"ceil"](i/h),p.settings.normalizeOffset&&this.getBoundingClientRect){var f=this.getBoundingClientRect();s=e.clientX-f.left,d=e.clientY-f.top}return e.deltaX=o,e.deltaY=i,e.deltaFactor=h,e.offsetX=s,e.offsetY=d,e.deltaMode=0,n.unshift(e,r,o,i),m&&clearTimeout(m),m=setTimeout(D,200),(c.event.dispatch||c.event.handle).apply(this,n)}}function D(){h=null}function y(e,t){return p.settings.adjustOldDeltas&&"mousewheel"===e.type&&t%120==0}c.fn.extend({mousewheel:function(e){return e?this.bind("mousewheel",e):this.trigger("mousewheel")},unmousewheel:function(e){return this.unbind("mousewheel",e)}})});;'use strict';

jQuery(document).ready(function($) {
	
	otw_form_init_fields();
});

function otw_form_init_fields(){
	
	jQuery( '.otw-form-select' ).on( 'change',  function(){
		jQuery( this ).parent().find( 'span' ).html( this.options[ this.selectedIndex ].text );
	} );
	
	var startingColour = '000000';
	jQuery( '.otw-color-selector' ).each( function(){ 
		
		var colourPicker = jQuery(this).ColorPicker({
		
		color: startingColour,
			onShow: function (colpkr) {
				jQuery(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				jQuery(colpkr).fadeOut(500);
				jQuery(colourPicker).next( 'input').change();
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				jQuery(colourPicker).children( 'div').css( 'backgroundColor', '#' + hex);
				jQuery(colourPicker).next( 'input').attr( 'value','#' + hex);
				
			}
		
		});
	});
	jQuery( '.otw-form-datetimepicker' ).each( function(){
		jQuery( this ).find( 'input' ).datetimepicker( {format: 'd.m.Y H:i' } );
	} );
	jQuery( '.otw-form-color-picker' ).on( 'change',  function(){
		jQuery( this ).parent( 'div' ).children( 'div' ).children( 'div' ).css( 'backgroundColor', this.value );
	});
	jQuery(  '.otw-form-uploader' ).on( 'change',  function(){
		otw_form_set_upload_preview_image( this.id );
	});
	jQuery(  '.otw-form-uploader-control' ).on( 'click',  function( event ){
	
		var $this = jQuery(this),
		editor = $this.data('editor'),
		
		options = {
			frame:    'post',
			state:    'insert',
			title:    wp.media.view.l10n.addMedia,
			multiple: true
		};
		
		event.preventDefault();
		$this.blur();
		if ( $this.hasClass( 'gallery' ) ) {
			options.state = 'gallery';
			options.title = wp.media.view.l10n.createGalleryTitle;
		}
		wp.media.editor.insert = function( params ){
		
			var matches = null;
			
			if( matches = params.match( /src="([^\"]*)"/ ) ){
				
				jQuery( '#' + editor ).val( matches[1] );
			}else{
				jQuery( '#' + editor ).val( '' );
			}
			jQuery( '#' + editor ).change();
			
			otw_form_set_upload_preview_image( editor );
		}
		
		wp.media.editor.open( editor, options );
	} );
	jQuery(  '.otw-form-uploader-control' ).each( function(){
		otw_form_set_upload_preview_image( jQuery( this ).data( 'editor' ) );
	});
	
	otw_form_init_dynamic_select_fields();
	
	otw_form_init_select_subfields();
	
	otw_form_init_active_items();
};

function otw_form_set_upload_preview_image( element_id ){

	var previewNode = jQuery( '#' + element_id + '-preview' );
	var previewURL  = jQuery( '#' + element_id ).val();
	
	previewNode.css('background-image', 'url("' + previewURL + '")');
	previewNode.css('background-repeat', 'no-repeat');
	
};

function otw_form_init_dynamic_select_fields(){
	
	jQuery(  '.otw-form-dynamic-select' ).each( function(){
	
		if( typeof( wp_url) == 'undefined' ){
			window.wp_url = 'admin-ajax';
		}
		
		var req_url = window.wp_url + '.php?action=otw_item_options_' + jQuery( this ).attr( 'id' ).replace( /\-/, '_' );
		
		var select_params = {};
		
		if( jQuery( this ).hasClass( 'drop_mask' ) ){
			select_params.allowClear = true;
			select_params.multiple = false;
			if( jQuery( '#' + jQuery( this ).attr( 'id' ) + '_json' ).size() && jQuery( '#' + jQuery( this ).attr( 'id' ) + '_json' ).val().length ){
				select_params.data = JSON.parse( jQuery( '#' + jQuery( this ).attr( 'id' ) + '_json' ).val() ).results;
			}
		}else{
			select_params.multiple = true;
			select_params.ajax = {
				url: req_url,
				dataType: 'json',
				data: function ( params ) {
					return {
						otw_search_term: params.term, //search term
						otw_options_limit: 10 // page size
					};
				},
				processResults: function (data, params) {
					return {
						results: data.results
					}
				}
			}
		}
		select_params.templateSelection = function( item ){
			return item.text;
		};
		select_params.templateResult = function( item ){ 
			return item.text;
		};
		
		var otw_form_select2_object = jQuery( this ).select2( select_params );
		
		otw_form_select2_object.on("select2:unselecting", function(e) {
			jQuery(this).data('state', 'unselected');
		} );
		
		otw_form_select2_object.on("select2:open", function(e) {
			
		if( jQuery(this).data('state') === 'unselected') {
				jQuery(this).removeData('state');
				var self = jQuery(this);
				setTimeout(function() {
					self.select2('close');
				}, 1);
			};
		});
		
		var initial_value = otw_form_select2_object.attr( 'data-value' );
		
		if( typeof( select_params.ajax ) != 'undefined' ){
			
			if( ( typeof( initial_value ) == 'string' ) && ( initial_value.length ) ){
				
				jQuery.ajax( select_params.ajax.url , {
					data: {
						otw_options_ids: initial_value
					},
					method: 'get',
					dataType: "json"
				}).done(function(data) {
					
					if( typeof( data.results ) == 'object' ){
						
						for( var cD = 0; cD < data.results.length; cD++ ){
							
							otw_form_select2_object.append( '<option value="' + data.results[ cD ].id + '" selected="selected">' + data.results[ cD ].text + '</option>');
						};
					};
				});
			};
		};
	
	});
	
	jQuery(  '.otw-dynamic-select-wrapper .otw-all-list' ).on( 'change', function(){
	    
		var input_node = jQuery( '#' + this.id.replace( /_allitems$/, '' ) );
		
		if( input_node.size() ){
			
			if( this.checked ){
				input_node.attr( 'disabled', 'disabled' );
			}else{
				input_node.removeAttr( 'disabled' );
			}
		}
	} );
	jQuery(  '.otw-dynamic-select-wrapper .otw-all-list' ).each( function(){
		var input_node = jQuery( '#' + this.id.replace( /_allitems$/, '' ) );
		
		if( input_node.size() ){
			
			if( this.checked ){
				input_node.attr( 'disabled', 'disabled' );
			}else{
				input_node.removeAttr( 'disabled' );
			}
		}
	} );
};

function otw_form_init_select_subfields(){
	
	jQuery( 'select.otw_with_subfield' ).on( 'change',  function(){
		otw_form_init_select_option_field( jQuery( this ) );
	});
	
	jQuery( 'select.otw_with_subfield' ).each( function(){
		otw_form_init_select_option_field( jQuery( this ) );
	});
};

function otw_form_init_active_items(){
	
	var otw_ac_elements = jQuery( '.otw-ac-elements' );
	
	if( otw_ac_elements.size() ){
		
		for( var cE = 0; cE < otw_ac_elements.size(); cE++ ){
			
			var itemElements = jQuery( otw_ac_elements[cE] ).find( '.otw-ac-items' ).val();
			
			if( typeof itemElements !== 'undefined' ){
				
				var splitedItems = itemElements.split(',');
				
				jQuery( splitedItems ).each( function( item, value ){
					
					jQuery( otw_ac_elements[cE] ).find( '.js-ac-items-inactive  > .otw-form-ac-item' ).each( function( miItem, miValue ){
					
						if( jQuery(miValue).data('value') === value ){
							jQuery( otw_ac_elements[cE] ).find( '.js-ac-items-active' ).append( miValue );
						};
					});
				});
			}
			
			jQuery( otw_ac_elements[cE] ).find( '.js-ac-items-active, .js-ac-items-inactive' ).sortable( {
				
				connectWith: ".otw-form-items-box",
				update: function( event, ui ) {
					
					var elementsArray = new Array();
					ui.item.parents( '.otw-ac-elements' ).first().find( '.js-ac-items-active > .otw-form-ac-item' ).each( function( item, value ){
						
						elementsArray.push( jQuery(value).data('value') );
						
						var value_container = ui.item.parents( '.otw-ac-elements' ).first().find( '.otw-ac-items' );
						
						if( value_container.size() ){
							value_container.val( elementsArray );
							value_container.change();
						};
					});
				},
				stop: function( event, ui ) {
					jQuery.event.trigger({
						type: "metaEvent"
					});
				}
			} );
		};
	};
};

function otw_form_init_select_option_field( select ){
	
	var parent = select.closest( 'div.otw-form-control' );
	parent.find( '.otw-form-subfield' ).fadeOut();
	
	var element_name = parent.attr( 'data-name' );
	
	var selected_node = jQuery( '#' + element_name + '_' + select.val() );
	
	if( selected_node.size() ){
		selected_node.fadeIn();
	};
};

function otw_form_set_upload_preview_image( element_id ){

	var previewNode = jQuery( '#' + element_id + '-preview' );
	var previewURL  = jQuery( '#' + element_id ).val();
	
	previewNode.css('background-image', 'url("' + previewURL + '")');
	previewNode.css('background-repeat', 'no-repeat');
	
};;'use strict';

function otw_shortcode_tabs( selectors ){
	
	for( var cS = 0; cS < selectors.size(); cS++ ){
		
		var selector = jQuery( selectors[cS] );
		
		var links = selector.find( 'ul.ui-tabs-nav>li a' );
		
		var active_tab = 0;
		
		for( var cA = 0; cA < links.length; cA++ ){
		
			if( jQuery( links[cA] ).parent().hasClass( 'ui-tabs-active ui-state-active' ) ){
			
				active_tab = cA;
				break;
			}
		}
		for( var cA = 0; cA < links.length; cA++ ){
			
			if( active_tab == cA ){
				jQuery( links[cA] ).parent().addClass( 'ui-tabs-active ui-state-active' );
				selector.find( jQuery( links[cA] ).attr( 'href' ) ).show();
			}else{
				jQuery( links[cA] ).parent().removeClass( 'ui-tabs-active ui-state-active' );
				selector.find( jQuery( links[cA] ).attr( 'href' ) ).hide();
			};
		};
		selector.find( 'ul.ui-tabs-nav>li a' ).on( 'click',  function( event ){
			event.preventDefault();
			jQuery(this).parents( 'li' ).siblings().removeClass("ui-tabs-active ui-state-active");
			jQuery( this ).parents( 'li' ).addClass("ui-tabs-active ui-state-active");
			var tab = jQuery(this).attr("href");
			jQuery( this ).parents( 'li' ).parent().parent().children(".ui-widget-content").not(tab).hide();
			jQuery( this ).parents( 'li' ).parent().parent().children(tab).show();
		} );
	};
	
	var preselected = window.location.hash;
	if( preselected.length ){
		
		preselected = preselected.replace( /^#/, '' );
		
		var tab_links = preselected.split( '&' );
		
		for( var cT = 0; cT < tab_links.length; cT++ ){
			
			var link = jQuery( '.otw-sc-tabs [href=#' + tab_links[ cT ] + ']' );
			
			if( link.length ){
				link.click();
			};
		};
	};
};
function otw_shortcode_content_toggle( selector, closed ){

	selector.off( 'click' ); 
	selector.on( 'click', function (){
		jQuery(this).toggleClass('closed').next('.toggle-content').slideToggle(350);
	});
	closed.next('.toggle-content').hide();
};

function otw_shortcode_accordions( accordions ){

	
	for( var cA = 0; cA < accordions.size(); cA++ ){
		
		var headers  = jQuery( accordions[ cA ] ).find( 'h3.accordion-title' );
		var contents = jQuery( accordions[ cA ] ).find( '.ui-accordion-content' );
		
		var has_open = false;
		
		for( var cH = 0; cH < headers.size(); cH++ ){
		
			if( jQuery( headers[cH] ).hasClass( 'closed' ) || has_open ){
				jQuery( contents[cH] ).hide();
			}else{
				has_open = true;
				jQuery( headers[cH] ).addClass( 'ui-accordion-header-active ui-state-active' );
			};
		};
		
		headers.off( 'click' );
		headers.on( 'click',  function(){
			
			jQuery( this ).parent().find( 'h3.accordion-title' ).not( jQuery( this ) ).removeClass( 'ui-accordion-header-active ui-state-active' );
			jQuery( this ).parent().find( '.ui-accordion-content' ).not( jQuery( this ).next() ).slideUp();
			jQuery( this ).next().slideToggle();
			jQuery( this ).toggleClass( 'ui-accordion-header-active ui-state-active' );
		} );
	};
};

function otw_shortcode_faq( faqs ){
	
	faqs.find('dl > dt').off( 'click' );
	faqs.find('dl > dt').on( 'click', function() {
		jQuery(this).toggleClass('open-faq').next().slideToggle(350);
	});
};

function otw_shortcode_shadow_overlay( selectors ){
	
	selectors.on( 'hover', function() {
		jQuery(this).css({boxShadow: '0 0 20px 0 rgba(0,0,0,0.7) inset'} );
	}, function(){
		jQuery(this).css({boxShadow: '0 0 0 0'});
	});
};
function otw_shortcode_testimonials( selectors ){
	
	selectors.find(".testimonials-prev").on( 'click', function() {
		selectors.find(".testimonials-slide.active").hide().toggleClass('active').otwPrevOrLast().animate({"opacity": "toggle"}).toggleClass('active');
	});
	selectors.find(".testimonials-next").on( 'click', function() {
		selectors.find(".testimonials-slide.active").hide().toggleClass('active').otwNextOrFirst().animate({"opacity": "toggle"}).toggleClass('active');
	});
};

jQuery.fn.otwNextOrFirst = function(selector){
	var next = this.next(selector);
    return (next.length) ? next : this.prevAll(selector).last();
};

jQuery.fn.otwPrevOrLast = function(selector){
	var prev = this.prev(selector);
	return (prev.length) ? prev : this.nextAll(selector).last();
};