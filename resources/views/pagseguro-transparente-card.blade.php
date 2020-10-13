<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PagSeguro Transparente Cartão</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Pagar com cartão de crédito</h1>

    {!! Form::open(['id' => 'form']) !!}
    <div class="form-group">
        <label>Número do cartão</label>
        {!! Form::text('cardNumber', null, ['class' => 'form-control', 'Placeholder' => 'Número do cartão', 'required']) !!}
    </div>
    <div class="form-group">
        <label>Mês de expiração</label>
        {!! Form::text('cardExpiryMonth', null, ['class' => 'form-control', 'Placeholder' => 'Mês de expiração', 'required']) !!}
    </div>
    <div class="form-group">
        <label>Ano de Expiração</label>
        {!! Form::text('cardExpiryYear', null, ['class' => 'form-control', 'Placeholder' => 'Ano de Expiração', 'required']) !!}
    </div>
    <div class="form-group">
        <label>Código de Segurança (3 números)</label>
        {!! Form::text('cardCVV', null, ['class' => 'form-control', 'Placeholder' => 'Código de Segurança', 'required']) !!}
    </div>
    <div class="form-group">
        {!! Form::hidden('cardName', null) !!}
        {!! Form::hidden('cardToken', null) !!}
        <button type="submit" class="btn btn-default btn-buy">Enviar Agora</button>
    </div>
    {!! Form::close() !!}
    <div class="preloader" style="display: none;">Preloader...</div>
    <div class="message" style="display: none;"></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{config('pagseguro.url_transparente_js_sandbox')}}"></script>
<script>
    $(function () {
        setSessionId();
        $('#form').submit(function () {
            getBrand();
            startPreloader("Enviando dados...");
            return false;
        });
    });

    function setSessionId() {
        var data = $('#form').serialize();
        $.ajax({
            url: "{{route('pagseguro.code.transparente')}}",
            method: "POST",
            data: data,
            beforeSend: startPreloader("Carregando a sessão..., aguarde")
        }).done(function (data) {
            PagSeguroDirectPayment.setSessionId(data);
            //getPaymentMethods();
            //paymentBillet();
        }).fail(function () {
            alert("Request Failed... :-(");
        }).always(function () {
            endPreloader();
        });
    }

    function getBrand() {
        PagSeguroDirectPayment.getBrand({
            cardBin: $('input[name=cardNumber]').val().replace(/ /g, ''),
            success: function (response) {
                console.log(response);
                $("input[name=cardName]").val(response.brand.name);
                createCredCardToken();
            },
            error: function (response) {
                console.log(response);
            },
            complete: function (response) {
                //console.log(response);
            }
        });
    }

    function createTransactionCard() {
        var senderHash = PagSeguroDirectPayment.getSenderHash();
        var data = $('#form').serialize() + "&senderHash = " + senderHash;
        $.ajax({
            url: "{{route('pagseguro.card.transparente')}}",
            method: "POST",
            data: data,
            beforeSend: startPreloader("Realizando o pagamento com o cartão")
        }).done(function (code) {
            $(".message").html("Código da transaçâo: " + code);
            $(".message").show();
        }).fail(function () {
            alert("Request Failed... :-(");
        }).always(function () {
            endPreloader();
        });
    }

    function createCredCardToken() {
        PagSeguroDirectPayment.createCardToken({
            cardNumber: $('input[name=cardNumber]').val().replace(/ /g, ''),
            brand: $('input[name=cardName]').val(),
            cvv: $('input[name=cardCVV]').val(),
            expirationMonth: $('input[name=cardExpiryMonth]').val(),
            expirationYear: $('input[name=cardExpiryYear]').val(),
            success: function (response) {
                console.log(response);
                $("input[name=cardToken]").val(response.card.token);
                createTransactionCard();
            },
            error: function (response) {
                console.log(response);
                console.log(expirationMounth);
            },
            complete: function (response) {
                //console.log(response);
                endPreloader();
            }
        });
    }

    function startPreloader(msgPreloader) {
        if (msgPreloader !== "") $('.preloader').html(msgPreloader)
        $('.preloader').show();
        $('.btn-buy').addClass('disabled');
    }

    function endPreloader() {
        $('.preloader').hide();
        $('.btn-buy').removeClass('disabled');
    }
</script>
</body>
</html>
