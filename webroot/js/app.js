/**
 * Application Library
 */
$(function () {

  // Tooltip
  $('[data-toggle="tooltip"]').tooltip();

  // CodeMirror
  $('textarea[data-code-mirror]').each(function() {
    this.__cm__ = CodeMirror.fromTextArea(this, Object.assign({
      lineNumbers: true,
      mode: "htmlmixed",
      extraKeys: {
        "Ctrl-F": function (cm) {
          cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function (cm) {
          cm.setOption("fullScreen", false);
        }
      }
    }, $(this).data('codeMirror'))).on('change', function (cm) {
      $(cm.getTextArea()).val(cm.getValue()).trigger('input');
    });
  });

});
