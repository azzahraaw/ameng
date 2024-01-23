<?php
include("includes/db.php");
include("includes/header.php");
include("functions/functions.php");

if (isset($_GET['invoice_no'])) {
    $invoice_no = $_GET['invoice_no'];

    // Retrieve order details from the database including order_date
    $get_order = "SELECT * FROM customer_orders WHERE invoice_no='$invoice_no'";
    $run_order = mysqli_query($con, $get_order);
    $row_order = mysqli_fetch_array($run_order);

    // Create and display the invoice
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nota Pembelian</title>

        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="title2" align="center">Catatan Pembelian</h3>
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table id="orderTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="2">Nomor Invoice: <?php echo $invoice_no; ?></td>
                                    <td colspan="2">Tanggal Pesanan: <?php echo date('d/m/Y | H:i:s', strtotime($row_order['order_date'])); ?></td>
                                </tr>
                                <tr>
                                    <th data-field="product_name">Nama Barang</th>
                                    <th data-field="price">Harga Satuan</th>
                                    <th data-field="quantity">Jumlah</th>
                                    <th data-field="subtotal">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $get_order_items = "SELECT detail.id, customer_orders.qty, customer_orders.price, customer_orders.invoice_no, products.product_title, products.product_psp_price FROM `detail` INNER JOIN customer_orders on customer_orders.order_id = detail.order_id INNER JOIN products on products.product_id = detail.product_id WHERE customer_orders.invoice_no = '$invoice_no'";
                                $run_order_items = mysqli_query($con, $get_order_items);
                                if (!$run_order_items) {
                                    die('Query Error: ' . mysqli_error($con));
                                }
                                while ($row_order_items = mysqli_fetch_array($run_order_items)) {
                                    $pro_id = $row_order_items['product_title'];
                                    $pro_qty = $row_order_items['product_psp_price'];
                                    $pro_size = $row_order_items['qty'];
                                    $sub_total = $pro_qty * $pro_size;
                                    ?>
                                    <tr>
                                        <td><?php echo $pro_id; ?></td>
                                        <td><?php echo $pro_qty; ?></td>
                                        <td><?php echo $pro_size; ?></td>
                                        <td><?php echo $sub_total; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="index.php" class="btn btn-primary">Back To Homepage</a>
                </div>
            </div>
        </div>
        <!-- DataTables JS -->
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>

        <!-- DataTables initialization -->
        <script>
    $(document).ready(function () {
        $('#orderTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel'
            ],
            searching: true
            });
            });
        </script>

    </body>
    </html>
    <?php
}
?>
