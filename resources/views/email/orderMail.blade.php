@extends('layouts.mail')
@section('content')

<div class="email-title">Order Confirmation</div>
<div class="thank-title">Hi, Bibek - Thanks for your order we hope you enjoyed shopping <br>us</div>
<hr>
<div style="display:flex; justify-content:space-between; align-items:center">
    <div style="min-height: 100px;">
        <h3>Delivery Address</h3>
        <p>bouddha-6 ,Nayabasti kathmandy nepal</p>
    </div>
    <hr>
    <div style="min-height: 100px;">
        <h3>Order Date</h3>
        <p>{{now()}}</p>
    </div>
</div>
<hr>
<div style="min-height: 100px;">
    <h3>What you order..</h3>
    <p style="margin-top: 20px;"> <strong>Order Id :</strong> #8888888888</p>
</div>
<div style="min-height: 100px;">
<table >
    <tr>
        <th>#</th>
        <th>Item</th>
        <th>Variant</th>
        <th>QTY</th>
        <th>Price</th>
    </tr>
    <tr>
        <td>1</td>
        <td>new product</td>
        <td>red</td>
        <td>12</td>
        <td>1299</td>
    </tr>
    <tr>
        <td>1</td>
        <td>new product</td>
        <td>red</td>
        <td>12</td>
        <td>1299</td>
    </tr>
    <tr>
        <td>1</td>
        <td>new product</td>
        <td>red</td>
        <td>12</td>
        <td>1299</td>
    </tr>
    <tr>
        
        <td colspan="3"></td>
        <td>Sub Total</td>
        <td>20,00,00</td>
    </tr>
    <tr>
        
        <td colspan="3"></td>
        <td>Delivery Charge</td>
        <td>0</td>
    </tr>
    <tr>
        
        <td colspan="3"></td>
        <td>Grand Total</td>
        <td>30,00,000</td>
    </tr>

</table>

</div>

@endsection
