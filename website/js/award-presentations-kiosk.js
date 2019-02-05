var g_count = 0;
var AwardPoller = {
  current_award_key: null,
  revealed: true,

  query_for_current_award: function() {
    $.ajax('action.php',
           {type: 'GET',
            data: {query: 'award.current'},
            success: function(data) {
              AwardPoller.queue_next_query();
              AwardPoller.process_current_award(data);
            },
            error: function() {
              AwardPoller.queue_next_query();
            }
           });
  },

  queue_next_query: function() {
    setTimeout(function() { AwardPoller.query_for_current_award(); }, 500 /* ms. */);
  },

  parse_award: function(data) {
    var award_xml = data.getElementsByTagName('award')[0];
    if (!award_xml) {
      return false;
    }
    return {key: award_xml.getAttribute('key'),
            reveal: award_xml.getAttribute('reveal') == 'true',
            awardname: award_xml.getAttribute('awardname'),
            carnumber: award_xml.getAttribute('carnumber'),
            carname: award_xml.getAttribute('carname'),
            firstname: award_xml.getAttribute('firstname'),
            lastname: award_xml.getAttribute('lastname'),
            subgroup: award_xml.getAttribute('subgroup'),
            headphoto: award_xml.getAttribute('headphoto'),
            carphoto: award_xml.getAttribute('carphoto')};
  },

  process_current_award: function(data) {
    var award = this.parse_award(data);
    if (!award) {
      $("#firstname").text("--");
      $("#lastname").text("--");
      return;
    }

    if (award.key != this.current_award_key) {
      $(".reveal").hide();

      $("#awardname").text(award.awardname);
      $("#carnumber").text(award.carnumber);
      $("#firstname").text(award.firstname);
      $("#lastname").text(award.lastname);
      if (award.carname && award.carname.length > 0) {
        $("#carname").text(award.carname);
        $("#carname").css('display', 'block');
      } else {
        $("#carname").css('display', 'none');
      }
      if (award.subgroup && award.subgroup.length > 0) {
        $("#subgroup").text(award.subgroup);
        $("#subgroup").css('display', 'block');
      } else {
        $("#subgroup").css('display', 'none');
      }
      // Need to account for the height of the award-racer text, even though
      // it's presently hidden.
      var previousCss  = $("#award-racer-text").attr("style");
      $("#award-racer-text")
        .css({
          position:   'absolute',
          visibility: 'hidden',
          display:    'block'
        });
      var textHeight = $("#award-racer-text").height();
      $("#award-racer-text").attr("style", previousCss ? previousCss : "");

      // TODO Literal 10 vaguely accounts for margins, but is basically just a guess.
      var maxPhotoHeight = $(window).height() - ($("#photos").offset().top + textHeight) - 10;

      $("#headphoto").empty();
      $("#headphoto").css('width', $(window).width() / 2 - 10);
      if (award.headphoto && award.headphoto.length > 0) {
        $("#headphoto").append("<img src=\"" + award.headphoto + "\"/>");
        $("#headphoto img").css('max-height', maxPhotoHeight);
      }
      $("#carphoto").empty();
      $("#carphoto").css('width', $(window).width() / 2 - 10);
      if (award.carphoto && award.carphoto.length > 0) {
        $("#carphoto").append("<img src=\"" + award.carphoto + "\"/>");
        $("#carphoto img").css('max-height', maxPhotoHeight);
      }
      this.current_award_key = award.key;
      this.revealed = false;
    }

    if (!award.reveal) {
      $(".reveal").hide();

      stopConfetti();

      if (g_count == 0) {
        console.log("Hiding!");
        console.log(award);
        ++g_count;
      }
    } else if (!this.revealed) {
      $(".reveal").fadeIn(1000);
      setTimeout(function() { startConfetti(); }, 500);
      setTimeout(function() { stopConfetti(); }, 20500);
    }
    this.revealed = award.reveal;
  }
}

$(function() { AwardPoller.queue_next_query(); });

