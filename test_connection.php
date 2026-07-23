<?php

include("includes/config.php");

if ($conn) {
    echo "✅ Database Connected Successfully!";
} else {
    echo "❌ Connection Failed!";
}

?>