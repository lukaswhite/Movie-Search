$(function () {
  $('input[name="q"]').autocomplete({
    source: '/autocomplete',
    minLength: 2
  });
});

