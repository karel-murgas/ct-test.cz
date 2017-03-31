(function(window) {
  window.tout = function() {
    var uuidCookie,
        uuid,
        firstTime,
        fpBlocked,
        gaBlocked,
        gtmBlocked;
    
    uuidCookie = window.tout.getCookie('tout_fp_uuid');
    
    if (typeof uuidCookie !== 'undefined') {
      uuid = uuidCookie;
      firstTime = false;
    } else {
      uuid = window.tout._generateUuid();
      firstTime = true;
    }

    window.tout.setCookie('tout_fp_uuid', uuid, 1825);
    
    window.tout.setCookie('tout_fp_test', 'OK');
    fpBlocked = (typeof window.tout.getCookie('tout_fp_test') === 'undefined');

    gaBlocked = !(window.ga && ga.create)
		gtmBlocked = !(window.google_tag_manager)

    window.tout._track(uuid, firstTime, fpBlocked, gaBlocked, gtmBlocked);
  };
  
  /**
   * Generate UUID according to RFC4122 version 4
   * @type {Function}
   * @private
   */
  window.tout._generateUuid = function () {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
      return v.toString(16);
    });
  }
  
  /**
   * Generate UUID according to RFC4122 version 4
   * @type {Function}
   * @private
   */
  window.tout._track = function (uuid, firstTime, fpBlocked, gaBlocked, gtmBlocked) {
    var img,
        src;
        
    img = document.createElement('img');
    img.width = 1;
    img.height = 1;
    src = 'http://dmp.417.cz/record.gif';
    src += '?fp_uuid=' + uuid;
    src += '&firstTime=' + firstTime;
    src += '&fpBlocked=' + fpBlocked;
    src += '&gaBlocked=' + gaBlocked;
    src += '&gtmBlocked=' + gtmBlocked;
    src += '&jsBlocked=' + false;
    img.src = src;
    tout._debug(src);
  }
  
  /**
   * Debug
   * @type {Function}
   * @private
   */
  window.tout._debug = (function () {
    var result;
    if (! window.console || ! console.log) {
      result = function(){};
    } else {
      result = Function.prototype.bind.call(console.log, console);
    }
    return result;
  })();
  
  /**
   * Get First Party Cookie
   * @method getCookie
   * @public
   * @param name {String}
   */
  window.tout.getCookie = function(name) {
    var ca;
    ca = document.cookie.split(';');
    name = name + '=';
    for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length,c.length);
      }
    }
    return undefined;
  };
  
  /**
   * Set First Party Cookie
   * @method _process
   * @public
   * @param name {String}
   * @param value {String}
   * @param days {Number}
   */
  window.tout.setCookie = function(name, value, days) {
    var date,
      expires;

    if (days) {
      date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = 'expires=' + date.toUTCString() + '; ';
    } else {
      expires = '';
    }
    document.cookie = name + '=' + value + '; ' + expires + '; path=/';
  };
  
  window.addEventListener('load', function(){setTimeout(tout,1000);}, false);
  
})(window);
