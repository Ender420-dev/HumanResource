<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
                body {
                        display: flex;
                        margin: 0;
                        font-family: Arial, sans-serif;
                }

                .sidebar {
                        width: 270px;
                        /* height: 100vh; */
                        background-color: #4A628A;
                        color: #fff;
                        height: 100vh;
                        padding: 10px;
                        box-sizing: border-box;
                }

                .sidebar-header {
                        text-align: center;
                        margin-bottom: 20px;
                }

                .sidebar-menu {
                        list-style: none;
                        padding: 0;
                }

                .sidebar-menu li {
                        margin: 10px 0;
                }

                .sidebar-menu a {
                        color: #fff;
                        text-decoration: none;
                        display: block;
                        padding: 10px;
                        border-radius: 4px;
                }

                .sidebar-menu a.active,
                .sidebar-menu a:hover {
                        background-color: #575757;
                }

                .main_content {
                        flex: 1;
                        padding: 20px;
                        box-sizing: border-box;
                }

                .dropdown a{
                        list-style: none;
                }

                table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                }

                table, th, td {
                        border: 1px solid #ddd;
                }

                th, td {
                        padding: 10px;
                        text-align: left;
                }

                th {
                        background-color: #f4f4f4;
                }

                .pay-btn {
                        background-color: #4CAF50;
                        color: white;
                        border: none;
                        padding: 5px 10px;
                        cursor: pointer;
                        border-radius: 4px;
                }

                .pay-btn:hover {
                        background-color: #45a049;
                }

                
 .button-container{
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 20px;
 }

                .button-container a {
        text-decoration: none;
        display: inline-block;
        padding: 15px 30px;
        margin: 10px;
        border-radius: 5px;
        font-size: 20px;
        text-align: center;
        max-width: 300px;
        min-width: 200px;
        color: white;
        flex: 1;
}

a.monthly{
        max-width: 100%;
        min-width: 20%;
}
a.bi-weekly{
        max-width: 100%;
        min-width: 20%;
}
a.weekly{
        max-width: 100%;
        min-width: 20%;
        /* max-height: 100%;
        min-height: 100%; */
}

.button-container a.monthly {
        background: linear-gradient(to bottom right, #007BFF, #68a6ec);
        height: 100px;
}

.button-container a.monthly:hover {
        background: linear-gradient(to bottom right, #68a6ec, #007BFF);
}

.button-container a.bi-weekly {
        background: linear-gradient(to bottom right, #29ca4f, #9cf19c);
        height: 100px;
}

.button-container a.bi-weekly:hover {
        background: linear-gradient(to bottom right, #9cf19c, #29ca4f);
}

.button-container a.weekly {
        background: linear-gradient(to bottom right, #f11c32, #ff9696);
        height: 100px;
}

.button-container a.weekly:hover {
        background: linear-gradient(to bottom right, #ff9696, #f11c32);
}

.button-container a span {
        font-size: 30px;
        display: block;
        margin-top: 10px;
}

 .dashbtn {
        width: 100px;
        background: linear-gradient(to bottom right, #2e8aec, #8ec0f8);
        height: 40px;
        border-radius: 10px;
        outline: none;
        border: none;
        color: white;
}

.dashbtn a{
        text-decoration: none;
        color: white;
        font-size: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
}
.dashbtn:hover {
        background: linear-gradient(to bottom right, #84c4f8, #40adf5);
}


.sidebar-menu li {
        position: relative;
}

.dropdown {
        list-style: none;
        padding-left: 15px;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.4s ease;
}

/* Active dropdown class */
.sidebar-menu li.active .dropdown {
        max-height: 500px; /* enough for visible content */
        opacity: 1;
}

/* Optional: Style dropdown items */
.dropdown li a {
        /* background-color: #5b74a1; */
        padding: 8px 15px;
        display: block;
        border-radius: 4px;
        margin: 4px 0;
        transition: background-color 0.3s;
        font-size: 14px;
}

.dropdown li a:hover {
        background-color: #6e87b6;
}
.has-dropdown::after {
        content: '▸';
        float: right;
        transition: transform 0.3s ease;
}

.sidebar-menu li.active .has-dropdown::after {
        transform: rotate(90deg);
}

.prof{
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto;
        z-index: 5;
        border: 2px solid white;
        background-color: white;
        /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); */
        /* transition: all 0.3s ease; */
}
.prof img{
        width: 100%;
        height: 100%;
        /* object-fit: cover; */
        object-position: center;
        border-radius: 50%;
}

span img{
        width: 20px;
        height: 20px;
        margin-right: 10px; 
}

/* .prof:hover img {
        transform: scale(1.1);
        transition: transform 0.3s ease;
} */

        </style>
<body>
    <aside>
                <div class="sidebar">
                        <div class="sidebar-header">
                                <div class="prof">
                                        <img src="../image/logo (1).png" alt="">
                                </div>
                                <h2>HR 4</h2>
                        </div>
                        <ul class="sidebar-menu">
                            <li><a href="dashboard.php" style="font-size: xx-large; margin-bottom: 20px; border-bottom: 2px solid white;  border-radius: 0px;">Dashboard</a></li>
                                <li>
                                        <a href="#" class="has-dropdown">Payroll</a>
                                        <ul class="dropdown">
                                                <li><a href="../Payroll/payroll_processing.php">Payroll Processing</a></li>
                                                <li><a href="../Payroll/deduction.php">Manage Deduction</a></li>
                                                <!-- <li><a href="../Payroll/payroll_record.php">Payroll Record</a></li> -->
                                                <li><a href="../Payroll/process_payment.php">Payment</a></li>
                                                <li><a href="../Payroll/add_bank_account.php">Bank Accounts</a></li>
                                                <!-- <li><a href="../Payroll/advance_payments.php">Advance Payment</a></li> -->
                                                <li><a href="../Payroll/payment_history.php">Payment History</a></li>
                                        </ul>
                                </li>
                                <li><a href="">Core Human Capital</a></li>
                                <li><a href="">HR Analytics</a></li>
                                <li><a href="">Compensation Planning & Administration</a></li>
                        </ul>
                </div>
        </aside>
    <div class="main_content">
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const dropdownToggle = document.querySelector(".has-dropdown");

        dropdownToggle.addEventListener("click", function (e) {
            e.preventDefault();
            const parentLi = this.parentElement;

            // Toggle active class
            parentLi.classList.toggle("active");
        });
    });
</script>
</body>
</html>