<?php
$page_title = 'View Product';
include ('includes/header.php');
require_once "config.php";

if (isset($_GET['id']) && isset($_GET['vid'])) {    //If variant ID is chosen
    $id = $_GET['id'];      //ID
    $vid = $_GET['vid'];    //Variant ID
} elseif (isset($_GET['id'])) {                     //Regular product page
    $id = $_GET['id'];
} else {
    echo '<h4 class="error text-center">Error: Product not found</h4>';
    exit;
}
?>

<script>
    
class Product {
    constructor(variant_id, quantity, price) {
        this.varID = variant_id;
        this.qty = quantity;
    }
}

function addCart() { 
    //Quantity
    var q = document.querySelector("#qty").value.trim();
    if (q > 99 || q < 1) {
        var err = document.getElementById("error");
        err.style.display = "block";                    //Toggle css display on 
        err.innerHTML = "Quantity must be within 1~99"; //Add & display error message
        return;
    }
    
    //Size ID (from enum table)
    var numBtn = document.querySelectorAll('.size-choose button').length;
    for (var i=0; i<numBtn; i++) {
        var x = document.getElementById("btn"+(i+1)).autofocus;
        //If (x) console.log("focused"); else console.log("not focused");
        if (x) {
            var vid = document.getElementById("btn"+(i+1)).value;   //Get variant ID
            
            //Create product
            var product = new Product(vid, parseInt(q));
            var str = JSON.stringify(product);
            document.cookie = "product=" + str;
            window.location = "add_cart.php";       //Go to cart
            return;
        }
    }
    var err = document.getElementById("error");
    err.style.display = "block"; //Toggle css display on 
    err.innerHTML = "Please select a size option"; //Add & display error message
}

</script>

<?php
//Query the product
$q = "SELECT `entity_product`.`product_id`, `entity_product_variant`.`variant_id`, `entity_product`.`product_name`, `entity_product`.`description`, `enum_product_type`.`product_type`, `entity_product`.`product_color`, `entity_product`.`product_price`, `entity_product`.`image`, `entity_product_variant`.`variant_size`, `enum_size`.`size`, `enum_size`.`size_id` FROM `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type`, `storefront`.`xref_product_variant_product` AS `xref_product_variant_product`, `storefront`.`entity_product_variant` AS `entity_product_variant`, `storefront`.`enum_size` AS `enum_size` WHERE `entity_product`.`type` = `enum_product_type`.`id` AND `xref_product_variant_product`.`product_id` = `entity_product`.`product_id` AND `entity_product_variant`.`variant_size` = `enum_size`.`size_id` AND `xref_product_variant_product`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `entity_product`.`product_id` = $id ORDER BY `enum_size`.`size_id` ASC";
$r = mysqli_query($link, $q);
$nSize = mysqli_num_rows($r);   //Get number of sizes for the product

//If query successful, fetch first row in order to extract information
if (mysqli_num_rows($r) > 0) {
    mysqli_data_seek($r, 0);
    $row = $r->fetch_assoc();
}
//Error
else {
    echo '<div class="text-center">Product not available yet. Sorry for the inconvenience.</div>';
    exit;
}
?>

<div class="product_container">
    <!--Product image, left column-->
    <div class="left-column">
        <img src="./img/<?php echo $row['image']; ?>" class="img-responsive center" alt="Image">
    </div>
    
    <!--Product details, right column-->
    <div class="right-column">
        <div class="product-description">
            <h2> <?php echo $row['product_name']; ?> </h2>
            <h2> $<?php echo $row['product_price']; ?> </h2><br>
            <p> <?php echo $row['description']; ?> </p>
        </div>
        
        <div class="product-color">
            <p>Color: <?php echo $row['product_color']; ?> </p>
        </div>

        <div class="product-size">
            <span>Size:</span>
            <div class="size-choose">
                <?php
                for ($i=0; $i<$nSize; $i++) {
                    mysqli_data_seek($r, $i);
                    $rowSz = $r->fetch_assoc();                        
                    if (isset($vid) && ($vid == $rowSz['variant_id'])) {
                        echo '<button autofocus class="active" onclick="window.location.href=\'product.php?id=' . $id . '&vid=' . $rowSz['variant_id'] . '\'" id="btn' . ($i+1) . '" name="size" value="' . $rowSz["variant_id"] . '">' . $rowSz['size'] . '</button>';
                    } else {
                        echo '<button onclick="window.location.href=\'product.php?id=' . $id . '&vid=' . $rowSz['variant_id'] . '\'" id="btn' . ($i+1) . '" name="size" value="' . $rowSz["variant_id"] . '">' . $rowSz['size'] . '</button>';
                    }
                }
                ?>
                <input type="hidden" id="btnValue" value=""/>
            </div>
        </div>
        
        <div class="product-quantity">
            <p>Quantity: <input id="qty" type="number" name="qty" value="1" step="1" min ="1" max ="99" style="width:50px; text-align:right;"></p>
        </div>
        
        <?php echo '<button class="cart-btn" onclick="addCart()">Add to cart</button>'; ?>
        <span id="error"></span>
    </div>  
</div>

</body>
</html>