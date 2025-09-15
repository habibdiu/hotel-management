// npm package: jquery-tags-input
// github link: https://github.com/xoxco/jQuery-Tags-Input

$(function() {
  'use strict';

  $('#tags').tagsInput({
    'width': '100%',
    'height': '75%',
    'interactive': true,
    'defaultText': 'Press Enter',
    'removeWithBackspace': true,
    'minChars': 0,
    'maxChars': 50,
    'placeholderColor': '#666666'
  });

});
