<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout Transparente PagSeguro</title>
</head>
<body>
<form method="POST" action="http://127.0.0.1/api/pagseguro-transparente" id="form">
    @csrf

</form>
<a href="" class="btn-finished">Finalizar Compra</a>
<div class="payments-methods"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{config('pagseguro.url_transparente_js_sandbox')}}"></script>
<script>
    $(function () {
        $('.btn-finished').click(function () {
            setSessionId();
            return false;
        });

        function setSessionId() {
            var data = $('#form').serialize();
            $.ajax({
                url: "{{route('pagseguro.code.transparente')}}",
                method: "POST",
                data: data
            }).done(function (data) {
                PagSeguroDirectPayment.setSessionId(data);
                getPaymentMethods();
            }).fail(function () {
                alert("Request Failed... :-(");
            });
        }

        function getPaymentMethods() {
            PagSeguroDirectPayment.getPaymentMethods({
                success: function (response) {
                    if (response.error == false) {
                        $.each(response.paymentMethods, function (key, value) {
                            $('.payments-methods').append(key + "<br/>");
                        });
                    }
                },
                error: function (response) {
                    console.log(response);
                },
                complete: function (response) {
                    //console.log(response);
                }
            });
        }
    });
</script>
</body>
</html>
