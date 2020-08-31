$(function() {
  $("form[name='add_form']").validate({
    rules: {
      requestor: "required",
      tool_name: "required",
      description: "required",
      fix_confirm: {
        required: true,
        number: true
      }
    },
    messages: {
      requestor: "Please enter a requestor",
      tool_name: "Please enter a tool name.",
      description: "Please enter a description.",
      fix_confirm: {
        required: "Please enter a fix confirm.",
        number: "Fix confirm should be a number."
      }
    }
  });
});
