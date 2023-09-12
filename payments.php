<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Invoice</title>
</head>

<body>
    <div style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
        <div style="max-width: 650px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #0f172bff; padding: 3px;">
                <h2 style="text-align: center; color: #fea116ff;">' . $app_name . '</h2>
                <h3 style="text-align: center; color: #fff; margin-bottom: 10px;"><strong>Hill View Hotel - Booking Invoice</strong></h3>
            </div><br>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 45%; padding: 10px;">
                        <p style="margin-bottom: 5px; color: #000;"><strong>Ref. Number:</strong> #' . $ref_code . '</p>
                        <p style="margin-bottom: 5px; color: #000;"><strong>Booked Room:</strong> ' . $room_name . '</p>
                        <p style="margin-bottom: 5px; color: #000;"><strong>Number of Rooms:</strong> ' . $rooms_total . '</p>
                        <p style="margin-bottom: 5px; color: #000;"><strong>Payment Status:</strong> <span style="background-color: #fea116ff; color: #fff; padding: 3px 7px; border-radius: 5px;">Not Paid</span></p>
                    </td>
                    <td style="border-left: 1px solid #ccc; width: 50%; padding: 10px; padding-left: 3%;">
                        <p style="margin-bottom: 5px; text-align: left; color: #000;"><span style="font-weight: bold;">Name: </span> ' . $name . '</p>
                        <p style="margin-bottom: 5px; text-align: left; color: #000;"><span style="font-weight: bold;">Email: </span> ' . $email . '</p>
                        <p style="margin-bottom: 5px; text-align: left; color: #000;"><span style="font-weight: bold;">Check In: </span> ' . $checkinFormatted . '</p>
                        <p style="text-align: left; color: #000;"><span style="font-weight: bold;">Check Out: </span> ' . $checkoutFormatted . '</p>
                    </td>
                </tr>
            </table>
            <div style="margin-top: 20px; padding: 10px;">
                <p style="color: #000; margin: 5px 0; font-style: italic; font-weight: lighter; font-size: large;">Payment Methods:</p>
                <div style="display:flex;">
                    <img src="https://www.transparentpng.com/download/payment-method/KWM0Hm-payment-method-bitcoin-photo.png" alt="PayPal" style="width: 60%; height: 80px; margin-right: 0px;">
                    <img src="https://seeklogo.com/images/T/tigo-logo-F189442F6A-seeklogo.com.png" alt="TigoPesa" style="width: 35px; height: 25px; margin: 30px 5px;">
                    <img src="http://halopesa.co.tz/static/1d8f7bc9a7487108a67e883871340ddf/e43a9/halopesa-logo.png" alt="HaloPesa" style="width: 87px; height: 32px; margin: 25px 5px;">
                    <img src="https://seeklogo.com/images/M/m-pesa-logo-E658B5D192-seeklogo.com.png" alt="M-Pesa" style="width: 70px; height: 25px; margin: 30px 5px;">
                </div>
                <p style="color: #000; font-size: small;">We accept all other payment methods. Please use your <strong>Reference number</strong> for payments.</p>
            </div>

            <div style="margin-top: 20px; padding: 10px;">
                <div style="padding: 10px 0; overflow: hidden; border-top: 1px solid grey;">
                    <span style="font-weight: bold; float: left;">Account:</span>
                    <span style="font-weight: normal; float: right;"> ' . $account_number . '  (' . $bank . ')</span>
                </div>
                <div style="padding: 10px 0; overflow: hidden; border-top: 1px solid grey;">
                    <span style="font-weight: bold; float: left;">Price Per Room:</span>
                    <span style="font-weight: normal; float: right;">Tshs.' . $price . '.00/-</span>
                </div>
                <div style="padding: 10px 0; overflow: hidden; border-top: 1px solid grey;">
                    <span style="font-weight: bold; float: left;">Total:</span>
                    <span style="font-weight: normal; float: right;">Tshs.' . $total_price . '.00/-</span>
                </div>
            </div>

            <p style="text-align: center; color: #000;">Thank you for booking with us! For any help [ <span style="color: #5d9fc5;">' . $hotel_email . '</span> ]</p>
            <div style="text-align: center;">
                <a href="http://localhost/workbench/hotel/controller/app.php?action=cancel_booking&ref=' . $ref_code . '" style="color: #5d9fc5; text-decoration: none;">Cancel Booking</a>
            </div>
        </div>
    </div>
</body>

</html>