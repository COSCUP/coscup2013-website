$(window).bind('pageload', function(){
  var program_data = null;
  var navHeight = $('#navTab').get(0).offsetTop;
  /* Program fixed Nav */
  $(window).scroll(function () {
    if ($(this).scrollTop() > navHeight) {
      $('#navTab').addClass("floatTab");
    } else {
      $('#navTab').removeClass("floatTab");
    }
  });

  function getDetailData(callback) {
    $.ajax({
      dataType: "json",
      url: "/2013/api/program/program.json.js",
      success: function (data) {
        console.log("ajax");
        program_data = data;
        callback(data);
      }
    });
  }

  $('#lock_background').on('click', function(evt) {
    // the user is not clicking on the background.
    if (this.id !== evt.target.id)
      return;
    $(this).removeClass('show');
  });

  $('.program').each(function() {
    $(this).on('click', function(event) {
      var id = $(this).data('id');
      function displayDetails(data) {
        var program = data.program[id];

        $('#program_detail').empty();
        $('#program_detail').append($('<div class="metadata"><div>')
          .append($('<div></div>').addClass('track_tag colorTag-' + program.type))
          .append($('<div></div>').addClass('place').html(data.room[program.room]['zh-tw']))
          .append($('<div></div>').addClass('community').html(data.community[program.community]))
          .append($('<div></div>').addClass('title').html(program.name))
          .append($('<div></div>').addClass('name').html(program.speaker))
          .append($('<div></div>').addClass('text').html(program.speakerTitle))
          .append($('<div></div>').addClass('time').html(getTime(program.from) + ' - ' + getTime(program.to))));

        $('#program_detail').append(
          $('<div></div>').addClass('bio').html(program.bio)).append(
          $('<div></div>').addClass('abstract').html(program.abstract));
        $('#lock_background').addClass('show');
      }

      if (!program_data) {
        getDetailData(displayDetails);
      } else {
        displayDetails(program_data);
      }
        
    });
  });

  function getTime(ts) {
    var date = new Date(ts*1000);
    var hour = date.getHours();
    hour = (hour < 10)? '0' + hour : hour.toString();
    var min = date.getMinutes();
    min = (min < 10)? '0' + min : min.toString();
    return hour + ':' + min;
  }
});

