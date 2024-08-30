<?php
    require_once("backend/Admin/votingRank.inc.php");
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

<h2>Partners in Crime</h2>
<table>
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Class</th>
        <th>Table Number</th>
        <th>Votes</th>
        <th>Description</th>
        <th>Image</th>
    </tr>
    <?php $rank = 1; foreach ($data[0] as $guest) {
        $studentInfo1 = $guestInfo[$guest['guestId1']];
        $studentInfo2 = $guestInfo[$guest['guestId2']];
        ?>
        <tr>
            <td><?php echo $rank; ?></td>
            <td><?php echo $studentInfo1['studentName']. "||". $studentInfo2['studentName']; ?></td>
            <td><?php echo $studentInfo1['class'] . "||". $studentInfo2['class']; ?></td>
            <td><?php echo $studentInfo1['tableNo']. "||". $studentInfo2['tableNo']; ?></td>
            <td><?php echo $guest['voteCount']; ?></td>
            <td><?php echo $guest['nomineeDesc']; ?></td>
            <td><img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>"></td>

        </tr>
    <?php ++$rank; } ?>
</table>

<h2>Prom King</h2>

<table>
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Class</th>
        <th>Table Number</th>
        <th>Votes</th>
        <th>Description</th>
        <th>Image</th>
    </tr>
    <?php $rank = 1; foreach ($data[1] as $guest) {
            $studentInfo1 = $guestInfo[$guest['guestId1']];
                    ?>
        <tr>
            <td><?php echo $rank; ?></td>
            <td><?php echo $studentInfo1['studentName']; ?></td>
            <td><?php echo $studentInfo1['class']; ?></td>
            <td><?php echo $studentInfo1['tableNo']; ?></td>
            <td><?php echo $guest['voteCount']; ?></td>
            <td><?php echo $guest['nomineeDesc']; ?></td>
            <td><img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
    <?php ++$rank; } ?>
</table>

<h2>Prom Queen</h2>
<table>
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Class</th>
        <th>Table Number</th>
        <th>Votes</th>
        <th>Description</th>
        <th>Image</th>
    </tr>
    <?php $rank = 1; foreach ($data[2] as $guest) { 
                    $studentInfo1 = $guestInfo[$guest['guestId1']];
                    ?>
        <tr>
            <td><?php echo $rank; ?></td>
            <td><?php echo $studentInfo1['studentName']; ?></td>
            <td><?php echo $studentInfo1['class']; ?></td>
            <td><?php echo $studentInfo1['tableNo']; ?></td>
            <td><?php echo $guest['voteCount']; ?></td>
            <td><?php echo $guest['nomineeDesc']; ?></td>
            <td><img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
    <?php ++$rank; } ?>
</table>


<h2>Best Dressed (Male)</h2>

<table>
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Class</th>
        <th>Table Number</th>
        <th>Votes</th>
        <th>Description</th>
        <th>Image</th>
    </tr>
    <?php $rank = 1; foreach ($data[3] as $guest) {
                    $studentInfo1 = $guestInfo[$guest['guestId1']];
                    ?>
        <tr>
            <td><?php echo $rank; ?></td>
            <td><?php echo $studentInfo1['studentName']; ?></td>
            <td><?php echo $studentInfo1['class']; ?></td>
            <td><?php echo $studentInfo1['tableNo']; ?></td>
            <td><?php echo $guest['voteCount']; ?></td>
            <td><?php echo $guest['nomineeDesc']; ?></td>
            <td><img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
    <?php ++$rank; } ?>
</table>

<h2>Best Dressed (Female)</h2>

<table>
    <tr>
        <th>Rank</th>
        <th>Name</th>
        <th>Class</th>
        <th>Table Number</th>
        <th>Votes</th>
        <th>Description</th>
        <th>Image</th>
    </tr>
    <?php $rank = 1; foreach ($data[4] as $guest) {
                    $studentInfo1 = $guestInfo[$guest['guestId1']];
                    ?>
        <tr>
            <td><?php echo $rank; ?></td>
            <td><?php echo $studentInfo1['studentName']; ?></td>
            <td><?php echo $studentInfo1['class']; ?></td>
            <td><?php echo $studentInfo1['tableNo']; ?></td>
            <td><?php echo $guest['voteCount']; ?></td>
            <td><?php echo $guest['nomineeDesc']; ?></td>
            <td><img class="nominee__img"
                            src="<?php echo htmlspecialchars($guest["image"], ENT_QUOTES, 'UTF-8'); ?>"></td>
        </tr>
    <?php ++$rank; } ?>
</table>


