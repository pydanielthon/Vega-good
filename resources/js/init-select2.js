jQuery(document).ready(function () {
$('#select-spec').select2({
    width: '100%',
    language: {
        noResults: function (params) {
            return "Brak wyników";
          }

        },

    templateResult: function (data, container) {
        if (data.element) {
          $(container).addClass($(data.element).attr("class"));
        }
        return data.text;
      }
})
$('.stest').select2({
    width: '100%',
    language: {
        noResults: function (params) {
            return "Brak wyników";
          }

        },

    templateResult: function (data, container) {
        if (data.element) {
          $(container).addClass($(data.element).attr("class"));
        }
        return data.text;
      }
})
$('.edit-summary #worker').select2({
    width: '100%',
    language: {
        noResults: function (params) {
            return "Brak wyników";
          }

        },

    templateResult: function (data, container) {
        if (data.element) {
          $(container).addClass($(data.element).attr("class"));
        }
        return data.text;
      }
})
$('.edit-summary #contrahent').select2({
    width: '100%',
    language: {
        noResults: function (params) {
            return "Brak wyników";
          }

        },

    templateResult: function (data, container) {
        if (data.element) {
          $(container).addClass($(data.element).attr("class"));
        }
        return data.text;
      }
})
$('.hours-container #inputContrahentName').select2({
    width: '100%',
    language: {
        noResults: function (params) {
            return "Brak wyników";
          }

        },

    templateResult: function (data, container) {
        if (data.element) {
          $(container).addClass($(data.element).attr("class"));
        }
        return data.text;
      }
})
$('#exampleFormControlSelect2').select2({
    width: '100%',
    language: {
        noResults: function (params) {
            return "Brak wyników";
          }

        },

    templateResult: function (data, container) {
        if (data.element) {
          $(container).addClass($(data.element).attr("class"));
        }
        return data.text;
      }
})
$('#exampleFormControlSelect22').select2({
    width: '100%',
    language: {
        noResults: function (params) {
            return "Brak wyników";
          }

        },

    templateResult: function (data, container) {
        if (data.element) {
          $(container).addClass($(data.element).attr("class"));
        }
        return data.text;
      }

})

})
