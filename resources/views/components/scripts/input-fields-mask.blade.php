<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fieldsWithPhoneMask = document.querySelectorAll('input[data-phone-mask]'),
              fieldsWithMoneyMask = document.querySelectorAll('input[data-money-mask]');

        fieldsWithPhoneMask.forEach(field => Inputmask("+380 (9{2}) 9{3} 9{2} 9{2}").mask(field));
        fieldsWithMoneyMask.forEach(field => Inputmask("integer", {
            groupSeparator: ',',
            autoGroup: true,
            allowMinus: false,
            rightAlign: false,
        }).mask(field));
    });
</script>
