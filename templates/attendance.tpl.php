<?php
$sql = "SELECT *,attendance.guestId as present FROM `guests` LEFT JOIN `attendance` ON `guests`.guestId = `attendance`.guestId 
 ORDER BY `tableNo` ASC";
$result = mysqli_query($conn, $sql);

?>
<style>
    table{
        border-collapse: collapse;
        width: fit-content;
        border: 1px solid white;
    }
    th, td{
        text-align: left;
        padding: 8px;
        border-collapse: collapse;
        border: 1px solid white;
        width:fit-content;
    }

    th{
        position:sticky;
        top:0;
        background:black;
    }
    .present{
        background-color: darkgreen;
    }
</style>
</head>
<table>
    <tr>
        <th>Name</th>
        <th>Class</th>
        <th>Table Number</th>
    </tr>
    <?php while ($guest = mysqli_fetch_assoc($result)) { ?>
        <tr <?php if ($guest['present']) echo "class='present'"?>>
            <td><?php echo $guest['studentName']; ?></td>
            <td><?php echo $guest['class']; ?></td>
            <td><?php echo $guest['tableNo']; ?></td>
        </tr>
    <?php } ?>
</table>