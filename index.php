<?php include('db_config.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Swift Logistics | Global Shipping</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-image">
    <header>
        <h1>Swift Logistics Global</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="track.php">Track Shipment</a>
            <a href="contact.php">Contact Us</a>
        </nav>
    </header>

    <div class="container" style="display: flex; align-items: center; justify-content: center; text-align: center;">
        <div style="max-width: 600px;">
            <h2 style="font-size: 3rem; margin-bottom: 10px;">Reliable Global Logistics</h2>
            <p style="font-size: 1.2rem; margin-bottom: 30px;">Shipping across America, Europe, and Australia with real-time tracking.</p>
            
            <div class="card">
                <form action="track.php" method="GET">
                    <input type="text" name="id" placeholder="Enter Tracking Number (e.g., US-NY-7734)" required>
                    <button type="submit">Track Now</button>
                </form>
            </div>
        </div>
    </div>

    <?php include('global_footer.php'); ?>
</body>
</html>