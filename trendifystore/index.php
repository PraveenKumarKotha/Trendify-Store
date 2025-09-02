<?php
// Include database connection
include_once __DIR__ . "/includes/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trendify Store</title>
<!-- Font Awesome for icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    /* GENERAL STYLING */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background: #f0f4f8;
    }
    a { text-decoration: none; }

    /* HEADER */
    header {
        background: linear-gradient(135deg, #ff6a00, #ff3d00);
        color: white;
        padding: 50px 20px 30px 20px;
        text-align: center;
        position: relative;
    }
    header div.logo-title {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }
    header img {
        height: 60px;
        width: 60px;
        object-fit: contain;
        border-radius: 12px;
    }
    header h1 {
        margin: 0;
        font-size: 3rem;
        font-weight: bold;
        letter-spacing: 2px;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.3);
        color: #fff;
    }
    .social-icons {
        margin-top: 15px;
    }
    .social-icons a {
        color: white;
        margin: 0 10px;
        font-size: 24px;
        transition: transform 0.3s, color 0.3s;
    }
    .social-icons a:hover {
        color: #fff200;
        transform: scale(1.2);
    }

    /* MAIN CONTENT */
    main {
        text-align: center;
        padding: 40px 20px;
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
    }

    /* PRODUCT CARDS */
    .products {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
        align-items: flex-start;
    }
    .product-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: white;
        padding: 15px;
        border-radius: 12px;
        width: 220px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
        overflow: visible; /* allow text to expand */
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.4s, filter 0.3s;
    }
    .product-card:hover img {
        transform: scale(1.08);
        filter: brightness(1.1);
    }
    .product-card h3 {
        margin: 10px 0 5px;
        font-size: 1.2rem;
        color: #333;
    }
    .product-card p {
        margin: 5px 0 10px;
        font-size: 0.9rem;
        color: #555;
        text-align: left; /* better readability for long text */
        word-wrap: break-word;
    }
    .product-card a {
        display: inline-block;
        padding: 8px 15px;
        background: #ff5722;
        color: white;
        border-radius: 8px;
        font-weight: bold;
        transition: background 0.3s, transform 0.3s;
        margin-top: auto;
    }
    .product-card a:hover {
        background: #ff7043;
        transform: scale(1.05);
    }

    /* ZOOM MODAL */
    #zoomModal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.7);
        justify-content: center;
        align-items: center;
        z-index: 999;
    }
    #zoomModal img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.5);
        animation: zoomIn 0.3s ease forwards;
    }
    @keyframes zoomIn {
        from { transform: scale(0.7); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* FOOTER */
    footer {
        background: #333;
        color: white;
        text-align: center;
        padding: 15px;
        margin-top: 20px;
    }

    /* RESPONSIVE */
    @media screen and (max-width: 768px) {
        .products {
            flex-direction: column;
            align-items: center;
        }
        header h1 {
            font-size: 2.2rem;
        }
        .product-card {
            width: 80%;
        }
        .product-card img {
            height: 200px;
        }
    }
</style>
</head>
<body>

<!-- HEADER -->
<header>
    <div class="logo-title">
        <img src="logo.jpg" alt="Logo">
        <h1>Trendify Store</h1>
    </div>
    <div class="social-icons">
        <a href="https://www.instagram.com/trendifystore_official/reels/" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://www.facebook.com/profile.php?id=61577133354735&sk=reels_tab" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="https://www.youtube.com/@didyouknowuniverse-i8w" target="_blank"><i class="fab fa-youtube"></i></a>
    </div>
</header>

<!-- MAIN -->
<main>
    <p>Welcome to Trendify Store! Stylish trends at your fingertips.</p>
    <h2>Available Products</h2>
    <div class="products">
        <?php
        try {
            $stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 10");
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class='product-card' onclick='openZoom(\"uploads/" . htmlspecialchars($row['image']) . "\")'>";
                    echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<a href='" . htmlspecialchars($row['affiliate_link']) . "' target='_blank'>Buy Now</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products available right now.</p>";
            }
        } catch (PDOException $e) {
            echo "Error fetching products: " . $e->getMessage();
        }
        ?>
    </div>
</main>

<!-- ZOOM MODAL -->
<div id="zoomModal" onclick="closeZoom()">
    <img id="zoomImage" src="" alt="Zoomed Product">
</div>

<!-- FOOTER -->
<footer>
    <p>&copy; <?php echo date("Y"); ?> Trendify Store | All Rights Reserved</p>
</footer>

<script>
    function openZoom(src) {
        document.getElementById('zoomImage').src = src;
        document.getElementById('zoomModal').style.display = 'flex';
    }
    function closeZoom() {
        document.getElementById('zoomModal').style.display = 'none';
    }
</script>

</body>
</html>
