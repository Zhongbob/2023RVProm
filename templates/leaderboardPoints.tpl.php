<?php
$sql = "SELECT SUM(points) AS totalPoints, tableNo FROM `points` 
JOIN `users` ON `points`.`userId` = `users`.`userid`
JOIN `guestuserjunction` ON `points`.`userId` = `guestuserjunction`.`userId`
JOIN `guests` ON `guestuserjunction`.`guestId` = `guests`.`guestId`
WHERE `users`.`fromrvhs` = 1
GROUP BY tableNo ORDER BY totalPoints DESC";


$result = mysqli_query($conn, $sql);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

$sql = "SELECT points, studentName,tableNo,class FROM `points` 
JOIN `users` ON `points`.`userId` = `users`.`userid`
JOIN `guestuserjunction` ON `points`.`userId` = `guestuserjunction`.`userId`
JOIN `guests` ON `guestuserjunction`.`guestId` = `guests`.`guestId`
WHERE `users`.`fromrvhs` = 1
GROUP BY tableNo ORDER BY points DESC"; 
$result = mysqli_query($conn, $sql);
$individualData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $individualData[] = $row;
}

?>
<style>
    .nominee__img {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
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
        text-align: center;
    }

    th{
        position:sticky;
        top:0;
        background:black;
    }
</style>
</head>
<h1>Leaderboard (Votings)</h1>

<h2>By Table</h2>
<table>
    <tr>
        <th>Rank</th>
        <th>Table Number</th>
        <th>Total Points</th>
    </tr>
    <?php $rank = 1; foreach ($data as $table) {
        ?>
        <tr>
            <td><?php echo $rank; ?></td>
            <td><?php echo $table['tableNo']; ?></td>
            <td><?php echo $table['totalPoints']; ?></td>

        </tr>
    <?php ++$rank; } ?>
</table>

<h2>By Student</h2>
<table>
    <tr>
        <th>Rank</th>
        <th>Student Name</th>
        <th>Student Class</th>
        <th>Table Number</th>
        <th>Total Points</th>
    </tr>
    <?php $rank = 1; foreach ($individualData as $student) {
        ?>
        <tr>
            <td><?php echo $rank; ?></td>
            <td><?php echo $student['studentName']; ?></td>
            <td><?php echo $student['class']; ?></td>
            <td><?php echo $student['tableNo']; ?></td>
            <td><?php echo $student['points']; ?></td>

        </tr>
    <?php ++$rank; } ?>
</table>

