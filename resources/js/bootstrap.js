import axios from 'axios';
import jQuery from 'jquery';
import Swal from 'sweetalert2';

// AXIOS
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// JQUERY
window.$ = window.jQuery = jQuery;

// SWEETALERT2
window.Swal = Swal;
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
}); 

// CUSTOM FUNCTION
function formatNumber($el) {
    $el.on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
}

function formatCurrency($el) {
    $el.on('input', function () {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            this.value = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        } else {
            this.value = '';
        }
    });

    $('form').on('submit', function () {
        let raw = $el.val().replace(/[^0-9]/g, '');
        $el.val(raw);
    });
}

window.initInputNumberFormatter = function () {
    $('.only-number').each(function () {
        formatNumber($(this));
    });
};

window.initInputCurrencyFormatter = function() {
    $('.currency').each(function () {
        formatCurrency($(this));
    });
}

window.confirmDelete = function(url, rowSelector = null) {
    Swal.fire({
        title: "Are you sure?",
        text: "This data will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Delete"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "DELETE",
                success: function (response) {
                    Swal.fire({
                        title: "Deleted",
                        text: "Data deleted successfully.",
                        icon: "success",
                        timer: 1000,
                        showConfirmButton: false
                    });

                    if (rowSelector) {
                        $(rowSelector).fadeOut(300, function () {
                            $(this).remove();
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire("Failed", "Failed to delete data.", "error");
                }
            });
        }
    });
};
