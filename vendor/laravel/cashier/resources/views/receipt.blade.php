<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <title>Invoice</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        
        }
        body {
            background: #fff;
            background-image: none;
            font-family : "Helvetica";
            font-size: 80%;
        }
        address{
            margin-top:15px;
        }
        h2 {
            font-size:28px;
            color:#cccccc;
        }
        .container {
            padding-top:30px;
        }
        .invoice-head td {
            padding:  8px 0;
        }
        .invoice-body{
            background-color:transparent;
        }
        .logo {
            padding-bottom: 10px;
        }
        .table {margin-left:-2px;}
        .table th {
            vertical-align: bottom;
            font-weight: bold;
            padding:  8px 0;
            line-height: 20px;
            text-align: left;
        }
        .table td {
            padding:  8px 0;
            line-height: 20px;
            text-align: left;
            vertical-align: top;
            border-top: 1px solid #dddddd;
        }
        .well {
            margin-top: 15px;
        }
        .red{font-weight:300;
            color: #0088ff;}
        .blue{font-weight:300;
            color: #ff0000;}
        .data{margin-left :-150px;}
    </style>
</head>

<body>
<div class="container">
    <table style="margin-left: auto; margin-right: auto" width="540">
        <tr>
            
            <!-- Organization Name / Image -->
            <td >
                <span style="font-size:56px;margin-left:-3px;"class="red">Identi</span><span style="font-size:56px;"class="blue">Sheep</span><br>
                <span style="color:#bbbbbb;font-size:10px;">Messrs J & J Corley, The Peels, Harbottle, Morpeth. NE65 7DL</span><br>
                @if (isset($vat))
                    <p style = "color:#888888">
                        {{ $vat }}
                    </p>
                @endif
            </td>
        </tr>
        <tr valign="top">
            <td style="font-size:28px;color:#aaaaaa;">
                Receipt
            </td>
    
        <!-- Organization Name / Date -->
            <td>
                <br><br>
                <strong>Date:</strong> {{ $invoice->date()->toFormattedDateString('d M Y') }}
                <br><br>
                <strong>To:</strong> {{ $user->business ?: $user->name }}
                <br>
                {{ $user->address }}
            </td>
        </tr>
        <tr valign="top">
            <!-- Organization Details -->
            <td style="font-size:9px;">
                
                @if (isset($street))
                    {{ $street }}<br>
                @endif
                @if (isset($location))
                    {{ $location }}<br>
                @endif
                @if (isset($phone))
                    <strong>T</strong> {{ $phone }}<br>
                @endif
                @if (isset($url))
                    <a href="{{ $url }}">{{ $url }}</a>
                @endif
            </td>
            <td>
                <!-- Invoice Info -->
                <p>
                    <strong>Product:</strong> {{ $product }}<br><br>
                    <strong>Invoice No:</strong> {{ $id or $invoice->id }}<br>
                </p>

                <br><br>

                <!-- Invoice Table -->
                <table width="100%" class="table data" border="0">
                    <tr>
                        <th align="left">Description</th>
                        <th align="right">Date</th>
                        <th align="right">Amount</th>
                    </tr>

                    <!-- Existing Balance -->
                    <tr>
                        <td>Starting Balance</td>
                        <td>&nbsp;</td>
                        <td>{{ $invoice->startingBalance() }}</td>
                    </tr>

                    <!-- Display The Invoice Items -->
                    @foreach ($invoice->invoiceItems() as $item)
                        <tr>
                            <td colspan="2">{{ $item->description }}</td>
                            <td>{{ $item->total() }}</td>
                        </tr>
                    @endforeach

                    <!-- Display The Subscriptions -->
                    @foreach ($invoice->subscriptions() as $subscription)
                        <tr>
                            <td>Subscription </td><!--$subscription->quantity -->
                            <td>
                                {{ $subscription->startDateAsCarbon()->formatLocalized('%B %e, %Y') }} -
                                {{ $subscription->endDateAsCarbon()->formatLocalized('%B %e, %Y') }}
                            </td>
                            <td>{{ $subscription->total() }}</td>
                        </tr>
                    @endforeach

                    <!-- Display The Discount -->
                    @if ($invoice->hasDiscount())
                        <tr>
                            @if ($invoice->discountIsPercentage())
                                <td>{{ $invoice->coupon() }} ({{ $invoice->percentOff() }}% Off)</td>
                            @else
                                <td>{{ $invoice->coupon() }} ({{ $invoice->amountOff() }} Off)</td>
                            @endif
                            <td>&nbsp;</td>
                            <td>-{{ $invoice->discount() }}</td>
                        </tr>
                    @endif

                    <!-- Display The Tax Amount -->
                    @if ($invoice->tax_percent)
                        <tr>
                            <td>V.A.T. ({{ $invoice->tax_percent }}%)</td>
                            <td>&nbsp;</td>
                            <td>{{ Laravel\Cashier\Cashier::formatAmount($invoice->tax) }}</td>
                        </tr>
                    @endif

                    <!-- Display The Final Total -->
                    <tr style="border-top:2px solid #000;">
                        <td>&nbsp;</td>
                        <td style="text-align: right;"><strong>Total&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                        <td><strong>{{ $invoice->total() }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
