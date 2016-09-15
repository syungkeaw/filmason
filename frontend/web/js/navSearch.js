var reTitleUrl = function(results){
  jQuery.each( results, function( i, val ) {
    results[i].title_url = val.original_title
      .replace(/(?![ก-๙])[\W]/g, '-')
      .replace(/-{2,}/g, '-')
      .toLowerCase();
  });
  return results;
};

$(document).ready(function() {
  var engine, remoteHost, template, empty;

  $.support.cors = true;

  remoteHost = 'http://api.themoviedb.org/3/search/movie?api_key=3b03c053f34ff11cfdc0d26b06ac95d1';
  template = Handlebars.compile($("#result-template").html());
  empty = Handlebars.compile($("#empty-template").html());

  engine = new Bloodhound({
    identify: function(o) { return o.id; },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('id', 'title'),
    dupDetector: function(a, b) { return a.id === b.id; },
    prefetch: {
      url: 'http://api.themoviedb.org/3/movie/now_playing?api_key=3b03c053f34ff11cfdc0d26b06ac95d1',
      filter: function(parsedResponse){
        // filter the returned data
        return reTitleUrl(parsedResponse.results);
      }
    },
    remote: {
      url: remoteHost + '&query=%QUERY',
      wildcard: '%QUERY',
      filter: function (parsedResponse) {
          // parsedResponse is the array returned from your backend
          // results = $.grep( parsedResponse.results, function( n, i ) {
          //   return n.media_type == 'movie';
          // });
          // console.log(parsedResponse.results);
          // do whatever processing you need here
          return reTitleUrl(parsedResponse.results);
      }
    }
  });

  // ensure default users are read on initialization
  engine.get('278924', '249070', '203801');

  function engineWithDefaults(q, sync, async) {
    if (q === '') {
      sync(engine.get('278924', '249070', '203801'));
      async([]);
    }

    else {
      engine.search(q, sync, async);
    }
  }

  $('.typeahead').typeahead({
    hint: $('.Typeahead-hint'),
    menu: $('.Typeahead-menu'),
    minLength: 0,
    highlight: true,
    classNames: {
      open: 'is-open',
      empty: 'is-empty',
      cursor: 'is-active',
      suggestion: 'Typeahead-suggestion',
      selectable: 'Typeahead-selectable'
    }
  }, {
    source: engineWithDefaults,
    displayKey: 'title',
    templates: {
      suggestion: template,
      empty: empty
    }
  })
  .on('typeahead:asyncrequest', function() {
    $('.Typeahead-spinner').show();
  })
  .on('typeahead:asynccancel typeahead:asyncreceive', function() {
    $('.Typeahead-spinner').hide();
  });

});

