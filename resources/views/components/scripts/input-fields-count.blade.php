<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputGroupFields = document.querySelectorAll('.input-field-count');
        inputGroupFields.forEach( inputGroup => {
            const inputField =  inputGroup.querySelector('.form-control'),
                count = inputGroup.querySelector('span[data-count]'),
                countTotal = parseInt(inputGroup.querySelector('span[data-total]').innerHTML);
            inputField.addEventListener('input', (e) => {
                count.innerHTML = e.target.value.length;
                e.target.value.length > countTotal
                    ? count.classList.add('text-danger')
                    : count.classList.remove('text-danger');
            });
        });
    });
</script>
