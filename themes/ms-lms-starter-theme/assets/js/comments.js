"use strict";

document.addEventListener('DOMContentLoaded', function () {
  var commentForm = document.getElementById('commentform');
  if (!commentForm) return;
  var fields = [{
    name: 'author'
  }, {
    name: 'email',
    validate: validateEmail
  }, {
    name: 'comment'
  }];
  commentForm.addEventListener('submit', function (event) {
    var valid = true;
    fields.forEach(function (field) {
      var input = document.querySelector("[name=\"".concat(field.name, "\"]"));
      if (!input) return;
      var error = input.parentNode.querySelector('span');
      if (error) {
        error.style.display = 'none';
        error.classList.remove('error');
      }
      if (input.value.trim() === '') {
        if (error) {
          error.style.display = 'inline';
          error.classList.add('error');
        }
        valid = false;
      } else if (field.validate && !field.validate(input.value)) {
        if (error) {
          error.textContent = 'Please enter a valid email';
          error.style.display = 'inline';
          error.classList.add('error');
        }
        valid = false;
      }
    });
    if (!valid) {
      event.preventDefault();
    }
  });
  function validateEmail(email) {
    var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(String(email).toLowerCase());
  }
});