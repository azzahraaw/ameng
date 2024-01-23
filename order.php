<?php
include("includes/db.php");
include("includes/header.php");
include("functions/functions.php");

if (isset($_GET['c_id'])) {
    $customer_id = $_GET['c_id'];
}

$ip_add = getRealUserIp();
$status = "pending";
$invoice_no = mt_rand();
$select_cart = "SELECT * FROM cart WHERE ip_add='$ip_add'";
$run_cart = mysqli_query($con, $select_cart);

while ($row = mysqli_fetch_array($run_cart)) {
    $pro_id = $row['p_id'];
    $pro_size = $row['size'];
    $pro_qty = $row['qty'];
    $sub_total = $row['p_price'] * $pro_qty;

    // Insert data ke dalam tabel customer_orders
    $insert_customer_order = "INSERT INTO customer_orders (customer_id, price, invoice_no, qty, size, order_date, order_status) VALUES ('$customer_id', '$sub_total', '$invoice_no', '$pro_qty', '$pro_size', NOW(), '$status')";
    $run_customer_order = mysqli_query($con, $insert_customer_order);

    // Insert data ke dalam tabel pending_orders
    $insert_pending_order = "INSERT INTO pending_orders (customer_id, invoice_no, product_id, qty, size, order_status) VALUES ('$customer_id', '$invoice_no', '$pro_id', '$pro_qty', '$pro_size', '$status')";
    $run_pending_order = mysqli_query($con, $insert_pending_order);

    // Insert data ke dalam tabel detail
    $order_id_query = "SELECT order_id FROM customer_orders WHERE invoice_no = '$invoice_no'";
    $order_id_result = mysqli_query($con, $order_id_query);
    $order_id_row = mysqli_fetch_assoc($order_id_result);
    $order_id = $order_id_row['order_id'];

    $insert_detail = "INSERT INTO detail (order_id, product_id, quantity) VALUES ('$order_id', '$pro_id', '$pro_qty')";
    $run_detail = mysqli_query($con, $insert_detail);

    $delete_cart = "DELETE FROM cart WHERE ip_add='$ip_add'";
    $run_delete = mysqli_query($con, $delete_cart);

    echo "<script>alert('Your order has been submitted, Thanks')</script>";
    echo "<script>window.open('cetak2.php?invoice_no=$invoice_no','_self')</script>";
}
?>
