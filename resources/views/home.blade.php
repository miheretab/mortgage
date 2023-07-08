<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="/js/jquery.number.format.js"></script>

        <title>Mortgage Calculator</title>
        <style>
        .header {
            min-width: 200px;
        }
        th {
            padding-right: 12px;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        input {
            padding: 5px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        button {
            padding: 10px 70px;
            cursor: pointer;
            background: #9097b8;
            color: #fff;
            font-size: 15px;
            margin: 5px 0px 10px;
            border-radius: 10px;
        }
        table {
            margin: auto;
        }
        table, td {
          border: 1px solid white;
          border-collapse: collapse;
          text-align: center;
        }
        td {
            background-color: #4db0b0;
            padding: 4px;
            color: #ffff;
        }
        .error {
            color: #f00;
        }
        </style>

    </head>
    <body class="antialiased">
        <h3>Mortgage Calculator</h3>
        <table>
            <thead>
                <tr>
                    <th class="text-right">Loan Amount (AED):</th> <th class="text-left"><input id="amount" value="10000"/></th>
                    <th class="text-right">Monthly Payment:</th> <th class="text-left" id="monthly-payment"></th>
                </tr>
                <tr>
                    <th class="text-right">Loan Term (In Years):</th> <th class="text-left"><input id="term" value="10"/></th>
                    <th class="text-right">Effective Interest Rate:</th> <th class="text-left" id="eir"></th>
                </tr>
                <tr>
                    <th class="text-right">Annual Interest Rate (%):</th> <th class="text-left"><input id="interest" value="5"/></th>
                    <th class="text-right">Total Interest:</th> <th class="text-left" id="total-interest"></th>
                </tr>
                <tr>
                    <th class="text-right">Extra Monthly Payment (AED):</th> <th class="text-left"><input id="extra" value="0"/></th>
                    <th class="text-right">Shortened Term:</th> <th class="text-left" id="shortened-term"></th>
                </tr>
                <tr><th></th> <th class="text-left"><button id="generate">Generate</button></th> <th></th> <th class="text-left"><button id="save">Save</button></th></tr>
                <tr><th class="header">Month Number</th> <th class="header">Principal</th> <th class="header">Interest</th> <th class="header">Remaining Balance</th></tr>
            </thead>
            <tbody id="schedule">
            </tbody>
        </table>
    </body>

    <script>
        //ajax request on 
        $(function(){
            $('#generate').click(function() {
                var data = {
                    amount: $('#amount').val(),
                    interest: $('#interest').val(),
                    term: $('#term').val(),
                    extra: $('#extra').val()
                };
                callUrl(data);
            });

            $('#save').click(function() {
                var data = {
                    amount: $('#amount').val(),
                    interest: $('#interest').val(),
                    term: $('#term').val(),
                    extra: $('#extra').val(),
                    save: true
                };
                callUrl(data);
            });

            function callUrl(data) {
                $('.error').remove();

                $.ajax({
                    method: "POST",
                    url: "{{url('api/calculate-mortgage')}}",
                    data: data,
                    success: function(r) {
                        if (r.monthlyPayment) {
                            $('#monthly-payment').text($.numberFormat(r.monthlyPayment, {prefix:'AED '}));
                        }
                        if (r.effectiveInterestRate) {
                            $('#eir').text($.numberFormat(r.effectiveInterestRate, {suffix:' %'}));
                        }
                        if (r.totalInterest) {
                            $('#total-interest').text($.numberFormat(r.totalInterest, {prefix:'AED '}));
                        }
                        if (r.totalTerm) {
                            $('#shortened-term').text(r.totalTerm + " months");
                        }
                        if (r.schedule) {
                            $(r.schedule).each(function(k, d) {
                                var row = "<tr><td>" + (k+1) + "</td> ";
                                row += "<td>" + $.numberFormat(d.principal, {prefix:'AED '}) + "</td> ";
                                row += "<td>" + $.numberFormat(d.interest, {prefix:'AED '}) + "</td> ";
                                row += "<td>" + $.numberFormat(d.balance, {prefix:'AED '}) + "</td></tr>";
                                $('#schedule').append(row);
                            });
                        }
                    }, error: function(r) {
                        if (r.status == 422) {
                            if (r.responseJSON.errors.amount) {
                                $('#amount').before("<span class='error'>" + r.responseJSON.errors.amount[0] + "</span>");
                            }
                            if (r.responseJSON.errors.interest) {
                                $('#interest').before("<span class='error'>" + r.responseJSON.errors.interest[0] + "</span>");
                            }
                            if (r.responseJSON.errors.term) {
                                $('#term').before("<span class='error'>" + r.responseJSON.errors.term[0] + "</span>");
                            }
                            if (r.responseJSON.errors.extra) {
                                $('#extra').before("<span class='error'>" + r.responseJSON.errors.extra[0] + "</span>");
                            }
                        }
                    }
                });
            }
        });
    </script>
</html>
