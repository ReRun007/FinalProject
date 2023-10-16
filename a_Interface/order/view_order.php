<?php 
    $currentPage = 'order';
    require_once '../main/a_headbar.php';
    require_once '../../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
    <link rel="stylesheet" href="../../CSS/table.css">
</head>
<body>
    <div class="bg"></div>
    <div class="container-table">
        <h1>Order List</h1>       
        <!-- รายการ Order -->
        <div class="mb-3">
            <h2>รายการ Order ของคุณ</h2>
            <label for="statusFilter" class="form-label">เลือกสถานะ Order</label>
            <select class="form-select" id="statusFilter">
                <option value="">ทั้งหมด</option>
                <option value="Pending">Pending</option>
                <option value="Verifying Payment">Verifying Payment</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div> 
        
        <?php if(isset($_SESSION['success'])){ ?>
            <div class="alert alert-success" role='alert'>
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>

        <?php if(isset($_SESSION['error'])){ ?>
            <div class="alert alert-danger" role='alert'>
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>

        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Customer Name</th>
                    <th>Product</th>
                    <th>จำนวน</th>
                    <th>Total price</th>
                    <th>Order Discount</th>
                    <th>Order Price</th>
                    <th>Order Status</th>
                    <th>Edit Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT  o.orderID,o.orderDate,c.username,o.orderStatus,o.orderPrice
                        FROM `order` o ,customers c
                        WHERE    c.customer_id = o.customer_id
                        ORDER BY o.orderDate;";
                $result = $conn->query($sql);
                if ($result->rowCount() > 0) {
                    $i=1;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr class="orderRow" data-order-status="' . $row['orderStatus'] . '">';
                        echo '<td >' . $i . '</td>';
                        echo '<td>' . $row['orderID'] . '</td>';
                        echo '<td>' . $row['orderDate'] . '</td>';
                        echo '<td>' . $row['username'] . '</td>';
                        // Loop Product
                        $od_sql = "SELECT  o.orderID,o.orderDate,c.username,p.product_name,od.od_quantity,od.total_price,o.orderStatus, od.one_price
                                    FROM `order` o , order_detail od, products p ,customers c
                                    WHERE  o.orderID = :orderID AND p.product_id = od.product_id AND o.orderID = od.orderID AND c.customer_id = o.customer_id
                                    ORDER BY o.orderDate;";
                        $od_statement = $conn->prepare($od_sql);
                        $od_statement->bindParam(':orderID', $row['orderID'], PDO::PARAM_INT);
                        $od_statement->execute();
                        $od_result = $od_statement->fetchAll(PDO::FETCH_ASSOC);

                        $totalPriceOrder = 0;
                        $discountOrder = 0;
                        
                        echo '<td>'; //ชื่อสินค้า - ราคา
                        foreach($od_result as $od_row){
                            echo '<div class="row">';
                            echo '<span class="col">'.$od_row['product_name']. ': ';
                            echo '<span class="col">'.$od_row['one_price'];
                            echo '</div>';
                        }
                        echo '</td>';
                        echo '<td>'; // จำนวน
                        foreach($od_result as $od_row){
                            echo '<div class="row">';
                            echo '<span class="col">'.$od_row['od_quantity'].' ชิ้น';
                            echo '</div>';
                        }
                        echo '</td>';
                        echo '<td>';// ราคารวม
                        foreach($od_result as $od_row){
                            echo '<div class="row">';
                            echo '<span class="col">'.$od_row['total_price'].' บาท';
                            $totalPriceOrder += $od_row['total_price'];
                            echo '</div>';
                        }
                        $discountOrder = $totalPriceOrder - $row['orderPrice'];
                        echo '<span class="col"> รวม:'. $totalPriceOrder . ' บาท';
                        echo '</td>';//ปิด ราคารวม
                        // End Loop Product
                        echo '<td>'. $discountOrder .' </td>';
                        echo '<td>'. $row['orderPrice'] .' </td>';
                        echo '<td>'. $row['orderStatus'] .' </td>';
                        echo "<td> <a class='btn btn-warning' href='edit.php?orderID=" . $row["orderID"] . "'> Edit </td>";
                        echo '</tr>';
                        $i++;
                    }
                } else {
                    echo '<tr><td colspan="5">ไม่พบข้อมูลในตาราง category.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // ตัวกรองรายการ Order ตาม Order Status
        const statusFilter = document.getElementById("statusFilter");
        const orderRows = document.querySelectorAll(".orderRow");
        statusFilter.addEventListener("change", function() {
            const selectedStatus = statusFilter.value;
            orderRows.forEach(row => {
                const rowStatus = row.getAttribute("data-order-status");
                if (selectedStatus === "" || selectedStatus === rowStatus) {
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>
