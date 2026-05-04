<?php
session_start();
include('db_config.php');

// Security Check
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== "active"){
    header("Location: login.php");
    exit();
}

// Delete Logic
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM shipments WHERE id = $id");
    header("Location: admin.php");
    exit();
}

// Save Logic
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])){
    $tn = $_POST['tn']; $stat = $_POST['stat']; $loc = $_POST['loc'];
    $rn = $_POST['rn']; $ra = $_POST['ra']; $re = $_POST['re']; $rp = $_POST['rp'];
    $sn = $_POST['sn']; $si = $_POST['si']; $lat = $_POST['lat']; $lng = $_POST['lng'];

    $pic_name = '';
    if(!empty($_FILES['pic']['name'])){
        if (!file_exists('uploads')) { mkdir('uploads', 0777, true); }
        $pic_name = time() . "_" . basename($_FILES['pic']['name']);
        move_uploaded_file($_FILES['pic']['tmp_name'], "uploads/" . $pic_name);
    }

    $stmt = $conn->prepare("INSERT INTO shipments (tracking_number, status, location, rec_name, rec_address, rec_email, rec_phone, send_name, send_info, latitude, longitude, package_pic) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE status=?, location=?, rec_name=?, rec_address=?, rec_email=?, rec_phone=?, latitude=?, longitude=?, package_pic=IF(?='', package_pic, ?)");
    
    $stmt->bind_param("sssssssssddssssssssdds", $tn, $stat, $loc, $rn, $ra, $re, $rp, $sn, $si, $lat, $lng, $pic_name, $stat, $loc, $rn, $ra, $re, $rp, $lat, $lng, $pic_name, $pic_name);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Global Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-section { background: #fdfdfd; padding: 15px; border: 1px solid #eee; border-radius: 8px; text-align: left; }
        select, input, textarea { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; background: white; font-size: 13px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #0056b3; color: white; }
        .btn-del { color: red; text-decoration: none; font-weight: bold; }
    </style>
    
    <script>
        function updateCoords() {
            const cityData = {
                // --- USA ---
                "New York, USA": {lat: 40.7128, lng: -74.0060},
                "Los Angeles, USA": {lat: 34.0522, lng: -118.2437},
                "Chicago, USA": {lat: 41.8781, lng: -87.6298},
                "Houston, USA": {lat: 29.7604, lng: -95.3698},
                "Miami, USA": {lat: 25.7617, lng: -80.1918},
                "Washington D.C., USA": {lat: 38.9072, lng: -77.0369},
                "San Francisco, USA": {lat: 37.7749, lng: -122.4194},
                "Dallas, USA": {lat: 32.7767, lng: -96.7970},
                "Las Vegas, USA": {lat: 36.1699, lng: -115.1398},
                
                // --- EUROPE ---
                "London, UK": {lat: 51.5074, lng: -0.1278},
                "Paris, France": {lat: 48.8566, lng: 2.3522},
                "Berlin, Germany": {lat: 52.5200, lng: 13.4050},
                "Rome, Italy": {lat: 41.9028, lng: 12.4964},
                "Madrid, Spain": {lat: 40.4168, lng: -3.7038},
                "Amsterdam, Netherlands": {lat: 52.3676, lng: 4.9041},
                "Brussels, Belgium": {lat: 50.8503, lng: 4.3517},
                "Vienna, Austria": {lat: 48.2082, lng: 16.3738},
                "Lisbon, Portugal": {lat: 38.7223, lng: -9.1393},
                "Athens, Greece": {lat: 37.9838, lng: 23.7275},
                "Stockholm, Sweden": {lat: 59.3293, lng: 18.0686},
                "Dublin, Ireland": {lat: 53.3498, lng: -6.2603},
                
                // --- AUSTRALIA ---
                "Sydney, Australia": {lat: -33.8688, lng: 151.2093},
                "Melbourne, Australia": {lat: -37.8136, lng: 144.9631},
                "Brisbane, Australia": {lat: -27.4705, lng: 153.0260},
                "Perth, Australia": {lat: -31.9505, lng: 115.8605},
                "Adelaide, Australia": {lat: -34.9285, lng: 138.6007},
                "Canberra, Australia": {lat: -35.2809, lng: 149.1300}
            };
            
            const selection = document.getElementById("city_pick").value;
            if(cityData[selection]) {
                document.getElementById("lat_input").value = cityData[selection].lat;
                document.getElementById("lng_input").value = cityData[selection].lng;
                document.getElementById("loc_input").value = selection;
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>Global Admin Hub</h1>
        <nav><a href="admin.php">Dashboard</a> | <a href="logout.php" style="color:red;">Logout</a></nav>
    </header>

    <div class="container">
        <div class="card" style="max-width: 1100px;">
            <h2>Create/Update Shipment</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="admin-grid">
                    <div class="form-section">
                        <h3>1. Location Logic</h3>
                        <label>Select Destination Hub:</label>
                        <select id="city_pick" onchange="updateCoords()">
                            <option value="">-- Choose Global City --</option>
                            <optgroup label="United States">
                                <option value="New York, USA">New York</option>
                                <option value="Los Angeles, USA">Los Angeles</option>
                                <option value="Chicago, USA">Chicago</option>
                                <option value="Miami, USA">Miami</option>
                                <option value="Las Vegas, USA">Las Vegas</option>
                                <option value="Washington D.C., USA">Washington D.C.</option>
                            </optgroup>
                            <optgroup label="Europe">
                                <option value="London, UK">London, UK</option>
                                <option value="Paris, France">Paris, France</option>
                                <option value="Berlin, Germany">Berlin, Germany</option>
                                <option value="Amsterdam, Netherlands">Amsterdam</option>
                                <option value="Madrid, Spain">Madrid</option>
                                <option value="Dublin, Ireland">Dublin</option>
                            </optgroup>
                            <optgroup label="Australia">
                                <option value="Sydney, Australia">Sydney</option>
                                <option value="Melbourne, Australia">Melbourne</option>
                                <option value="Brisbane, Australia">Brisbane</option>
                                <option value="Perth, Australia">Perth</option>
                            </optgroup>
                        </select>
                        
                        <input type="text" name="tn" placeholder="Tracking ID (e.g. US-NY-990)" required>
                        <input type="text" name="stat" placeholder="Current Status (e.g. Cleared Customs)">
                        <input type="text" id="loc_input" name="loc" placeholder="City Display Name">
                        
                        <input type="hidden" id="lat_input" name="lat" value="0">
                        <input type="hidden" id="lng_input" name="lng" value="0">
                        
                        <label>Package Image:</label>
                        <input type="file" name="pic">
                    </div>

                    <div class="form-section">
                        <h3>2. Address & Contacts</h3>
                        <input type="text" name="rn" placeholder="Recipient Full Name">
                        <input type="text" name="ra" placeholder="Full Street Address">
                        <input type="text" name="re" placeholder="Recipient Email">
                        <input type="text" name="rp" placeholder="Recipient Phone">
                        <hr>
                        <input type="text" name="sn" placeholder="Sender Name">
                        <textarea name="si" placeholder="Sender Details" rows="2"></textarea>
                    </div>
                </div>
                <button type="submit" name="save" style="margin-top:20px; background:#28a745;">Save International Shipment</button>
            </form>
        </div>

        <h2 style="margin-top:40px;">Current Database Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Tracking ID</th>
                    <th>Status</th>
                    <th>Recipient</th>
                    <th>Global Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM shipments ORDER BY last_updated DESC");
                while($r = $res->fetch_assoc()){
                    echo "<tr>
                            <td><b>{$r['tracking_number']}</b></td>
                            <td>{$r['status']}</td>
                            <td>" . ($r['rec_name'] ?? 'N/A') . "</td>
                            <td>{$r['location']}</td>
                            <td><a href='admin.php?delete={$r['id']}' class='btn-del' onclick='return confirm(\"Confirm Delete?\")'>Delete</a></td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>