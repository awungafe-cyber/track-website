<?php include('db_config.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Track Shipment | Swift Logistics</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

    <div class="container">
        <?php
        if(isset($_GET['id'])){
            $id = mysqli_real_escape_string($conn, $_GET['id']);
            $sql = "SELECT * FROM shipments WHERE tracking_number = '$id'";
            $result = mysqli_query($conn, $sql);
            
            if(mysqli_num_rows($result) > 0){
                $r = mysqli_fetch_assoc($result);
        ?>
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
                    <div style="flex: 1; min-width: 300px;">
                        <h2 style="color: #0056b3; margin-bottom: 15px;">Shipment Found</h2>
                        <p><strong>Tracking ID:</strong> <?php echo $r['tracking_number']; ?></p>
                        <p><strong>Current Status:</strong> <?php echo $r['status']; ?></p>
                        <p><strong>Location:</strong> <?php echo $r['location']; ?></p>
                        <p><strong>Recipient:</strong> <?php echo $r['rec_name']; ?></p>
                    </div>
                    
                    <div style="flex: 1; min-width: 250px; text-align: center;">
                        <?php if(!empty($r['package_pic'])){ ?>
                            <img src="uploads/<?php echo $r['package_pic']; ?>" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <?php } ?>
                    </div>
                </div>

                <div id="map" style="height: 400px; width: 100%; border-radius: 10px; margin-top: 30px; border: 1px solid #ddd;"></div>
                
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                <script>
                    var map = L.map('map').setView([<?php echo $r['latitude']; ?>, <?php echo $r['longitude']; ?>], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                    L.marker([<?php echo $r['latitude']; ?>, <?php echo $r['longitude']; ?>]).addTo(map)
                        .bindPopup('<b>Package Location</b><br><?php echo $r['location']; ?>').openPopup();
                </script>
            </div>
        <?php 
            } else {
                echo "<div class='card' style='text-align:center;'><h3 style='color:red;'>Invalid Tracking Number. Please try again.</h3></div>";
            }
        } else {
            echo "<div class='card' style='text-align:center;'><h3>Please enter your tracking number to see shipment status.</h3></div>";
        }
        ?>
    </div>

    <?php include('global_footer.php'); ?>
</body>
</html>