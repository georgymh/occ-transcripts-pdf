$('input#allow').on('click', function() {
  $('.checkbox').css('color', 'black');
});

$('#transcripts-form').on('submit', function(e) {
  e.preventDefault();

  if (! $('input#allow').is(':checked')) {
    $('.checkbox').css('color', '#A52F2D');
    return false;
  }

  $.ajax({
    method: "POST",
    url: "getMyTranscripts.php",
    data: {
      user: $('#user').val(),
      pass: $('#pass').val(),
      action: "transcripts"
    },
    beforeSend: function() {
      $('.checkbox').css('color', 'black');
      $('#spinner').fadeIn();
      $('#generate').prop('disabled', true);
      hideErrorAlert();
    },
    success: function(obj) {
      if (obj != 0) {
        $('#html').text(obj);
        showDownloadButton();
      } else {
        showErrorAlert('We could not connect to your MyOCC account.<br> <b>Please verify your username and password by logging in normally and try again.</b>');
        $('#generate').prop('disabled', false);
        $('#spinner').hide();
      }
    },
    error: function() {
      showErrorAlert('An error occurred while accessing your MyOCC account. Please refresh and try again.');
      $('#generate').prop('disabled', false);
      $('#spinner').hide();
    }
  });

});

function showDownloadButton() {
  // Hide form and show download button.
  $('#transcripts-form').fadeOut(function() {
    $('#download-transcripts').fadeIn();
  });
}

function showErrorAlert(msg) {
  $('#alert-box').show();
  $('#alert-box').html(msg);
}

function hideErrorAlert() {
  $('#alert-box').hide();
  $('#alert-box').html('');
}
