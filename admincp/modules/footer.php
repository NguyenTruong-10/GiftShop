<div>
    <br><br><br><br>
    <br><br><br><br>
    <br><br><br><br>
</div>


<script>

    $(document).ready(function() {

        $('#subtotal').html(<?= $subtotal - $shippingfee ?>)
        total = $('#subtotal').html();
        $('#shippingfee').on('input', function() {
            fee = ($('#shippingfee').val()) ? $('#shippingfee').val() : 0;
            paid = ($('#buyerpaid').val()) ? $('#buyerpaid').val() : 0;
            $('#subtotal').html(parseFloat(total) - parseFloat(fee) + parseFloat(paid));
        });
        $('#buyerpaid').on('input', function() {
            fee = ($('#shippingfee').val()) ? $('#shippingfee').val() : 0;
            paid = ($('#buyerpaid').val()) ? $('#buyerpaid').val() : 0;
            $('#subtotal').html(parseFloat(total) - parseFloat(fee) + parseFloat(paid));
        });
    });
</script>